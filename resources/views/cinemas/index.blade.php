@extends('layouts.app')

@section('title', 'Hệ Thống Rạp Phim - HCTV')

@section('content')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<style>
    /* Leaflet Popup Styling */
    .leaflet-popup-content-wrapper {
        background: #ffffff !important;
        color: #18181b !important;
        border-radius: 12px !important;
        box-shadow: 0 10px 25px rgba(0,0,0,0.35) !important;
        padding: 4px 6px !important;
    }
    .leaflet-popup-tip {
        background: #ffffff !important;
    }
    .leaflet-popup-content {
        margin: 8px 12px !important;
        font-family: inherit !important;
    }
    .custom-cinema-pin {
        background: transparent !important;
        border: none !important;
    }
    /* Custom scrollbar for left list */
    .cinema-scroll-list::-webkit-scrollbar {
        width: 6px;
    }
    .cinema-scroll-list::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 9999px;
    }
    .cinema-scroll-list::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 9999px;
    }
    .cinema-scroll-list::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.4);
    }

    /* =========================================================================
       CINEMA CARDS - DARK MODE (DEFAULT)
       ========================================================================= */
    .cinema-card {
        background-color: #18181b;
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #f4f4f5;
    }
    .cinema-card:hover {
        border-color: rgba(249, 115, 22, 0.5);
        background-color: #202024;
    }
    .cinema-card .cinema-name {
        color: #ffffff;
    }
    .cinema-card .cinema-sub {
        color: #9ca3af;
    }
    .cinema-card .cinema-card-actions {
        border-top: 1px solid rgba(255, 255, 255, 0.08);
    }
    .cinema-card .cinema-action-detail {
        color: #eab308;
    }
    .cinema-card .cinema-action-detail:hover {
        color: #ffffff;
    }
    .cinema-card .cinema-action-dir {
        color: #9ca3af;
    }
    .cinema-card .cinema-action-dir:hover {
        color: #ffffff;
    }
    .cinema-card .cinema-action-sep {
        color: #4b5563;
    }

    /* Active Cinema Card in Dark Mode */
    .cinema-card.is-active {
        background-color: #221f1d !important;
        border: 2px solid #f97316 !important;
        box-shadow: 0 0 20px rgba(234, 88, 12, 0.25) !important;
    }
    .cinema-card.is-active .cinema-name {
        color: #f97316 !important;
    }
    .cinema-card.is-active .cinema-sub {
        color: #cbd5e1 !important;
    }
    .cinema-card.is-active .cinema-active-badge {
        color: #f97316 !important;
    }

    /* =========================================================================
       CINEMA SYSTEM IN LIGHT MODE (Complete High-Contrast & Readability Fix)
       ========================================================================= */
    html.light-mode .cinema-scroll-list {
        background-color: transparent !important;
    }
    html.light-mode .cinema-scroll-list::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.05) !important;
    }
    html.light-mode .cinema-scroll-list::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.2) !important;
    }
    html.light-mode .cinema-scroll-list::-webkit-scrollbar-thumb:hover {
        background: rgba(0, 0, 0, 0.35) !important;
    }

    /* Inactive Cinema Cards in Light Mode */
    html.light-mode .cinema-card {
        background-color: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04) !important;
        color: #0f172a !important;
    }
    html.light-mode .cinema-card:hover {
        background-color: #fffaf5 !important;
        border-color: #fdba74 !important;
        box-shadow: 0 4px 14px rgba(249, 115, 22, 0.12) !important;
    }
    html.light-mode .cinema-card .cinema-name {
        color: #0f172a !important;
        font-weight: 700 !important;
    }
    html.light-mode .cinema-card:hover .cinema-name {
        color: #ea580c !important;
    }
    html.light-mode .cinema-card .cinema-sub {
        color: #64748b !important;
    }
    html.light-mode .cinema-card .cinema-card-actions {
        border-top: 1px solid #f1f5f9 !important;
    }
    html.light-mode .cinema-card .cinema-action-detail {
        color: #ea580c !important;
        font-weight: 700 !important;
    }
    html.light-mode .cinema-card .cinema-action-detail:hover {
        color: #c2410c !important;
    }
    html.light-mode .cinema-card .cinema-action-dir {
        color: #64748b !important;
        font-weight: 500 !important;
    }
    html.light-mode .cinema-card .cinema-action-dir:hover {
        color: #0f172a !important;
    }
    html.light-mode .cinema-card .cinema-action-sep {
        color: #cbd5e1 !important;
    }

    /* ACTIVE Cinema Card in Light Mode - High Contrast Warm Amber/Ivory */
    html.light-mode .cinema-card.is-active {
        background-color: #fff7ed !important; /* Soft warm ivory amber tone */
        border: 2px solid #ea580c !important; /* Bold cinema orange border */
        box-shadow: 0 6px 24px rgba(234, 88, 12, 0.18) !important;
    }
    html.light-mode .cinema-card.is-active .cinema-name {
        color: #c2410c !important; /* Vivid deep cinema orange, 100% sharp and readable */
        font-weight: 800 !important;
    }
    html.light-mode .cinema-card.is-active .cinema-sub {
        color: #334155 !important; /* Slate-700, perfect contrast */
        font-weight: 500 !important;
    }
    html.light-mode .cinema-card.is-active .cinema-active-badge {
        color: #ea580c !important;
        font-weight: 700 !important;
    }
    html.light-mode .cinema-card.is-active .cinema-card-actions {
        border-top: 1px solid #fed7aa !important;
    }
    html.light-mode .cinema-card.is-active .cinema-action-detail {
        color: #c2410c !important;
        font-weight: 800 !important;
    }
    html.light-mode .cinema-card.is-active .cinema-action-dir {
        color: #475569 !important;
        font-weight: 600 !important;
    }
    html.light-mode .cinema-card.is-active .cinema-action-sep {
        color: #fdba74 !important;
    }

    /* City Filter Buttons in Light Mode */
    html.light-mode .city-btn.active {
        background-color: #ea580c !important;
        border-color: #ea580c !important;
        color: #ffffff !important;
        box-shadow: 0 2px 8px rgba(234, 88, 12, 0.3) !important;
    }
    html.light-mode .city-btn:not(.active) {
        background-color: #ffffff !important;
        border-color: #e2e8f0 !important;
        color: #475569 !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05) !important;
    }
    html.light-mode .city-btn:not(.active):hover {
        background-color: #fff7ed !important;
        border-color: #ea580c !important;
        color: #ea580c !important;
    }

    /* Recenter button in Light Mode */
    html.light-mode #btn-recenter {
        background-color: #ffffff !important;
        border-color: #cbd5e1 !important;
        color: #1e293b !important;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06) !important;
    }
    html.light-mode #btn-recenter:hover {
        background-color: #fff7ed !important;
        border-color: #ea580c !important;
        color: #ea580c !important;
    }

    /* Cinema Map Canvas Wrapper in Light Mode */
    html.light-mode .cinema-map-wrapper {
        background-color: #ffffff !important;
        border-color: #e2e8f0 !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08) !important;
    }

    /* Map Overlay Legend in Light Mode */
    html.light-mode .map-overlay-badge {
        background-color: rgba(255, 255, 255, 0.95) !important;
        border-color: #e2e8f0 !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1) !important;
    }
    html.light-mode .map-overlay-badge span.text-gray-200 {
        color: #1e293b !important;
        font-weight: 600 !important;
    }
