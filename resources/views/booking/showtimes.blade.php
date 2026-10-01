@extends('layouts.app')

@section('title', 'Lịch Chiếu Phim - HCTV')

@section('content')
<div class="pt-24 pb-16 bg-cinematic-dark min-h-screen">
    <!-- Header -->
    <div class="relative py-12 mb-8 flex justify-center items-center overflow-hidden border-b border-white/10">
        <div class="absolute inset-0 bg-cinematic-red/20 blur-3xl opacity-30"></div>
        <h1 class="text-3xl md:text-5xl font-serif font-bold text-white relative z-10 tracking-widest uppercase flex items-center gap-4">
            <span class="w-8 md:w-12 h-1 bg-cinematic-red inline-block"></span>
            Lịch Chiếu Phim
            <span class="w-8 md:w-12 h-1 bg-cinematic-red inline-block"></span>
        </h1>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-12">
        @if(session('error'))
            <div class="bg-red-500/20 border border-red-500 text-red-200 px-5 py-4 rounded-xl mb-8 shadow-lg flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-500/20 border border-green-500 text-green-200 px-5 py-4 rounded-xl mb-8 shadow-lg flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Filter Bar: Cinemas & Dates (Synchronized with POS) -->
        <div class="showtime-filter-card rounded-2xl p-4 sm:p-6 mb-10 shadow-2xl backdrop-blur-md space-y-6">
            <!-- Cinema Selector -->
            <div>
                <label class="filter-label text-xs font-bold text-gray-400 uppercase tracking-wider block mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-cinematic-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span>Chọn Rạp Chiếu:</span>
                </label>
                <div class="flex flex-wrap gap-2.5">
                    <a href="{{ route('showtimes', ['date' => $selectedDate]) }}"
                       class="cinema-pill px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all border {{ empty($selectedCinemaId) ? 'bg-cinematic-red text-white border-red-500 shadow-[0_0_12px_rgba(229,9,20,0.5)]' : 'bg-white/5 text-gray-300 border-white/10 hover:bg-white/10 hover:text-white' }}">
                        Tất Cả Rạp
                    </a>
                    @foreach($cinemas as $c)
                        <a href="{{ route('showtimes', ['date' => $selectedDate, 'cinema_id' => $c->id]) }}"
                           class="cinema-pill px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all border {{ $selectedCinemaId == $c->id ? 'bg-cinematic-red text-white border-red-500 shadow-[0_0_12px_rgba(229,9,20,0.5)]' : 'bg-white/5 text-gray-300 border-white/10 hover:bg-white/10 hover:text-white' }}">
                            {{ $c->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Date Selector Tabs (7 Days) -->
            <div>
                <label class="filter-label text-xs font-bold text-gray-400 uppercase tracking-wider block mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-cinematic-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Chọn Ngày Chiếu:</span>
                </label>
                <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
                    @foreach($dates as $d)
                        <a href="{{ route('showtimes', ['date' => $d['date'], 'cinema_id' => $selectedCinemaId]) }}"
                           class="date-tab-btn shrink-0 px-4 py-2.5 rounded-xl text-xs font-bold transition-all border flex flex-col items-center min-w-[90px] {{ $selectedDate === $d['date'] ? 'bg-gradient-to-b from-cinematic-red to-red-700 text-white border-red-400 shadow-[0_0_15px_rgba(229,9,20,0.6)] scale-105' : 'bg-white/5 text-gray-300 border-white/10 hover:bg-white/10 hover:text-white' }}">
                            <span class="text-sm font-black">{{ $d['day_name'] }}</span>
                            <span class="text-[11px] opacity-80 mt-0.5">{{ $d['formatted'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Movie List with Showtimes -->
        @forelse($movies as $item)
            @php
                $movie = $item['movie'];
                $showtimes = $item['showtimes'];
            @endphp
            @if(count($showtimes) > 0)
            <div class="mb-8 showtime-movie-card rounded-2xl overflow-hidden flex flex-col md:flex-row items-center md:items-start shadow-xl border border-white/10 bg-white/[0.02]">
                <!-- Movie Poster (Chuẩn tỷ lệ 2:3, kích thước cố định 200x300 đồng bộ tuyệt đối) -->
                <div class="showtime-poster-col p-4 sm:p-5 md:p-6 shrink-0 flex items-start justify-center">
                    <a href="{{ route('movies.show', $movie->id) }}" 
                       class="showtime-poster-card group border border-black/10 dark:border-white/15"
                       style="width: 200px; max-width: 200px; min-width: 200px; height: 300px; max-height: 300px; min-height: 300px; aspect-ratio: 2/3;">
                        <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" 
                             class="transition-transform duration-300 group-hover:scale-105"
                             style="width: 100%; height: 100%; object-fit: cover; display: block;">
                        <span class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded text-xs font-black bg-red-600 text-white uppercase shadow-md z-10">
                            {{ $movie->rating ?? 'P' }}
                        </span>
                    </a>
                </div>
                
                <!-- Movie Details & Showtimes -->
                <div class="p-4 sm:p-6 md:pl-0 flex-1 flex flex-col justify-between w-full">
                    <div>
                        <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                            <h2 class="showtime-movie-title text-2xl md:text-3xl font-serif font-bold text-white">{{ $movie->title }}</h2>
                            <a href="{{ route('movies.show', $movie->id) }}" class="text-xs text-cinematic-gold hover:underline flex items-center gap-1 font-semibold">
                                Chi tiết phim &raquo;
                            </a>
                        </div>
                        <p class="showtime-movie-meta text-gray-400 text-xs sm:text-sm mb-6 flex items-center gap-2">
                            <span>⏱ {{ $movie->duration }} Phút</span>
                            <span>•</span>
                            <span>🎭 {{ $movie->genre }}</span>
                        </p>
                        
                        @php
                            // Group showtimes by cinema
                            $cinemasInMovie = [];
                            foreach($showtimes as $st) {
                                $cId = $st->room->cinema->id;
                                if(!isset($cinemasInMovie[$cId])) {
                                    $cinemasInMovie[$cId] = [
                                        'cinema' => $st->room->cinema,
                                        'showtimes' => []
                                    ];
                                }
                                $cinemasInMovie[$cId]['showtimes'][] = $st;
                            }
                        @endphp
                        
                        <div class="space-y-6">
                            @foreach($cinemasInMovie as $cGroup)
                            <div>
                                <h3 class="showtime-cinema-title text-base sm:text-lg font-bold text-cinematic-gold mb-3 flex items-center gap-2">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    {{ $cGroup['cinema']->name }}
                                </h3>
                                <div class="flex flex-wrap gap-3">
                                    @foreach($cGroup['showtimes'] as $st)
                                        @php
                                            $startTime = \Carbon\Carbon::parse($st->start_time);
                                        @endphp
                                        <a href="{{ route('booking.seats', $st->id) }}" 
                                           class="showtime-slot-btn group relative bg-white/5 hover:bg-white/10 border border-white/15 hover:border-cinematic-red rounded-xl p-3 sm:p-3.5 transition-all duration-200 hover:-translate-y-1 hover:shadow-[0_8px_20px_rgba(229,9,20,0.35)] flex flex-col min-w-[130px] sm:min-w-[145px]">
                                            <div class="flex items-center justify-between gap-2 mb-1.5 w-full">
                                                <span class="slot-time text-lg sm:text-xl font-black font-mono text-white group-hover:text-cinematic-gold transition-colors">
                                                    {{ $startTime->format('H:i') }}
                                                </span>
                                                <span class="slot-format text-[10px] px-1.5 py-0.5 rounded font-black uppercase tracking-wide bg-white/10 text-gray-300 border border-white/10">
                                                    {{ $st->format ?? ($st->room->type ?? '2D') }}
                                                </span>
                                            </div>

                                            <div class="slot-room text-[11px] font-medium text-gray-400 mb-1 truncate w-full text-left" title="{{ $st->room->name }}">
                                                Phòng: <strong class="text-white">{{ $st->room->name }}</strong>
                                            </div>

                                            <div class="slot-divider flex items-center justify-between gap-1 text-[11px] pt-1.5 border-t border-white/10 w-full">
                                                <span class="slot-price font-black text-cinematic-gold">{{ number_format($st->price, 0, ',', '.') }}đ</span>
                                                <span class="slot-avail text-[10px] text-emerald-400 font-semibold">Còn {{ $st->available_seats }} ghế</span>
                                            </div>

                                            @if($st->is_started)
                                                <span class="absolute -top-2 -right-2 px-1.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-amber-500 text-black shadow-md animate-pulse">
                                                    Đang chiếu
                                                </span>
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @empty
            <div class="empty-showtime-card text-center py-20 bg-white/5 border border-white/10 rounded-2xl p-8 shadow-xl">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-white/10 flex items-center justify-center text-3xl">
                    🎬
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Không Có Suất Chiếu Nào</h3>
                <p class="text-gray-400 text-sm max-w-md mx-auto mb-6">
                    Không tìm thấy suất chiếu nào vào ngày {{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }} {{ !empty($selectedCinemaId) ? 'tại rạp đã chọn' : '' }}. Vui lòng chọn ngày khác hoặc chọn tất cả rạp!
                </p>
                <div class="flex justify-center gap-3">
                    <a href="{{ route('showtimes', ['date' => \Carbon\Carbon::today()->format('Y-m-d')]) }}" class="px-6 py-2.5 bg-cinematic-red hover:bg-red-700 text-white font-bold rounded-xl transition shadow-lg text-sm">
                        Xem Lịch Hôm Nay
                    </a>
                    <a href="{{ route('movies.index') }}" class="px-6 py-2.5 bg-white/10 hover:bg-white/20 text-white font-bold rounded-xl transition border border-white/20 text-sm">
                        Khám Phá Phim
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
