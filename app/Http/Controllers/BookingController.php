<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Showtime;
use App\Models\Movie;
use App\Models\Cinema;
use App\Models\Seat;
use App\Models\Food;
use App\Models\Booking;
use App\Models\Ticket;
use App\Mail\TicketConfirmationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    /**
     * Helper to compute ticket price based on base showtime price and seat type.
     */
    private function calculateTicketPrice($showtimePrice, $seat)
    {
        $price = (float)$showtimePrice;
        if ($seat) {
            $type = strtolower($seat->type ?? 'standard');
            if ($type === 'vip') {
                $price += 20000;
            } elseif ($type === 'sweetbox') {
                $price += 40000;
            } elseif ($type === 'deluxe') {
                $price += 30000;
            }
        }
        return $price;
    }

    public function showtimes()
    {
        $movies = Movie::with(['showtimes.room.cinema'])->get();
        return view('booking.showtimes', compact('movies'));
    }

    public function seats($showtime_id)
    {
        $showtime = Showtime::with(['movie', 'room.cinema', 'room.seats'])->findOrFail($showtime_id);
        
        // Fetch booked seats
        // We get tickets related to this showtime through booking that are NOT cancelled
        $bookedSeatIds = Ticket::whereHas('booking', function($query) use ($showtime_id) {
            $query->where('showtime_id', $showtime_id)
                  ->where('status', '!=', 'cancelled');
        })->pluck('seat_id')->toArray();

        return view('booking.seats', compact('showtime', 'bookedSeatIds'));
    }

    public function processSeats(Request $request, $showtime_id)
    {
        $selectedSeats = $request->input('seats', []);
        if (empty($selectedSeats)) {
            return back()->with('error', 'Vui lòng chọn ít nhất 1 ghế.');
        }

        $showtime = Showtime::findOrFail($showtime_id);
        
        // Calculate total seat price including seat type surcharges (VIP, Deluxe, Sweetbox)
        $seats = Seat::whereIn('id', $selectedSeats)->get()->keyBy('id');
        $totalSeatPrice = 0;
        foreach ($selectedSeats as $seatId) {
            $seat = $seats->get($seatId);
            $totalSeatPrice += $this->calculateTicketPrice($showtime->price, $seat);
        }

        $booking = new Booking();
        $booking->user_id = auth()->id();
        $booking->showtime_id = $showtime_id;
        $booking->total_price = $totalSeatPrice;
        $booking->status = 'pending';
        $booking->save();

        foreach ($selectedSeats as $seat_id) {
            $ticket = new Ticket();
            $ticket->booking_id = $booking->id;
            $ticket->seat_id = $seat_id;
            $ticket->status = 'booked';
            $ticket->save();
        }

        return redirect()->route('booking.food', $booking->id);
    }

    public function food($booking_id)
    {
        $booking = Booking::with(['showtime.movie', 'tickets.seat'])->findOrFail($booking_id);
        
        // Security check
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        // Calculate accurate base ticket total
        $ticketTotal = 0;
        foreach ($booking->tickets as $ticket) {
            $ticketTotal += $this->calculateTicketPrice($booking->showtime->price, $ticket->seat);
        }

        $foods = Food::all();
        return view('booking.food', compact('booking', 'foods', 'ticketTotal'));
    }

    public function processFood(Request $request, $booking_id)
    {
        $booking = Booking::with(['showtime', 'tickets.seat'])->findOrFail($booking_id);
        
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        // Calculate base ticket total
        $ticketTotal = 0;
        foreach ($booking->tickets as $ticket) {
            $ticketTotal += $this->calculateTicketPrice($booking->showtime->price, $ticket->seat);
        }

        $foods = $request->input('foods', []);
        $totalFoodPrice = 0;
        
        // Clear previous food selections for this booking to prevent duplicate addition
        DB::table('booking_food')->where('booking_id', $booking->id)->delete();

        foreach ($foods as $food_id => $quantity) {
            $quantity = (int)$quantity;
            if ($quantity > 0) {
                $food = Food::find($food_id);
                if ($food) {
                    $totalFoodPrice += $food->price * $quantity;
                    DB::table('booking_food')->insert([
                        'booking_id' => $booking->id,
                        'food_id' => $food->id,
                        'quantity' => $quantity,
                        'price' => $food->price,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
        }
        
        $booking->total_price = $ticketTotal + $totalFoodPrice;
        $booking->save();

        return redirect()->route('booking.checkout', $booking->id);
    }

    public function checkout($booking_id)
    {
        $booking = Booking::with(['tickets.seat', 'showtime.movie', 'showtime.room.cinema'])->findOrFail($booking_id);
        
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        // Calculate base ticket price
        $ticketTotal = 0;
        foreach ($booking->tickets as $ticket) {
            $ticketTotal += $this->calculateTicketPrice($booking->showtime->price, $ticket->seat);
        }

        // Get booked foods
        $bookingFoods = DB::table('booking_food')
            ->join('food', 'booking_food.food_id', '=', 'food.id')
            ->where('booking_id', $booking->id)
            ->select('food.name', 'booking_food.quantity', 'booking_food.price')
            ->get();

        return view('booking.checkout', compact('booking', 'bookingFoods', 'ticketTotal'));
    }

    public function processPayment(Request $request, $booking_id)
    {
        $booking = Booking::with(['tickets.seat', 'showtime.movie', 'showtime.room.cinema', 'user'])->findOrFail($booking_id);
        
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $paymentMethod = $request->input('payment_method', 'VNPay');
        $isSimulate = $request->input('simulate', false);

        // Case 1: Fast simulate mode or non-VNPAY methods
        if ($isSimulate || in_array($paymentMethod, ['MoMo', 'Credit Card', 'VNPay_Simulate'])) {
            $booking->status = 'paid';
            $booking->payment_method = $paymentMethod === 'VNPay_Simulate' ? 'VNPay (Demo)' : $paymentMethod;
            $booking->save();

            // Add a record to payments table
            DB::table('payments')->insert([
                'user_id' => auth()->id(),
                'booking_id' => $booking->id,
                'payment_method' => $booking->payment_method,
                'amount' => $booking->total_price,
                'transaction_id' => 'HCTV_DEMO_' . strtoupper(uniqid()),
                'status' => 'success',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Send ticket confirmation email to registered user
            $this->sendTicketConfirmationMail($booking);

            return redirect()->route('booking.success', $booking->id)
                             ->with('success', 'Thanh toán thành công! Mã vé đã được gửi về Gmail của bạn.');
        }

        // Case 2: Official VNPay Sandbox Gateway or Dedicated VNPay Test Screen
        if ($paymentMethod === 'VNPay') {
            $vnp_TmnCode = config('services.vnpay.tmn_code', 'CTTVNP01');
            $vnp_HashSecret = config('services.vnpay.hash_secret', '');
            $vnp_Url = config('services.vnpay.url', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html');
            $vnp_ReturnUrl = route('booking.vnpay_return');
            
            $vnp_TxnRef = $booking->id . '_' . time();
            $inputData = [
                "vnp_Version" => "2.1.0",
                "vnp_TmnCode" => $vnp_TmnCode,
                "vnp_Amount" => (int)($booking->total_price * 100),
                "vnp_Command" => "pay",
                "vnp_CreateDate" => date('YmdHis'),
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr" => $request->ip() ?: '127.0.0.1',
                "vnp_Locale" => "vn",
                "vnp_OrderInfo" => "Thanh toan ve xem phim HCTV Booking #" . $booking->id,
                "vnp_OrderType" => "other",
                "vnp_ReturnUrl" => $vnp_ReturnUrl,
                "vnp_TxnRef" => $vnp_TxnRef,
                "vnp_ExpireDate" => date('YmdHis', strtotime('+15 minutes')),
            ];

            if ($request->filled('bank_code')) {
                $inputData['vnp_BankCode'] = $request->input('bank_code');
            }

            ksort($inputData);
            $query = "";
            $i = 0;
            $hashdata = "";
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashdata .= urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
                $query .= urlencode($key) . "=" . urlencode($value) . '&';
            }

            // Save pending payment record
            DB::table('payments')->updateOrInsert(
                ['booking_id' => $booking->id],
                [
                    'user_id' => auth()->id(),
                    'payment_method' => 'VNPay',
                    'amount' => $booking->total_price,
                    'transaction_id' => $vnp_TxnRef,
                    'status' => 'pending',
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            // If an active merchant secret is provided in .env, redirect to official sandbox URL
            if (!empty($vnp_HashSecret) && $vnp_HashSecret !== 'VNPAY_DEMO_SECRET') {
                $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
                $redirectUrl = $vnp_Url . "?" . $query . 'vnp_SecureHash=' . $vnpSecureHash;
                return redirect()->away($redirectUrl);
            }

            // Otherwise, redirect to the dedicated VNPay Sandbox Test Gateway interface
            return redirect()->route('booking.vnpay_sandbox', $booking->id);
        }

        return redirect()->route('booking.checkout', $booking->id);
    }

    /**
     * Display the VNPay Sandbox Gateway test screen.
     */
    public function vnpaySandboxGateway($booking_id)
    {
        $booking = Booking::with(['tickets.seat', 'showtime.movie', 'showtime.room.cinema', 'user'])->findOrFail($booking_id);

        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status === 'paid') {
            return redirect()->route('booking.success', $booking->id);
        }

        $bookingFoods = DB::table('booking_food')
            ->join('food', 'booking_food.food_id', '=', 'food.id')
            ->where('booking_food.booking_id', $booking->id)
            ->select('food.name', 'booking_food.quantity', 'booking_food.price')
            ->get();

        $ticketTotal = $booking->tickets->sum('price');
        $vnp_TxnRef = $booking->id . '_' . time();

        return view('booking.vnpay-sandbox', compact('booking', 'bookingFoods', 'ticketTotal', 'vnp_TxnRef'));
    }

    /**
     * Handle return response from VNPay Gateway.
     */
    public function vnpayReturn(Request $request)
    {
        $vnp_TxnRef = $request->input('vnp_TxnRef');
        $vnp_ResponseCode = $request->input('vnp_ResponseCode');
        $vnp_TransactionNo = $request->input('vnp_TransactionNo');
        
        if (empty($vnp_TxnRef)) {
            return redirect()->route('home')->with('error', 'Không tìm thấy thông tin giao dịch.');
        }

        // Parse booking ID from TxnRef (format: bookingId_timestamp)
        $parts = explode('_', $vnp_TxnRef);
        $bookingId = (int)$parts[0];

        $booking = Booking::with(['tickets.seat', 'showtime.movie', 'showtime.room.cinema', 'user'])->findOrFail($bookingId);

        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        // Response code '00' indicates success in VNPay
        if ($vnp_ResponseCode === '00') {
            $booking->status = 'paid';
            $booking->payment_method = 'VNPay';
            $booking->save();

            // Update payment record to success
            DB::table('payments')->updateOrInsert(
                ['booking_id' => $booking->id],
                [
                    'user_id' => auth()->id(),
                    'payment_method' => 'VNPay',
                    'amount' => $booking->total_price,
                    'transaction_id' => $vnp_TransactionNo ?: $vnp_TxnRef,
                    'status' => 'success',
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            // Send confirmation email with ticket details and QR code to registered user's Gmail
            $this->sendTicketConfirmationMail($booking);

            return redirect()->route('booking.success', $booking->id)
                             ->with('success', 'Thanh toán qua VNPAY thành công! Vé điện tử đã được gửi về Gmail của bạn.');
        } else {
            // Payment failed or was cancelled
            return redirect()->route('booking.checkout', $booking->id)
                             ->with('error', "Thanh toán qua VNPAY không thành công (Mã lỗi: {$vnp_ResponseCode}). Quý khách vui lòng thử lại!");
        }
    }

    /**
     * Send email confirmation with ticket code and details to user.
     */
    private function sendTicketConfirmationMail($booking)
    {
        try {
            $booking->loadMissing(['tickets.seat', 'showtime.movie', 'showtime.room.cinema', 'user']);
            
            $bookingFoods = DB::table('booking_food')
                ->join('food', 'booking_food.food_id', '=', 'food.id')
                ->where('booking_id', $booking->id)
                ->select('food.name', 'booking_food.quantity', 'booking_food.price')
                ->get();

            if ($booking->user && !empty($booking->user->email)) {
                Mail::to($booking->user->email)->send(new TicketConfirmationMail($booking, $bookingFoods));
                Log::info("Ticket confirmation email sent successfully to {$booking->user->email} for Booking #{$booking->id}");
            }
        } catch (\Throwable $e) {
            Log::error('Lỗi gửi email xác nhận vé: ' . $e->getMessage());
        }
    }

    public function success($booking_id)
    {
        $booking = Booking::with(['tickets.seat', 'showtime.movie', 'showtime.room.cinema'])->findOrFail($booking_id);
        
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        return view('booking.success', compact('booking'));
    }
}
