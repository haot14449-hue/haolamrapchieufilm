<!DOCTYPE html>
<html lang="vi" class="light-mode" x-data="posCounter()">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quầy Bán Vé Trực Tiếp (POS) - HCTV Cinema</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Enforce default light mode for POS counter -->
    <script>
        document.documentElement.classList.add('light-mode');
        document.documentElement.classList.remove('dark');
    </script>

    <style>
        [x-cloak] { display: none !important; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        body {
            background-color: #f1f5f9 !important;
            color: #0f172a !important;
        }
    </style>
</head>
<body class="font-sans antialiased min-h-screen flex flex-col select-none overflow-x-hidden bg-slate-100 text-slate-800">

    <!-- Top POS Navigation Bar -->
    <header class="h-16 border-b border-slate-200 bg-white text-slate-800 shadow-xs px-4 sm:px-6 flex items-center justify-between shrink-0 z-30">
        <div class="flex items-center gap-4">
            <!-- Brand: Just HCTV, without QUẦY VÉ POS badge -->
            <a href="{{ route('pos.index') }}" class="flex items-center gap-2">
                <span class="text-2xl font-black tracking-wider text-slate-900 hover:opacity-90 transition">
                    HC<span class="text-red-600">TV</span>
                </span>
            </a>

            <!-- Cinema Selector -->
            <div class="hidden md:flex items-center gap-2 ml-4 pl-4 border-l border-slate-200">
                <label class="text-xs font-bold text-slate-700">Rạp:</label>
                <select x-model="selectedCinemaId" @change="fetchShowtimes()" 
                        class="text-xs font-bold rounded-lg px-3 py-1.5 border-2 border-slate-200 bg-slate-50 text-slate-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition">
                    @foreach($cinemas as $c)
                        <option value="{{ $c->id }}" {{ $c->id == $selectedCinemaId ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Date Tabs -->
        <div class="hidden lg:flex items-center gap-1.5 p-1 rounded-xl border border-slate-200 bg-slate-100">
            @foreach($dates as $d)
                <button type="button" 
                        @click="selectedDate = '{{ $d['date'] }}'; fetchShowtimes()"
                        :class="selectedDate === '{{ $d['date'] }}' 
                                ? 'bg-emerald-600 text-white shadow-sm' 
                                : 'text-slate-700 hover:text-slate-900 hover:bg-white'"
                        class="px-3 py-1 rounded-lg text-xs font-bold transition-all flex flex-col items-center">
                    <span>{{ $d['day_name'] }}</span>
                    <span class="text-[10px] opacity-80 font-normal">{{ $d['formatted'] }}</span>
                </button>
            @endforeach
        </div>

        <!-- Right Controls: Cashier, Links -->
        <div class="flex items-center gap-2.5 sm:gap-3">
            <!-- Cashier Pill -->
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-full border text-xs bg-slate-50 border-slate-200 text-slate-800">
                <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                <div class="text-left">
                    <span class="font-bold text-slate-900">{{ auth()->user()->name }}</span>
                    <span class="text-[10px] text-slate-500">({{ auth()->user()->role === 'admin' ? 'Quản trị' : 'Thu ngân' }})</span>
                </div>
            </div>

            <!-- Back links -->
            @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" 
               :class="isLightMode ? 'bg-slate-100 hover:bg-slate-200 text-slate-700 border-slate-300' : 'bg-gray-800 hover:bg-gray-700 text-gray-300 border-gray-700'"
               class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold border transition" title="Trở về Bảng Quản Trị">
                Admin
            </a>
            @endif
            <a href="{{ route('home') }}" 
               :class="isLightMode ? 'hover:bg-slate-100 text-slate-600' : 'hover:bg-gray-800 text-gray-400'"
               class="p-2 rounded-lg transition" title="Xem trang chủ">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>

            <!-- Quick Logout for Staff -->
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" 
                        :class="isLightMode ? 'hover:bg-red-50 text-red-600' : 'hover:bg-red-950/40 text-red-400'"
                        class="p-2 rounded-lg transition flex items-center" title="Đăng xuất tài khoản">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
        </div>
    </header>

    <!-- Main Workspace (2-Column Layout) -->
    <div class="flex-1 flex flex-col lg:flex-row overflow-hidden">

        <!-- LEFT COLUMN: Main Stepper Area (Phim -> Ghế -> Bắp Nước) -->
        <div class="flex-1 flex flex-col overflow-y-auto border-r border-slate-200 bg-slate-100/90 transition-colors duration-200">
            
            <!-- Stepper Navigation Header -->
            <div class="sticky top-0 backdrop-blur-md px-6 py-3.5 border-b border-slate-200 bg-white/95 text-slate-900 shadow-xs z-20 flex items-center justify-between transition-colors duration-200">
                <div class="flex items-center gap-2 sm:gap-3">
                    <!-- Step 1 Tab -->
                    <button type="button" @click="currentStep = 1"
                            :class="currentStep === 1 
                                    ? 'bg-emerald-600 text-white shadow-md ring-2 ring-emerald-500/30' 
                                    : 'bg-white text-slate-800 hover:bg-slate-50 border border-slate-300'"
                            class="flex items-center gap-2 px-3.5 py-1.5 rounded-xl text-xs font-bold transition">
                        <span class="w-5 h-5 rounded-full flex items-center justify-center text-[10px]"
                              :class="currentStep === 1 ? 'bg-black/20 text-white' : 'bg-slate-200 text-slate-800'">1</span>
                        <span>Chọn Phim & Suất</span>
                    </button>

                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>

                    <!-- Step 2 Tab -->
                    <button type="button" @click="if (selectedShowtime) currentStep = 2"
                            :class="currentStep === 2 
                                    ? 'bg-emerald-600 text-white shadow-md ring-2 ring-emerald-500/30' 
                                    : (selectedShowtime ? 'bg-white text-slate-800 hover:bg-slate-50 border border-slate-300' : 'bg-slate-100 text-slate-400 border border-slate-200 cursor-not-allowed')"
                            class="flex items-center gap-2 px-3.5 py-1.5 rounded-xl text-xs font-bold transition">
                        <span class="w-5 h-5 rounded-full flex items-center justify-center text-[10px]"
                              :class="currentStep === 2 ? 'bg-black/20 text-white' : (selectedShowtime ? 'bg-slate-200 text-slate-800' : 'bg-slate-200/60 text-slate-400')">2</span>
                        <span>Chọn Ghế</span>
                        <span x-show="selectedSeats.length > 0" class="px-1.5 py-0.2 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold" x-text="selectedSeats.length"></span>
                    </button>

                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>

                    <!-- Step 3 Tab -->
                    <button type="button" @click="if (selectedSeats.length > 0) proceedToStep3()"
                            :class="currentStep === 3 
                                    ? 'bg-emerald-600 text-white shadow-md ring-2 ring-emerald-500/30' 
                                    : (selectedSeats.length > 0 ? 'bg-white text-slate-800 hover:bg-slate-50 border border-slate-300' : 'bg-slate-100 text-slate-400 border border-slate-200 cursor-not-allowed')"
                            class="flex items-center gap-2 px-3.5 py-1.5 rounded-xl text-xs font-bold transition">
                        <span class="w-5 h-5 rounded-full flex items-center justify-center text-[10px]"
                              :class="currentStep === 3 ? 'bg-black/20 text-white' : (selectedSeats.length > 0 ? 'bg-slate-200 text-slate-800' : 'bg-slate-200/60 text-slate-400')">3</span>
                        <span>Bắp Nước & Combo</span>
                        <span x-show="totalFoodQuantity > 0" class="px-1.5 py-0.2 rounded-full bg-amber-400 text-gray-900 text-[10px] font-bold" x-text="totalFoodQuantity"></span>
                    </button>
                </div>

                <!-- Reset Selection Button -->
                <button type="button" @click="resetSelection()" 
                        class="text-xs font-semibold text-slate-600 hover:text-red-600 flex items-center gap-1.5 transition px-2.5 py-1 rounded-lg hover:bg-red-50">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span>Làm mới</span>
                </button>
            </div>

            <!-- Content Area of Steps -->
            <div class="p-6">
                <!-- STEP 1: CHỌN PHIM & SUẤT CHIẾU -->
                <div x-show="currentStep === 1" x-cloak>
                    <!-- Loading State -->
                    <div x-show="loadingShowtimes" class="py-16 text-center text-slate-500">
                        <svg class="animate-spin h-8 w-8 mx-auto text-emerald-500 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="text-sm font-semibold text-slate-700">Đang tải lịch chiếu rạp...</p>
                    </div>

                    <!-- Empty State -->
                    <div x-show="!loadingShowtimes && movieShowtimes.length === 0" class="py-16 text-center">
                        <div class="w-14 h-14 mx-auto mb-3 rounded-2xl flex items-center justify-center bg-slate-200 text-slate-500">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/></svg>
                        </div>
                        <p class="text-base font-bold text-slate-800">Không có suất chiếu nào vào ngày này</p>
                        <p class="text-xs mt-1 text-slate-500">Vui lòng chọn ngày khác hoặc đổi rạp chiếu ở thanh điều hướng trên.</p>
                    </div>

                    <!-- Movie List with Showtimes -->
                    <div x-show="!loadingShowtimes && movieShowtimes.length > 0" class="space-y-4">
                        <template x-for="item in movieShowtimes" :key="item.movie.id">
                            <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-sm hover:shadow-md hover:border-slate-300 text-slate-900 transition flex flex-col md:flex-row gap-5">
                                <!-- Movie Poster -->
                                <div class="w-24 h-36 shrink-0 rounded-xl overflow-hidden bg-slate-900 border border-slate-200 shadow-md relative">
                                    <img :src="item.movie.poster_url" :alt="item.movie.title" class="w-full h-full object-cover">
                                    <span class="absolute top-1.5 left-1.5 px-1.5 py-0.5 rounded text-[10px] font-black bg-red-600 text-white uppercase shadow" x-text="item.movie.rating || 'P'"></span>
                                </div>

                                <!-- Movie Details & Showtimes -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <h3 class="text-lg font-black text-slate-900 leading-tight" x-text="item.movie.title"></h3>
                                            <p class="text-xs mt-1 text-slate-600 font-medium">
                                                <span x-text="item.movie.duration + ' phút'"></span> • 
                                                <span x-text="item.movie.genre"></span>
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Showtimes List -->
                                    <div class="mt-4">
                                        <p class="text-xs font-bold uppercase tracking-wider mb-2.5 text-slate-800 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <span>CHỌN SUẤT CHIẾU:</span>
                                        </p>
                                        <div class="flex flex-wrap gap-2.5">
                                            <template x-for="st in item.showtimes" :key="st.id">
                                                <button type="button"
                                                        @click="selectShowtime(st, item.movie)"
                                                        :class="selectedShowtime && selectedShowtime.id === st.id 
                                                                ? 'bg-emerald-600 text-white border-2 border-emerald-600 shadow-md ring-2 ring-emerald-400/50 scale-[1.02]' 
                                                                : 'bg-white hover:bg-emerald-50/40 text-slate-900 border-2 border-slate-200 hover:border-emerald-500 shadow-xs hover:shadow'"
                                                        class="px-3.5 py-2.5 rounded-xl text-left border-2 transition-all duration-150 group cursor-pointer">
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-base font-black font-mono tracking-tight"
                                                              :class="selectedShowtime && selectedShowtime.id === st.id ? 'text-white' : 'text-slate-900'"
                                                              x-text="st.start_time"></span>
                                                        <span class="text-[10px] px-1.5 py-0.5 rounded font-black uppercase tracking-wide border" 
                                                              :class="selectedShowtime && selectedShowtime.id === st.id ? 'bg-emerald-800 text-white border-emerald-700' : 'bg-slate-100 text-slate-800 border-slate-300'" 
                                                              x-text="st.format"></span>
                                                        <span x-show="st.is_started" 
                                                              class="text-[9px] px-1.5 py-0.5 rounded font-bold uppercase tracking-wider"
                                                              :class="selectedShowtime && selectedShowtime.id === st.id ? 'bg-amber-400 text-amber-950' : 'bg-amber-100 text-amber-900 border border-amber-300'">Đang chiếu</span>
                                                    </div>
                                                    <div class="flex items-center justify-between gap-3 text-xs mt-1.5 font-medium"
                                                         :class="selectedShowtime && selectedShowtime.id === st.id ? 'text-emerald-100' : 'text-slate-600'">
                                                        <span class="font-bold truncate max-w-[85px]" x-text="st.room_name"></span>
                                                        <span class="font-black"
                                                              :class="selectedShowtime && selectedShowtime.id === st.id ? 'text-yellow-300' : 'text-emerald-700'"
                                                              x-text="st.price_formatted"></span>
                                                    </div>
                                                    <div class="text-[11px] mt-0.5 font-semibold"
                                                         :class="selectedShowtime && selectedShowtime.id === st.id ? 'text-emerald-100' : 'text-slate-500'"
                                                         x-text="'Còn ' + st.available_seats + ' ghế trống'"></div>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- STEP 2: CHỌN GHẾ NGỒI (SEAT MAP) -->
                <div x-show="currentStep === 2" x-cloak>
                    <!-- Showtime Summary Bar -->
                    <div class="bg-white border border-slate-200/90 text-slate-900 shadow-sm rounded-2xl p-4 mb-6 flex flex-col sm:flex-row items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <button type="button" @click="currentStep = 1" 
                                    class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                            <div>
                                <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                                    <span x-text="selectedMovie ? selectedMovie.title : ''"></span>
                                    <span class="text-xs px-2 py-0.5 rounded font-black bg-emerald-100 text-emerald-800 border border-emerald-300" x-text="selectedShowtime ? selectedShowtime.format : ''"></span>
                                </h3>
                                <p class="text-xs mt-0.5 text-slate-600 font-medium">
                                    <span x-text="selectedShowtime ? selectedShowtime.cinema_name : ''"></span> • 
                                    <span class="font-bold text-slate-800" x-text="selectedShowtime ? selectedShowtime.room_name : ''"></span> • 
                                    <span class="text-emerald-700 font-black" x-text="selectedShowtime ? selectedShowtime.start_time : ''"></span>
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="text-xs text-slate-700 font-medium">Đã chọn: <strong class="text-emerald-700 text-sm font-black" x-text="selectedSeats.length + ' ghế'"></strong></span>
                            <button type="button" 
                                    @click="proceedToStep3()"
                                    :disabled="selectedSeats.length === 0 || holdingSeats"
                                    :class="selectedSeats.length > 0 && !holdingSeats ? 'bg-emerald-600 hover:bg-emerald-500 text-white shadow-lg shadow-emerald-600/30' : 'bg-slate-200 text-slate-400 cursor-not-allowed'"
                                    class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                                <span x-text="holdingSeats ? 'Đang giữ chỗ...' : 'Tiếp tục (Chọn Bắp Nước)'"></span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Seat Map Container -->
                    <!-- Seat Map Container (Đồng bộ chuẩn 100% sơ đồ phòng chiếu như online) -->
                    <div class="bg-white border border-slate-200/90 shadow-sm rounded-2xl p-6 flex flex-col items-center">
                        <!-- Screen Curve & Glow -->
                        <div class="seat-screen-container relative w-full max-w-xl mx-auto mb-8 select-none text-center">
                            <div class="h-2.5 w-full bg-gradient-to-r from-transparent via-cyan-500 to-transparent rounded-t-full shadow-[0_0_15px_rgba(6,182,212,0.4)]"></div>
                            <div class="h-8 w-full bg-gradient-to-b from-cyan-500/10 to-transparent -mt-1"></div>
                            <p class="text-[11px] uppercase tracking-widest font-black mt-1 text-slate-500">MÀN HÌNH CHIẾU</p>
                        </div>

                        <!-- Loading Seats -->
                        <div x-show="loadingSeats" class="py-12 text-center text-slate-500">
                            <svg class="animate-spin h-7 w-7 mx-auto text-emerald-500 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <p class="text-xs font-semibold">Đang tải sơ đồ ghế phòng chiếu...</p>
                        </div>

                        <!-- Seats Grid View (Exact Synchronized Layout with Aisles & Empty cells) -->
                        <div x-show="!loadingSeats" class="w-full overflow-x-auto pb-4 flex justify-center" style="scrollbar-width: thin;">
                            <div class="inline-flex flex-col items-center gap-2">
                                <!-- Column Numbers Header (if room has grid layout) -->
                                <template x-if="hasLayout && numCols > 0">
                                    <div class="flex items-center gap-1.5 sm:gap-2 mb-1 select-none">
                                        <div class="w-6 text-center text-xs font-bold text-slate-400"></div>
                                        <div class="flex gap-1.5 sm:gap-2">
                                            <template x-for="c in numCols" :key="c">
                                                <div class="w-7 h-6 sm:w-8 sm:h-6 md:w-9 md:h-6 text-center text-[10px] sm:text-xs font-semibold text-slate-400 flex items-center justify-center" x-text="c"></div>
                                            </template>
                                        </div>
                                        <div class="w-6 text-center text-xs font-bold text-slate-400"></div>
                                    </div>
                                </template>

                                <!-- Grid Rows (When Room Has Layout Data) -->
                                <template x-if="hasLayout">
                                    <div class="flex flex-col items-center gap-2">
                                        <template x-for="(rowObj, rIdx) in gridRows" :key="rIdx">
                                            <div class="flex items-center gap-1.5 sm:gap-2">
                                                <!-- Left Row Letter -->
                                                <div class="w-6 text-center text-xs font-black text-slate-600 select-none" x-text="rowObj.row_letter"></div>

                                                <!-- Row Cells (Seats + Aisles + Empty Gaps) -->
                                                <div class="flex items-center gap-1.5 sm:gap-2">
                                                    <template x-for="(cell, cIdx) in rowObj.cells" :key="cIdx">
                                                        <div>
                                                            <!-- Empty / Aisle space -->
                                                            <template x-if="cell.is_empty">
                                                                <div class="w-7 h-7 sm:w-8 sm:h-8 md:w-9 md:h-9"></div>
                                                            </template>

                                                            <!-- Real Seat Button -->
                                                            <template x-if="!cell.is_empty">
                                                                <button type="button"
                                                                        @click="toggleSeat(cell)"
                                                                        :disabled="cell.is_booked"
                                                                        :title="cell.name + ' (' + cell.type.toUpperCase() + ') - ' + cell.price_formatted"
                                                                        :class="{
                                                                            'cursor-not-allowed opacity-40 line-through bg-slate-200 text-slate-400 border-slate-300': cell.is_booked,
                                                                            'bg-emerald-600 text-white border-2 border-emerald-500 ring-2 ring-emerald-300 font-black shadow-md scale-105': isSeatSelected(cell.id),
                                                                            'bg-rose-100 text-rose-900 border-2 border-rose-400 hover:bg-rose-200 font-bold': !cell.is_booked && !isSeatSelected(cell.id) && cell.type === 'vip',
                                                                            'bg-amber-100 text-amber-900 border-2 border-amber-400 hover:bg-amber-200 font-bold': !cell.is_booked && !isSeatSelected(cell.id) && cell.type === 'deluxe',
                                                                            'bg-sky-100 text-sky-900 border-2 border-sky-400 hover:bg-sky-200 font-bold': !cell.is_booked && !isSeatSelected(cell.id) && cell.type === 'sweetbox',
                                                                            'bg-white text-slate-800 border-2 border-slate-300 hover:bg-slate-100 hover:border-emerald-500 font-bold': !cell.is_booked && !isSeatSelected(cell.id) && cell.type === 'standard'
                                                                        }"
                                                                        class="w-7 h-7 sm:w-8 sm:h-8 md:w-9 md:h-9 rounded-t-lg rounded-b-sm border text-[10px] sm:text-xs font-semibold flex items-center justify-center transition-all duration-150 select-none">
                                                                    <span x-text="cell.number"></span>
                                                                </button>
                                                            </template>
                                                        </div>
                                                    </template>
                                                </div>

                                                <!-- Right Row Letter -->
                                                <div class="w-6 text-center text-xs font-black text-slate-600 select-none" x-text="rowObj.row_letter"></div>
                                            </div>
                                        </template>
                                    </div>
                                </template>

                                <!-- Fallback Rows (if room has no layout data) -->
                                <template x-if="!hasLayout">
                                    <div class="flex flex-col items-center gap-2">
                                        <template x-for="rowKey in seatRows" :key="rowKey">
                                            <div class="flex items-center gap-1.5 sm:gap-2">
                                                <span class="w-6 text-center text-xs font-black text-slate-600 select-none" x-text="rowKey"></span>
                                                <div class="flex items-center gap-1.5 sm:gap-2">
                                                    <template x-for="seat in seatsByRow[rowKey]" :key="seat.id">
                                                        <button type="button"
                                                                @click="toggleSeat(seat)"
                                                                :disabled="seat.is_booked"
                                                                :title="seat.name + ' (' + seat.type.toUpperCase() + ') - ' + seat.price_formatted"
                                                                :class="{
                                                                    'cursor-not-allowed opacity-40 line-through bg-slate-200 text-slate-400 border-slate-300': seat.is_booked,
                                                                    'bg-emerald-600 text-white border-2 border-emerald-500 ring-2 ring-emerald-300 font-black shadow-md scale-105': isSeatSelected(seat.id),
                                                                    'bg-rose-100 text-rose-900 border-2 border-rose-400 hover:bg-rose-200 font-bold': !seat.is_booked && !isSeatSelected(seat.id) && seat.type === 'vip',
                                                                    'bg-amber-100 text-amber-900 border-2 border-amber-400 hover:bg-amber-200 font-bold': !seat.is_booked && !isSeatSelected(seat.id) && seat.type === 'deluxe',
                                                                    'bg-sky-100 text-sky-900 border-2 border-sky-400 hover:bg-sky-200 font-bold': !seat.is_booked && !isSeatSelected(seat.id) && seat.type === 'sweetbox',
                                                                    'bg-white text-slate-800 border-2 border-slate-300 hover:bg-slate-100 hover:border-emerald-500 font-bold': !seat.is_booked && !isSeatSelected(seat.id) && seat.type === 'standard'
                                                                }"
                                                                class="w-7 h-7 sm:w-8 sm:h-8 md:w-9 md:h-9 rounded-t-lg rounded-b-sm border text-[10px] sm:text-xs font-semibold flex items-center justify-center transition-all duration-150 select-none">
                                                            <span x-text="seat.number"></span>
                                                        </button>
                                                    </template>
                                                </div>
                                                <span class="w-6 text-center text-xs font-black text-slate-600 select-none" x-text="rowKey"></span>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Seat Legend (Synchronized with Online) -->
                        <div class="mt-8 pt-5 border-t border-slate-200 w-full flex flex-wrap items-center justify-center gap-6 text-xs text-slate-700">
                            <div class="flex items-center gap-2">
                                <span class="w-4 h-4 rounded-t bg-white border-2 border-slate-300 inline-block"></span>
                                <span class="font-medium text-slate-700">Standard</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-4 h-4 rounded-t bg-rose-100 border-2 border-rose-400 inline-block"></span>
                                <span class="font-bold text-rose-700">VIP (+20k)</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-4 h-4 rounded-t bg-amber-100 border-2 border-amber-400 inline-block"></span>
                                <span class="font-bold text-amber-700">Deluxe (+30k)</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-4 h-4 rounded-t bg-sky-100 border-2 border-sky-400 inline-block"></span>
                                <span class="font-bold text-sky-700">Sweetbox (+40k)</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-4 h-4 rounded-t bg-emerald-600 border border-emerald-500 inline-block shadow-sm"></span>
                                <span class="text-emerald-700 font-black">Đang chọn</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-4 h-4 rounded-t border border-slate-300 bg-slate-200 inline-block opacity-60 line-through"></span>
                                <span class="font-medium text-slate-400">Đã bán</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 3: CHỌN BẮP NƯỚC & COMBO -->
                <div x-show="currentStep === 3" x-cloak>
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-black text-slate-900">Bắp Nước & Combo Ăn Vặt</h3>
                            <p class="text-xs text-slate-600 font-medium">Chọn thêm bắp nước phục vụ khách xem phim tại quầy</p>
                        </div>
                        <button type="button" @click="currentStep = 2" 
                                class="px-3.5 py-1.5 rounded-xl text-xs font-bold border border-slate-300 bg-white hover:bg-slate-100 text-slate-800 shadow-xs flex items-center gap-1.5 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            <span>Quay lại chọn ghế</span>
                        </button>
                    </div>

                    <!-- Foods Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($foods as $food)
                        <div class="bg-white border border-slate-200/90 shadow-sm text-slate-900 rounded-2xl p-4 flex flex-col justify-between hover:border-emerald-500/50 hover:shadow-md transition">
                            <div class="flex gap-3">
                                @if(!empty($food->image_url))
                                    <img src="{{ $food->image_url }}" alt="{{ $food->name }}" class="w-16 h-16 rounded-xl object-cover bg-slate-100 border border-slate-200 shrink-0">
                                @else
                                    <div class="w-16 h-16 rounded-xl flex items-center justify-center text-2xl shrink-0 bg-slate-100 border border-slate-200">🍿</div>
                                @endif
                                <div class="min-w-0">
                                    <h4 class="font-bold text-sm leading-tight text-slate-900 truncate">{{ $food->name }}</h4>
                                    <p class="text-[11px] mt-1 line-clamp-2 text-slate-500 font-medium">{{ $food->description }}</p>
                                    <p class="text-emerald-700 font-black text-sm mt-1">{{ number_format($food->price, 0, ',', '.') }} đ</p>
                                </div>
                            </div>

                            <!-- Counter Controls -->
                            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                                <span class="text-xs font-semibold text-slate-600">Số lượng:</span>
                                <div class="flex items-center gap-2">
                                    <button type="button" 
                                            @click="decreaseFood({{ $food->id }})" 
                                            class="w-7 h-7 rounded-lg font-black bg-slate-100 hover:bg-slate-200 text-slate-800 border border-slate-300 flex items-center justify-center text-sm transition">
                                        -
                                    </button>
                                    <span class="w-8 text-center font-black text-sm font-mono text-slate-900" x-text="getFoodQuantity({{ $food->id }})"></span>
                                    <button type="button" 
                                            @click="increaseFood({{ $food->id }}, '{{ addslashes($food->name) }}', {{ $food->price }})" 
                                            class="w-7 h-7 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-black flex items-center justify-center text-sm transition shadow-sm">
                                        +
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Customer Details, Order Summary & Checkout (35% Width) -->
        <div class="w-full lg:w-96 xl:w-[420px] bg-white border-t lg:border-t-0 border-l border-slate-200 text-slate-900 shadow-sm flex flex-col justify-between overflow-y-auto shrink-0 transition-colors duration-200">
            
            <div class="p-5 sm:p-6 space-y-6">
                <!-- Section 1: Customer Phone Search & Auto-Fill -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-xs font-black uppercase tracking-wider text-slate-900 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span>THÔNG TIN KHÁCH HÀNG</span>
                        </label>
                        <span class="text-[11px] text-slate-500 font-semibold">Tìm kiếm tự động qua SĐT</span>
                    </div>

                    <!-- Phone Search Input -->
                    <div class="relative">
                        <input type="text" 
                               x-model="customerPhone" 
                               @input.debounce.400ms="searchCustomer()"
                               @blur="searchCustomer()"
                               placeholder="Nhập số điện thoại khách..." 
                               class="w-full pl-9 pr-24 py-2.5 text-sm font-mono font-bold bg-slate-50 focus:bg-white text-slate-900 border-2 border-slate-200 focus:border-emerald-500 rounded-xl focus:ring-2 focus:ring-emerald-500/20 transition">
                        <svg class="w-4 h-4 absolute left-3 top-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        
                        <button type="button" @click="searchCustomer()" 
                                class="absolute right-1.5 top-1.5 px-3 py-1.5 text-xs font-bold bg-slate-800 hover:bg-slate-900 text-white rounded-lg transition shadow-xs">
                            Tra cứu
                        </button>
                    </div>

                    <!-- Customer Status Badge -->
                    <div x-show="customerFound" x-cloak 
                         class="mt-2.5 p-3 border-2 border-emerald-300 bg-emerald-50 text-emerald-950 rounded-xl text-xs flex items-center justify-between shadow-xs">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-600 animate-pulse"></span>
                            <span class="font-black text-emerald-900">Khách hàng thành viên thân thiết</span>
                        </div>
                        <span class="font-mono text-xs font-black bg-emerald-600 text-white px-2.5 py-0.5 rounded-full" x-text="'Tích lũy: ' + customerPoints + ' điểm'"></span>
                    </div>

                    <div x-show="!customerFound && customerPhone.length >= 9" x-cloak 
                         class="mt-2 p-2.5 border border-blue-300 bg-blue-50 text-blue-900 rounded-xl text-xs flex items-center gap-1.5 font-medium">
                        <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Khách hàng mới: Hệ thống tự động tạo hồ sơ thành viên cho lần mua sau.</span>
                    </div>

                    <!-- Customer Detail Inputs -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-3">
                        <!-- Tên khách hàng -->
                        <div class="col-span-1 sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-800 mb-1">
                                Họ và tên <span class="text-red-500">*</span>
                            </label>
                            <input type="text" x-model="customerName" placeholder="VD: Nguyễn Văn A" 
                                   class="w-full px-3 py-2 text-xs font-medium bg-slate-50 focus:bg-white text-slate-900 border border-slate-300 focus:border-emerald-500 rounded-xl focus:ring-2 focus:ring-emerald-500/20">
                        </div>

                        <!-- Sinh nhật -->
                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">Ngày sinh</label>
                            <input type="date" x-model="customerBirthday" 
                                   class="w-full px-3 py-2 text-xs font-medium bg-slate-50 focus:bg-white text-slate-900 border border-slate-300 focus:border-emerald-500 rounded-xl focus:ring-2 focus:ring-emerald-500/20">
                        </div>

                        <!-- Email nhận vé -->
                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">
                                Email nhận vé QR <span class="text-red-500">*</span>
                            </label>
                            <input type="email" x-model="customerEmail" placeholder="khach@gmail.com" 
                                   class="w-full px-3 py-2 text-xs font-medium bg-slate-50 focus:bg-white text-slate-900 border border-slate-300 focus:border-emerald-500 rounded-xl focus:ring-2 focus:ring-emerald-500/20">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Order Items Summary -->
                <div class="pt-4 border-t border-slate-200">
                    <p class="text-xs font-black uppercase tracking-wider mb-2.5 flex items-center justify-between text-slate-900">
                        <span>CHI TIẾT ĐƠN HÀNG</span>
                        <span class="text-xs font-bold text-emerald-700 font-mono" x-text="selectedShowtime ? selectedShowtime.start_time : ''"></span>
                    </p>

                    <!-- Empty notice -->
                    <div x-show="!selectedShowtime" 
                         class="p-4 rounded-xl text-center text-xs font-medium border border-dashed border-slate-300 bg-slate-50 text-slate-500">
                        Chưa chọn suất chiếu nào.
                    </div>

                    <!-- Movie & Showtime Info -->
                    <div x-show="selectedShowtime" 
                         class="p-3 rounded-xl text-xs space-y-1 mb-3 bg-slate-50 border border-slate-200 text-slate-900">
                        <p class="font-black text-sm text-slate-900" x-text="selectedMovie ? selectedMovie.title : ''"></p>
                        <p class="text-xs text-slate-600 font-medium" x-text="selectedShowtime ? (selectedShowtime.cinema_name + ' • ' + selectedShowtime.room_name) : ''"></p>
                    </div>

                    <!-- Selected Seats List -->
                    <div x-show="selectedSeats.length > 0" class="space-y-1.5 mb-3">
                        <div class="flex items-center justify-between text-xs font-semibold text-slate-700">
                            <span>Ghế (<span x-text="selectedSeats.length"></span>):</span>
                            <span class="font-mono font-black text-slate-900" x-text="selectedSeats.map(s => s.name).join(', ')"></span>
                        </div>
                        <div class="flex items-center justify-between text-xs font-medium">
                            <span class="text-slate-600">Tiền vé:</span>
                            <span class="font-black text-slate-900 font-mono" x-text="formatCurrency(totalSeatPrice)"></span>
                        </div>
                    </div>

                    <!-- Selected Foods List -->
                    <div x-show="foodOrders.length > 0" class="pt-2 border-t border-slate-200 space-y-1.5 mb-3">
                        <div class="text-[11px] uppercase font-bold text-slate-600">Bắp nước & Combo:</div>
                        <template x-for="item in foodOrders" :key="item.food_id">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-slate-800 font-medium" x-text="item.name + ' x' + item.quantity"></span>
                                <span class="font-mono font-bold text-slate-900" x-text="formatCurrency(item.price * item.quantity)"></span>
                            </div>
                        </template>
                        <div class="flex items-center justify-between text-xs pt-1">
                            <span class="text-slate-600 font-medium">Tiền bắp nước:</span>
                            <span class="font-black text-slate-900 font-mono" x-text="formatCurrency(totalFoodPrice)"></span>
                        </div>
                    </div>

                    <!-- Total Amount Banner -->
                    <div class="p-4 bg-gradient-to-r from-emerald-600 to-teal-700 text-white rounded-2xl flex items-center justify-between mt-4 shadow-lg shadow-emerald-600/20">
                        <div>
                            <span class="text-[10px] uppercase font-black tracking-wider text-emerald-100 block">TỔNG THANH TOÁN</span>
                            <span class="text-2xl font-black font-mono text-white" x-text="formatCurrency(grandTotal)"></span>
                        </div>
                        <div class="text-right text-[11px] text-emerald-100 font-bold">
                            <span x-text="selectedSeats.length + ' vé'"></span>
                            <span x-show="totalFoodQuantity > 0" x-text="' + ' + totalFoodQuantity + ' món'"></span>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Payment Method Selection -->
                <div class="pt-4 border-t border-slate-200">
                    <p class="text-xs font-black uppercase tracking-wider mb-2.5 text-slate-900">PHƯƠNG THỨC THANH TOÁN</p>
                    
                    <div class="grid grid-cols-3 gap-2">
                        <!-- Tiền mặt (Cash) -->
                        <button type="button" @click="paymentMethod = 'cash'"
                                :class="paymentMethod === 'cash' 
                                        ? 'bg-emerald-600 text-white border-2 border-emerald-600 shadow-md ring-2 ring-emerald-500/30' 
                                        : 'bg-white text-slate-800 border-2 border-slate-200 hover:border-slate-300 hover:bg-slate-50 font-bold shadow-xs'"
                                class="p-2.5 rounded-xl border-2 text-center transition flex flex-col items-center gap-1">
                            <span class="text-lg">💵</span>
                            <span class="text-xs font-bold">Tiền mặt</span>
                        </button>

                        <!-- MB Bank QR (SePay) -->
                        <button type="button" @click="paymentMethod = 'sepay_mb'"
                                :class="paymentMethod === 'sepay_mb' 
                                        ? 'bg-emerald-600 text-white border-2 border-emerald-600 shadow-md ring-2 ring-emerald-500/30' 
                                        : 'bg-white text-slate-800 border-2 border-slate-200 hover:border-slate-300 hover:bg-slate-50 font-bold shadow-xs'"
                                class="p-2.5 rounded-xl border-2 text-center transition flex flex-col items-center gap-1">
                            <span class="text-lg">🏦</span>
                            <span class="text-xs font-bold">Mã MB Bank</span>
                        </button>

                        <!-- VNPay -->
                        <button type="button" @click="paymentMethod = 'vnpay'"
                                :class="paymentMethod === 'vnpay' 
                                        ? 'bg-emerald-600 text-white border-2 border-emerald-600 shadow-md ring-2 ring-emerald-500/30' 
                                        : 'bg-white text-slate-800 border-2 border-slate-200 hover:border-slate-300 hover:bg-slate-50 font-bold shadow-xs'"
                                class="p-2.5 rounded-xl border-2 text-center transition flex flex-col items-center gap-1">
                            <span class="text-lg">💳</span>
                            <span class="text-xs font-bold">VNPay</span>
                        </button>
                    </div>

                    <!-- Cash Options Panel -->
                    <div x-show="paymentMethod === 'cash'" x-cloak 
                         class="mt-3.5 p-3.5 border border-slate-200 bg-slate-50 rounded-xl space-y-3">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold text-slate-800">Khách đưa (VNĐ):</label>
                            <input type="number" 
                                   x-model="cashGiven" 
                                   step="1000"
                                   placeholder="0" 
                                   class="w-36 px-3 py-1.5 text-right font-mono font-bold text-sm bg-white text-slate-900 border-2 border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
                        </div>

                        <!-- Quick Cash Shortcuts -->
                        <div class="flex flex-wrap gap-1.5">
                            <button type="button" @click="cashGiven = grandTotal" class="px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-200 hover:bg-slate-300 text-slate-800 transition">Đúng số tiền</button>
                            <button type="button" @click="cashGiven = 100000" class="px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-200 hover:bg-slate-300 text-slate-800 transition">100.000đ</button>
                            <button type="button" @click="cashGiven = 200000" class="px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-200 hover:bg-slate-300 text-slate-800 transition">200.000đ</button>
                            <button type="button" @click="cashGiven = 500000" class="px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-200 hover:bg-slate-300 text-slate-800 transition">500.000đ</button>
                            <button type="button" @click="cashGiven = 1000000" class="px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-200 hover:bg-slate-300 text-slate-800 transition">1.000.000đ</button>
                        </div>

                        <!-- Cash Change Return -->
                        <div class="pt-2 border-t border-slate-200 flex items-center justify-between text-xs">
                            <span class="text-slate-600 font-semibold">Tiền thối trả khách:</span>
                            <span class="text-base font-black text-amber-600 font-mono" x-text="formatCurrency(calculateCashChange())"></span>
                        </div>
                    </div>

                    <!-- MB Bank SePay QR Panel -->
                    <div x-show="paymentMethod === 'sepay_mb'" x-cloak 
                         class="mt-3.5 p-3.5 border border-slate-200 bg-slate-50 rounded-xl text-center space-y-2">
                        <p class="text-xs font-black text-emerald-700 uppercase tracking-wide">Mã QR Thanh Toán MB Bank (SePay)</p>
                        <div class="bg-white p-2.5 rounded-xl inline-block shadow border border-slate-200">
                            <img :src="getSepayQrUrl()" alt="SePay MB QR" class="w-36 h-36 mx-auto object-contain">
                        </div>
                        <p class="text-xs text-slate-700 font-medium">Chủ TK: <strong class="text-slate-900 font-black">TRAN VAN HAO</strong> (MB: 031205090305)</p>
                        <p class="text-[11px] text-slate-500 font-medium">Khách quét mã xong, thu ngân bấm "Xác nhận & Xuất vé"</p>
                    </div>

                    <!-- VNPay Panel -->
                    <div x-show="paymentMethod === 'vnpay'" x-cloak 
                         class="mt-3.5 p-3.5 border border-slate-200 bg-slate-50 rounded-xl text-center">
                        <p class="text-xs text-sky-700 font-black uppercase tracking-wide">Thanh toán qua Cổng VNPay / Quẹt Thẻ</p>
                        <p class="text-xs mt-1 text-slate-600 font-medium">Xác nhận giao dịch thẻ hoặc mã VNPay tại quầy POS.</p>
                    </div>
                </div>
            </div>

            <!-- Bottom Checkout Action Bar -->
            <div class="p-5 border-t border-slate-200 bg-slate-50 sticky bottom-0 z-10 shadow-lg">
                <button type="button" 
                        @click="submitCheckout()"
                        :disabled="submitting || selectedSeats.length === 0"
                        :class="submitting || selectedSeats.length === 0 
                                ? 'bg-slate-200 text-slate-400 cursor-not-allowed border border-slate-300' 
                                : 'bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white shadow-xl shadow-emerald-600/30 active:scale-[0.99]'"
                        class="w-full py-3.5 rounded-xl font-black text-sm tracking-wide transition flex items-center justify-center gap-2">
                    <svg x-show="submitting" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span x-text="submitting ? 'Đang xuất vé & gửi email...' : 'XUẤT VÉ & THANH TOÁN (' + formatCurrency(grandTotal) + ')'"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- SUCCESS MODAL & TICKET PRINT POPUP -->
    <div x-show="showSuccessModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
        <div class="bg-white border border-slate-200 text-slate-900 shadow-2xl rounded-3xl max-w-md w-full p-6 text-center relative">
            <!-- Close button -->
            <button type="button" @click="showSuccessModal = false; resetSelection();" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 p-2">✕</button>

            <!-- Success Checkmark Icon -->
            <div class="w-16 h-16 bg-emerald-500/20 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4 border border-emerald-500/30">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
            </div>

            <h3 class="text-xl font-black text-slate-900">Xuất Vé Thành Công!</h3>
            <p class="text-xs mt-1 text-slate-600 font-medium">Mã vé và QR code đã được gửi về email của khách hàng.</p>

            <!-- Ticket Summary Card -->
            <div class="my-5 p-4 rounded-2xl text-left text-xs space-y-2 border border-slate-200 bg-slate-50">
                <div class="flex justify-between pb-2 border-b border-slate-200">
                    <span class="text-slate-500 font-medium">Mã vé:</span>
                    <span class="font-black text-emerald-700 text-sm font-mono" x-text="completedOrder.ticket_code"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500 font-medium">Khách hàng:</span>
                    <span class="font-bold text-slate-900" x-text="completedOrder.customer_name"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500 font-medium">Email:</span>
                    <span class="font-semibold text-slate-800" x-text="completedOrder.customer_email"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500 font-medium">Phim:</span>
                    <span class="font-bold text-slate-900 text-right max-w-[200px] truncate" x-text="completedOrder.movie_title"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500 font-medium">Suất chiếu:</span>
                    <span class="font-bold text-slate-800" x-text="completedOrder.showtime"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500 font-medium">Ghế:</span>
                    <span class="font-black text-slate-900 text-right" x-text="completedOrder.seats"></span>
                </div>
                <div class="flex justify-between pt-2 border-t border-slate-200 text-sm font-bold">
                    <span class="text-slate-700">Tổng tiền:</span>
                    <span class="text-emerald-700 font-black" x-text="completedOrder.total_price_formatted"></span>
                </div>
            </div>

            <!-- Modal Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-2.5">
                <button type="button" @click="printTicketWindow()" class="flex-1 py-2.5 px-4 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl font-bold text-xs shadow-lg transition flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>In Phiếu Vé (Print)</span>
                </button>
                <button type="button" @click="showSuccessModal = false; resetSelection();" 
                        class="py-2.5 px-4 rounded-xl font-bold text-xs border border-slate-300 bg-slate-100 hover:bg-slate-200 text-slate-800 transition">
                    Đơn Mới
                </button>
            </div>
        </div>
    </div>

    <!-- Alpine.js Logic -->
    <script>
        function posCounter() {
            return {
                // Default Light Mode
                isLightMode: true,

                selectedCinemaId: '{{ $selectedCinemaId }}',
                selectedDate: '{{ $selectedDate }}',
                currentStep: 1,

                // Movie & Showtime
                movieShowtimes: [],
                loadingShowtimes: false,
                selectedMovie: null,
                selectedShowtime: null,

                // Seats
                hasLayout: false,
                gridRows: [],
                numCols: 0,
                seats: [],
                seatRows: [],
                seatsByRow: {},
                selectedSeats: [],
                loadingSeats: false,
                holdBookingId: null,
                holdingSeats: false,
                pollingInterval: null,

                // Foods
                foodOrders: [], // array of {food_id, name, price, quantity}

                // Customer
                customerPhone: '',
                customerName: '',
                customerBirthday: '',
                customerEmail: '',
                customerPoints: 0,
                customerFound: false,

                // Payment
                paymentMethod: 'cash', // 'cash', 'sepay_mb', 'vnpay'
                cashGiven: null,
                submitting: false,

                // Modal
                showSuccessModal: false,
                completedOrder: {},

                init() {
                    this.fetchShowtimes();

                    // Real-time synchronization: poll seats every 3 seconds while in Step 2 or 3
                    this.pollingInterval = setInterval(() => {
                        if (this.selectedShowtime && (this.currentStep === 2 || this.currentStep === 3)) {
                            this.pollSeats();
                        }
                    }, 3000);

                    // Unload listener: release any held seats if cashier closes tab or leaves
                    window.addEventListener('beforeunload', () => {
                        if (this.holdBookingId) {
                            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                            const data = new FormData();
                            data.append('hold_booking_id', this.holdBookingId);
                            data.append('_token', token);
                            navigator.sendBeacon('{{ route('pos.release_hold') }}', data);
                        }
                    });
                },

                formatCurrency(amount) {
                    return new Intl.NumberFormat('vi-VN').format(Math.max(0, amount || 0)) + ' đ';
                },

                get totalSeatPrice() {
                    return this.selectedSeats.reduce((sum, s) => sum + (s.price || 0), 0);
                },

                get totalFoodPrice() {
                    return this.foodOrders.reduce((sum, f) => sum + (f.price * f.quantity), 0);
                },

                get grandTotal() {
                    return this.totalSeatPrice + this.totalFoodPrice;
                },

                get totalFoodQuantity() {
                    return this.foodOrders.reduce((sum, f) => sum + f.quantity, 0);
                },

                fetchShowtimes() {
                    this.loadingShowtimes = true;
                    fetch(`{{ route('pos.showtimes') }}?cinema_id=${this.selectedCinemaId}&date=${this.selectedDate}`)
                        .then(res => res.json())
                        .then(data => {
                            this.movieShowtimes = data.movies || [];
                            this.loadingShowtimes = false;
                        })
                        .catch(err => {
                            console.error(err);
                            this.loadingShowtimes = false;
                        });
                },

                selectShowtime(showtime, movie) {
                    // Release previous hold if selecting a different showtime
                    if (this.holdBookingId) {
                        this.releaseHold();
                    }

                    this.selectedShowtime = showtime;
                    this.selectedMovie = movie;
                    this.selectedSeats = [];
                    this.hasLayout = false;
                    this.gridRows = [];
                    this.numCols = 0;
                    this.currentStep = 2;
                    this.fetchSeats(showtime.id);
                },

                fetchSeats(showtimeId) {
                    this.loadingSeats = true;
                    const url = `{{ url('pos/showtime-seats') }}/${showtimeId}?cashier_hold_id=${this.holdBookingId || ''}`;
                    fetch(url)
                        .then(res => res.json())
                        .then(data => {
                            this.hasLayout = !!data.has_layout;
                            this.gridRows = data.grid_rows || [];
                            this.numCols = data.num_cols || 0;
                            this.seats = data.seats || [];
                            
                            // Group seats by row (for fallback)
                            this.seatsByRow = {};
                            this.seats.forEach(s => {
                                if (!this.seatsByRow[s.row]) {
                                    this.seatsByRow[s.row] = [];
                                }
                                this.seatsByRow[s.row].push(s);
                            });

                            // Sort row numbers ascending
                            Object.keys(this.seatsByRow).forEach(r => {
                                this.seatsByRow[r].sort((a, b) => a.number - b.number);
                            });

                            this.seatRows = Object.keys(this.seatsByRow).sort();
                            this.loadingSeats = false;
                        })
                        .catch(err => {
                            console.error(err);
                            this.loadingSeats = false;
                        });
                },

                pollSeats() {
                    if (!this.selectedShowtime) return;
                    const url = `{{ url('pos/showtime-seats') }}/${this.selectedShowtime.id}?cashier_hold_id=${this.holdBookingId || ''}`;
                    fetch(url)
                        .then(res => res.json())
                        .then(data => {
                            if (data.seats) {
                                const bookedMap = {};
                                data.seats.forEach(s => {
                                    bookedMap[s.id] = s.is_booked;
                                });

                                // Update existing fallback seats
                                this.seats.forEach(s => {
                                    if (bookedMap[s.id] !== undefined) {
                                        s.is_booked = bookedMap[s.id];
                                    }
                                });

                                // Update layout gridRows
                                if (this.gridRows && this.gridRows.length > 0) {
                                    this.gridRows.forEach(row => {
                                        if (row.cells) {
                                            row.cells.forEach(cell => {
                                                if (!cell.is_empty && bookedMap[cell.id] !== undefined) {
                                                    cell.is_booked = bookedMap[cell.id];
                                                }
                                            });
                                        }
                                    });
                                }

                                // Check if any seat currently in selectedSeats was booked by someone else
                                const conflicted = [];
                                for (let i = this.selectedSeats.length - 1; i >= 0; i--) {
                                    const sel = this.selectedSeats[i];
                                    if (bookedMap[sel.id] === true) {
                                        conflicted.push(sel.name);
                                        this.selectedSeats.splice(i, 1);
                                    }
                                }

                                if (conflicted.length > 0) {
                                    alert(`⚠️ Ghế [${conflicted.join(', ')}] vừa có người đặt hoặc giữ chỗ online. Hệ thống đã tự động bỏ chọn ghế này!`);
                                }
                            }
                        })
                        .catch(err => console.error('Lỗi kiểm tra ghế POS định kỳ:', err));
                },

                toggleSeat(seat) {
                    if (seat.is_booked) return;
                    const index = this.selectedSeats.findIndex(s => s.id === seat.id);
                    if (index >= 0) {
                        this.selectedSeats.splice(index, 1);
                    } else {
                        this.selectedSeats.push(seat);
                    }
                },

                isSeatSelected(seatId) {
                    return this.selectedSeats.some(s => s.id === seatId);
                },

                async holdSeats() {
                    if (!this.selectedShowtime || this.selectedSeats.length === 0) return true;
                    try {
                        const res = await fetch('{{ route('pos.hold_seats') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                showtime_id: this.selectedShowtime.id,
                                seats: this.selectedSeats.map(s => s.id),
                                hold_booking_id: this.holdBookingId,
                            })
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.holdBookingId = data.hold_booking_id;
                            return true;
                        } else {
                            alert(data.message || 'Một số ghế bạn chọn đã bị người khác đặt hoặc giữ chỗ online!');
                            this.fetchSeats(this.selectedShowtime.id);
                            return false;
                        }
                    } catch (e) {
                        console.error('Lỗi giữ chỗ POS:', e);
                        return false;
                    }
                },

                releaseHold() {
                    if (!this.holdBookingId) return;
                    const idToRelease = this.holdBookingId;
                    this.holdBookingId = null;
                    fetch('{{ route('pos.release_hold') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ hold_booking_id: idToRelease })
                    }).catch(err => console.error(err));
                },

                async proceedToStep3() {
                    if (this.selectedSeats.length === 0) {
                        alert('Vui lòng chọn ít nhất 1 ghế trước khi tiếp tục!');
                        return;
                    }
                    this.holdingSeats = true;
                    const ok = await this.holdSeats();
                    this.holdingSeats = false;
                    if (ok) {
                        this.currentStep = 3;
                    }
                },

                getFoodQuantity(foodId) {
                    const item = this.foodOrders.find(f => f.food_id === foodId);
                    return item ? item.quantity : 0;
                },

                increaseFood(foodId, name, price) {
                    const item = this.foodOrders.find(f => f.food_id === foodId);
                    if (item) {
                        item.quantity++;
                    } else {
                        this.foodOrders.push({
                            food_id: foodId,
                            name: name,
                            price: price,
                            quantity: 1,
                        });
                    }
                },

                decreaseFood(foodId) {
                    const index = this.foodOrders.findIndex(f => f.food_id === foodId);
                    if (index >= 0) {
                        if (this.foodOrders[index].quantity > 1) {
                            this.foodOrders[index].quantity--;
                        } else {
                            this.foodOrders.splice(index, 1);
                        }
                    }
                },

                searchCustomer() {
                    const phone = this.customerPhone.trim();
                    if (phone.length < 9) {
                        this.customerFound = false;
                        return;
                    }

                    fetch(`{{ route('pos.lookup_customer') }}?phone=${encodeURIComponent(phone)}`)
                        .then(res => res.json())
                        .then(data => {
                            if (data.found && data.customer) {
                                this.customerFound = true;
                                this.customerName = data.customer.name || '';
                                this.customerBirthday = data.customer.birthday || '';
                                this.customerEmail = data.customer.email || '';
                                this.customerPoints = data.customer.points || 0;
                            } else {
                                this.customerFound = false;
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            this.customerFound = false;
                        });
                },

                calculateCashChange() {
                    const given = parseFloat(this.cashGiven) || 0;
                    return Math.max(0, given - this.grandTotal);
                },

                getSepayQrUrl() {
                    const acc = '{{ $sepayConfig['account_number'] }}';
                    const bank = '{{ $sepayConfig['bank_name'] }}';
                    const amount = Math.max(0, this.grandTotal);
                    const des = 'HCTVPOS' + (this.selectedShowtime ? this.selectedShowtime.id : '');
                    return `https://qr.sepay.vn/img?acc=${acc}&bank=${bank}&amount=${amount}&des=${des}&template=compact`;
                },

                submitCheckout() {
                    if (!this.selectedShowtime) {
                        alert('Vui lòng chọn suất chiếu trước khi thanh toán!');
                        this.currentStep = 1;
                        return;
                    }
                    if (this.selectedSeats.length === 0) {
                        alert('Vui lòng chọn ít nhất 1 ghế!');
                        this.currentStep = 2;
                        return;
                    }
                    if (!this.customerPhone.trim()) {
                        alert('Vui lòng nhập số điện thoại của khách hàng!');
                        return;
                    }
                    if (!this.customerName.trim()) {
                        alert('Vui lòng nhập họ tên của khách hàng!');
                        return;
                    }
                    if (!this.customerEmail.trim()) {
                        alert('Vui lòng nhập email khách hàng để gửi mã vé điện tử!');
                        return;
                    }

                    this.submitting = true;

                    const payload = {
                        showtime_id: this.selectedShowtime.id,
                        seats: this.selectedSeats.map(s => s.id),
                        hold_booking_id: this.holdBookingId,
                        customer_name: this.customerName.trim(),
                        customer_phone: this.customerPhone.trim(),
                        customer_birthday: this.customerBirthday || null,
                        customer_email: this.customerEmail.trim(),
                        payment_method: this.paymentMethod,
                        cash_given: this.paymentMethod === 'cash' ? (this.cashGiven || this.grandTotal) : null,
                        foods: this.foodOrders.map(f => ({ food_id: f.food_id, quantity: f.quantity })),
                    };

                    fetch('{{ route('pos.checkout') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify(payload)
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.submitting = false;
                        if (data.success && data.booking) {
                            this.holdBookingId = null; // Hold successfully converted to paid booking
                            this.completedOrder = data.booking;
                            this.showSuccessModal = true;
                        } else {
                            alert(data.message || 'Có lỗi xảy ra trong quá trình xuất vé. Vui lòng thử lại!');
                        }
                    })
                    .catch(err => {
                        this.submitting = false;
                        console.error(err);
                        alert('Lỗi kết nối máy chủ hoặc dữ liệu không hợp lệ!');
                    });
                },

                printTicketWindow() {
                    if (this.completedOrder.print_url) {
                        window.open(this.completedOrder.print_url, '_blank', 'width=450,height=700');
                    }
                },

                resetSelection() {
                    this.releaseHold();
                    this.selectedSeats = [];
                    this.foodOrders = [];
                    this.hasLayout = false;
                    this.gridRows = [];
                    this.numCols = 0;
                    this.customerPhone = '';
                    this.customerName = '';
                    this.customerBirthday = '';
                    this.customerEmail = '';
                    this.customerFound = false;
                    this.cashGiven = null;
                    this.paymentMethod = 'cash';
                    this.currentStep = 1;
                    this.fetchShowtimes();
                }
            };
        }
    </script>
</body>
</html>
