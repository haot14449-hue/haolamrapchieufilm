@extends('layouts.app')

@section('title', 'Phim Đang Chiếu - HCTV')

@section('content')
<div class="pt-24 pb-12 bg-cinematic-dark min-h-screen">
    <!-- Header -->
    <div class="relative py-16 mb-12 flex justify-center items-center overflow-hidden border-b border-white/10">
        <div class="absolute inset-0 bg-cinematic-red/20 blur-3xl opacity-30"></div>
        <h1 class="text-4xl md:text-5xl font-serif font-bold text-white relative z-10 tracking-widest uppercase flex items-center gap-4">
            <span class="w-12 h-1 bg-cinematic-red inline-block"></span>
            Phim Đang Chiếu
            <span class="w-12 h-1 bg-cinematic-red inline-block"></span>
        </h1>
    </div>

    <div class="max-w-7xl mx-auto px-6 md:px-12">
        <!-- Filter Tabs & Active Filter Status -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 border-b border-white/10 pb-4">
            <!-- Tabs -->
            <div class="flex space-x-6 text-sm md:text-base font-bold">
                <a href="{{ route('movies.index') }}" class="text-cinematic-red border-b-2 border-cinematic-red pb-4 -mb-[18px]">
                    {{ !empty($search) || !empty($genre) ? 'KẾT QUẢ TÌM KIẾM' : 'TẤT CẢ PHIM' }} ({{ count($movies) }})
                </a>
                <a href="{{ route('showtimes') }}" class="text-gray-400 hover:text-white transition-colors pb-4">LỊCH CHIẾU</a>
            </div>

            <!-- Active Search & Genre Badges -->
            @if(!empty($search) || !empty($genre))
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs text-gray-400">Đang lọc theo:</span>
                    @if(!empty($search))
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/10 border border-white/20 rounded-full text-xs text-white">
                            <span>Từ khóa: <strong>"{{ $search }}"</strong></span>
                            <a href="{{ route('movies.index', array_filter(['genre' => $genre])) }}" class="text-gray-400 hover:text-white ml-0.5">✕</a>
                        </span>
                    @endif
                    @if(!empty($genre))
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-cinematic-gold/15 border border-cinematic-gold/30 rounded-full text-xs text-cinematic-gold">
                            <span>Thể loại: <strong>{{ $genre }}</strong></span>
                            <a href="{{ route('movies.index', array_filter(['search' => $search])) }}" class="text-cinematic-gold hover:text-white ml-0.5">✕</a>
                        </span>
                    @endif
                    <a href="{{ route('movies.index') }}" class="text-xs text-cinematic-red hover:underline font-semibold ml-2">Xóa tất cả lọc</a>
                </div>
            @endif
        </div>

        <!-- Genre Carousel Section ("Bạn đang quan tâm gì?") -->
        @if(isset($genres) && count($genres) > 0)
            <x-genre-carousel :genres="$genres" :activeGenre="$genre ?? ''" :search="$search ?? ''" :showTitle="true" prefix="movies" />
        @endif

        <!-- Movie Grid -->
        @if(count($movies) > 0)
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8">
                @foreach($movies as $movie)
                <div class="group relative block cursor-pointer" onclick="window.location.href='{{ route('movies.show', $movie->id) }}'">
                    <!-- Poster -->
                    <div class="relative aspect-[2/3] rounded-xl overflow-hidden border border-white/5 shadow-lg group-hover:shadow-[0_0_25px_rgba(255,255,255,0.1)] transition-all duration-300 group-hover:-translate-y-3">
                        <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" onerror="this.src='https://placehold.co/300x450/202020/white?text=Poster'">
                        
                        @if($movie->release_date)
                        <div class="absolute top-3 left-3 bg-black/60 backdrop-blur-md px-2 py-1 rounded text-xs font-bold text-white border border-white/10">
                            {{ \Carbon\Carbon::parse($movie->release_date)->format('Y') }}
                        </div>
                        @endif
                        
                        <!-- Hover Info overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                            <p class="text-cinematic-gold text-sm font-semibold mb-2 truncate">{{ $movie->genre }}</p>
                            <p class="text-gray-300 text-xs mb-4 line-clamp-3">{{ $movie->description }}</p>
                            <a href="{{ route('movies.show', $movie->id) }}" class="w-full bg-cinematic-red text-center text-white font-bold py-3 rounded text-sm hover:bg-red-700 transition-colors shadow-lg">MUA VÉ</a>
                        </div>
                    </div>
                    <!-- Title below -->
                    <div class="mt-5 text-center">
                        <h3 class="font-bold text-white text-lg truncate px-2" title="{{ $movie->title }}">{{ $movie->title }}</h3>
                        <p class="text-gray-400 text-sm mt-1">{{ $movie->duration }} Phút</p>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="py-20 text-center max-w-md mx-auto">
                <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-500">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Không tìm thấy bộ phim nào</h3>
                <p class="text-sm text-gray-400 mb-6">
                    @if(!empty($search) && !empty($genre))
                        Không có phim nào khớp với từ khóa "<strong>{{ $search }}</strong>" trong thể loại "<strong>{{ $genre }}</strong>".
                    @elseif(!empty($search))
                        Không có phim nào khớp với từ khóa "<strong>{{ $search }}</strong>".
                    @elseif(!empty($genre))
                        Hiện tại chưa có phim nào thuộc thể loại "<strong>{{ $genre }}</strong>".
                    @else
                        Hiện chưa có phim nào trong danh sách.
                    @endif
                </p>
                <a href="{{ route('movies.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-cinematic-red text-white font-bold text-sm rounded-lg hover:bg-red-700 transition shadow-lg">
                    <span>Xem tất cả phim</span>
                    <span>→</span>
                </a>
            </div>
        @endif
</div>
@endsection
