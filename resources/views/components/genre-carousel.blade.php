@props(['genres' => [], 'activeGenre' => '', 'search' => '', 'showTitle' => true, 'prefix' => 'main'])

@php
    $genreMeta = [
        'Tất cả thể loại' => [
            'image' => asset('images/genres/tat-ca.jpg'),
            'gradient' => 'from-red-600/90 via-rose-600/50 to-orange-500/20',
            'border' => 'border-red-500/30 hover:border-red-400',
            'glow' => 'rgba(239, 68, 68, 0.4)',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z" />',
        ],
        'Bí ẩn' => [
            'image' => asset('images/genres/bi-an.jpg'),
            'gradient' => 'from-blue-700/90 via-sky-600/50 to-cyan-500/20',
            'border' => 'border-sky-500/30 hover:border-sky-400',
            'glow' => 'rgba(56, 189, 248, 0.4)',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />',
        ],
        'Gia đình' => [
            'image' => asset('images/genres/gia-dinh.jpg'),
            'gradient' => 'from-cyan-600/90 via-sky-500/50 to-blue-400/20',
            'border' => 'border-cyan-500/30 hover:border-cyan-400',
            'glow' => 'rgba(6, 182, 212, 0.4)',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />',
        ],
        'Giật gân' => [
            'image' => asset('images/genres/giat-gan.jpg'),
            'gradient' => 'from-purple-950/95 via-fuchsia-900/60 to-rose-800/20',
            'border' => 'border-fuchsia-500/30 hover:border-fuchsia-400',
            'glow' => 'rgba(217, 70, 239, 0.4)',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />',
        ],
        'Hoạt hình' => [
            'image' => asset('images/genres/hoat-hinh.jpg'),
            'gradient' => 'from-pink-600/90 via-fuchsia-500/50 to-rose-400/20',
            'border' => 'border-pink-500/30 hover:border-pink-400',
            'glow' => 'rgba(236, 72, 153, 0.4)',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />',
        ],
        'Hài hước' => [
            'image' => asset('images/genres/hai-huoc.jpg'),
            'gradient' => 'from-amber-600/90 via-yellow-500/50 to-orange-400/20',
            'border' => 'border-amber-500/30 hover:border-amber-400',
            'glow' => 'rgba(245, 158, 11, 0.4)',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
        ],
        'Hành động' => [
            'image' => asset('images/genres/hanh-dong.jpg'),
            'gradient' => 'from-blue-700/90 via-indigo-600/50 to-cyan-500/20',
            'border' => 'border-blue-500/30 hover:border-blue-400',
            'glow' => 'rgba(59, 130, 246, 0.4)',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />',
        ],
        'Kinh dị' => [
            'image' => asset('images/genres/kinh-di.jpg'),
            'gradient' => 'from-emerald-800/90 via-green-700/50 to-teal-800/20',
            'border' => 'border-emerald-500/30 hover:border-emerald-400',
            'glow' => 'rgba(16, 185, 129, 0.4)',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />',
        ],
        'Phiêu lưu' => [
            'image' => asset('images/genres/phieu-luu.jpg'),
            'gradient' => 'from-indigo-900/90 via-purple-800/50 to-blue-700/20',
            'border' => 'border-indigo-500/30 hover:border-indigo-400',
            'glow' => 'rgba(99, 102, 241, 0.4)',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />',
        ],
        'Tình cảm' => [
            'image' => asset('images/genres/tinh-cam.jpg'),
            'gradient' => 'from-teal-700/90 via-cyan-600/50 to-emerald-500/20',
            'border' => 'border-teal-500/30 hover:border-teal-400',
            'glow' => 'rgba(20, 184, 166, 0.4)',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />',
        ],
        'Tội phạm' => [
            'image' => asset('images/genres/toi-pham.jpg'),
            'gradient' => 'from-rose-950/95 via-red-900/60 to-pink-900/20',
            'border' => 'border-rose-500/30 hover:border-rose-400',
            'glow' => 'rgba(244, 63, 94, 0.4)',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />',
        ],
        'Viễn tưởng' => [
            'image' => asset('images/genres/vien-tuong.jpg'),
            'gradient' => 'from-blue-950/95 via-indigo-900/60 to-cyan-800/20',
            'border' => 'border-blue-500/30 hover:border-blue-400',
            'glow' => 'rgba(59, 130, 246, 0.4)',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />',
        ],
        'Tài liệu' => [
            'image' => asset('images/genres/tai-lieu.jpg'),
            'gradient' => 'from-stone-900/90 via-amber-900/50 to-yellow-800/20',
            'border' => 'border-amber-600/30 hover:border-amber-400',
            'glow' => 'rgba(217, 119, 6, 0.4)',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />',
        ],
        'Tâm lý' => [
            'image' => asset('images/genres/tam-ly.jpg'),
            'gradient' => 'from-slate-900/95 via-purple-950/60 to-indigo-900/20',
            'border' => 'border-purple-500/30 hover:border-purple-400',
            'glow' => 'rgba(168, 85, 247, 0.4)',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />',
        ],
        'Võ thuật' => [
            'image' => asset('images/genres/vo-thuat.jpg'),
            'gradient' => 'from-orange-800/90 via-red-700/50 to-amber-600/20',
            'border' => 'border-orange-500/30 hover:border-orange-400',
            'glow' => 'rgba(234, 88, 12, 0.4)',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />',
        ],
        'Âm nhạc' => [
            'image' => asset('images/genres/am-nhac.jpg'),
            'gradient' => 'from-violet-800/90 via-fuchsia-700/50 to-blue-600/20',
            'border' => 'border-violet-500/30 hover:border-violet-400',
            'glow' => 'rgba(139, 92, 246, 0.4)',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />',
        ],
        'Khoa Học' => [
            'image' => asset('images/genres/khoa-hoc.jpg'),
            'gradient' => 'from-cyan-900/90 via-blue-800/50 to-indigo-700/20',
            'border' => 'border-cyan-500/30 hover:border-cyan-400',
            'glow' => 'rgba(6, 182, 212, 0.4)',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />',
        ],
    ];

    $allMeta = $genreMeta['Tất cả thể loại'];
    $isAllActive = empty($activeGenre);
