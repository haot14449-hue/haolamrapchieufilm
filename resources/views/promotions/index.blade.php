@extends('layouts.app')

@section('title', 'Khuyến Mãi & Thành Viên - HCTV')

@section('content')
<div class="pt-24 pb-12 bg-cinematic-dark min-h-screen">
    <!-- Header -->
    <div class="relative py-16 mb-12 flex justify-center items-center overflow-hidden border-b border-white/10">
        <div class="absolute inset-0 bg-cinematic-gold/20 blur-3xl opacity-30"></div>
        <h1 class="text-4xl md:text-5xl font-serif font-bold text-white relative z-10 tracking-widest uppercase flex items-center gap-4">
            <span class="w-12 h-1 bg-cinematic-gold inline-block"></span>
            Ưu Đãi Đặc Quyền
            <span class="w-12 h-1 bg-cinematic-gold inline-block"></span>
        </h1>
    </div>

    <div class="max-w-7xl mx-auto px-6 md:px-12">
        
        <!-- Membership Card (if logged in) -->
        @auth
        <div class="membership-card bg-gradient-to-br from-gray-900 to-black border border-cinematic-gold/30 rounded-2xl p-8 mb-16 shadow-[0_0_30px_rgba(212,175,55,0.15)] relative overflow-hidden">
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-cinematic-gold/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="flex flex-col md:flex-row justify-between items-center gap-8 relative z-10">
                <div class="flex items-center gap-6">
                    <div class="w-20 h-20 bg-cinematic-gold rounded-full flex items-center justify-center text-3xl font-bold text-black shadow-lg">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-gray-400 uppercase tracking-widest text-xs mb-1">Thành viên HCTV</p>
                        <h2 class="text-3xl font-bold text-white">{{ auth()->user()->name }}</h2>
                    </div>
                </div>
                
                <div class="text-center md:text-right border-l md:border-l-0 md:border-l-2 border-white/10 pl-0 md:pl-8">
                    <p class="text-gray-400 mb-1">Điểm tích luỹ hiện tại</p>
                    <p class="text-4xl font-bold text-cinematic-gold">{{ auth()->user()->points }} <span class="text-lg text-white font-normal">pts</span></p>
                </div>
            </div>
        </div>
        @else
        <div class="bg-white/5 border border-white/10 rounded-2xl p-8 text-center mb-16">
            <h2 class="text-2xl font-bold text-white mb-4">Đăng ký thành viên HCTV</h2>
            <p class="text-gray-400 mb-6">Tích điểm đổi quà, nhận ưu đãi sinh nhật và vô vàn đặc quyền khác.</p>
            <a href="{{ route('register') }}" class="px-8 py-3 bg-cinematic-gold text-black font-bold uppercase rounded hover:bg-yellow-500 transition-colors shadow-[0_0_15px_rgba(212,175,55,0.4)]">
                Tham Gia Ngay
            </a>
        </div>
        @endauth

        <h2 class="text-3xl font-serif font-bold text-white mb-8 border-l-4 border-cinematic-red pl-4">Chương Trình Khuyến Mãi</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($promotions as $promo)
            <div class="bg-white/5 border border-white/10 rounded-xl overflow-hidden group hover:border-white/30 transition-all duration-300 shadow-lg hover:shadow-2xl">
                <div class="aspect-video relative overflow-hidden">
                    <img src="{{ $promo->image_url }}" alt="{{ $promo->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    @if($promo->discount_percent)
                        <div class="absolute top-4 right-4 bg-cinematic-red text-white font-bold px-3 py-1 rounded shadow-lg">
                            -{{ $promo->discount_percent }}%
                        </div>
                    @endif
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-white mb-2 line-clamp-2">{{ $promo->title }}</h3>
                    <p class="text-gray-400 text-sm mb-4 line-clamp-2">{{ $promo->description }}</p>
                    
                    <div class="flex justify-between items-center pt-4 border-t border-white/10">
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Mã code</p>
                            <p class="font-mono font-bold text-cinematic-gold">{{ $promo->code }}</p>
                        </div>
                        <button class="text-sm font-bold text-white hover:text-cinematic-red transition-colors border border-white/20 px-3 py-1.5 rounded">Chi tiết</button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
    </div>
</div>
@endsection
