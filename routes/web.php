<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\CinemaController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\SepayController;
use App\Http\Controllers\PageController;

// SePay Webhook Endpoint (Called by SePay when receiving money into MB Bank)
Route::post('/api/sepay/webhook', [SepayController::class, 'webhook'])->name('sepay.webhook');
Route::post('/sepay/webhook', [SepayController::class, 'webhook']);

// Thông tin HCTV Việt Nam
Route::get('/gioi-thieu', [PageController::class, 'about'])->name('pages.about');
Route::get('/tien-ich-online', [PageController::class, 'onlineServices'])->name('pages.online_services');
Route::get('/the-qua-tang', [PageController::class, 'giftCards'])->name('pages.gift_cards');
Route::get('/tuyen-dung', [PageController::class, 'careers'])->name('pages.careers');
Route::get('/lien-he-quang-cao', [PageController::class, 'advertising'])->name('pages.advertising');
Route::get('/danh-cho-doi-tac', [PageController::class, 'partners'])->name('pages.partners');

// Điều khoản sử dụng & Quy định
Route::get('/dieu-khoan-chung', [PageController::class, 'terms'])->name('pages.terms');
Route::get('/dieu-khoan-giao-dich', [PageController::class, 'termsTransaction'])->name('pages.terms_transaction');
Route::get('/chinh-sach-thanh-toan', [PageController::class, 'paymentPolicy'])->name('pages.payment_policy');
Route::get('/chinh-sach-bao-mat', [PageController::class, 'privacyPolicy'])->name('pages.privacy_policy');
Route::get('/quy-dinh-rap-phim', [PageController::class, 'cinemaRules'])->name('pages.cinema_rules');
Route::get('/cau-hoi-thuong-gap', [PageController::class, 'faq'])->name('pages.faq');

// 01. Trang chủ
Route::get('/', [HomeController::class, 'index'])->name('home');

// 02 & 03. Phim
Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');
Route::get('/movies/suggest', [MovieController::class, 'suggest'])->name('movies.suggest');
Route::get('/movies/{id}', [MovieController::class, 'show'])->name('movies.show');

// 04 & 05. Rạp
Route::get('/cinemas', [CinemaController::class, 'index'])->name('cinemas.index');
Route::get('/cinemas/{id}', [CinemaController::class, 'show'])->name('cinemas.show');

// 12. Khuyến mãi
Route::get('/promotions', [PromotionController::class, 'index'])->name('promotions.index');

// 06. Lịch chiếu tổng hợp
Route::get('/showtimes', [BookingController::class, 'showtimes'])->name('showtimes');

