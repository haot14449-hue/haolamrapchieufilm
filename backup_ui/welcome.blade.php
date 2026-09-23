@extends('layouts.app')

@section('title', 'Trang chủ - HCTV')

@section('content')
    <!-- Hero Section -->
    @if($heroMovie)
    <div class="relative h-screen w-full">
        <!-- Backdrop Image -->
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" style="background-image: url('{{ $heroMovie->backdrop_url }}');"></div>
        
        <!-- Gradient Overlays -->
        <div class="absolute inset-0 bg-black/30"></div>
        <div class="absolute inset-0 bg-cinematic-gradient"></div>
        
        <!-- Content -->
        <div class="relative z-10 h-full flex flex-col justify-end pb-32 px-6 md:px-16 max-w-7xl mx-auto">
            <div class="max-w-3xl">
                <div class="flex items-center space-x-3 mb-4">
                    <span class="bg-cinematic-red text-white text-xs font-bold px-2 py-1 rounded uppercase tracking-wider">Mới nhất</span>
                    <span class="text-cinematic-gold text-sm font-semibold">{{ $heroMovie->genre }}</span>
                </div>
                
                <h1 class="text-5xl md:text-7xl font-serif font-bold text-white leading-tight mb-6 drop-shadow-lg">
                    {{ $heroMovie->title }}
                </h1>
                
                <p class="text-gray-300 text-lg md:text-xl mb-8 line-clamp-3 md:line-clamp-none max-w-2xl drop-shadow-md">
                    {{ $heroMovie->description }}
                </p>
                
                <div class="flex flex-wrap gap-4 items-center">
                    <a href="#" class="group relative px-8 py-4 bg-cinematic-red text-white font-bold rounded overflow-hidden shadow-[0_0_20px_rgba(229,9,20,0.5)] transition-all hover:scale-105 hover:shadow-[0_0_30px_rgba(229,9,20,0.8)]">
                        <span class="relative z-10 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                              <path d="M2 6a2 2 0 012-2h12a2 2 0 012 2v2a2 2 0 100 4v2a2 2 0 01-2 2H4a2 2 0 01-2-2v-2a2 2 0 100-4V6z" />
                            </svg>
                            MUA VÉ NGAY
                        </span>
                        <div class="absolute inset-0 h-full w-full bg-white/20 -translate-x-full group-hover:translate-x-0 transition-transform duration-300 ease-out"></div>
                    </a>
                    
                    <a href="#" class="px-8 py-4 border border-white/30 text-white font-bold rounded hover:bg-white/10 transition-colors flex items-center gap-2 backdrop-blur-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        XEM TRAILER
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Now Showing Section -->
    <div class="max-w-7xl mx-auto px-6 md:px-16 py-20">
        <div class="flex items-end justify-between mb-10">
            <div>
                <h2 class="text-3xl font-serif font-bold text-white flex items-center gap-3">
                    <span class="w-2 h-8 bg-cinematic-red inline-block"></span>
                    PHIM ĐANG CHIẾU
                </h2>
            </div>
            <a href="#" class="text-cinematic-gold hover:text-white transition-colors text-sm font-medium flex items-center gap-1">
                Xem tất cả
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
            @foreach($movies as $movie)
            <div class="group relative block cursor-pointer">
                <!-- Poster -->
                <div class="relative aspect-[2/3] rounded-lg overflow-hidden border border-white/10 shadow-lg group-hover:shadow-[0_0_20px_rgba(255,255,255,0.15)] transition-all duration-300 group-hover:-translate-y-2">
                    <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    
                    <!-- Hover Info overlay -->
                    <div class="absolute inset-0 bg-black/80 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-center items-center p-4 text-center backdrop-blur-sm">
                        <h3 class="font-bold text-white mb-2">{{ $movie->title }}</h3>
                        <p class="text-cinematic-gold text-sm mb-4">{{ $movie->duration }} Phút</p>
                        <button class="bg-cinematic-red text-white font-bold py-2 px-6 rounded text-sm hover:bg-red-700 transition-colors">MUA VÉ</button>
                    </div>
                </div>
                <!-- Title below (visible when not hovering) -->
                <div class="mt-4">
                    <h3 class="font-bold text-white text-lg truncate" title="{{ $movie->title }}">{{ $movie->title }}</h3>
                    <p class="text-gray-400 text-sm mt-1">{{ \Carbon\Carbon::parse($movie->release_date)->format('d/m/Y') }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
@endsection