@endphp

<div class="genre-carousel-wrapper my-6 relative select-none">
    @if($showTitle)
        <!-- Title Header -->
        <div class="flex items-center justify-between mb-5 px-1">
            <div class="flex items-center gap-3">
                <span class="w-1.5 h-7 rounded-full bg-gradient-to-b from-rose-500 via-pink-500 to-indigo-500 inline-block shadow-[0_0_12px_rgba(244,63,94,0.6)]"></span>
                <h2 class="text-2xl md:text-3xl font-bold text-white tracking-tight flex items-center">
                    Bạn đang&nbsp;<span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-500 via-rose-400 to-pink-600">quan tâm gì?</span>
                </h2>
            </div>
        </div>
    @endif

    <!-- Cards Scroll Container -->
    <div id="{{ $prefix }}-genre-container" 
         class="genre-scroll-box flex items-center gap-3.5 overflow-x-auto py-2 px-1 scroll-smooth cursor-grab active:cursor-grabbing"
         style="scrollbar-width: none; -ms-overflow-style: none;">
        
        <!-- 01. Card: Tất cả thể loại -->
        <a href="{{ route('movies.index', array_filter(['search' => $search])) }}"
           class="genre-card-item shrink-0 relative rounded-2xl overflow-hidden group transition-all duration-300 transform hover:-translate-y-1.5 hover:scale-[1.02] border {{ $isAllActive ? 'ring-2 ring-white border-white scale-[1.02] shadow-[0_0_25px_rgba(255,255,255,0.4)]' : $allMeta['border'] }}"
           style="width: 165px; height: 215px; text-decoration: none; box-shadow: {{ $isAllActive ? '0 0 25px rgba(255,255,255,0.3)' : '0 10px 25px rgba(0,0,0,0.5)' }};">
            <!-- Background Image -->
            <img src="{{ $allMeta['image'] }}" 
                 alt="Tất cả thể loại" 
                 class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                 loading="lazy">
            
            <!-- Color Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-t {{ $allMeta['gradient'] }} transition-opacity duration-300"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent"></div>
            
            <!-- Top Icon Badge -->
            <div class="absolute top-3 left-3 w-8 h-8 rounded-xl bg-black/25 backdrop-blur-md border border-white/30 flex items-center justify-center text-white shadow group-hover:bg-white/30 transition">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    {!! $allMeta['icon'] !!}
                </svg>
            </div>

            <!-- Bottom Text -->
            <div class="absolute bottom-3 left-3 right-3">
                <h3 class="text-[15px] font-bold text-white tracking-wide truncate group-hover:text-amber-200 transition">
                    Tất cả thể loại
                </h3>
                <div class="text-[10px] font-extrabold tracking-widest text-white/90 group-hover:text-white uppercase flex items-center gap-1 mt-0.5">
                    <span>XEM NGAY</span>
                    <svg class="w-3 h-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </div>
        </a>

        <!-- 02. Genre Cards from DB -->
        @foreach($genres as $g)
            @php
                $meta = $genreMeta[$g->name] ?? [
                    'image' => asset('images/genres/tat-ca.jpg'),
                    'gradient' => 'from-slate-900/95 via-purple-900/50 to-blue-900/20',
                    'border' => 'border-white/20 hover:border-white/40',
                    'glow' => 'rgba(255,255,255,0.3)',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />',
                ];
                $isActive = ($activeGenre === $g->name);
            @endphp

            <a href="{{ route('movies.index', array_filter(['genre' => $g->name, 'search' => $search])) }}"
               class="genre-card-item shrink-0 relative rounded-2xl overflow-hidden group transition-all duration-300 transform hover:-translate-y-1.5 hover:scale-[1.02] border {{ $isActive ? 'ring-2 ring-white border-white scale-[1.02] shadow-[0_0_25px_rgba(255,255,255,0.4)]' : $meta['border'] }}"
               style="width: 165px; height: 215px; text-decoration: none; box-shadow: {{ $isActive ? '0 0 25px rgba(255,255,255,0.3)' : '0 10px 25px rgba(0,0,0,0.5)' }};">
                <!-- Background Image -->
                <img src="{{ $meta['image'] }}" 
                     alt="{{ $g->name }}" 
                     class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                     loading="lazy">
                
                <!-- Color Gradient Overlay -->
                <div class="absolute inset-0 bg-gradient-to-t {{ $meta['gradient'] }} transition-opacity duration-300"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent"></div>
                
                <!-- Top Icon Badge -->
                <div class="absolute top-3 left-3 w-8 h-8 rounded-xl bg-black/25 backdrop-blur-md border border-white/30 flex items-center justify-center text-white shadow group-hover:bg-white/30 transition">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        {!! $meta['icon'] !!}
                    </svg>
                </div>

                <!-- Bottom Text -->
                <div class="absolute bottom-3 left-3 right-3">
                    <h3 class="text-[15px] font-bold text-white tracking-wide truncate group-hover:text-amber-200 transition">
                        {{ $g->name }}
                    </h3>
                    <div class="text-[10px] font-extrabold tracking-widest text-white/90 group-hover:text-white uppercase flex items-center gap-1 mt-0.5">
                        <span>XEM NGAY</span>
                        <svg class="w-3 h-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    <!-- Bottom Navigation Bar (Arrows & Scroll Indicator) -->
    <div class="flex items-center gap-3 mt-4 px-1">
        <!-- Left Arrow Button -->
        <button type="button" 
                id="{{ $prefix }}-scroll-left" 
                class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/25 text-gray-400 hover:text-white flex items-center justify-center transition shrink-0 cursor-pointer focus:outline-none"
                aria-label="Cuộn sang trái">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>

        <!-- Progress Track -->
        <div id="{{ $prefix }}-scroll-track" 
             class="flex-1 h-1.5 bg-white/10 rounded-full relative overflow-hidden cursor-pointer">
            <div id="{{ $prefix }}-scroll-thumb" 
                 class="h-full bg-white/40 hover:bg-white/70 rounded-full transition-all duration-150 absolute top-0 left-0"
                 style="width: 25%; transform: translateX(0%);"></div>
        </div>

        <!-- Right Arrow Button -->
        <button type="button" 
                id="{{ $prefix }}-scroll-right" 
                class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/25 text-gray-400 hover:text-white flex items-center justify-center transition shrink-0 cursor-pointer focus:outline-none"
                aria-label="Cuộn sang phải">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
    </div>
