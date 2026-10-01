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
        $now = now();
        // Only fetch movies that have upcoming showtimes, and only eager load future showtimes
        $movies = Movie::whereHas('showtimes', function ($query) use ($now) {
            $query->where('start_time', '>=', $now);
        })->with(['showtimes' => function ($query) use ($now) {
            $query->where('start_time', '>=', $now)
                  ->with(['room.cinema'])
                  ->orderBy('start_time', 'asc');
        }])->get();

        return view('booking.showtimes', compact('movies'));
    }

    /**
     * Helper to release expired pending bookings (past 5 minutes hold time).
     */
    private function cleanupExpiredBookings(?int $showtimeId = null): void
    {
        Booking::cleanupExpired($showtimeId);
    }

    public function seats($showtime_id)
    {
        $this->cleanupExpiredBookings($showtime_id);

        $showtime = Showtime::with(['movie', 'room.cinema', 'room.seats'])->findOrFail($showtime_id);

        // Disallow accessing past showtimes
        if ($showtime->isPast()) {
            return redirect()->route('showtimes')
                             ->with('error', 'Suất chiếu này đã qua thời gian chiếu. Vui lòng chọn suất chiếu khác!');
        }
        
        // Fetch booked seats (tickets belonging to paid bookings or active pending bookings within 5 minutes)
        $bookedSeatIds = Ticket::where('status', '!=', 'cancelled')
            ->whereHas('booking', function ($query) use ($showtime_id) {
                $query->where('showtime_id', $showtime_id)
                      ->where('status', '!=', 'cancelled');
            })->pluck('seat_id')->toArray();

        return view('booking.seats', compact('showtime', 'bookedSeatIds'));
    }

    public function processSeats(Request $request, $showtime_id)
    {
        $this->cleanupExpiredBookings($showtime_id);

        $showtime = Showtime::findOrFail($showtime_id);

        // Disallow booking past showtimes
        if ($showtime->isPast()) {
            return redirect()->route('showtimes')
                             ->with('error', 'Suất chiếu này đã qua thời gian chiếu. Vui lòng chọn suất chiếu khác!');
        }

        $selectedSeats = $request->input('seats', []);
        if (empty($selectedSeats)) {
            return back()->with('error', 'Vui lòng chọn ít nhất 1 ghế.');
        }
        
        // Check for seat conflict: ensure none of the selected seats are already booked or held
        $conflictSeatIds = Ticket::whereIn('seat_id', $selectedSeats)
            ->where('status', '!=', 'cancelled')
            ->whereHas('booking', function ($q) use ($showtime_id) {
                $q->where('showtime_id', $showtime_id)
                  ->where('status', '!=', 'cancelled');
            })->pluck('seat_id')->toArray();

        if (!empty($conflictSeatIds)) {
            $conflictSeats = Seat::whereIn('id', $conflictSeatIds)->get();
            $seatNames = $conflictSeats->map(fn($s) => $s->row . $s->number)->join(', ');
            return back()->with('error', "Ghế [{$seatNames}] hiện đang được người khác giữ chỗ hoặc đã được đặt. Vui lòng chọn ghế khác!");
        }

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
        $booking->original_price = $totalSeatPrice;
        $booking->total_price = $totalSeatPrice;
        $booking->status = 'pending';
        $booking->expires_at = now()->addMinutes(5); // Hold seats for 5 minutes
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

        // Check if 5-minute seat hold time has expired
        if ($booking->status === 'cancelled' || $booking->isExpired()) {
            $booking->releaseSeats();
            return redirect()->route('booking.seats', $booking->showtime_id)
                             ->with('error', 'Thời gian giữ ghế (5 phút) đã hết. Ghế của bạn đã được hoàn trả về trạng thái trống, vui lòng chọn lại!');
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

        // Check if 5-minute seat hold time has expired
        if ($booking->status === 'cancelled' || $booking->isExpired()) {
            $booking->releaseSeats();
            return redirect()->route('booking.seats', $booking->showtime_id)
                             ->with('error', 'Thời gian giữ ghế (5 phút) đã hết. Ghế của bạn đã được hoàn trả về trạng thái trống, vui lòng chọn lại!');
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
        
        $subtotal = $ticketTotal + $totalFoodPrice;
        $booking->original_price = $subtotal;
        $booking->total_price = max(0, $subtotal - ($booking->discount_amount ?? 0));
        $booking->save();

        return redirect()->route('booking.checkout', $booking->id);
    }

    public function checkout($booking_id)
    {
        $booking = Booking::with(['tickets.seat', 'showtime.movie', 'showtime.room.cinema'])->findOrFail($booking_id);
        
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        // Check if 5-minute seat hold time has expired
        if ($booking->status === 'cancelled' || $booking->isExpired()) {
            $booking->releaseSeats();
            return redirect()->route('booking.seats', $booking->showtime_id)
                             ->with('error', 'Thời gian giữ ghế (5 phút) đã hết. Ghế của bạn đã được hoàn trả về trạng thái trống, vui lòng chọn lại!');
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

        $foodTotal = $bookingFoods->sum(fn($f) => $f->price * $f->quantity);
        $subtotal = $ticketTotal + $foodTotal;

        // Ensure original_price is set
        if (empty($booking->original_price) || $booking->original_price <= 0) {
            $booking->original_price = $subtotal;
            $booking->total_price = $subtotal - ($booking->discount_amount ?? 0);
            $booking->save();
        }

        // User points and max usable points (10 points = 10,000 VND => 1 point = 1,000 VND)
        $userPoints = (int)(auth()->user()->points ?? 0);
        $maxUsablePoints = min($userPoints, (int)floor($booking->original_price / 1000));
        $earnedPoints = $booking->tickets->count() * 10;

        // User's active vouchers in wallet
        $userVouchers = \App\Models\UserVoucher::where('user_id', auth()->id())
            ->where('is_used', false)
            ->with('promotion')
            ->get()
            ->filter(function ($uv) {
                return $uv->promotion && (!$uv->promotion->end_date || \Carbon\Carbon::parse($uv->promotion->end_date)->endOfDay()->isFuture());
            });

        // All currently valid promotions from cinema
        $availablePromotions = \App\Models\Promotion::active()->get();

        return view('booking.checkout', compact(
            'booking', 'bookingFoods', 'ticketTotal', 'foodTotal', 'subtotal', 
            'userPoints', 'maxUsablePoints', 'earnedPoints', 'userVouchers', 'availablePromotions'
        ));
    }

    /**
     * Apply reward points to discount this booking.
     */
    public function applyPoints(Request $request, $booking_id)
    {
        $booking = Booking::with(['tickets.seat'])->findOrFail($booking_id);
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status === 'paid') {
            return back()->with('error', 'Đơn hàng này đã được thanh toán.');
        }

        if ($booking->status === 'cancelled' || $booking->isExpired()) {
            $booking->releaseSeats();
            $msg = 'Thời gian giữ ghế (5 phút) đã hết. Ghế của bạn đã được hoàn trả về trạng thái trống, vui lòng chọn lại!';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'expired' => true, 'redirect' => route('booking.seats', $booking->showtime_id), 'message' => $msg], 410);
            }
            return redirect()->route('booking.seats', $booking->showtime_id)->with('error', $msg);
        }

        $user = auth()->user();
        $userPoints = (int)($user->points ?? 0);

        // Ensure original price is valid
        if (empty($booking->original_price) || $booking->original_price <= 0) {
            $ticketTotal = 0;
            foreach ($booking->tickets as $ticket) {
                $ticketTotal += $this->calculateTicketPrice($booking->showtime->price, $ticket->seat);
            }
            $foodTotal = DB::table('booking_food')
                ->where('booking_id', $booking->id)
                ->sum(DB::raw('price * quantity'));
            $booking->original_price = $ticketTotal + $foodTotal;
        }

        $pointsToUse = (int)$request->input('points', 0);
        $maxPoints = min($userPoints, (int)floor($booking->original_price / 1000));

        if ($pointsToUse < 0) {
            $pointsToUse = 0;
        }
        if ($pointsToUse > $maxPoints) {
            $pointsToUse = $maxPoints;
        }

        $discountAmount = $pointsToUse * 1000;
        $finalPrice = max(0, $booking->original_price - $discountAmount);

        $booking->points_used = $pointsToUse;
        $booking->discount_amount = $discountAmount;
        $booking->total_price = $finalPrice;
        $booking->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'points_used' => $pointsToUse,
                'discount_amount' => $discountAmount,
                'total_price' => $finalPrice,
                'formatted_discount' => number_format($discountAmount, 0, ',', '.') . ' đ',
                'formatted_total' => number_format($finalPrice, 0, ',', '.') . ' đ',
                'message' => $pointsToUse > 0 
                    ? "Đã áp dụng {$pointsToUse} điểm tích lũy (giảm " . number_format($discountAmount, 0, ',', '.') . " đ)."
                    : "Đã hủy áp dụng điểm tích lũy."
            ]);
        }

        return back()->with('success', "Đã áp dụng {$pointsToUse} điểm tích lũy (giảm " . number_format($discountAmount, 0, ',', '.') . " đ) vào hóa đơn!");
    }

    /**
     * Remove reward points discount from this booking.
     */
    public function removePoints(Request $request, $booking_id)
    {
        $booking = Booking::findOrFail($booking_id);
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status === 'paid') {
            return back()->with('error', 'Đơn hàng này đã được thanh toán.');
        }

        if ($booking->status === 'cancelled' || $booking->isExpired()) {
            $booking->releaseSeats();
            $msg = 'Thời gian giữ ghế (5 phút) đã hết. Ghế của bạn đã được hoàn trả về trạng thái trống, vui lòng chọn lại!';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'expired' => true, 'redirect' => route('booking.seats', $booking->showtime_id), 'message' => $msg], 410);
            }
            return redirect()->route('booking.seats', $booking->showtime_id)->with('error', $msg);
        }

        if (!empty($booking->original_price)) {
            $booking->total_price = $booking->original_price;
        }
        $booking->points_used = 0;
        $booking->discount_amount = 0;
        $booking->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'total_price' => $booking->total_price,
                'formatted_total' => number_format($booking->total_price, 0, ',', '.') . ' đ',
                'message' => 'Đã hủy áp dụng điểm tích lũy.'
            ]);
        }

        return back()->with('success', 'Đã hủy sử dụng điểm tích lũy.');
    }

    /**
     * Apply a voucher from user's wallet or by promo code.
     */
    public function applyVoucher(Request $request, $booking_id)
    {
        $booking = Booking::with(['tickets.seat', 'showtime'])->findOrFail($booking_id);
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status === 'paid') {
            return back()->with('error', 'Đơn hàng này đã được thanh toán.');
        }

        if ($booking->status === 'cancelled' || $booking->isExpired()) {
            $booking->releaseSeats();
            $msg = 'Thời gian giữ ghế (5 phút) đã hết. Ghế của bạn đã được hoàn trả về trạng thái trống, vui lòng chọn lại!';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'expired' => true, 'redirect' => route('booking.seats', $booking->showtime_id), 'message' => $msg], 410);
            }
            return redirect()->route('booking.seats', $booking->showtime_id)->with('error', $msg);
        }

        // Find promo
        $promo = null;
        if ($request->filled('promotion_id')) {
            $promo = \App\Models\Promotion::find($request->promotion_id);
        } elseif ($request->filled('code')) {
            $code = strtoupper(trim($request->code));
            $promo = \App\Models\Promotion::where('code', $code)->first();
        }

        if (!$promo) {
            $msg = 'Mã voucher không tồn tại hoặc đã hết hiệu lực.';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 404);
            }
            return back()->with('error', $msg);
        }

        if ($promo->isExpired()) {
            $msg = 'Voucher này đã hết hạn sử dụng.';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 400);
            }
            return back()->with('error', $msg);
        }

        if ($promo->isUpcoming()) {
            $msg = 'Voucher này chưa đến ngày bắt đầu áp dụng.';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 400);
            }
            return back()->with('error', $msg);
        }

        // Ensure original price is set
        if (empty($booking->original_price) || $booking->original_price <= 0) {
            $ticketTotal = 0;
            foreach ($booking->tickets as $ticket) {
                $ticketTotal += $this->calculateTicketPrice($booking->showtime->price, $ticket->seat);
            }
            $foodTotal = DB::table('booking_food')
                ->where('booking_id', $booking->id)
                ->sum(DB::raw('price * quantity'));
            $booking->original_price = $ticketTotal + $foodTotal;
        }

        // Calculate discount amount
        $discountAmount = 0;
        if ($promo->discount_percent) {
            $discountAmount = round(($booking->original_price * $promo->discount_percent) / 100);
        } elseif ($promo->discount_amount) {
            $discountAmount = min($booking->original_price, $promo->discount_amount);
        }

        // Reset points when applying voucher
        $booking->promotion_id = $promo->id;
        $booking->points_used = 0;
        $booking->discount_amount = $discountAmount;
        $booking->total_price = max(0, $booking->original_price - $discountAmount);
        $booking->save();

        // Also save to user wallet if not yet saved
        \App\Models\UserVoucher::firstOrCreate([
            'user_id' => auth()->id(),
            'promotion_id' => $promo->id,
        ], [
            'is_used' => false,
            'saved_at' => now(),
        ]);

        $msg = "Đã áp dụng thành công voucher '{$promo->title}' (Giảm " . number_format($discountAmount, 0, ',', '.') . " đ)!";

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'discount_amount' => $discountAmount,
                'total_price' => $booking->total_price,
                'voucher_title' => $promo->title,
                'voucher_code' => $promo->code,
                'formatted_discount' => number_format($discountAmount, 0, ',', '.') . ' đ',
                'formatted_total' => number_format($booking->total_price, 0, ',', '.') . ' đ',
                'message' => $msg,
            ]);
        }

        return back()->with('success', $msg);
    }

    /**
     * Remove voucher discount from this booking.
     */
    public function removeVoucher(Request $request, $booking_id)
    {
        $booking = Booking::findOrFail($booking_id);
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status === 'paid') {
            return back()->with('error', 'Đơn hàng này đã được thanh toán.');
        }

        $booking->promotion_id = null;
        $booking->discount_amount = 0;
        $booking->total_price = $booking->original_price;
        $booking->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'total_price' => $booking->total_price,
                'formatted_total' => number_format($booking->total_price, 0, ',', '.') . ' đ',
                'message' => 'Đã hủy áp dụng mã voucher.',
            ]);
        }

        return back()->with('success', 'Đã hủy áp dụng mã voucher thành công.');
    }

    /**
     * Helper to complete a paid booking: update status, debit/credit points, send mail.
     */
    public function completePaidBooking(Booking $booking, string $paymentMethod, ?string $transactionId = null)
    {
        $booking->status = 'paid';
        $booking->payment_method = $paymentMethod;

        // Ensure all tickets are marked as booked
        $booking->tickets()->update(['status' => 'booked']);

        // Process reward points atomically (debit used points & credit 10 points per ticket)
        if (!$booking->points_processed) {
            $user = $booking->user ?? \App\Models\User::find($booking->user_id);
            if ($user) {
                // 1. Deduct redeemed points
                if ($booking->points_used > 0) {
                    $user->points = max(0, (int)$user->points - $booking->points_used);
                }

                // 2. Award 10 points per ticket successfully booked
                $ticketCount = $booking->tickets->count();
                $earnedPoints = $ticketCount * 10;
                $user->points += $earnedPoints;
                $user->save();

                $booking->points_earned = $earnedPoints;
                $booking->points_processed = true;
            }
        }

        // Mark used voucher if applied
        if (!empty($booking->promotion_id)) {
            $uv = \App\Models\UserVoucher::where('user_id', $booking->user_id)
                ->where('promotion_id', $booking->promotion_id)
                ->where('is_used', false)
                ->first();
            if ($uv) {
                $uv->update([
                    'is_used' => true,
                    'used_at' => now(),
                    'booking_id' => $booking->id,
                ]);
            } else {
                \App\Models\UserVoucher::create([
                    'user_id' => $booking->user_id,
                    'promotion_id' => $booking->promotion_id,
                    'is_used' => true,
                    'used_at' => now(),
                    'booking_id' => $booking->id,
                ]);
            }
        }

        $booking->save();

        // Add or update payment record
        DB::table('payments')->updateOrInsert(
            ['booking_id' => $booking->id],
            [
                'user_id' => $booking->user_id,
                'payment_method' => $paymentMethod,
                'amount' => $booking->total_price,
                'transaction_id' => $transactionId ?: ('HCTV_' . strtoupper(uniqid())),
                'status' => 'success',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        // Send ticket confirmation email
        $this->sendTicketConfirmationMail($booking);
    }

    public function processPayment(Request $request, $booking_id)
    {
        $booking = Booking::with(['tickets.seat', 'showtime.movie', 'showtime.room.cinema', 'user'])->findOrFail($booking_id);
        
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        // Check if 5-minute seat hold time has expired
        if ($booking->status === 'cancelled' || $booking->isExpired()) {
            $booking->releaseSeats();
            return redirect()->route('booking.seats', $booking->showtime_id)
                             ->with('error', 'Thời gian giữ ghế (5 phút) đã hết. Ghế của bạn đã được hoàn trả về trạng thái trống, vui lòng chọn lại!');
        }

        // Case 0: 100% covered by voucher or reward points (total_price == 0)
        if ($booking->total_price <= 0) {
            $paymentMethodName = !empty($booking->promotion_id) ? 'Voucher Khuyến Mãi' : 'Điểm tích lũy (Reward Points)';
            $txId = !empty($booking->promotion_id) ? ('VOUCHER_' . strtoupper(uniqid())) : ('POINTS_' . strtoupper(uniqid()));
            $this->completePaidBooking($booking, $paymentMethodName, $txId);
            return redirect()->route('booking.success', $booking->id)
                             ->with('success', 'Thanh toán thành công 100%! Mã vé đã được gửi về Gmail của bạn.');
        }

        $paymentMethod = $request->input('payment_method', 'MB_QR');
        $isSimulate = $request->input('simulate', false);

        // Case 1: Fast simulate mode or non-VNPAY methods
        if ($isSimulate || in_array($paymentMethod, ['MoMo', 'Credit Card', 'VNPay_Simulate'])) {
            $methodName = $paymentMethod === 'VNPay_Simulate' ? 'VNPay' : $paymentMethod;
            $this->completePaidBooking($booking, $methodName, 'HCTV_' . strtoupper(uniqid()));

            return redirect()->route('booking.success', $booking->id)
                             ->with('success', 'Thanh toán thành công! Mã vé đã được gửi về Gmail của bạn.');
        }

        // Case 2: MB Bank QR Payment with SePay
        if (in_array($paymentMethod, ['MB_QR', 'MBBank', 'MB', 'SePay'])) {
            return redirect()->route('booking.pay_qr', $booking->id);
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

        // Check if 5-minute seat hold time has expired
        if ($booking->status === 'cancelled' || $booking->isExpired()) {
            $booking->releaseSeats();
            return redirect()->route('booking.seats', $booking->showtime_id)
                             ->with('error', 'Thời gian giữ ghế (5 phút) đã hết. Ghế của bạn đã được hoàn trả về trạng thái trống, vui lòng chọn lại!');
        }

        $bookingFoods = DB::table('booking_food')
            ->join('food', 'booking_food.food_id', '=', 'food.id')
            ->where('booking_food.booking_id', $booking->id)
            ->select('food.name', 'booking_food.quantity', 'booking_food.price')
            ->get();

        $ticketTotal = 0;
        foreach ($booking->tickets as $ticket) {
            $ticketTotal += $this->calculateTicketPrice($booking->showtime->price, $ticket->seat);
        }
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
            $this->completePaidBooking($booking, 'VNPay', $vnp_TransactionNo ?: $vnp_TxnRef);

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

    /**
     * Display the MB Bank QR Payment screen with SePay automated integration.
     */
    public function payQr($booking_id)
    {
        $booking = Booking::with(['tickets.seat', 'showtime.movie', 'showtime.room.cinema', 'user'])->findOrFail($booking_id);

        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        // If already paid, redirect straight to success page
        if ($booking->status === 'paid') {
            return redirect()->route('booking.success', $booking->id);
        }

        // Check if 5-minute seat hold time has expired
        if ($booking->status === 'cancelled' || $booking->isExpired()) {
            $booking->releaseSeats();
            return redirect()->route('booking.seats', $booking->showtime_id)
                             ->with('error', 'Thời gian giữ ghế (5 phút) đã hết. Ghế của bạn đã được hoàn trả về trạng thái trống, vui lòng chọn lại!');
        }

        // Food orders
        $bookingFoods = DB::table('booking_food')
            ->join('food', 'booking_food.food_id', '=', 'food.id')
            ->where('booking_food.booking_id', $booking->id)
            ->select('food.name', 'booking_food.quantity', 'booking_food.price')
            ->get();

        $ticketTotal = 0;
        foreach ($booking->tickets as $ticket) {
            $ticketTotal += $this->calculateTicketPrice($booking->showtime->price, $ticket->seat);
        }

        // Bank Account Information for MB
        $bankAccount = config('services.sepay.account_number', '031205090305');
        $bankName = config('services.sepay.bank_name', 'MB');
        $accountHolder = config('services.sepay.account_holder', 'TRAN VAN HAO');
        $transferContent = 'HCTV' . $booking->id;
        $amount = (int)$booking->total_price;

        // Dynamic QR code URLs
        $sepayQrUrl = "https://qr.sepay.vn/img?acc={$bankAccount}&bank={$bankName}&amount={$amount}&des={$transferContent}&template=compact";
        $vietQrUrl = "https://img.vietqr.io/image/{$bankName}-{$bankAccount}-compact2.png?amount={$amount}&addInfo={$transferContent}&accountName=" . urlencode($accountHolder);

        return view('booking.pay-qr', compact(
            'booking', 
            'bookingFoods', 
            'ticketTotal', 
            'bankAccount', 
            'bankName', 
            'accountHolder', 
            'transferContent', 
            'sepayQrUrl', 
            'vietQrUrl'
        ));
    }

    /**
     * Ajax polling endpoint to check if the booking has been paid via SePay webhook.
     */
    public function checkStatus($booking_id)
    {
        $booking = Booking::findOrFail($booking_id);

        if ($booking->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($booking->status === 'pending' && $booking->isExpired()) {
            $booking->releaseSeats();
            $booking->refresh();
        }

        return response()->json([
            'id' => $booking->id,
            'status' => $booking->status,
            'is_paid' => $booking->status === 'paid',
            'is_expired' => $booking->status === 'cancelled' || $booking->isExpired(),
            'remaining_seconds' => $booking->remaining_seconds,
            'redirect_url' => route('booking.success', $booking->id),
        ]);
    }

    /**
     * Fast test/demo helper to simulate SePay payment completion on localhost.
     */
    public function simulateQrPaid(Request $request, $booking_id)
    {
        $booking = Booking::with(['tickets.seat', 'showtime.movie', 'showtime.room.cinema', 'user'])->findOrFail($booking_id);

        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status === 'cancelled' || $booking->isExpired()) {
            $booking->releaseSeats();
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Đơn hàng đã hết hạn giữ chỗ (quá 5 phút).'], 400);
            }
            return redirect()->route('booking.seats', $booking->showtime_id)
                             ->with('error', 'Thời gian giữ ghế (5 phút) đã hết. Ghế của bạn đã được hoàn trả về trạng thái trống, vui lòng chọn lại!');
        }

        if ($booking->status === 'paid') {
            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'redirect' => route('booking.success', $booking->id)]);
            }
            return redirect()->route('booking.success', $booking->id);
        }

        $this->completePaidBooking($booking, 'MBBank (VietQR)', 'SEPAY_' . strtoupper(uniqid()));

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Xác nhận nhận tiền thành công!',
                'redirect' => route('booking.success', $booking->id),
            ]);
        }

        return redirect()->route('booking.success', $booking->id)
                         ->with('success', 'Thanh toán quét mã QR MB Bank thành công qua SePay! Vé đã gửi về email.');
    }
}

