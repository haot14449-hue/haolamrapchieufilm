@extends('layouts.app')

@section('title', 'Vé Của Tôi - HCTV')

@section('content')
<div class="pt-24 pb-12 bg-cinematic-dark min-h-screen">
    <div class="max-w-7xl mx-auto px-6 md:px-12 flex flex-col md:flex-row gap-8">
        
        <!-- Sidebar -->
        <div class="w-full md:w-64 shrink-0">
            <div class="bg-white/5 border border-white/10 rounded-xl p-6 sticky top-28 shadow-xl">
                <div class="flex items-center gap-4 mb-8">
                    @if(!empty(auth()->user()->avatar_url))
                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-14 h-14 rounded-full object-cover border-2 border-cinematic-red shadow-[0_0_15px_rgba(229,9,20,0.5)]">
                    @else
                        <div class="w-14 h-14 bg-gradient-to-tr from-cinematic-red to-red-800 rounded-full flex items-center justify-center text-xl font-bold text-white shadow-[0_0_15px_rgba(229,9,20,0.5)] border border-white/20">
                            {{ mb_substr(auth()->user()->name, 0, 1) }}
                        </div>
                    @endif
                    <div class="min-w-0">
                        <p class="font-bold text-white truncate text-base">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-cinematic-gold font-semibold flex items-center gap-1 mt-0.5">
                            <span>💎</span> {{ number_format(auth()->user()->points) }} Điểm
                        </p>
                    </div>
                </div>
                
                <nav class="space-y-2">
                    <a href="{{ route('account.tickets') }}" class="block px-4 py-2.5 rounded bg-white/10 text-white font-medium border-l-4 border-cinematic-red flex items-center gap-2.5 text-sm">
                        <svg class="w-4 h-4 text-cinematic-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                        Vé của tôi
                    </a>
                    <a href="{{ route('account.vouchers') }}" class="block px-4 py-2.5 rounded text-gray-400 hover:bg-white/5 hover:text-white transition-colors flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            <span>Ví voucher</span>
                        </div>
                        <span class="px-2 py-0.5 text-[10px] font-bold bg-cinematic-gold/20 text-cinematic-gold border border-cinematic-gold/40 rounded-full">
                            {{ auth()->user()->activeVouchersCount() }}
                        </span>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 rounded text-gray-400 hover:bg-white/5 hover:text-white transition-colors flex items-center gap-2.5 text-sm">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Hồ sơ cá nhân
                    </a>
                    <a href="{{ route('profile.edit') }}#passwordSection" class="block px-4 py-2.5 rounded text-gray-400 hover:bg-white/5 hover:text-white transition-colors flex items-center gap-2.5 text-sm">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Đổi mật khẩu
                    </a>
                    <a href="{{ route('profile.edit') }}#pointsSection" class="block px-4 py-2.5 rounded text-gray-400 hover:bg-white/5 hover:text-white transition-colors flex items-center gap-2.5 text-sm">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Điểm tích lũy
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}" class="pt-6 border-t border-white/10 mt-6">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2.5 rounded text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-colors flex items-center gap-2.5 text-sm">
                            <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Đăng xuất
                        </button>
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
                                    @if($booking->status === 'paid')
                                        <span class="px-2.5 py-1 rounded text-xs font-bold uppercase tracking-wider bg-green-500/20 text-green-400 border border-green-500/50">
                                            Đã thanh toán
                                        </span>
                                    @elseif($booking->status === 'pending')
                                        <span class="px-2.5 py-1 rounded text-xs font-bold uppercase tracking-wider bg-yellow-500/20 text-yellow-400 border border-yellow-500/50">
                                            Chờ thanh toán
                                        </span>
                                    @elseif($booking->status === 'cancelled')
                                        <span class="px-2.5 py-1 rounded text-xs font-bold uppercase tracking-wider bg-red-500/20 text-red-400 border border-red-500/50">
                                            Đã hủy
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded text-xs font-bold uppercase tracking-wider bg-gray-500/20 text-gray-300 border border-gray-500/50">
                                            {{ $booking->status_label }}
                                        </span>
                                    @endif
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