// Booking Flow (Requires Login)
Route::middleware('auth')->group(function () {
    // 07. Chọn ghế
    Route::get('/booking/seats/{showtime_id}', [BookingController::class, 'seats'])->name('booking.seats');
    Route::post('/booking/seats/{showtime_id}', [BookingController::class, 'processSeats'])->name('booking.process_seats');
    
    // 08. Chọn đồ ăn
    Route::get('/booking/food/{booking_id}', [BookingController::class, 'food'])->name('booking.food');
    Route::post('/booking/food/{booking_id}', [BookingController::class, 'processFood'])->name('booking.process_food');
    
    // 09. Thanh toán
    Route::get('/booking/checkout/{booking_id}', [BookingController::class, 'checkout'])->name('booking.checkout');
    Route::post('/booking/checkout/{booking_id}', [BookingController::class, 'processPayment'])->name('booking.process_payment');
    Route::post('/booking/checkout/{booking_id}/apply-points', [BookingController::class, 'applyPoints'])->name('booking.apply_points');
    Route::post('/booking/checkout/{booking_id}/remove-points', [BookingController::class, 'removePoints'])->name('booking.remove_points');
    Route::post('/booking/checkout/{booking_id}/apply-voucher', [BookingController::class, 'applyVoucher'])->name('booking.apply_voucher');
    Route::post('/booking/checkout/{booking_id}/remove-voucher', [BookingController::class, 'removeVoucher'])->name('booking.remove_voucher');
    Route::get('/booking/pay-qr/{booking_id}', [BookingController::class, 'payQr'])->name('booking.pay_qr');
    Route::get('/booking/check-status/{booking_id}', [BookingController::class, 'checkStatus'])->name('booking.check_status');
    Route::post('/booking/simulate-qr-paid/{booking_id}', [BookingController::class, 'simulateQrPaid'])->name('booking.simulate_qr_paid');
    Route::get('/booking/vnpay-sandbox/{booking_id}', [BookingController::class, 'vnpaySandboxGateway'])->name('booking.vnpay_sandbox');
    Route::get('/booking/vnpay-return', [BookingController::class, 'vnpayReturn'])->name('booking.vnpay_return');
    
    // 10. Đặt vé thành công
    Route::get('/booking/success/{booking_id}', [BookingController::class, 'success'])->name('booking.success');

    // 11. Tài khoản cá nhân & Ví voucher
    Route::get('/account/tickets', [AccountController::class, 'tickets'])->name('account.tickets');
    Route::get('/account/vouchers', [AccountController::class, 'vouchers'])->name('account.vouchers');
    Route::post('/account/vouchers/save', [AccountController::class, 'saveVoucher'])->name('account.vouchers.save');
    Route::delete('/account/vouchers/{id}', [AccountController::class, 'removeVoucher'])->name('account.vouchers.remove');

    // Mặc định login redirect to dashboard -> đổi sang home
    Route::get('/dashboard', function () {
        return redirect()->route('home');
    })->name('dashboard');

    // Profile & Password Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password/send-otp', [ProfileController::class, 'sendPasswordOtp'])->name('profile.password.send_otp');
    Route::post('/profile/password/update-with-otp', [ProfileController::class, 'updatePasswordWithOtp'])->name('profile.password.update_otp');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('movies', \App\Http\Controllers\Admin\MovieController::class);
    Route::resource('genres', \App\Http\Controllers\Admin\GenreController::class);
    Route::resource('cinemas', \App\Http\Controllers\Admin\CinemaController::class);
    // Phòng chiếu & Thiết kế sơ đồ ghế (Room & Seat Grid Designer)
    Route::post('cinemas/{cinema}/rooms', [\App\Http\Controllers\Admin\RoomController::class, 'store'])->name('cinemas.rooms.store');
    Route::put('rooms/{room}', [\App\Http\Controllers\Admin\RoomController::class, 'update'])->name('rooms.update');
    Route::delete('rooms/{room}', [\App\Http\Controllers\Admin\RoomController::class, 'destroy'])->name('rooms.destroy');
    Route::get('rooms/{room}/layout', [\App\Http\Controllers\Admin\RoomController::class, 'getLayout'])->name('rooms.layout.get');
    Route::post('rooms/{room}/layout', [\App\Http\Controllers\Admin\RoomController::class, 'saveLayout'])->name('rooms.layout.save');

    // Tạo suất chiếu tự động
    Route::post('showtimes/generate-auto', [\App\Http\Controllers\Admin\ShowtimeController::class, 'generateAutoSchedule'])->name('showtimes.generate_auto');
    Route::post('showtimes/save-auto', [\App\Http\Controllers\Admin\ShowtimeController::class, 'saveAutoSchedule'])->name('showtimes.save_auto');

    Route::resource('showtimes', \App\Http\Controllers\Admin\ShowtimeController::class);
    Route::resource('foods', \App\Http\Controllers\Admin\FoodController::class);
    Route::resource('promotions', \App\Http\Controllers\Admin\PromotionController::class);
    Route::resource('bookings', \App\Http\Controllers\Admin\BookingController::class)->only(['index', 'show', 'update']);
});

require __DIR__.'/auth.php';
