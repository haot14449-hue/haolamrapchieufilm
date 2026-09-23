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
        @foreach($movies as $movie)
            @if($movie->showtimes->count() > 0)
            <div class="mb-12 bg-white/5 border border-white/10 rounded-xl overflow-hidden flex flex-col md:flex-row shadow-lg">
                <div class="w-full md:w-1/4 shrink-0 relative">
                    <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent md:bg-gradient-to-r md:from-transparent md:to-black/50"></div>
                </div>
                
                <div class="p-8 flex-1">
                    <h2 class="text-3xl font-serif font-bold text-white mb-2">{{ $movie->title }}</h2>
                    <p class="text-gray-400 text-sm mb-6">{{ $movie->duration }} Phút | {{ $movie->genre }}</p>
                    
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
                            <h3 class="text-xl font-bold text-cinematic-gold mb-3 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                {{ $c['cinema']->name }}
                            </h3>
                            <div class="flex flex-wrap gap-3">
                                @foreach($c['showtimes'] as $st)
                                <a href="{{ route('booking.seats', $st->id) }}" class="px-4 py-2 border border-white/20 rounded bg-white/5 hover:bg-cinematic-red hover:border-cinematic-red text-white font-semibold transition-colors duration-300">
                                    {{ \Carbon\Carbon::parse($st->start_time)->format('H:i') }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        @endforeach
    </div>
</div>
@endsection
