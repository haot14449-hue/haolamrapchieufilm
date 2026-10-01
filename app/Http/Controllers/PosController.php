<?php

namespace App\Http\Controllers;

use App\Mail\TicketConfirmationMail;
use App\Models\Booking;
use App\Models\Cinema;
use App\Models\Food;
use App\Models\Movie;
use App\Models\Seat;
use App\Models\Showtime;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PosController extends Controller
{
    /**
     * Authorize that only staff or admin can access POS counter.
     */
    protected function authorizeStaffOrAdmin(): void
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'staff'])) {
            abort(403, 'Chỉ Nhân viên và Quản trị viên mới có quyền truy cập trang Bán vé tại quầy (POS).');
        }
    }

    /**
     * Display the POS counter terminal.
     */
    public function index(Request $request)
    {
        $this->authorizeStaffOrAdmin();

        // Release expired pending bookings across the system
        Booking::cleanupExpired();

        $cinemas = Cinema::orderBy('name')->get();
        $selectedCinemaId = $request->input('cinema_id', $cinemas->first()?->id);

        // Date selection: today and next 6 days
        $dates = [];
        for ($i = 0; $i < 7; $i++) {
            $d = Carbon::today()->addDays($i);
            $dates[] = [
                'date' => $d->format('Y-m-d'),
                'day_name' => $i === 0 ? 'Hôm nay' : ($i === 1 ? 'Ngày mai' : 'Thứ ' . ($d->dayOfWeek === 0 ? 'CN' : $d->dayOfWeek + 1)),
                'formatted' => $d->format('d/m'),
            ];
        }

        $selectedDate = $request->input('date', Carbon::today()->format('Y-m-d'));

        // Concessions / Food combos
        $foods = Food::orderBy('price', 'desc')->get();

        // Bank info for SePay MB QR
        $sepayConfig = [
            'account_number' => config('services.sepay.account_number', '031205090305'),
            'bank_name' => config('services.sepay.bank_name', 'MB'),
            'account_holder' => config('services.sepay.account_holder', 'TRAN VAN HAO'),
        ];

        return view('pos.index', compact(
            'cinemas',
            'selectedCinemaId',
            'dates',
            'selectedDate',
            'foods',
            'sepayConfig'
        ));
    }

    /**
     * Look up existing customer by phone number.
     * When returning customer comes a second time, typing phone auto-fills all info.
     */
    public function lookupCustomer(Request $request)
    {
        $this->authorizeStaffOrAdmin();

        $rawPhone = trim($request->input('phone', ''));
        if (empty($rawPhone)) {
            return response()->json(['found' => false]);
        }

        $cleanedPhone = preg_replace('/[^0-9]/', '', $rawPhone);

        // Search in users table by exact or cleaned phone
        $user = User::where('phone', $rawPhone)
            ->orWhere('phone', $cleanedPhone)
            ->first();

        // Also check international vs local format (09... vs +849...)
        if (!$user && str_starts_with($cleanedPhone, '84')) {
            $local = '0' . substr($cleanedPhone, 2);
            $user = User::where('phone', $local)->orWhere('phone', 'like', "%{$local}%")->first();
        } elseif (!$user && str_starts_with($rawPhone, '0')) {
            $intl = '+84' . substr($rawPhone, 1);
            $user = User::where('phone', $intl)->first();
        }

        if ($user) {
            return response()->json([
                'found' => true,
                'customer' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'phone' => $user->phone,
                    'birthday' => $user->birthday ? Carbon::parse($user->birthday)->format('Y-m-d') : '',
                    'birthday_formatted' => $user->birthday ? Carbon::parse($user->birthday)->format('d/m/Y') : '',
                    'email' => $user->email,
                    'points' => (int) $user->points,
                    'bookings_count' => $user->bookings()->where('status', 'paid')->count(),
                ],
            ]);
        }

        return response()->json(['found' => false]);
    }

    /**
     * Get list of movies and upcoming showtimes for the selected date & cinema.
     */
    public function getShowtimes(Request $request)
    {
        $this->authorizeStaffOrAdmin();

        $cinemaId = $request->input('cinema_id');
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));

        $query = Showtime::query()
            ->whereDate('start_time', $date)
            ->with(['movie', 'room.cinema'])
            ->orderBy('start_time', 'asc');

        if (!empty($cinemaId)) {
            $query->whereHas('room', function ($q) use ($cinemaId) {
                $q->where('cinema_id', $cinemaId);
            });
        }

        // Active buffer: if today, show showtimes that haven't ended (start_time >= now()->subHours(4))
        if ($date === Carbon::today()->format('Y-m-d')) {
            $query->where('start_time', '>=', now()->subHours(4));
        }

        $showtimes = $query->get();

        // Group by movie
        $grouped = [];
        foreach ($showtimes as $st) {
            $movieId = $st->movie_id;
            if (!isset($grouped[$movieId])) {
                $grouped[$movieId] = [
                    'movie' => [
                        'id' => $st->movie->id,
                        'title' => $st->movie->title,
                        'duration' => $st->movie->duration,
                        'poster_url' => $st->movie->poster_url,
                        'genre' => $st->movie->genre,
                        'rating' => $st->movie->rating ?? 'P',
                    ],
                    'showtimes' => [],
                ];
            }

            // Calculate total seats & booked seats for quick capacity pill
            $totalSeats = $st->room->total_seats ?? $st->room->seats()->count();
            $bookedSeatsCount = Ticket::where('status', '!=', 'cancelled')
                ->whereHas('booking', fn($b) => $b->where('showtime_id', $st->id)->where('status', '!=', 'cancelled'))
                ->count();
            $availableSeats = max(0, $totalSeats - $bookedSeatsCount);

            $grouped[$movieId]['showtimes'][] = [
                'id' => $st->id,
                'start_time' => Carbon::parse($st->start_time)->format('H:i'),
                'end_time' => $st->end_time ? Carbon::parse($st->end_time)->format('H:i') : null,
                'room_name' => $st->room->name,
                'cinema_name' => $st->room->cinema->name ?? '',
                'format' => $st->format ?? ($st->room->type ?? '2D'),
                'price' => (float) $st->price,
                'price_formatted' => number_format($st->price, 0, ',', '.') . ' đ',
                'total_seats' => $totalSeats,
                'available_seats' => $availableSeats,
                'is_past' => Carbon::parse($st->start_time)->addHours(4)->isPast(),
                'is_started' => Carbon::parse($st->start_time)->isPast(),
            ];
        }

        return response()->json([
            'movies' => array_values($grouped),
        ]);
    }

    /**
     * Get seat grid layout and booked seat IDs for a selected showtime.
     */
    public function getShowtimeSeats($id, Request $request)
    {
        $this->authorizeStaffOrAdmin();

        Booking::cleanupExpired($id);

        $showtime = Showtime::with(['movie', 'room.cinema', 'room.seats'])->findOrFail($id);
        $cashierHoldId = $request->input('cashier_hold_id');

        // Fetch booked seats (exclude current cashier's active hold so cashier sees their selected seats)
        $bookedSeatIds = Ticket::where('status', '!=', 'cancelled')
            ->whereHas('booking', function ($query) use ($id, $cashierHoldId) {
                $query->where('showtime_id', $id)
                      ->where('status', '!=', 'cancelled');
                if (!empty($cashierHoldId)) {
                    $query->where('id', '!=', $cashierHoldId);
                }
            })->pluck('seat_id')->toArray();

        // Index seats by row_number
        $seatMap = [];
        foreach ($showtime->room->seats as $seat) {
            $seatMap[$seat->row . '_' . $seat->number] = $seat;
        }

        // Parse custom layout if present (grid of aisles, empty cells, and seats)
        $layoutRows = null;
        if (!empty($showtime->room->layout_data)) {
            $decoded = json_decode($showtime->room->layout_data, true);
            if (is_array($decoded)) {
                $layoutRows = isset($decoded['grid']) ? $decoded['grid'] : (isset($decoded[0]) ? $decoded : null);
            }
        }

        // Build grid of rows with processed seat cells (with full pricing, surcharges, and status)
        $gridRows = [];
        if ($layoutRows) {
            foreach ($layoutRows as $rIdx => $rowCells) {
                $rowLetter = chr(65 + $rIdx);
                foreach ($rowCells as $c) {
                    if (!empty($c['row'])) {
                        $rowLetter = $c['row'];
                        break;
                    }
                }
                $processedCells = [];
                foreach ($rowCells as $cIdx => $cell) {
                    if (empty($cell) || ($cell['type'] ?? '') === 'aisle' || ($cell['type'] ?? '') === 'empty') {
                        $processedCells[] = [
                            'is_empty' => true,
                            'type' => $cell['type'] ?? 'empty',
                        ];
                    } else {
                        $key = ($cell['row'] ?? $rowLetter) . '_' . ($cell['number'] ?? '');
                        $seat = $seatMap[$key] ?? null;
                        if ($seat) {
                            $type = strtolower($cell['type'] ?? ($seat->type ?? 'standard'));
                            $surcharge = match($type) {
                                'vip' => 20000,
                                'deluxe' => 30000,
                                'sweetbox' => 40000,
                                default => 0,
                            };
                            $seatPrice = (float) $showtime->price + $surcharge;
                            $isBooked = in_array($seat->id, $bookedSeatIds);

                            $processedCells[] = [
                                'is_empty' => false,
                                'id' => $seat->id,
                                'row' => $seat->row,
                                'number' => $seat->number,
                                'name' => $seat->row . $seat->number,
                                'type' => $type,
                                'surcharge' => $surcharge,
                                'price' => $seatPrice,
                                'price_formatted' => number_format($seatPrice, 0, ',', '.') . ' đ',
                                'is_booked' => $isBooked,
                            ];
                        } else {
                            $processedCells[] = [
                                'is_empty' => true,
                                'type' => 'empty',
                            ];
                        }
                    }
                }

                $gridRows[] = [
                    'row_letter' => $rowLetter,
                    'cells' => $processedCells,
                ];
            }
        }

        // Format seats list as fallback and for lookup
        $seatsData = $showtime->room->seats->map(function ($seat) use ($showtime, $bookedSeatIds) {
            $type = strtolower($seat->type ?? 'standard');
            $surcharge = match($type) {
                'vip' => 20000,
                'deluxe' => 30000,
                'sweetbox' => 40000,
                default => 0,
            };
            $seatPrice = (float) $showtime->price + $surcharge;

            return [
                'id' => $seat->id,
                'row' => $seat->row,
                'number' => $seat->number,
                'name' => $seat->row . $seat->number,
                'type' => $type,
                'surcharge' => $surcharge,
                'price' => $seatPrice,
                'price_formatted' => number_format($seatPrice, 0, ',', '.') . ' đ',
                'is_booked' => in_array($seat->id, $bookedSeatIds),
            ];
        });

        $numCols = 0;
        if (!empty($gridRows) && isset($gridRows[0]['cells'])) {
            $numCols = count($gridRows[0]['cells']);
        }

        return response()->json([
            'showtime' => [
                'id' => $showtime->id,
                'movie_title' => $showtime->movie->title,
                'cinema_name' => $showtime->room->cinema->name ?? '',
                'room_name' => $showtime->room->name,
                'start_time' => Carbon::parse($showtime->start_time)->format('H:i - d/m/Y'),
                'base_price' => (float) $showtime->price,
            ],
            'seats' => $seatsData,
            'booked_seat_ids' => $bookedSeatIds,
            'has_layout' => !empty($gridRows),
            'grid_rows' => $gridRows,
            'num_cols' => $numCols,
        ]);
    }

    /**
     * Reserve seats temporarily for POS cashier so online users cannot select them.
     */
    public function holdSeats(Request $request)
    {
        $this->authorizeStaffOrAdmin();

        $showtimeId = (int)$request->input('showtime_id');
        $seatIds = $request->input('seats', []);
        $holdBookingId = $request->input('hold_booking_id');

        Booking::cleanupExpired($showtimeId);

        $showtime = Showtime::findOrFail($showtimeId);

        // Find existing hold booking if any
        $holdBooking = null;
        if (!empty($holdBookingId)) {
            $holdBooking = Booking::where('id', $holdBookingId)
                ->where('status', 'pending')
                ->where('cashier_id', auth()->id())
                ->first();
        }

        // If no seats selected, release existing hold
        if (empty($seatIds)) {
            if ($holdBooking) {
                $holdBooking->releaseSeats();
            }
            return response()->json([
                'success' => true,
                'hold_booking_id' => null,
            ]);
        }

        // Check if any seat is already booked or held by someone else
        $conflictSeatIds = Ticket::whereIn('seat_id', $seatIds)
            ->where('status', '!=', 'cancelled')
            ->whereHas('booking', function ($q) use ($showtimeId, $holdBooking) {
                $q->where('showtime_id', $showtimeId)
                  ->where('status', '!=', 'cancelled');
                if ($holdBooking) {
                    $q->where('id', '!=', $holdBooking->id);
                }
            })->pluck('seat_id')->toArray();

        if (!empty($conflictSeatIds)) {
            $conflictSeats = Seat::whereIn('id', $conflictSeatIds)->get()->map(fn($s) => $s->row . $s->number)->join(', ');
            return response()->json([
                'success' => false,
                'conflict_seats' => $conflictSeatIds,
                'message' => "Các ghế [{$conflictSeats}] vừa có người đặt hoặc giữ chỗ online. Vui lòng chọn ghế khác!",
            ], 422);
        }

        // Create or update hold booking
        if (!$holdBooking) {
            $holdBooking = new Booking();
            $holdBooking->user_id = auth()->id();
            $holdBooking->cashier_id = auth()->id();
            $holdBooking->showtime_id = $showtimeId;
            $holdBooking->status = 'pending';
            $holdBooking->original_price = 0;
            $holdBooking->total_price = 0;
        }

        $holdBooking->expires_at = now()->addMinutes(10); // 10 minutes hold for cashier counter
        $holdBooking->save();

        // Sync tickets
        Ticket::where('booking_id', $holdBooking->id)
            ->whereNotIn('seat_id', $seatIds)
            ->delete();

        $existingTickets = Ticket::where('booking_id', $holdBooking->id)->pluck('seat_id')->toArray();
        foreach ($seatIds as $sid) {
            if (!in_array($sid, $existingTickets)) {
                Ticket::create([
                    'booking_id' => $holdBooking->id,
                    'seat_id' => $sid,
                    'status' => 'booked',
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'hold_booking_id' => $holdBooking->id,
        ]);
    }

    /**
     * Release temporary seat hold when cashier resets or cancels.
     */
    public function releaseHold(Request $request)
    {
        $this->authorizeStaffOrAdmin();

        $holdBookingId = $request->input('hold_booking_id');
        if (!empty($holdBookingId)) {
            $booking = Booking::where('id', $holdBookingId)
                ->where('status', 'pending')
                ->where('cashier_id', auth()->id())
                ->first();

            if ($booking) {
                $booking->releaseSeats();
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * Process checkout for the in-person counter sale.
     * Supports Cash (Tiền mặt), MB Bank QR (SePay), and VNPay.
     */
    public function checkout(Request $request)
    {
        $this->authorizeStaffOrAdmin();

        $validated = $request->validate([
            'showtime_id' => 'required|exists:showtimes,id',
            'seats' => 'required|array|min:1',
            'seats.*' => 'integer|exists:seats,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:25',
            'customer_birthday' => 'nullable|date',
            'customer_email' => 'required|email|max:255',
            'payment_method' => 'required|in:cash,sepay_mb,vnpay',
            'cash_given' => 'nullable|numeric|min:0',
            'hold_booking_id' => 'nullable|integer',
            'foods' => 'nullable|array',
            'foods.*.food_id' => 'required|exists:food,id',
            'foods.*.quantity' => 'required|integer|min:1',
        ], [
            'seats.required' => 'Vui lòng chọn ít nhất một ghế ngồi cho khách.',
            'customer_name.required' => 'Vui lòng nhập họ và tên khách hàng.',
            'customer_phone.required' => 'Vui lòng nhập số điện thoại khách hàng.',
            'customer_email.required' => 'Vui lòng nhập email khách hàng để nhận mã vé.',
            'customer_email.email' => 'Địa chỉ email không hợp lệ.',
        ]);

        $showtime = Showtime::with(['movie', 'room.cinema'])->findOrFail($validated['showtime_id']);

        $holdBookingId = $request->input('hold_booking_id');
        $holdBooking = null;
        if (!empty($holdBookingId)) {
            $holdBooking = Booking::where('id', $holdBookingId)
                ->where('status', 'pending')
                ->where('cashier_id', auth()->id())
                ->first();
        }

        // Check if any selected seat is already booked by someone else
        $conflictSeatIds = Ticket::whereIn('seat_id', $validated['seats'])
            ->where('status', '!=', 'cancelled')
            ->whereHas('booking', function ($q) use ($showtime, $holdBooking) {
                $q->where('showtime_id', $showtime->id)
                  ->where('status', '!=', 'cancelled');
                if ($holdBooking) {
                    $q->where('id', '!=', $holdBooking->id);
                }
            })->pluck('seat_id')->toArray();

        if (!empty($conflictSeatIds)) {
            $conflictSeats = Seat::whereIn('id', $conflictSeatIds)->get()->map(fn($s) => $s->row . $s->number)->join(', ');
            return response()->json([
                'success' => false,
                'message' => "Các ghế [{$conflictSeats}] vừa có người đặt hoặc giữ chỗ online. Vui lòng chọn ghế khác!",
            ], 422);
        }

        // 1. Process Customer (Find existing or register new automatically)
        $cleanPhone = preg_replace('/[^0-9]/', '', $validated['customer_phone']);
        $email = strtolower(trim($validated['customer_email']));

        $customer = User::where('phone', $validated['customer_phone'])
            ->orWhere('phone', $cleanPhone)
            ->first();

        if (!$customer) {
            // Check if user exists by email
            $customer = User::where('email', $email)->first();
        }

        if ($customer) {
            // Update customer details if provided
            $customer->name = trim($validated['customer_name']);
            if (!empty($validated['customer_phone'])) {
                $customer->phone = trim($validated['customer_phone']);
            }
            if (!empty($validated['customer_birthday'])) {
                $customer->birthday = Carbon::parse($validated['customer_birthday']);
            }
            $customer->save();
        } else {
            // Register new customer account
            $customer = User::create([
                'name' => trim($validated['customer_name']),
                'email' => $email,
                'phone' => trim($validated['customer_phone']),
                'birthday' => !empty($validated['customer_birthday']) ? Carbon::parse($validated['customer_birthday']) : null,
                'role' => 'customer',
                'password' => Hash::make(Str::random(16)),
                'email_verified_at' => now(),
            ]);
        }

        // 2. Calculate seat total with surcharges
        $seats = Seat::whereIn('id', $validated['seats'])->get();
        $totalSeatPrice = 0;
        foreach ($seats as $seat) {
            $price = (float) $showtime->price;
            $type = strtolower($seat->type ?? 'standard');
            if ($type === 'vip') {
                $price += 20000;
            } elseif ($type === 'sweetbox') {
                $price += 40000;
            } elseif ($type === 'deluxe') {
                $price += 30000;
            }
            $totalSeatPrice += $price;
        }

        // 3. Calculate foods total
        $totalFoodPrice = 0;
        $orderFoods = [];
        if (!empty($validated['foods'])) {
            foreach ($validated['foods'] as $item) {
                $food = Food::find($item['food_id']);
                if ($food && $item['quantity'] > 0) {
                    $itemPrice = (float) $food->price * $item['quantity'];
                    $totalFoodPrice += $itemPrice;
                    $orderFoods[] = [
                        'food' => $food,
                        'quantity' => $item['quantity'],
                        'price' => (float) $food->price,
                    ];
                }
            }
        }

        $grandTotal = $totalSeatPrice + $totalFoodPrice;

        // Payment details & cash accounting
        $paymentMethodLabel = match ($validated['payment_method']) {
            'cash' => 'Tiền mặt',
            'sepay_mb' => 'MB Bank QR (SePay)',
            'vnpay' => 'VNPay',
            default => 'Tiền mặt',
        };

        $cashGiven = null;
        $cashChange = null;
        if ($validated['payment_method'] === 'cash') {
            $cashGiven = !empty($validated['cash_given']) ? (float) $validated['cash_given'] : $grandTotal;
            $cashChange = max(0, $cashGiven - $grandTotal);
        }

        // 4. Create or Upgrade to Paid Booking
        if ($holdBooking) {
            $booking = $holdBooking;
        } else {
            $booking = new Booking();
            $booking->cashier_id = auth()->id();
            $booking->showtime_id = $showtime->id;
        }

        $booking->user_id = $customer->id;
        $booking->original_price = $grandTotal;
        $booking->total_price = $grandTotal;
        $booking->status = 'paid'; // Immediately paid at counter
        $booking->payment_method = $paymentMethodLabel;
        $booking->cash_given = $cashGiven;
        $booking->cash_change = $cashChange;
        $booking->expires_at = null;
        $booking->save();

        // 5. Ensure Tickets are created/updated
        Ticket::where('booking_id', $booking->id)->delete();
        foreach ($seats as $seat) {
            Ticket::create([
                'booking_id' => $booking->id,
                'seat_id' => $seat->id,
                'status' => 'booked',
            ]);
        }

        // 6. Attach Food Items
        foreach ($orderFoods as $foodOrder) {
            DB::table('booking_food')->insert([
                'booking_id' => $booking->id,
                'food_id' => $foodOrder['food']->id,
                'quantity' => $foodOrder['quantity'],
                'price' => $foodOrder['price'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 7. Create Payment Record
        DB::table('payments')->insert([
            'booking_id' => $booking->id,
            'user_id' => $customer->id,
            'payment_method' => $paymentMethodLabel,
            'amount' => $grandTotal,
            'transaction_id' => 'POS_' . strtoupper(uniqid()),
            'status' => 'success',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 8. Award loyalty points (1 point per 1,000 VND)
        $pointsEarned = (int) floor($grandTotal / 1000);
        $customer->increment('points', $pointsEarned);
        $booking->points_earned = $pointsEarned;
        $booking->points_processed = true;
        $booking->save();

        // 9. Send Ticket Confirmation Email with QR code
        $this->sendTicketConfirmationMail($booking);

        $ticketCode = 'HCTV-' . sprintf('%06d', $booking->id);

        return response()->json([
            'success' => true,
            'message' => 'Xuất vé thành công! Mã vé đã được gửi về email của khách hàng.',
            'booking' => [
                'id' => $booking->id,
                'ticket_code' => $ticketCode,
                'customer_name' => $customer->name,
                'customer_phone' => $customer->phone,
                'customer_email' => $customer->email,
                'movie_title' => $showtime->movie->title,
                'cinema_name' => $showtime->room->cinema->name ?? '',
                'room_name' => $showtime->room->name,
                'showtime' => Carbon::parse($showtime->start_time)->format('H:i - d/m/Y'),
                'seats' => $seats->map(fn($s) => $s->row . $s->number)->join(', '),
                'total_price' => $grandTotal,
                'total_price_formatted' => number_format($grandTotal, 0, ',', '.') . ' đ',
                'payment_method' => $paymentMethodLabel,
                'cash_given' => $cashGiven ? number_format($cashGiven, 0, ',', '.') . ' đ' : null,
                'cash_change' => $cashChange ? number_format($cashChange, 0, ',', '.') . ' đ' : null,
                'points_earned' => $pointsEarned,
                'print_url' => route('pos.print', $booking->id),
            ],
        ]);
    }

    /**
     * Send email confirmation with ticket code and details to user.
     */
    private function sendTicketConfirmationMail(Booking $booking)
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
                Log::info("POS Ticket email sent to {$booking->user->email} for Booking #{$booking->id}");
            }
        } catch (\Throwable $e) {
            Log::error('Lỗi gửi email xác nhận vé POS: ' . $e->getMessage());
        }
    }

    /**
     * Printable thermal / standard ticket view for physical ticket printing at counter.
     */
    public function printTicket($booking_id)
    {
        $this->authorizeStaffOrAdmin();

        $booking = Booking::with([
            'tickets.seat',
            'showtime.movie',
            'showtime.room.cinema',
            'user',
            'cashier',
        ])->findOrFail($booking_id);

        $bookingFoods = DB::table('booking_food')
            ->join('food', 'booking_food.food_id', '=', 'food.id')
            ->where('booking_food.booking_id', $booking->id)
            ->select('food.name', 'booking_food.quantity', 'booking_food.price')
            ->get();

        $ticketCode = 'HCTV-' . sprintf('%06d', $booking->id);

        return view('pos.print-ticket', compact('booking', 'bookingFoods', 'ticketCode'));
    }
}