</style>

<div class="pt-24 pb-16 bg-cinematic-dark min-h-screen">
    <!-- Header Title Banner -->
    <div class="relative py-12 mb-8 flex justify-center items-center overflow-hidden border-b border-white/10">
        <div class="absolute inset-0 bg-cinematic-gold/10 blur-3xl opacity-30"></div>
        <div class="text-center relative z-10 px-4">
            <h1 class="text-3xl md:text-5xl font-serif font-bold text-white uppercase tracking-widest flex items-center justify-center gap-4">
                <span class="w-10 h-1 bg-cinematic-gold inline-block"></span>
                Hệ Thống Rạp
                <span class="w-10 h-1 bg-cinematic-gold inline-block"></span>
            </h1>
            <p class="text-gray-400 text-sm md:text-base mt-2 max-w-xl mx-auto">
                Khám phá hệ thống rạp chiếu hiện đại trên toàn quốc với bản đồ vị trí trực quan
            </p>
        </div>
    </div>

    <!-- Main Container: 2 Columns Layout -->
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10">
        <!-- City Filter Pills & Quick Stats -->
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <!-- Filter by City -->
            <div class="flex items-center gap-2 overflow-x-auto pb-1" id="city-filters">
                <button type="button" 
                        class="city-btn active px-3.5 py-1.5 rounded-full text-xs font-bold transition-all border bg-orange-500 border-orange-500 text-white shadow"
                        data-city="all">
                    Tất cả ({{ count($cinemas) }})
                </button>
                @php
                    $cities = $cinemas->pluck('city')->filter()->unique();
                @endphp
                @foreach($cities as $city)
                    <button type="button" 
                            class="city-btn px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all border bg-white/5 border-white/15 text-gray-300 hover:border-white/30 hover:text-white"
                            data-city="{{ $city }}">
                        {{ $city }}
                    </button>
                @endforeach
            </div>

            <div class="text-xs text-gray-400 hidden sm:block">
                Nhấp vào rạp để <strong class="text-orange-400">chuyển động bản đồ</strong> đến vị trí tương ứng
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Left Column: Danh Sách Rạp (approx 4.5 cols) -->
            <div class="lg:col-span-5 xl:col-span-4 flex flex-col">
                <div class="flex items-center justify-between mb-4 px-1">
                    <h2 class="text-xl md:text-2xl font-bold text-white tracking-tight flex items-center gap-2">
                        <span class="w-1.5 h-6 rounded-full bg-orange-500 inline-block shadow-[0_0_10px_rgba(249,115,22,0.6)]"></span>
                        Danh Sách Rạp
                    </h2>
                    <span class="text-xs text-gray-400" id="cinema-count-label">{{ count($cinemas) }} cụm rạp</span>
                </div>

                <!-- Cinema Cards List -->
                <div class="cinema-scroll-list space-y-3.5 overflow-y-auto pr-1.5" style="max-height: 640px;">
                    @foreach($cinemas as $index => $cinema)
                    <div class="cinema-card relative rounded-xl p-4 transition-all duration-300 cursor-pointer {{ $index === 0 ? 'is-active bg-[#221f1d] border-2 border-orange-500 shadow-[0_0_20px_rgba(234,88,12,0.25)]' : 'bg-[#18181b] border border-white/10 hover:border-orange-500/50 hover:bg-[#202024]' }}"
                         id="card-cinema-{{ $cinema->id }}"
                         data-id="{{ $cinema->id }}"
                         data-name="{{ $cinema->name }}"
                         data-city="{{ $cinema->city }}"
                         data-lat="{{ $cinema->latitude ?? 10.76348 }}"
                         data-lng="{{ $cinema->longitude ?? 106.68595 }}"
                         data-location="{{ $cinema->location }}"
                         data-phone="{{ $cinema->phone ?? '1900 6017' }}"
                         data-url="{{ route('cinemas.show', $cinema->id) }}">
                        
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <h3 class="cinema-name text-[16px] font-bold transition-colors leading-tight {{ $index === 0 ? 'text-orange-500' : 'text-white' }}">
                                    {{ $cinema->name }}
                                </h3>
                                <p class="cinema-sub text-gray-400 text-xs mt-1 leading-relaxed">
                                    {{ $cinema->location }}
                                </p>
                            </div>
                        </div>

                        <!-- Active Indicator -->
                        <div class="cinema-active-badge items-center gap-1.5 text-xs text-orange-500 font-semibold mt-2.5 {{ $index === 0 ? 'flex' : 'hidden' }}">
                            <span class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></span>
                            <span>Đang xem trên bản đồ</span>
                        </div>

                        <!-- Action buttons -->
                        <div class="cinema-card-actions flex items-center gap-3 mt-3 pt-3 border-t border-white/5 text-xs">
                            <a href="{{ route('cinemas.show', $cinema->id) }}" 
                               class="cinema-action-detail text-cinematic-gold hover:text-white font-semibold transition-colors flex items-center gap-1"
                               onclick="event.stopPropagation();">
                                <span>Xem chi tiết</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                            <span class="cinema-action-sep text-gray-600">|</span>
                            <a href="https://www.google.com/maps/dir/?api=1&destination={{ $cinema->latitude }},{{ $cinema->longitude }}" 
                               target="_blank" 
                               rel="noopener"
                               class="cinema-action-dir text-gray-400 hover:text-white transition-colors flex items-center gap-1"
                               onclick="event.stopPropagation();">
                                <svg class="w-3.5 h-3.5 text-orange-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                                <span>Chỉ đường</span>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Right Column: Bản Đồ Vị Trí (approx 7.5 cols) -->
            <div class="lg:col-span-7 xl:col-span-8 flex flex-col">
                <div class="flex items-center justify-between mb-4 px-1">
                    <h2 class="text-xl md:text-2xl font-bold text-white tracking-tight flex items-center gap-2">
                        <span class="w-1.5 h-6 rounded-full bg-orange-500 inline-block shadow-[0_0_10px_rgba(249,115,22,0.6)]"></span>
                        Bản Đồ Vị Trí
                    </h2>
                    <button type="button" 
                            id="btn-recenter" 
                            class="text-xs bg-white/10 hover:bg-white/20 text-gray-300 hover:text-white px-3 py-1.5 rounded-lg border border-white/15 transition flex items-center gap-1.5"
                            title="Hiển thị tất cả rạp">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5v-4m0 4h-4m4 0l-5-5"></path></svg>
                        <span>Xem toàn cảnh</span>
                    </button>
                </div>

                <!-- Interactive Map Canvas Container -->
                <div class="cinema-map-wrapper relative w-full h-[480px] sm:h-[540px] lg:h-[640px] rounded-2xl overflow-hidden border border-white/15 shadow-[0_20px_50px_rgba(0,0,0,0.7)] bg-[#18181b]">
                    <div id="cinema-map" class="w-full h-full z-10"></div>

                    <!-- Overlay Legend Card -->
                    <div class="map-overlay-badge absolute bottom-4 left-4 z-20 bg-black/80 backdrop-blur-md border border-white/20 rounded-xl px-3.5 py-2 text-xs text-white flex items-center gap-2 shadow-lg pointer-events-none">
                        <span class="w-3 h-3 rounded-full bg-orange-500 border border-white shadow shrink-0"></span>
                        <span class="text-gray-200 font-medium">Rạp phim HCTV / Cinema</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cinema data passed from Laravel
    const cinemas = @json($cinemas);
    if (!cinemas || cinemas.length === 0) return;

    let activeCinemaId = cinemas[0].id;
    const markers = {};

    // Initial center on the first cinema
    const initialLat = parseFloat(cinemas[0].latitude) || 10.76348;
    const initialLng = parseFloat(cinemas[0].longitude) || 106.68595;

    // Initialize Leaflet Map
    const map = L.map('cinema-map', {
        center: [initialLat, initialLng],
        zoom: 16,
        zoomControl: true,
        scrollWheelZoom: true
    });

    // Add OpenStreetMap Standard Tiles (Matching user reference screenshot)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Custom Pin Marker Creator
    function createPinIcon(isActive = false) {
        const size = isActive ? 40 : 34;
        const pulse = isActive ? '<div class="absolute -inset-1 rounded-full bg-orange-500 animate-ping opacity-60 pointer-events-none"></div>' : '';
        return L.divIcon({
            className: 'custom-cinema-pin',
            html: `
                <div class="relative flex items-center justify-center" style="width: ${size}px; height: ${size}px;">
                    ${pulse}
                    <div class="relative w-full h-full rounded-full bg-gradient-to-tr from-[#ea580c] to-[#f97316] border-2 border-white shadow-[0_4px_12px_rgba(0,0,0,0.45)] flex items-center justify-center text-white cursor-pointer transition-transform duration-200 hover:scale-110">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            `,
            iconSize: [size, size],
            iconAnchor: [size / 2, size / 2],
            popupAnchor: [0, -size / 2 - 4]
        });
    }

    // Add Markers to Map
    cinemas.forEach((c, index) => {
        const lat = parseFloat(c.latitude);
        const lng = parseFloat(c.longitude);
        if (isNaN(lat) || isNaN(lng)) return;

        const isCurrent = (c.id === activeCinemaId);
        const marker = L.marker([lat, lng], {
            icon: createPinIcon(isCurrent)
        }).addTo(map);

        // Custom Popup Content
        const popupContent = `
            <div style="font-family: inherit; min-width: 180px; max-width: 240px;">
                <h4 style="font-weight: 700; font-size: 14px; margin: 0 0 4px 0; color: #18181b; line-height: 1.3;">${c.name}</h4>
                <p style="font-size: 12px; color: #6b7280; margin: 0 0 8px 0; line-height: 1.4;">${c.location || ''}</p>
                <div style="display: flex; gap: 8px; font-size: 12px;">
                    <a href="/cinemas/${c.id}" style="color: #ea580c; font-weight: 700; text-decoration: none;">Xem chi tiết &rarr;</a>
                </div>
            </div>
        `;

        marker.bindPopup(popupContent, {
            offset: [0, -6],
            closeButton: true
        });

        // Click on marker
        marker.on('click', () => {
            selectCinema(c, true);
        });

        markers[c.id] = marker;

        // Open initial active popup
        if (isCurrent) {
            setTimeout(() => {
                marker.openPopup();
            }, 500);
        }
    });

    // Select Cinema Function with Smooth Animated Fly Motion
    function selectCinema(cinema, animate = true) {
        activeCinemaId = cinema.id;

        // 1. Update Left Column UI
        document.querySelectorAll('.cinema-card').forEach(card => {
            const id = parseInt(card.dataset.id);
            const isCur = (id === cinema.id);

            card.classList.toggle('is-active', isCur);
            card.classList.toggle('border-orange-500', isCur);
            card.classList.toggle('border-2', isCur);
            card.classList.toggle('bg-[#221f1d]', isCur);
            card.classList.toggle('shadow-[0_0_20px_rgba(234,88,12,0.25)]', isCur);

            card.classList.toggle('border-white/10', !isCur);
            card.classList.toggle('bg-[#18181b]', !isCur);

            const title = card.querySelector('.cinema-name');
            if (title) {
                title.classList.toggle('text-orange-500', isCur);
                title.classList.toggle('text-white', !isCur);
            }

            const badge = card.querySelector('.cinema-active-badge');
            if (badge) {
                badge.classList.toggle('hidden', !isCur);
                badge.classList.toggle('flex', isCur);
            }

            if (isCur) {
                card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        });

        // 2. Update Markers Icon Styles
        Object.keys(markers).forEach(k => {
            const isCur = (parseInt(k) === cinema.id);
            markers[k].setIcon(createPinIcon(isCur));
            markers[k].setZIndexOffset(isCur ? 1000 : 0);
        });

        const lat = parseFloat(cinema.latitude);
        const lng = parseFloat(cinema.longitude);

        // 3. Smooth Camera Flying Animation ("map có thể chuyển động được bạn")
        if (!isNaN(lat) && !isNaN(lng)) {
            if (animate) {
                map.flyTo([lat, lng], 16, {
                    animate: true,
                    duration: 1.4,
                    easeLinearity: 0.25
                });
            } else {
                map.setView([lat, lng], 16);
            }

            // Open Popup after camera motion
            setTimeout(() => {
                if (markers[cinema.id]) {
                    markers[cinema.id].openPopup();
                }
            }, animate ? 700 : 100);
        }
    }

    // Attach click events on Left Column Cinema Cards
    document.querySelectorAll('.cinema-card').forEach(card => {
        card.addEventListener('click', () => {
            const id = parseInt(card.dataset.id);
            const cinema = cinemas.find(c => c.id === id);
            if (cinema) {
                selectCinema(cinema, true);
            }
        });
    });

    // City Filter Buttons
    const cityButtons = document.querySelectorAll('.city-btn');
    cityButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            cityButtons.forEach(b => {
                b.classList.remove('active', 'bg-orange-500', 'border-orange-500', 'text-white', 'shadow');
                b.classList.add('bg-white/5', 'border-white/15', 'text-gray-300');
            });
            btn.classList.add('active', 'bg-orange-500', 'border-orange-500', 'text-white', 'shadow');
            btn.classList.remove('bg-white/5', 'border-white/15', 'text-gray-300');

            const selectedCity = btn.dataset.city;
            let visibleCount = 0;
            let firstVisible = null;

            document.querySelectorAll('.cinema-card').forEach(card => {
                const city = card.dataset.city;
                if (selectedCity === 'all' || city === selectedCity) {
                    card.classList.remove('hidden');
                    visibleCount++;
                    if (!firstVisible) {
                        const id = parseInt(card.dataset.id);
                        firstVisible = cinemas.find(c => c.id === id);
                    }
                } else {
                    card.classList.add('hidden');
                }
            });

            document.getElementById('cinema-count-label').textContent = `${visibleCount} cụm rạp`;

            if (firstVisible) {
                selectCinema(firstVisible, true);
            }
        });
    });

    // Recenter / View All Button
    const recenterBtn = document.getElementById('btn-recenter');
    if (recenterBtn) {
        recenterBtn.addEventListener('click', () => {
            const group = new L.featureGroup(Object.values(markers));
            map.flyToBounds(group.getBounds().pad(0.2), {
                animate: true,
                duration: 1.5
            });
        });
    }

    // Window Resize Map Fix
    window.addEventListener('resize', () => {
        map.invalidateSize();
    });
});
</script>
@endsection
