@extends('layouts.app')

@section('title', 'Trang chủ - HCTV')

@section('content')
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <style>
        /* Hero Carousel Styles */
        .heroSwiper .swiper-pagination-bullet {
            background: rgba(255, 255, 255, 0.5);
            width: 10px;
            height: 10px;
            opacity: 1;
            transition: all 0.3s ease;
        }
        .heroSwiper .swiper-pagination-bullet-active {
            background: #e50914;
            width: 30px;
            border-radius: 5px;
        }
        .heroSwiper .swiper-button-next, .heroSwiper .swiper-button-prev {
            color: rgba(255,255,255,0.6);
            transition: color 0.3s;
        }
        .heroSwiper .swiper-button-next:hover, .heroSwiper .swiper-button-prev:hover {
            color: #fff;
        }

        /* Top 10 Hot Rank Number Styles (Matching Netflix / iQiyi Reference Design) */
        .top-hot-num {
            font-family: 'Impact', 'Arial Black', sans-serif;
            font-style: italic;
            color: #e6b85c;
            line-height: 0.9;
            letter-spacing: -2px;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.85);
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.6));
        }

        /* Top 10 Poster Border Glow on Hover & Active Card */
        .top-hot-card {
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .top-hot-card:hover {
            transform: translateY(-6px);
        }
        .top-hot-poster-wrap {
            border: 2px solid transparent;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .top-hot-card:hover .top-hot-poster-wrap,
        .top-hot-poster-wrap.active-golden {
            border-color: #e6b85c !important;
            box-shadow: 0 0 25px rgba(230, 184, 92, 0.4) !important;
        }

        /* Light Mode Overrides for Top 10 Section */
        html.light-mode #top-10-hot-section {
            background-color: #f1f5f9 !important;
            border-color: #e2e8f0 !important;
        }
        html.light-mode #top-10-hot-section .section-title {
            color: #0f172a !important;
        }
        html.light-mode #top-10-hot-section .top-hot-movie-title {
            color: #0f172a !important;
        }
        html.light-mode #top-10-hot-section .top-hot-movie-sub {
            color: #64748b !important;
        }
        html.light-mode #top-10-hot-section .top-hot-movie-meta {
            color: #475569 !important;
        }
        html.light-mode #top-10-hot-section .top-hot-nav-btn {
            background-color: #ffffff !important;
            border-color: #cbd5e1 !important;
            color: #1e293b !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06) !important;
        }
        html.light-mode #top-10-hot-section .top-hot-nav-btn:hover {
            background-color: #e6b85c !important;
            color: #000000 !important;
            border-color: #e6b85c !important;
        }
    </style>

    <!-- ========================================================================= -->
    <!-- 1. HERO SECTION CAROUSEL -->
    <!-- ========================================================================= -->
    @if(isset($movies) && $movies->count() > 0)
    <div class="swiper heroSwiper w-full h-screen">
        <div class="swiper-wrapper">
            @foreach($movies->take(5) as $movie)
            <div class="swiper-slide relative h-screen w-full overflow-hidden hero-slide">
                <!-- Backdrop Image -->
                <div class="absolute inset-0 bg-cover bg-center bg-no-repeat hero-backdrop-img" style="background-image: url('{{ $movie->backdrop_url }}');" data-swiper-parallax="-23%"></div>
                
                <!-- Gradient Overlays -->
                <div class="absolute inset-0 hero-dark-overlay pointer-events-none"></div>
                <div class="absolute inset-0 hero-gradient-overlay pointer-events-none"></div>
                
                <!-- Content -->
                <div class="relative z-10 h-full flex flex-col justify-end pb-32 px-6 md:px-16 max-w-7xl mx-auto">
                    <div class="max-w-3xl" data-swiper-parallax="-300" data-swiper-parallax-opacity="0">
                        <div class="flex items-center space-x-3 mb-4">
                            @if($loop->first)
                                <span class="bg-cinematic-red text-white text-xs font-bold px-2 py-1 rounded uppercase tracking-wider">Mới nhất</span>
                            @endif
                            <span class="text-cinematic-gold text-sm font-semibold">{{ $movie->genre }}</span>
                            <span class="text-gray-300 text-sm flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $movie->duration }} Phút
                            </span>
                        </div>
                        
                        <h1 class="text-5xl md:text-7xl font-serif font-bold text-white leading-tight mb-6 drop-shadow-lg">
                            {{ $movie->title }}
                        </h1>
                        
                        <p class="text-gray-300 text-lg md:text-xl mb-8 line-clamp-3 md:line-clamp-none max-w-2xl drop-shadow-md">
                            {{ $movie->description }}
                        </p>
                        
                        <div class="flex flex-wrap gap-4 items-center">
                            <a href="{{ route('movies.show', $movie->id) }}" class="group relative px-8 py-4 bg-cinematic-red text-white font-bold rounded overflow-hidden shadow-[0_0_20px_rgba(229,9,20,0.5)] transition-all hover:scale-105 hover:shadow-[0_0_30px_rgba(229,9,20,0.8)]">
                                <span class="relative z-10 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                      <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                    </svg>
                                    MUA VÉ NGAY
                                </span>
                                <div class="absolute inset-0 h-full w-full bg-white/20 -translate-x-full group-hover:translate-x-0 transition-transform duration-300 ease-out"></div>
                            </a>
                            
                            <a href="{{ $movie->trailer_url }}" target="_blank" class="px-8 py-4 border border-white/30 text-white font-bold rounded hover:bg-white/10 transition-colors flex items-center gap-2 backdrop-blur-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                XEM TRAILER
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination & Navigation -->
        <div class="swiper-pagination mb-6"></div>
        <div class="swiper-button-next mr-4 md:mr-8 drop-shadow-md"></div>
        <div class="swiper-button-prev ml-4 md:ml-8 drop-shadow-md"></div>
    </div>
    @endif

    <!-- ========================================================================= -->
    <!-- 2. TOP 10 PHIM HOT HÔM NAY (BẢNG XẾP HẠNG NETFLIX / IQIYI STYLE) -->
    <!-- ========================================================================= -->
    @if(isset($topHotMovies) && $topHotMovies->count() > 0)
    <section class="w-full bg-[#0d0f15]/95 py-12 md:py-14 border-y border-white/5 relative overflow-hidden transition-colors" id="top-10-hot-section">
        <div class="max-w-7xl mx-auto px-6 md:px-16">
            <!-- Section Header -->
            <div class="flex items-center justify-between mb-7">
                <div class="flex items-center gap-3">
                    <h2 class="text-2xl md:text-3xl font-bold text-white tracking-tight section-title flex items-center gap-3">
                        Top 10 phim hot hôm nay
                    </h2>

                    @if(auth()->check() && auth()->user()->role === 'admin')
                    <a href="{{ route('admin.top_movies.index') }}" class="hidden sm:inline-flex items-center gap-1.5 bg-amber-500/15 hover:bg-amber-500/25 text-amber-300 border border-amber-400/40 text-xs font-semibold px-2.5 py-1 rounded-full transition shadow-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Cập nhật Top 10</span>
                    </a>
                    @endif
                </div>

                <!-- Carousel Navigation Arrows -->
                <div class="flex items-center gap-2">
                    <button type="button" class="top-hot-prev top-hot-nav-btn w-10 h-10 rounded-full bg-white/10 hover:bg-[#e6b85c] text-white hover:text-gray-950 transition-all flex items-center justify-center border border-white/15 cursor-pointer backdrop-blur-md shadow-sm active:scale-90" aria-label="Previous slide">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button type="button" class="top-hot-next top-hot-nav-btn w-10 h-10 rounded-full bg-white/10 hover:bg-[#e6b85c] text-white hover:text-gray-950 transition-all flex items-center justify-center border border-white/15 cursor-pointer backdrop-blur-md shadow-sm active:scale-90" aria-label="Next slide">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Top 10 Swiper Slider -->
            <div class="swiper topHotSwiper overflow-visible pb-2">
                <div class="swiper-wrapper">
                    @foreach($topHotMovies as $item)
                    @php
                        $movie = $item->movie;
                        if (!$movie) continue;
                    @endphp
                    <div class="swiper-slide top-hot-card select-none">
                        <!-- Poster Container -->
                        <div class="relative aspect-[1/1.48] rounded-2xl overflow-hidden bg-gray-900 shadow-md top-hot-poster-wrap {{ $item->rank == 4 ? 'active-golden' : '' }} cursor-pointer" onclick="window.location.href='{{ route('movies.show', $movie->id) }}'">
                            <img src="{{ $item->display_poster }}" alt="{{ $movie->title }}" class="w-full h-full object-cover transition-transform duration-500 hover:scale-105" loading="lazy">
                            
                            <!-- Bottom Dark Gradient for Pill Badges -->
                            <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-black/90 via-black/40 to-transparent pointer-events-none"></div>

                            <!-- Overlaid Badges on Poster (e.g., PD. 30, TM. 16) -->
                            <div class="absolute bottom-2.5 inset-x-2 flex items-center justify-center gap-1.5 pointer-events-none z-10">
                                @if($item->badge_text)
                                    <span class="bg-black/75 backdrop-blur-md text-[11px] font-semibold text-gray-200 px-2.5 py-0.5 rounded border border-white/15 shadow-sm">
                                        {{ $item->badge_text }}
                                    </span>
                                @endif
                                @if($item->badge_text_2)
                                    <span class="bg-emerald-600/90 backdrop-blur-md text-[11px] font-bold text-white px-2.5 py-0.5 rounded shadow-sm border border-emerald-400/30">
                                        {{ $item->badge_text_2 }}
                                    </span>
                                @endif
                            </div>

                            <!-- Hover Overlay Button -->
                            <div class="absolute inset-0 bg-black/40 opacity-0 hover:opacity-100 transition-opacity duration-300 flex items-center justify-center p-4">
                                <span class="bg-[#e6b85c] text-gray-950 font-bold text-xs uppercase px-4 py-2 rounded-full shadow-lg transform -translate-y-2 hover:translate-y-0 transition-transform duration-300">
                                    Mua Vé
                                </span>
                            </div>
                        </div>

                        <!-- Row Below Poster: Large Slanted Rank Number + Movie Info -->
                        <div class="mt-3.5 flex items-start gap-3 px-1 cursor-pointer" onclick="window.location.href='{{ route('movies.show', $movie->id) }}'">
                            <!-- Large Golden Italic Number (e.g. 1, 2, 3...) -->
                            <div class="top-hot-num text-4xl sm:text-5xl shrink-0 pt-0.5">
                                {{ $item->rank }}
                            </div>

                            <!-- Titles and Meta -->
                            <div class="min-w-0 flex-1">
                                <h3 class="font-bold text-white text-sm sm:text-base leading-snug line-clamp-1 hover:text-[#e6b85c] transition-colors top-hot-movie-title" title="{{ $movie->title }}">
                                    {{ $movie->title }}
                                </h3>
                                
                                <p class="text-xs text-gray-400 line-clamp-1 mt-0.5 font-normal top-hot-movie-sub" title="{{ $item->display_sub_title }}">
                                    {{ $item->display_sub_title }}
                                </p>
                                
                                <div class="flex items-center gap-1.5 text-xs text-gray-300 mt-1 font-medium flex-wrap top-hot-movie-meta">
                                    <span class="font-bold text-gray-200">{{ $item->age_rating ?: 'T13' }}</span>
                                    <span class="text-gray-500">•</span>
                                    <span>{{ $movie->duration ? $movie->duration . ' phút' : 'Phần 1' }}</span>
                                    @if($movie->genre)
                                        <span class="text-gray-500">•</span>
                                        <span class="text-gray-400 line-clamp-1">{{ explode(',', $movie->genre)[0] }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- ========================================================================= -->
    <!-- 3. GENRE CAROUSEL SECTION ("BẠN ĐANG QUAN TÂM GÌ?") -->
    <!-- ========================================================================= -->
    @if(isset($genres) && count($genres) > 0)
        <div class="max-w-7xl mx-auto px-6 md:px-16 pt-16 pb-0">
            <x-genre-carousel :genres="$genres" :activeGenre="''" :search="''" :showTitle="true" prefix="home" />
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- 4. NOW SHOWING SECTION (PHIM ĐANG CHIẾU) -->
    <!-- ========================================================================= -->
    <div class="max-w-7xl mx-auto px-6 md:px-16 py-16">
        <div class="flex items-end justify-between mb-10">
            <div>
                <h2 class="text-3xl font-serif font-bold text-white flex items-center gap-3">
                    <span class="w-2 h-8 bg-cinematic-red inline-block"></span>
                    PHIM ĐANG CHIẾU
                </h2>
            </div>
            <a href="{{ route('movies.index') }}" class="text-cinematic-gold hover:text-white transition-colors text-sm font-medium flex items-center gap-1">
                Xem tất cả
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
            @foreach($movies as $movie)
            <div class="group relative block cursor-pointer" onclick="window.location.href='{{ route('movies.show', $movie->id) }}'">
                <!-- Poster -->
                <div class="relative aspect-[2/3] rounded-lg overflow-hidden border border-white/10 shadow-lg group-hover:shadow-[0_0_20px_rgba(255,255,255,0.15)] transition-all duration-300 group-hover:-translate-y-2">
                    <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    
                    <!-- Hover Info overlay -->
                    <div class="absolute inset-0 bg-black/80 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-center items-center p-4 text-center backdrop-blur-sm">
                        <h3 class="font-bold text-white mb-2">{{ $movie->title }}</h3>
                        <p class="text-cinematic-gold text-sm mb-4">{{ $movie->duration }} Phút</p>
                        <a href="{{ route('movies.show', $movie->id) }}" class="bg-cinematic-red text-white font-bold py-2 px-6 rounded text-sm hover:bg-red-700 transition-colors">MUA VÉ</a>
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

    <!-- ========================================================================= -->
    <!-- 5. SWIPER JAVASCRIPT INITIALIZATION -->
    <!-- ========================================================================= -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Hero Swiper
            if (document.querySelector('.heroSwiper')) {
                new Swiper(".heroSwiper", {
                    speed: 1000,
                    parallax: true,
                    loop: true,
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                    pagination: {
                        el: ".swiper-pagination",
                        clickable: true,
                    },
                    navigation: {
                        nextEl: ".swiper-button-next",
                        prevEl: ".swiper-button-prev",
                    },
                });
            }

            // 2. Top 10 Phim Hot Swiper (Responsive Slider matching user reference image)
            if (document.querySelector('.topHotSwiper')) {
                new Swiper(".topHotSwiper", {
                    slidesPerView: 2.2,
                    spaceBetween: 14,
                    speed: 600,
                    grabCursor: true,
                    navigation: {
                        nextEl: ".top-hot-next",
                        prevEl: ".top-hot-prev",
                    },
                    breakpoints: {
                        480: {
                            slidesPerView: 2.4,
                            spaceBetween: 16,
                        },
                        640: {
                            slidesPerView: 3.2,
                            spaceBetween: 18,
                        },
                        768: {
                            slidesPerView: 3.8,
                            spaceBetween: 20,
                        },
                        1024: {
                            slidesPerView: 4.5,
                            spaceBetween: 22,
                        },
                        1280: {
                            slidesPerView: 5,
                            spaceBetween: 24,
                        },
                    },
                });
            }
        });
    </script>
@endsection
