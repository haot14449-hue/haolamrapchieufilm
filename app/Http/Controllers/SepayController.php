<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;

class SepayController extends Controller
{
    /**
     * Handle incoming webhook notification from SePay.
     * When payment is received in MB Bank (031205090305 - TRAN VAN HAO),
     * SePay will trigger this endpoint to automatically complete the booking.
     */
    public function webhook(Request $request)
    {
        Log::info('SePay Webhook Received:', $request->all());

        // 1. Verify API Key if configured in .env
        $configuredKey = config('services.sepay.api_key');
        if (!empty($configuredKey)) {
            $authHeader = $request->header('Authorization', '');
            $apiKeyHeader = $request->header('apikey', '');
            $reqKey = $request->input('api_key', '');

            $isValid = stripos($authHeader, trim($configuredKey)) !== false 
                    || strcasecmp(trim($apiKeyHeader), trim($configuredKey)) === 0 
                    || strcasecmp(trim($reqKey), trim($configuredKey)) === 0;

            if (!$isValid) {
                Log::warning('SePay Webhook: Invalid API Key authentication', [
                    'auth_header' => $authHeader,
                    'ip' => $request->ip()
                ]);
                return response()->json([
                    'success' => false, 
                    'message' => 'Unauthorized: Invalid API Key'
                ], 401);
            }
        }

        // 2. Validate transfer direction (only handle money coming in)
        $transferType = strtolower($request->input('transferType', 'in'));
        if ($transferType !== 'in') {
            return response()->json([
                'success' => true, 
                'message' => 'Ignored outgoing transfer'
            ]);
        }

        // 3. Extract Booking ID from payment content / description / code
        $content = (string)$request->input('content', '');
        $code = (string)$request->input('code', '');
        $description = (string)$request->input('description', '');
        $fullText = $code . ' ' . $content . ' ' . $description;

        $bookingId = null;
        if (preg_match('/HCTV[\s\-_]*(\d+)/i', $fullText, $matches)) {
            $bookingId = (int)$matches[1];
        } elseif (preg_match('/(?:BOOKING|DONHANG|HD)[\s\-_]*(\d+)/i', $fullText, $matches)) {
            $bookingId = (int)$matches[1];
        } elseif ($request->filled('booking_id')) {
            $bookingId = (int)$request->input('booking_id');
        }

        if (!$bookingId) {
            Log::warning('SePay Webhook: Could not extract Booking ID from transfer content', [
                'fullText' => $fullText
            ]);
            return response()->json([
                'success' => false, 
                'message' => 'Could not determine booking ID from content: ' . $fullText
            ], 400);
        }

        // 4. Retrieve booking
        $booking = Booking::with(['tickets.seat', 'showtime.movie', 'showtime.room.cinema', 'user'])->find($bookingId);
        if (!$booking) {
            Log::warning("SePay Webhook: Booking #{$bookingId} does not exist");
            return response()->json([
                'success' => false, 
                'message' => "Booking #{$bookingId} not found"
            ], 404);
        }

        // 5. If already paid, return 200 OK so SePay stops retrying
        if ($booking->status === 'paid') {
            Log::info("SePay Webhook: Booking #{$bookingId} is already marked as paid.");
            return response()->json([
                'success' => true, 
                'message' => "Booking #{$bookingId} is already paid",
                'booking_id' => $booking->id
            ]);
        }

        // 6. Check received amount against booking total
        $transferAmount = (float)$request->input('transferAmount', 0);
        $expectedAmount = (float)$booking->total_price;

        // Allow tolerance of 1,000 VND
        if ($transferAmount < ($expectedAmount - 1000)) {
            Log::warning("SePay Webhook: Received amount {$transferAmount} is less than required {$expectedAmount} for Booking #{$bookingId}");
            return response()->json([
                'success' => false, 
                'message' => "Underpaid: received {$transferAmount}, expected {$expectedAmount}"
            ], 400);
        }

        // 7. Complete the booking
        $sepayId = $request->input('id');
        $referenceCode = $request->input('referenceCode');
        $transactionId = $referenceCode ?: ('SEPAY_' . ($sepayId ?: uniqid()));

        app(BookingController::class)->completePaidBooking(
            $booking, 
            'MBBank (SePay QR)', 
            $transactionId
        );

        Log::info("SePay Webhook: Successfully paid Booking #{$bookingId} via MB Bank! (Amount: {$transferAmount} VND)");

        return response()->json([
            'success' => true, 
            'message' => 'Payment processed successfully',
            'booking_id' => $booking->id,
            'status' => 'paid'
        ]);
    }
}