</div>

<style>
    #{{ $prefix }}-genre-container::-webkit-scrollbar {
        display: none;
    }
</style>

<script>
(function() {
    function initGenreSlider_{{ $prefix }}() {
        const container = document.getElementById('{{ $prefix }}-genre-container');
        const track = document.getElementById('{{ $prefix }}-scroll-track');
        const thumb = document.getElementById('{{ $prefix }}-scroll-thumb');
        const btnLeft = document.getElementById('{{ $prefix }}-scroll-left');
        const btnRight = document.getElementById('{{ $prefix }}-scroll-right');

        if (!container || !track || !thumb) return;

        function updateThumb() {
            const maxScroll = container.scrollWidth - container.clientWidth;
            if (maxScroll <= 0) {
                thumb.style.width = '100%';
                thumb.style.transform = 'translateX(0%)';
                return;
            }

            const visibleRatio = container.clientWidth / container.scrollWidth;
            const thumbWidth = Math.max(visibleRatio * 100, 15);
            thumb.style.width = thumbWidth + '%';

            const scrollRatio = container.scrollLeft / maxScroll;
            const maxTranslate = (100 - thumbWidth);
            const translateX = (scrollRatio * maxTranslate * (100 / thumbWidth));
            thumb.style.transform = `translateX(${translateX}%)`;
        }

        container.addEventListener('scroll', updateThumb, { passive: true });
        window.addEventListener('resize', updateThumb, { passive: true });
        setTimeout(updateThumb, 100);

        if (btnLeft) {
            btnLeft.addEventListener('click', () => {
                container.scrollBy({ left: -360, behavior: 'smooth' });
            });
        }

        if (btnRight) {
            btnRight.addEventListener('click', () => {
                container.scrollBy({ left: 360, behavior: 'smooth' });
            });
        }

        track.addEventListener('click', (e) => {
            const rect = track.getBoundingClientRect();
            const clickRatio = (e.clientX - rect.left) / rect.width;
            const maxScroll = container.scrollWidth - container.clientWidth;
            container.scrollTo({
                left: clickRatio * maxScroll,
                behavior: 'smooth'
            });
        });

        // Mouse Drag to Scroll
        let isDown = false;
        let startX;
        let scrollLeft;

        container.addEventListener('mousedown', (e) => {
            isDown = true;
            startX = e.pageX - container.offsetLeft;
            scrollLeft = container.scrollLeft;
        });

        container.addEventListener('mouseleave', () => {
            isDown = false;
        });

        container.addEventListener('mouseup', () => {
            isDown = false;
        });

        container.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - container.offsetLeft;
            const walk = (x - startX) * 1.5;
            container.scrollLeft = scrollLeft - walk;
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initGenreSlider_{{ $prefix }});
    } else {
        initGenreSlider_{{ $prefix }}();
    }
})();
</script>
