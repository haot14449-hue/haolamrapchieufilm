@extends('layouts.app')

@section('title', 'Vé Của Tôi - HCTV')

@section('content')
<div class="pt-24 pb-12 bg-cinematic-dark min-h-screen">
    <div class="max-w-7xl mx-auto px-6 md:px-12 flex flex-col md:flex-row gap-8">
        
        <!-- Sidebar -->
        <div class="w-full md:w-64 shrink-0">
            <div class="bg-white/5 border border-white/10 rounded-xl p-6 sticky top-28">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 bg-cinematic-red rounded-full flex items-center justify-center text-xl font-bold text-white shadow-[0_0_15px_rgba(229,9,20,0.5)]">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-bold text-white">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-cinematic-gold">{{ auth()->user()->points }} Điểm</p>
                    </div>
                </div>
                
                <nav class="space-y-2">
                    <a href="{{ route('account.tickets') }}" class="block px-4 py-2 rounded bg-white/10 text-white font-medium border-l-4 border-cinematic-red">Vé của tôi</a>
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 rounded text-gray-400 hover:bg-white/5 hover:text-white transition-colors">Đổi mật khẩu / Hồ sơ</a>
                    <form method="POST" action="{{ route('logout') }}" class="mt-8">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 rounded text-red-400 hover:bg-white/5 hover:text-red-300 transition-colors">Đăng xuất</button>
                    </form>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1">
            <h2 class="text-3xl font-serif font-bold text-white mb-8">Lịch Sử Đặt Vé</h2>
            
            @if($bookings->isEmpty())
                <div class="bg-white/5 border border-white/10 rounded-xl p-12 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                    <p class="text-gray-400 mb-6">Bạn chưa có giao dịch nào.</p>
                    <a href="{{ route('movies.index') }}" class="px-6 py-2 bg-cinematic-red text-white font-bold rounded shadow-[0_0_15px_rgba(229,9,20,0.4)] hover:bg-red-700 transition-colors">
                        Đặt Vé Ngay
                    </a>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($bookings as $booking)
                        <div class="bg-white/5 border border-white/10 rounded-xl p-6 flex flex-col md:flex-row gap-6 shadow-lg hover:border-white/20 transition-colors">
                            <div class="w-full md:w-32 shrink-0 aspect-[2/3] rounded-lg overflow-hidden border border-white/10">
                                <img src="{{ $booking->showtime->movie->poster_url }}" alt="{{ $booking->showtime->movie->title }}" class="w-full h-full object-cover">
                            </div>
                            
                            <div class="flex-1">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="text-xl font-bold text-white">{{ $booking->showtime->movie->title }}</h3>
                                    <span class="px-2 py-1 rounded text-xs font-bold uppercase
                                        {{ $booking->status == 'paid' ? 'bg-green-500/20 text-green-400 border border-green-500/50' : 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/50' }}">
                                        {{ $booking->status }}
                                    </span>
                                </div>
                                
                                <p class="text-sm text-gray-400 mb-4">Mã giao dịch: <span class="text-white">HCTV-{{ sprintf('%06d', $booking->id) }}</span></p>
                                
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm mb-4">
                                    <div>
                                        <p class="text-gray-500">Rạp</p>
                                        <p class="text-white">{{ $booking->showtime->room->cinema->name }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">Ngày giờ</p>
                                        <p class="text-white">{{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('H:i | d/m/Y') }}</p>
                                    </div>
                                    <div class="col-span-2">
                                        <p class="text-gray-500">Ghế</p>
                                        <p class="text-white">{{ $booking->tickets->map(function($t) { return $t->seat->row . $t->seat->number; })->join(', ') }}</p>
                                    </div>
                                </div>
                                
                                <div class="border-t border-white/10 pt-4 flex justify-between items-center mt-auto">
                                    <p class="text-gray-400 text-sm">Tổng thanh toán: <span class="text-xl font-bold text-cinematic-gold ml-2">{{ number_format($booking->total_price, 0, ',', '.') }} đ</span></p>
                                    @if($booking->status == 'paid')
                                        <a href="{{ route('booking.success', $booking->id) }}" class="text-sm text-cinematic-red hover:text-red-400 font-bold transition-colors">Xem vé điện tử &rarr;</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            
        </div>
    </div>
</div>
@endsection
