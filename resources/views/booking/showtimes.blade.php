@extends('layouts.app')

@section('title', 'Lịch Chiếu - HCTV')

@section('content')
<div class="pt-24 pb-12 bg-cinematic-dark min-h-screen">
    <!-- Header -->
    <div class="relative py-16 mb-12 flex justify-center items-center overflow-hidden border-b border-white/10">
        <div class="absolute inset-0 bg-cinematic-red/20 blur-3xl opacity-30"></div>
        <h1 class="text-4xl md:text-5xl font-serif font-bold text-white relative z-10 tracking-widest uppercase flex items-center gap-4">
            <span class="w-12 h-1 bg-cinematic-red inline-block"></span>
            Lịch Chiếu Phim
            <span class="w-12 h-1 bg-cinematic-red inline-block"></span>
        </h1>
    </div>

    <div class="max-w-7xl mx-auto px-6 md:px-12">
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

        @forelse($movies as $movie)
            @if($movie->showtimes->count() > 0)
            <div class="mb-12 showtime-movie-card rounded-2xl overflow-hidden flex flex-col md:flex-row shadow-lg">
                <div class="w-full md:w-1/4 shrink-0 relative">
                    <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent md:hidden"></div>
                </div>
                
                <div class="p-8 flex-1">
                    <h2 class="showtime-movie-title text-3xl font-serif font-bold text-white mb-2">{{ $movie->title }}</h2>
                    <p class="showtime-movie-meta text-gray-400 text-sm mb-6">{{ $movie->duration }} Phút | {{ $movie->genre }}</p>
                    
                    @php
                        // Group showtimes by cinema
                        $cinemas = [];
                        foreach($movie->showtimes as $st) {
                            $cinemaId = $st->room->cinema->id;
                            if(!isset($cinemas[$cinemaId])) {
                                $cinemas[$cinemaId] = [
                                    'cinema' => $st->room->cinema,
                                    'showtimes' => []
                                ];
                            }
                            $cinemas[$cinemaId]['showtimes'][] = $st;
                        }
                    @endphp
                    
                    <div class="space-y-6">
                        @foreach($cinemas as $c)
                        <div>
                            <h3 class="showtime-cinema-title text-xl font-bold text-cinematic-gold mb-3 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                {{ $c['cinema']->name }}
                            </h3>
                            <div class="flex flex-wrap gap-3">
                                @foreach($c['showtimes'] as $st)
                                    @php
                                        $startTime = \Carbon\Carbon::parse($st->start_time);
                                        $dateLabel = $startTime->isToday() ? 'Hôm nay' : ($startTime->isTomorrow() ? 'Ngày mai' : $startTime->format('d/m'));
                                    @endphp
                                    <a href="{{ route('booking.seats', $st->id) }}" class="showtime-slot-btn group">
                                        <span class="slot-date">{{ $dateLabel }}</span>
                                        <span class="slot-time">{{ $startTime->format('H:i') }}</span>
                                        <span class="slot-price">{{ number_format($st->price, 0, ',', '.') }}đ</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        @empty
            <div class="text-center py-20 bg-white/5 border border-white/10 rounded-2xl p-8 shadow-xl">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-white/10 flex items-center justify-center text-3xl">
                    🎬
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Hiện Chưa Có Lịch Chiếu Sắp Tới</h3>
                <p class="text-gray-400 text-sm max-w-md mx-auto mb-6">
                    Các suất chiếu quá thời gian chiếu đã tự động được ẩn khỏi hệ thống. Vui lòng quay lại sau để cập nhật các suất chiếu mới nhất!
                </p>
                <a href="{{ route('movies.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-cinematic-red hover:bg-red-700 text-white font-bold rounded-xl transition shadow-lg">
                    Khám Phá Danh Sách Phim
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection
