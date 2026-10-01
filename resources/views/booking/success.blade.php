@extends('layouts.app')

@section('title', 'Đặt Vé Thành Công - HCTV')

@section('content')
<div class="pt-24 pb-12 bg-cinematic-dark min-h-screen flex items-center justify-center">
    <div class="max-w-3xl w-full px-6">
        
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-5 shadow-[0_0_35px_rgba(34,197,94,0.6)]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h2 class="success-title text-3xl font-serif font-bold text-white mb-2">Thanh Toán Thành Công!</h2>
            <p class="success-desc text-gray-400">Cảm ơn bạn đã lựa chọn HCTV Cinema. Dưới đây là vé điện tử của bạn.</p>

            <!-- Gmail Alert Box -->
            <div class="success-email-alert mt-4 inline-flex items-center gap-2 bg-green-500/10 border border-green-500/40 text-green-300 px-5 py-2.5 rounded-full text-sm font-medium shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                </svg>
                <span>Mã vé và thông tin chi tiết đã được gửi về Gmail: <strong class="text-white">{{ auth()->user()->email }}</strong></span>
            </div>
        </div>

        @php
            $bookingFoods = \DB::table('booking_food')
                ->join('food', 'booking_food.food_id', '=', 'food.id')
                ->where('booking_id', $booking->id)
                ->select('food.name', 'booking_food.quantity', 'booking_food.price')
                ->get();
        @endphp

        <!-- E-Ticket Card -->
        <div class="relative bg-white text-black rounded-2xl overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.8)] flex flex-col md:flex-row">
            <!-- Left cutout -->
            <div class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-1/2 w-8 h-8 bg-cinematic-dark rounded-full hidden md:block"></div>
            <!-- Right cutout -->
            <div class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-1/2 w-8 h-8 bg-cinematic-dark rounded-full hidden md:block"></div>
            <!-- Horizontal cutouts (Mobile) -->
            <div class="absolute top-1/2 left-0 -translate-y-1/2 -translate-x-1/2 w-8 h-8 bg-cinematic-dark rounded-full md:hidden"></div>
            <div class="absolute top-1/2 right-0 -translate-y-1/2 translate-x-1/2 w-8 h-8 bg-cinematic-dark rounded-full md:hidden"></div>
            
            <!-- Ticket Info -->
            <div class="flex-1 p-6 md:p-8 border-b md:border-b-0 md:border-r border-dashed border-gray-300 relative">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-widest">Mã đặt vé</p>
                        <p class="text-2xl font-black text-cinematic-red font-mono">HCTV-{{ sprintf('%06d', $booking->id) }}</p>
                    </div>
                    <div class="text-right">
                        <span class="inline-block px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full uppercase">
                            {{ $booking->payment_method ?? 'Đã thanh toán' }}
                        </span>
                    </div>
                </div>
                
                <h3 class="text-2xl font-serif font-bold mb-4 text-gray-900 leading-tight">{{ $booking->showtime->movie->title }}</h3>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Ngày chiếu</p>
                        <p class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Giờ chiếu</p>
                        <p class="font-bold text-cinematic-red text-lg">{{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Rạp</p>
                        <p class="font-bold text-gray-800">{{ $booking->showtime->room->cinema->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Phòng chiếu</p>
                        <p class="font-bold text-gray-800">{{ $booking->showtime->room->name }}</p>
                    </div>
                </div>
                
                <!-- Seats -->
                <div class="bg-gray-100 p-3.5 rounded-xl mb-3">
                    <p class="text-xs text-gray-500 uppercase font-semibold">Ghế đã chọn ({{ $booking->tickets->count() }} vé)</p>
                    <p class="font-black text-xl text-gray-900 mt-0.5 tracking-wider">
                        {{ $booking->tickets->map(function($t) { return $t->seat ? ($t->seat->row . $t->seat->number) : ''; })->filter()->join(', ') }}
                    </p>
                </div>

                <!-- Foods if any -->
                @if($bookingFoods->count() > 0)
                <div class="bg-gray-50 border border-gray-200 p-3 rounded-xl mb-3 text-xs text-gray-700">
                    <p class="text-gray-500 uppercase font-semibold mb-1">Bắp nước & Combo:</p>
                    @foreach($bookingFoods as $f)
                        <div class="flex justify-between">
                            <span>{{ $f->quantity }}x {{ $f->name }}</span>
                            <span class="font-bold">{{ number_format($f->price * $f->quantity, 0, ',', '.') }} đ</span>
                        </div>
                    @endforeach
                </div>
                @endif

                <!-- Total amount -->
                <div class="flex justify-between items-center pt-2 border-t border-gray-200 text-sm">
                    <span class="text-gray-600 font-medium">Tổng tiền đã thanh toán:</span>
                    <span class="text-xl font-black text-cinematic-red">{{ number_format($booking->total_price, 0, ',', '.') }} VNĐ</span>
                </div>
            </div>
            
            <!-- QR & Code Scanner Section -->
            <div class="w-full md:w-64 shrink-0 p-6 md:p-8 flex flex-col items-center justify-center bg-gray-50 relative">
                <p class="text-xs text-gray-500 uppercase mb-3 text-center font-bold tracking-wider">Quét mã tại cổng</p>
                
                <!-- Dynamic QR Code -->
                <div class="p-2 bg-white rounded-xl shadow-md border border-gray-200 mb-3">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode('HCTV-' . sprintf('%06d', $booking->id)) }}" 
                         alt="QR Code" class="w-32 h-32 block">
                </div>

                <p class="text-[11px] text-center text-gray-500 leading-snug">
                    Vui lòng xuất trình mã này cho nhân viên soát vé tại rạp
                </p>
            </div>
        </div>
        
        <div class="mt-10 flex flex-wrap justify-center gap-4">
            <a href="{{ route('home') }}" class="btn-home-link px-8 py-3 border border-white/20 text-white rounded-xl hover:bg-white/10 transition-colors font-medium">
                Về Trang Chủ
            </a>
            <a href="{{ route('account.tickets') }}" class="px-8 py-3 bg-cinematic-red text-white font-bold rounded-xl shadow-[0_0_15px_rgba(229,9,20,0.4)] hover:bg-red-700 transition-colors">
                Xem Lịch Sử Mua Vé
            </a>
        </div>
        
    </div>
</div>
@endsection
