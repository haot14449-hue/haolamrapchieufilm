@extends('layouts.app')

@section('title', $cinema->name . ' - HCTV')

@section('content')
<div class="pt-24 pb-12 bg-cinematic-dark min-h-screen">
    <div class="max-w-4xl mx-auto px-6 md:px-12">
        <div class="bg-white/5 border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
            <!-- Cinema Header -->
            <div class="bg-cinematic-red p-10 text-center relative overflow-hidden">
                <div class="absolute inset-0 bg-black/20"></div>
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-black/20 rounded-full blur-xl"></div>
                
                <h1 class="text-4xl md:text-6xl font-serif font-bold text-white relative z-10 drop-shadow-md mb-4">{{ $cinema->name }}</h1>
                <p class="text-white/80 text-lg relative z-10 flex justify-center items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    {{ $cinema->location }}
                </p>
            </div>
            
            <div class="p-10">
                <h3 class="text-2xl font-bold text-white mb-6 border-b border-white/10 pb-4">Thông tin rạp</h3>
                <p class="text-gray-300 leading-relaxed mb-8">
                    Trải nghiệm hệ thống phòng chiếu hiện đại bậc nhất với âm thanh vòm Dolby Atmos chuẩn Hollywood và hệ thống ghế ngồi cao cấp, mang lại trải nghiệm xem phim hoàn hảo cho mọi khách hàng.
                </p>
                
                <div class="flex justify-center">
                    <a href="{{ route('showtimes') }}" class="px-10 py-4 bg-cinematic-gold text-black font-bold uppercase tracking-widest rounded hover:bg-yellow-500 transition-colors shadow-[0_0_20px_rgba(212,175,55,0.4)]">
                        Xem Lịch Chiếu Tại Đây
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
