@extends('layouts.app')

@section('title', 'Thanh Toán - HCTV')

@section('content')
<div class="pt-24 pb-12 bg-cinematic-dark min-h-screen">
    <div class="max-w-5xl mx-auto px-6 md:px-12">
        
        <h2 class="text-3xl font-serif font-bold text-white mb-6 text-center uppercase tracking-widest">Thanh Toán</h2>

        @if(session('error'))
            <div class="bg-red-500/20 border border-red-500 text-red-200 px-5 py-4 rounded-xl mb-6 shadow-lg flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-500/20 border border-green-500 text-green-200 px-5 py-4 rounded-xl mb-6 shadow-lg flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Payment Methods -->
            <div class="flex-1 space-y-6">
                <div class="bg-white/5 border border-white/10 rounded-xl p-6 md:p-8 shadow-xl">
                    <h3 class="text-xl font-bold text-white mb-6">Phương thức thanh toán</h3>
                    
                    <form id="paymentForm" action="{{ route('booking.process_payment', $booking->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="payment_method" value="VNPay">

                        <!-- VNPay Option (Duy nhất) -->
                        <div class="p-5 border-2 border-cinematic-red bg-red-500/5 rounded-2xl relative shadow-lg">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-10 bg-white rounded-lg flex items-center justify-center p-1.5 shadow-md border border-gray-200">
                                        <span class="font-black text-blue-700 text-base tracking-tight">VN<span class="text-red-600">PAY</span></span>
                                    </div>
                                    <div>
                                        <span class="vnpay-method-title text-white font-bold block text-lg flex items-center gap-2">
                                            Cổng thanh toán VNPAY (Demo Sandbox)
                                            <span class="vnpay-status-badge px-2.5 py-0.5 bg-green-500/20 border border-green-500/40 text-green-400 text-[11px] font-semibold rounded-full uppercase tracking-wider whitespace-nowrap">Đang chọn</span>
                                        </span>
                                        <span class="vnpay-method-sub text-gray-400 text-xs">Hỗ trợ quét mã VNPAY-QR, thẻ ATM nội địa & Thẻ quốc tế Visa/Mastercard</span>
                                    </div>
                                </div>
                                <div class="w-6 h-6 rounded-full bg-cinematic-red text-white flex items-center justify-center shrink-0 shadow">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            </div>

                            <!-- VNPAY Demo Test Guide Accordion -->
                            <div class="vnpay-guide-box mt-5 pt-4 border-t border-white/10 text-xs text-gray-300 bg-black/40 rounded-xl p-4 space-y-2">
                                <div class="flex items-center justify-between">
                                    <p class="vnpay-guide-title font-bold text-cinematic-gold flex items-center gap-1.5 text-sm">
                                        <span>💳</span> Thông tin thẻ test VNPAY Sandbox:
                                    </p>
                                    <span class="vnpay-guide-note text-[11px] text-gray-400">Tự động chuyển màn hình test khi bấm thanh toán</span>
                                </div>
                                <div class="vnpay-guide-grid grid grid-cols-2 gap-3 mt-2 font-mono text-[12px] bg-black/30 p-3 rounded-lg border border-white/5">
                                    <div>Ngân hàng: <span class="vnpay-grid-value text-white font-bold">NCB</span></div>
                                    <div>Số thẻ: <span class="vnpay-grid-value text-white font-bold tracking-wider">9704198526191432198</span></div>
                                    <div>Tên chủ thẻ: <span class="vnpay-grid-value text-white font-bold">NGUYEN VAN A</span></div>
                                    <div>Ngày phát hành: <span class="vnpay-grid-value text-white font-bold">07/15</span></div>
                                    <div>Mã OTP: <span class="vnpay-grid-otp text-white font-bold text-cinematic-gold">123456</span></div>
                                    <div>Trạng thái: <span class="vnpay-grid-status text-green-400 font-bold">Thành công</span></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Summary Sidebar -->
            <div class="w-full lg:w-96 shrink-0">
                <div class="checkout-summary-card bg-white/5 border border-white/10 rounded-xl overflow-hidden shadow-2xl sticky top-28">
                    <!-- Movie Image & Title -->
                    <div class="h-32 relative">
                        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ $booking->showtime->movie->backdrop_url ?? $booking->showtime->movie->poster_url }}');"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-[#121520] via-black/60 to-transparent"></div>
                        <div class="relative z-10 p-5 flex flex-col justify-end h-full">
                            <h3 class="checkout-movie-title text-xl font-bold text-white leading-tight drop-shadow">{{ $booking->showtime->movie->title }}</h3>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="mb-4 text-sm space-y-1">
                            <p class="text-gray-400">Rạp: <span class="checkout-detail-value text-white font-semibold ml-1">{{ $booking->showtime->room->cinema->name }}</span></p>
                            <p class="text-gray-400">Phòng chiếu: <span class="checkout-detail-value text-white font-semibold ml-1">{{ $booking->showtime->room->name }}</span></p>
                            <p class="text-gray-400">Suất chiếu: <span class="checkout-detail-value text-white font-semibold ml-1">{{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('H:i | d/m/Y') }}</span></p>
                        </div>
                        
                        <div class="border-t border-white/10 py-4 text-sm">
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-400">Ghế đã chọn ({{ $booking->tickets->count() }}):</span>
                                <span class="checkout-seats-value text-white font-bold text-right text-cinematic-gold">
                                    {{ $booking->tickets->map(function($t) { return $t->seat ? ($t->seat->row . $t->seat->number) : ''; })->filter()->join(', ') }}
                                </span>
                            </div>

                            @if(isset($ticketTotal))
                            <div class="flex justify-between mb-2 text-xs text-gray-400">
                                <span>Tiền vé:</span>
                                <span class="checkout-detail-value text-white font-medium">{{ number_format($ticketTotal, 0, ',', '.') }} đ</span>
                            </div>
                            @endif
                            
                            @if(isset($bookingFoods) && $bookingFoods->count() > 0)
                            <div class="mt-3 pt-3 border-t border-white/5 text-gray-400 text-xs">Bắp nước & Combo:</div>
                                @foreach($bookingFoods as $f)
                                <div class="flex justify-between mt-1 text-xs">
                                    <span class="text-gray-300">{{ $f->quantity }}x {{ $f->name }}</span>
                                    <span class="checkout-detail-value text-white font-medium">{{ number_format($f->price * $f->quantity, 0, ',', '.') }} đ</span>
                                </div>
                                @endforeach
                            @endif
                        </div>
                        
                        <div class="mt-2 pt-4 border-t border-white/20">
                            <div class="flex justify-between items-center">
                                <span class="checkout-total-label text-gray-300 font-medium">Tổng thanh toán:</span>
                                <span class="text-2xl md:text-3xl font-bold text-cinematic-red">{{ number_format($booking->total_price, 0, ',', '.') }} đ</span>
                            </div>
                        </div>

                        <div class="mt-3 text-xs text-gray-400 text-center flex items-center justify-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                              <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>Mã vé sẽ được gửi về: <strong class="checkout-email-value text-white">{{ auth()->user()->email }}</strong></span>
                        </div>
                        
                        <button type="submit" form="paymentForm" class="w-full mt-6 py-4 bg-cinematic-red text-white font-bold rounded-xl shadow-[0_0_20px_rgba(229,9,20,0.5)] hover:bg-red-700 transition-all uppercase tracking-wider cursor-pointer flex items-center justify-center gap-2 text-sm md:text-base hover:scale-[1.02]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            Thanh Toán VNPAY Test
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
