@extends('admin.layouts.app')

@section('title', 'Quản lý Lịch Chiếu - Admin HCTV')

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Quản lý Lịch Chiếu</h1>
        <p class="text-sm text-gray-500 mt-1">Sắp xếp thời gian chiếu phim tại các cụm rạp</p>
    </div>
    <div class="flex items-center gap-3">
        <button type="button" onclick="openAutoSchedulerModal()" class="px-4 py-2.5 bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600 hover:from-amber-600 hover:to-orange-700 text-white text-sm font-bold rounded-xl shadow-md hover:shadow-lg transition-all flex items-center gap-2 cursor-pointer border border-amber-400/40">
            <svg class="w-4 h-4 text-white animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            <span>Tạo Suất Chiếu Tự Động (AI)</span>
        </button>
        <a href="{{ route('admin.showtimes.create') }}" class="px-4 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-xl shadow hover:bg-gray-800 transition-colors flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            <span>Tạo Thủ Công</span>
        </a>
    </div>
</div>

<!-- Filter Bar -->
<div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
    <form method="GET" action="{{ route('admin.showtimes.index') }}" class="flex flex-wrap items-center gap-3">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Lọc theo Rạp</label>
            <select name="cinema_id" onchange="this.form.submit()" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-gray-900 focus:outline-none">
                <option value="">-- Tất cả các Rạp --</option>
                @foreach($cinemas as $cinema)
                    <option value="{{ $cinema->id }}" {{ request('cinema_id') == $cinema->id ? 'selected' : '' }}>
                        {{ $cinema->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Lọc theo Phim</label>
            <select name="movie_id" onchange="this.form.submit()" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-gray-900 focus:outline-none">
                <option value="">-- Tất cả các Phim --</option>
                @foreach($movies as $movie)
                    <option value="{{ $movie->id }}" {{ request('movie_id') == $movie->id ? 'selected' : '' }}>
                        {{ $movie->title }}
                    </option>
                @endforeach
            </select>
        </div>

        @if(request('cinema_id') || request('movie_id'))
        <div class="self-end pb-1">
            <a href="{{ route('admin.showtimes.index') }}" class="px-3 py-2 text-xs font-semibold text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition border border-gray-200">
                ✕ Xóa lọc
            </a>
        </div>
        @endif
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-500">
                <tr>
                    <th class="px-6 py-3 font-medium tracking-wider">Thời Gian</th>
                    <th class="px-6 py-3 font-medium tracking-wider">Phim</th>
                    <th class="px-6 py-3 font-medium tracking-wider">Rạp & Phòng</th>
                    <th class="px-6 py-3 font-medium tracking-wider">Định dạng & Tiếng</th>
                    <th class="px-6 py-3 font-medium tracking-wider">Giá Vé</th>
                    <th class="px-6 py-3 font-medium tracking-wider text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($showtimes as $showtime)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <p class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i') }}</p>
                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($showtime->start_time)->format('d/m/Y') }}</p>
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-900">
                        {{ $showtime->movie->title ?? 'N/A' }}
                        @if($showtime->movie && $showtime->movie->duration)
                            <span class="text-xs text-gray-400 font-normal">({{ $showtime->movie->duration }}p)</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-900 font-medium">{{ $showtime->room->cinema->name ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500">Phòng: {{ $showtime->room->name ?? 'N/A' }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-block px-2 py-0.5 rounded text-xs font-semibold bg-purple-100 text-purple-700 mr-1">
                            {{ $showtime->format ?: '2D' }}
                        </span>
                        <span class="inline-block px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">
                            {{ $showtime->language ?: 'Phụ Đề' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-semibold text-green-600">{{ number_format($showtime->price, 0, ',', '.') }} đ</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.showtimes.edit', $showtime->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded transition-colors" title="Sửa">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                            </a>
                            <form action="{{ route('admin.showtimes.destroy', $showtime->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa lịch chiếu này?');" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded transition-colors" title="Xóa">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">Chưa có lịch chiếu nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL: TẠO SUẤT CHIẾU TỰ ĐỘNG (MATCHING USER SCREENSHOTS 1, 2, 3) -->
<!-- ========================================================================= -->
<div id="auto-scheduler-modal" class="fixed inset-0 z-[99999] hidden items-center justify-center bg-black/75 backdrop-blur-sm p-2 sm:p-4 overflow-y-auto">
    <div class="bg-white w-full max-w-[1550px] h-[95vh] rounded-2xl shadow-2xl flex flex-col overflow-hidden border border-gray-200">
        
        <!-- 1. Top Header -->
        <div class="px-6 py-3.5 border-b border-gray-200 flex items-center justify-between bg-white shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-lg font-extrabold uppercase tracking-wide text-gray-900">TẠO SUẤT CHIẾU</h3>
            </div>
            <button type="button" onclick="closeAutoSchedulerModal()" class="p-1.5 text-gray-400 hover:text-gray-700 rounded-lg hover:bg-gray-100 transition" title="Đóng">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- 2. Configuration Bar (Screenshot 1) -->
        <div class="px-6 py-3 border-b border-gray-200 bg-gray-50 flex flex-wrap items-center justify-between gap-4 shrink-0">
            <!-- Left Date Range Badge -->
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-gray-600 shadow-sm shrink-0">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h4 id="cfg-summary-dates" class="text-sm font-bold text-gray-900 leading-tight">25/12 - 28/12 (4 ngày)</h4>
                    <p id="cfg-summary-slots" class="text-[11px] text-gray-500 mt-0.5">Khung thời gian: 08:00 - 22:00 (15 khung giờ)</p>
                </div>
            </div>

            <!-- Controls: Dates, Times, Golden Hour, Cinema, Buttons -->
            <div class="flex flex-wrap items-center gap-3">
                <!-- Từ ngày -->
                <div class="flex flex-col">
                    <label class="text-[10px] font-bold text-gray-400 uppercase">Từ ngày</label>
                    <input type="date" id="auto-start-date" value="{{ date('Y-m-d', strtotime('+1 day')) }}" class="px-2.5 py-1.5 bg-white border border-gray-300 rounded-lg text-xs font-semibold focus:ring-1 focus:ring-amber-500 focus:outline-none">
                </div>

                <span class="text-gray-400 self-end pb-2">&rarr;</span>

                <!-- Đến ngày -->
                <div class="flex flex-col">
                    <label class="text-[10px] font-bold text-gray-400 uppercase">Đến ngày</label>
                    <input type="date" id="auto-end-date" value="{{ date('Y-m-d', strtotime('+4 days')) }}" class="px-2.5 py-1.5 bg-white border border-gray-300 rounded-lg text-xs font-semibold focus:ring-1 focus:ring-amber-500 focus:outline-none">
                </div>

                <div class="h-8 w-px bg-gray-200 mx-1 hidden sm:block"></div>

                <!-- Giờ bắt đầu -->
                <div class="flex flex-col">
                    <label class="text-[10px] font-bold text-gray-400 uppercase">Giờ bắt đầu</label>
                    <input type="time" id="auto-start-time" value="08:00" class="px-2.5 py-1.5 bg-white border border-gray-300 rounded-lg text-xs font-semibold focus:ring-1 focus:ring-amber-500 focus:outline-none">
                </div>

                <span class="text-gray-400 self-end pb-2">&rarr;</span>

                <!-- Giờ kết thúc -->
                <div class="flex flex-col">
                    <label class="text-[10px] font-bold text-gray-400 uppercase">Giờ kết thúc</label>
                    <input type="time" id="auto-end-time" value="22:30" class="px-2.5 py-1.5 bg-white border border-gray-300 rounded-lg text-xs font-semibold focus:ring-1 focus:ring-amber-500 focus:outline-none">
                </div>

                <!-- Giờ vàng (Golden Hour) -->
                <div class="flex flex-col">
                    <label class="text-[10px] font-bold text-amber-600 uppercase flex items-center gap-1">
                        <span>Giờ vàng</span>
                        <span class="text-amber-500">⭐</span>
                    </label>
                    <input type="time" id="auto-golden-hour" value="19:00" class="px-2.5 py-1.5 bg-amber-50/60 border-2 border-amber-400 rounded-lg text-xs font-bold text-amber-900 focus:ring-1 focus:ring-amber-500 focus:outline-none">
                </div>

                <!-- Chọn Rạp -->
                <div class="flex flex-col min-w-[200px]">
                    <label class="text-[10px] font-bold text-gray-400 uppercase">Cụm rạp</label>
                    <select id="auto-cinema-select" class="px-3 py-1.5 bg-white border border-gray-300 rounded-lg text-xs font-bold text-gray-800 focus:ring-1 focus:ring-amber-500 focus:outline-none">
                        @foreach($cinemas as $cinema)
                            <option value="{{ $cinema->id }}">
                                {{ $cinema->name }} ({{ $cinema->rooms->count() }} phòng)
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Action Buttons: Tạo & Lưu -->
                <div class="flex items-center gap-2 self-end pb-0.5">
                    <button type="button" id="btn-trigger-ai" onclick="runAutoScheduleGeneration()" class="px-5 py-2 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <span>Tạo</span>
                    </button>
                    <button type="button" id="btn-save-ai-schedule" onclick="saveAutoScheduleToDb()" disabled class="px-5 py-2 bg-slate-600 hover:bg-slate-700 text-white font-bold text-xs rounded-xl shadow transition-all flex items-center gap-1.5 cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        <span id="btn-save-ai-label">Lưu</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- 3. AI Progress Banner (Screenshot 3) -->
        <div id="ai-progress-banner" class="hidden px-6 py-3.5 bg-amber-50/90 border-b border-amber-200 transition-all">
            <div class="flex items-center justify-between text-xs font-bold text-amber-900 mb-1.5">
                <span class="flex items-center gap-2">
                    <svg class="animate-spin h-3.5 w-3.5 text-amber-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Đang tạo suất chiếu với AI... Phân tích phim, phòng chiếu và giờ vàng</span>
                </span>
                <span id="ai-progress-percentage" class="font-extrabold text-amber-700 text-sm">0%</span>
            </div>
            <div class="w-full bg-amber-200/60 rounded-full h-2 overflow-hidden">
                <div id="ai-progress-bar" class="bg-gradient-to-r from-amber-500 to-orange-500 h-2 rounded-full transition-all duration-200" style="width: 0%"></div>
            </div>
            <p class="text-[11px] text-amber-700 mt-1">Quá trình tối ưu hóa phòng chiếu, thể loại và giờ vàng đang diễn ra. Vui lòng đợi...</p>
        </div>

        <!-- 4. Main Body: 2 Columns (Movie Picker Left + Calendar Right) -->
        <div class="flex-1 overflow-hidden flex flex-col md:flex-row divide-y md:divide-y-0 md:divide-x divide-gray-200 bg-white">
            
            <!-- Left Column: Movie Picker (~300px) -->
            <div class="w-full md:w-80 shrink-0 flex flex-col bg-white border-r border-gray-100 overflow-hidden">
                <!-- Search Box -->
                <div class="p-3.5 border-b border-gray-100">
                    <div class="relative flex items-center">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" id="movie-search-input" placeholder="Tìm kiếm phim theo tên" class="w-full pl-9 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-1 focus:ring-amber-500 focus:bg-white focus:outline-none">
                    </div>
                </div>

                <!-- Select All + Counter Header -->
                <div class="px-4 py-2.5 bg-gray-50/70 border-b border-gray-100 flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 cursor-pointer font-bold text-gray-700 select-none">
                        <input type="checkbox" id="check-all-movies" class="rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                        <span>Danh sách phim (<span id="total-movies-count">{{ count($movies) }}</span>)</span>
                    </label>
                    <span id="selected-movies-badge" class="px-2.5 py-0.5 rounded-full bg-purple-100 text-purple-700 font-bold text-[11px]">
                        Đã chọn 0 phim
                    </span>
                </div>

                <!-- Scrollable Movies List -->
                <div class="flex-1 overflow-y-auto p-3 space-y-2" id="auto-movie-list-container">
                    @foreach($movies as $m)
                    <div class="movie-pick-card p-3 rounded-xl border border-gray-200 hover:border-gray-300 hover:bg-gray-50/80 transition-all flex items-start justify-between gap-2.5 cursor-pointer relative"
                         data-movie-id="{{ $m->id }}"
                         data-title="{{ $m->title }}"
                         data-duration="{{ $m->duration ?: 110 }}"
                         data-poster="{{ $m->poster_url }}"
                         data-format="2D"
                         data-language="Phụ Đề"
                         data-price="100000">
                        <div class="flex-1 min-w-0" onclick="toggleMovieSelection({{ $m->id }})">
                            <h5 class="movie-title font-bold text-xs text-gray-900 truncate leading-tight uppercase">{{ $m->title }}</h5>
                            <p class="text-[11px] text-gray-500 mt-0.5">Thời lượng: {{ $m->duration ?: 110 }} phút</p>
                            
                            <!-- Badges for Format & Language -->
                            <div class="flex items-center gap-1.5 mt-2">
                                <span class="badge-format px-2 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-purple-700">2D</span>
                                <span class="badge-language px-2 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-600">Phụ Đề</span>
                            </div>
                        </div>

                        <!-- Config Button + Checkbox -->
                        <div class="flex flex-col items-end gap-2 shrink-0">
                            <input type="checkbox" class="movie-checkbox w-4 h-4 rounded border-gray-300 text-amber-600 focus:ring-amber-500 cursor-pointer" 
                                   data-movie-id="{{ $m->id }}" 
                                   onchange="onMovieCheckboxChange({{ $m->id }})">
                            
                            <button type="button" onclick="openFormatLanguageModal({{ $m->id }})" class="p-1 text-gray-400 hover:text-purple-600 rounded hover:bg-purple-50 transition" title="Cài đặt định dạng & ngôn ngữ">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Right Column: Multi-Day Calendar View -->
            <div class="flex-1 flex flex-col bg-[#fafafc] overflow-hidden">
                <!-- Calendar Top Legend Bar -->
                <div class="px-6 py-2.5 bg-white border-b border-gray-200 flex items-center justify-between text-xs text-gray-500 shrink-0">
                    <div class="flex items-center gap-4">
                        <span class="font-bold text-gray-700">Lịch biểu suất chiếu:</span>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                            <span class="text-amber-800 font-semibold">Giờ vàng (18:00 - 21:00)</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-purple-600"></span>
                            <span>IMAX / Tiêu chuẩn</span>
                        </div>
                    </div>
                    <div id="calendar-summary-stat" class="font-semibold text-gray-700">
                        Chưa tạo suất chiếu
                    </div>
                </div>

                <!-- Calendar Content Area -->
                <div class="flex-1 overflow-y-auto p-4 sm:p-6" id="calendar-view-container">
                    <!-- Empty State before generation -->
                    <div id="calendar-empty-state" class="h-full flex flex-col items-center justify-center text-center p-8">
                        <div class="w-20 h-20 rounded-2xl bg-amber-100/60 text-amber-600 flex items-center justify-center mb-4 shadow-inner">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                        <h4 class="text-base font-bold text-gray-800">Sẵn sàng tạo suất chiếu thông minh</h4>
                        <p class="text-xs text-gray-500 max-w-md mt-1 leading-relaxed">
                            Chọn danh sách phim ở cột bên trái, tùy chỉnh dải ngày và giờ hoạt động ở trên, sau đó bấm nút <strong class="text-amber-600 font-bold">"Tạo"</strong> màu cam để hệ thống tự động tối ưu hóa lịch chiếu không trùng phòng.
                        </p>
                    </div>

                    <!-- Populated Calendar Grid -->
                    <div id="calendar-grid-wrapper" class="hidden flex-col gap-6">
                        <!-- Populated via JS -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- SUB-MODAL: CHỌN ĐỊNH DẠNG & NGÔN NGỮ (SCREENSHOT 2) -->
<!-- ========================================================================= -->
<div id="format-language-modal" class="fixed inset-0 z-[100000] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-6 border border-gray-200">
        <h3 class="text-base font-extrabold text-gray-900 leading-tight">Chọn định dạng và ngôn ngữ cho</h3>
        <h4 id="cfg-modal-movie-title" class="text-sm font-bold text-purple-700 uppercase tracking-wide mt-1 mb-5">TÊN PHIM</h4>

        <!-- Định dạng -->
        <div class="mb-5">
            <label class="block text-xs font-bold text-gray-600 mb-2">Định dạng</label>
            <div class="grid grid-cols-2 gap-2.5" id="cfg-format-options">
                @foreach(['IMAX', '4DX', 'ScreenX', 'Dolby Cinema', '2D', '3D'] as $fmt)
                <button type="button" 
                        class="format-btn p-3 rounded-xl border font-bold text-xs transition select-none flex items-center justify-center {{ $fmt === '2D' ? 'border-purple-600 bg-purple-50 text-purple-700 shadow-sm ring-1 ring-purple-400' : 'border-gray-200 text-gray-700 hover:bg-gray-50' }}"
                        data-value="{{ $fmt }}"
                        onclick="selectFormatOption('{{ $fmt }}')">
                    {{ $fmt }}
                </button>
                @endforeach
            </div>
        </div>

        <!-- Ngôn ngữ -->
        <div class="mb-6">
            <label class="block text-xs font-bold text-gray-600 mb-2">Ngôn ngữ</label>
            <div class="grid grid-cols-2 gap-2.5" id="cfg-language-options">
                @foreach(['Lồng Tiếng', 'Phụ Đề', 'Tiếng Anh'] as $lang)
                <button type="button" 
                        class="language-btn p-3 rounded-xl border font-bold text-xs transition select-none flex items-center justify-center {{ $lang === 'Phụ Đề' ? 'border-purple-600 bg-purple-50 text-purple-700 shadow-sm ring-1 ring-purple-400' : 'border-gray-200 text-gray-700 hover:bg-gray-50' }}"
                        data-value="{{ $lang }}"
                        onclick="selectLanguageOption('{{ $lang }}')">
                    {{ $lang }}
                </button>
                @endforeach
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
            <button type="button" onclick="closeFormatLanguageModal()" class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 text-xs font-bold hover:bg-gray-50 transition">
                Hủy
            </button>
            <button type="button" onclick="confirmFormatLanguageModal()" class="px-6 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold shadow-md transition">
                Xác nhận
            </button>
        </div>
    </div>
</div>

<script>
let currentDraftShowtimes = [];
let editingMovieId = null;
let currentSelectedFormat = '2D';
let currentSelectedLanguage = 'Phụ Đề';

// Open & Close Main Auto Scheduler Modal
function openAutoSchedulerModal() {
    document.getElementById('auto-scheduler-modal').classList.remove('hidden');
    document.getElementById('auto-scheduler-modal').classList.add('flex');
    updateDateRangeSummary();
    
    // Select first 5 movies by default for instant convenience
    const cards = document.querySelectorAll('.movie-pick-card');
    let count = 0;
    cards.forEach((c, idx) => {
        const cb = c.querySelector('.movie-checkbox');
        if (idx < 5 && cb) {
            cb.checked = true;
            c.classList.add('border-amber-500', 'bg-amber-50/40');
            count++;
        }
    });
    updateSelectedMovieCount();
}

function closeAutoSchedulerModal() {
    document.getElementById('auto-scheduler-modal').classList.add('hidden');
    document.getElementById('auto-scheduler-modal').classList.remove('flex');
}

// Date & Time Summary update
function updateDateRangeSummary() {
    const sDate = document.getElementById('auto-start-date').value;
    const eDate = document.getElementById('auto-end-date').value;
    const sTime = document.getElementById('auto-start-time').value || '08:00';
    const eTime = document.getElementById('auto-end-time').value || '22:00';

    if (sDate && eDate) {
        const d1 = new Date(sDate);
        const d2 = new Date(eDate);
        const diffDays = Math.max(1, Math.round((d2 - d1) / (1000 * 60 * 60 * 24)) + 1);
        
        const f1 = `${d1.getDate().toString().padStart(2, '0')}/${(d1.getMonth()+1).toString().padStart(2, '0')}`;
        const f2 = `${d2.getDate().toString().padStart(2, '0')}/${(d2.getMonth()+1).toString().padStart(2, '0')}`;
        
        document.getElementById('cfg-summary-dates').textContent = `${f1} - ${f2} (${diffDays} ngày)`;
        document.getElementById('cfg-summary-slots').textContent = `Khung thời gian: ${sTime} - ${eTime}`;
    }
}

document.getElementById('auto-start-date')?.addEventListener('change', updateDateRangeSummary);
document.getElementById('auto-end-date')?.addEventListener('change', updateDateRangeSummary);
document.getElementById('auto-start-time')?.addEventListener('change', updateDateRangeSummary);
document.getElementById('auto-end-time')?.addEventListener('change', updateDateRangeSummary);

// Search Movie Filter
document.getElementById('movie-search-input')?.addEventListener('input', function() {
    const q = this.value.toLowerCase().trim();
    document.querySelectorAll('.movie-pick-card').forEach(card => {
        const title = (card.dataset.title || '').toLowerCase();
        if (!q || title.includes(q)) {
            card.classList.remove('hidden');
        } else {
            card.classList.add('hidden');
        }
    });
});

// Check All Movies
document.getElementById('check-all-movies')?.addEventListener('change', function() {
    const isChecked = this.checked;
    document.querySelectorAll('.movie-pick-card:not(.hidden)').forEach(card => {
        const cb = card.querySelector('.movie-checkbox');
        if (cb) {
            cb.checked = isChecked;
            if (isChecked) {
                card.classList.add('border-amber-500', 'bg-amber-50/40');
            } else {
                card.classList.remove('border-amber-500', 'bg-amber-50/40');
            }
        }
    });
    updateSelectedMovieCount();
});

function toggleMovieSelection(movieId) {
    const card = document.querySelector(`.movie-pick-card[data-movie-id="${movieId}"]`);
    if (!card) return;
    const cb = card.querySelector('.movie-checkbox');
    if (cb) {
        cb.checked = !cb.checked;
        onMovieCheckboxChange(movieId);
    }
}

function onMovieCheckboxChange(movieId) {
    const card = document.querySelector(`.movie-pick-card[data-movie-id="${movieId}"]`);
    if (!card) return;
    const cb = card.querySelector('.movie-checkbox');
    if (cb.checked) {
        card.classList.add('border-amber-500', 'bg-amber-50/40');
    } else {
        card.classList.remove('border-amber-500', 'bg-amber-50/40');
    }
    updateSelectedMovieCount();
}

function updateSelectedMovieCount() {
    const checked = document.querySelectorAll('.movie-checkbox:checked').length;
    document.getElementById('selected-movies-badge').textContent = `Đã chọn ${checked} phim`;
}

// Sub-Modal: Format & Language Configuration (Screenshot 2)
function openFormatLanguageModal(movieId) {
    editingMovieId = movieId;
    const card = document.querySelector(`.movie-pick-card[data-movie-id="${movieId}"]`);
    if (!card) return;

    document.getElementById('cfg-modal-movie-title').textContent = card.dataset.title;
    currentSelectedFormat = card.dataset.format || '2D';
    currentSelectedLanguage = card.dataset.language || 'Phụ Đề';

    selectFormatOption(currentSelectedFormat);
    selectLanguageOption(currentSelectedLanguage);

    document.getElementById('format-language-modal').classList.remove('hidden');
    document.getElementById('format-language-modal').classList.add('flex');
}

function closeFormatLanguageModal() {
    document.getElementById('format-language-modal').classList.add('hidden');
    document.getElementById('format-language-modal').classList.remove('flex');
    editingMovieId = null;
}

function selectFormatOption(fmt) {
    currentSelectedFormat = fmt;
    document.querySelectorAll('.format-btn').forEach(btn => {
        if (btn.dataset.value === fmt) {
            btn.className = 'format-btn p-3 rounded-xl border-2 font-bold text-xs transition select-none flex items-center justify-center border-purple-600 bg-purple-50 text-purple-700 shadow-sm ring-1 ring-purple-400';
        } else {
            btn.className = 'format-btn p-3 rounded-xl border font-bold text-xs transition select-none flex items-center justify-center border-gray-200 text-gray-700 hover:bg-gray-50';
        }
    });
}

function selectLanguageOption(lang) {
    currentSelectedLanguage = lang;
    document.querySelectorAll('.language-btn').forEach(btn => {
        if (btn.dataset.value === lang) {
            btn.className = 'language-btn p-3 rounded-xl border-2 font-bold text-xs transition select-none flex items-center justify-center border-purple-600 bg-purple-50 text-purple-700 shadow-sm ring-1 ring-purple-400';
        } else {
            btn.className = 'language-btn p-3 rounded-xl border font-bold text-xs transition select-none flex items-center justify-center border-gray-200 text-gray-700 hover:bg-gray-50';
        }
    });
}

function confirmFormatLanguageModal() {
    if (!editingMovieId) return;
    const card = document.querySelector(`.movie-pick-card[data-movie-id="${editingMovieId}"]`);
    if (card) {
        card.dataset.format = currentSelectedFormat;
        card.dataset.language = currentSelectedLanguage;
        
        card.querySelector('.badge-format').textContent = currentSelectedFormat;
        card.querySelector('.badge-language').textContent = currentSelectedLanguage;
    }
    closeFormatLanguageModal();
}

// Generate Auto Schedule (Trigger AI Animation & API)
function runAutoScheduleGeneration() {
    const selectedCards = document.querySelectorAll('.movie-checkbox:checked');
    if (selectedCards.length === 0) {
        alert('Vui lòng chọn ít nhất 1 bộ phim từ danh sách bên trái!');
        return;
    }

    const moviesPayload = [];
    selectedCards.forEach(cb => {
        const card = cb.closest('.movie-pick-card');
        moviesPayload.push({
            id: parseInt(card.dataset.movieId),
            format: card.dataset.format || '2D',
            language: card.dataset.language || 'Phụ Đề',
            price: parseFloat(card.dataset.price || 100000)
        });
    });

    const payload = {
        cinema_id: parseInt(document.getElementById('auto-cinema-select').value),
        start_date: document.getElementById('auto-start-date').value,
        end_date: document.getElementById('auto-end-date').value,
        start_time: document.getElementById('auto-start-time').value,
        end_time: document.getElementById('auto-end-time').value,
        golden_hour: document.getElementById('auto-golden-hour').value,
        cleaning_gap: 15,
        movies: moviesPayload
    };

    // Show Progress Bar
    const progressBanner = document.getElementById('ai-progress-banner');
    const progressBar = document.getElementById('ai-progress-bar');
    const progressPct = document.getElementById('ai-progress-percentage');
    const btnTrigger = document.getElementById('btn-trigger-ai');
    
    progressBanner.classList.remove('hidden');
    btnTrigger.disabled = true;
    btnTrigger.innerHTML = `<span>Đang tạo...</span>`;

    // Simulated step animation
    let currentPct = 10;
    progressBar.style.width = '10%';
    progressPct.textContent = '10%';

    const progressInterval = setInterval(() => {
        if (currentPct < 90) {
            currentPct += Math.floor(Math.random() * 15) + 5;
            if (currentPct > 90) currentPct = 90;
            progressBar.style.width = `${currentPct}%`;
            progressPct.textContent = `${currentPct}%`;
        }
    }, 200);

    fetch('{{ route("admin.showtimes.generate_auto") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(data => {
        clearInterval(progressInterval);
        progressBar.style.width = '100%';
        progressPct.textContent = '100%';

        setTimeout(() => {
            progressBanner.classList.add('hidden');
            btnTrigger.disabled = false;
            btnTrigger.innerHTML = `
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <span>Tạo</span>
            `;

            if (data.success) {
                currentDraftShowtimes = data.showtimes || [];
                renderCalendarSchedule(data);
                
                // Enable Save button
                const btnSave = document.getElementById('btn-save-ai-schedule');
                btnSave.disabled = false;
                document.getElementById('btn-save-ai-label').textContent = `Lưu (${currentDraftShowtimes.length} suất)`;
            } else {
                alert(data.message || 'Lỗi khi tạo suất chiếu tự động.');
            }
        }, 400);
    })
    .catch(err => {
        clearInterval(progressInterval);
        progressBanner.classList.add('hidden');
        btnTrigger.disabled = false;
        btnTrigger.innerHTML = `<span>Tạo</span>`;
        console.error(err);
        alert('Có lỗi xảy ra khi kết nối máy chủ tạo suất chiếu.');
    });
}

// Render the generated Multi-Day Calendar View
function renderCalendarSchedule(data) {
    const container = document.getElementById('calendar-view-container');
    const emptyState = document.getElementById('calendar-empty-state');
    const gridWrapper = document.getElementById('calendar-grid-wrapper');
    const summaryStat = document.getElementById('calendar-summary-stat');

    emptyState.classList.add('hidden');
    gridWrapper.classList.remove('hidden');
    gridWrapper.innerHTML = '';

    summaryStat.innerHTML = `
        <span class="text-green-600 font-bold">${data.total_showtimes} suất chiếu</span> &bull; 
        <span class="text-amber-600 font-bold">${data.golden_hour_count} suất giờ vàng</span>
    `;

    // Group showtimes by Date
    const byDate = {};
    data.dates.forEach(d => { byDate[d] = []; });
    data.showtimes.forEach(st => {
        if (!byDate[st.date]) byDate[st.date] = [];
        byDate[st.date].push(st);
    });

    data.dates.forEach(dateStr => {
        const dayShowtimes = byDate[dateStr] || [];
        const dateObj = new Date(dateStr);
        const dayNames = ['Chủ Nhật', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7'];
        const dayTitle = `${dayNames[dateObj.getDay()]}, ${dateObj.getDate()}/${dateObj.getMonth()+1}/${dateObj.getFullYear()}`;

        const dayCard = document.createElement('div');
        dayCard.className = 'bg-white rounded-2xl p-5 border border-gray-200 shadow-sm';

        dayCard.innerHTML = `
            <div class="flex items-center justify-between pb-3 mb-4 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                    <h4 class="font-extrabold text-sm text-gray-900">${dayTitle}</h4>
                </div>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-700">
                    ${dayShowtimes.length} suất chiếu
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3.5" id="day-slots-${dateStr}">
            </div>
        `;

        const slotsContainer = dayCard.querySelector(`#day-slots-${dateStr}`);

        if (dayShowtimes.length === 0) {
            slotsContainer.innerHTML = `<p class="col-span-full text-xs text-gray-400 italic">Không có suất chiếu nào trong ngày này.</p>`;
        } else {
            dayShowtimes.forEach(st => {
                const slotEl = document.createElement('div');
                slotEl.className = `p-3 rounded-xl border transition relative select-none flex flex-col justify-between ${
                    st.is_golden_hour 
                        ? 'border-amber-400 bg-amber-50/50 shadow-sm ring-1 ring-amber-300' 
                        : 'border-gray-200 bg-gray-50/50 hover:bg-white'
                }`;

                slotEl.innerHTML = `
                    <div>
                        <div class="flex items-center justify-between gap-1 mb-1.5">
                            <span class="text-xs font-extrabold text-gray-900 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>${st.start_time_formatted} - ${st.end_time_formatted}</span>
                            </span>
                            <button type="button" onclick="removeDraftShowtime('${st.temp_id}')" class="text-gray-400 hover:text-red-600 p-0.5" title="Xóa suất này">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <h5 class="font-bold text-xs text-gray-900 line-clamp-1 leading-tight">${st.movie_title}</h5>
                        <p class="text-[11px] text-gray-500 mt-0.5 flex items-center gap-1">
                            <svg class="w-3 h-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/></svg>
                            <span>${st.room_name}</span>
                        </p>
                    </div>

                    <div class="flex items-center justify-between gap-1 mt-2.5 pt-2 border-t border-gray-100">
                        <div class="flex items-center gap-1">
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-purple-700">${st.format}</span>
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-medium bg-gray-200/80 text-gray-700">${st.language}</span>
                        </div>
                        ${st.is_golden_hour ? '<span class="text-[10px] font-bold text-amber-700 flex items-center gap-0.5">⭐ Giờ vàng</span>' : ''}
                    </div>
                `;

                slotsContainer.appendChild(slotEl);
            });
        }

        gridWrapper.appendChild(dayCard);
    });
}

// Remove a single draft slot from preview
function removeDraftShowtime(tempId) {
    currentDraftShowtimes = currentDraftShowtimes.filter(st => st.temp_id !== tempId);
    document.getElementById('btn-save-ai-label').textContent = `Lưu (${currentDraftShowtimes.length} suất)`;
    
    // Refresh display
    const dates = [...new Set(currentDraftShowtimes.map(s => s.date))].sort();
    const goldenCount = currentDraftShowtimes.filter(s => s.is_golden_hour).length;
    renderCalendarSchedule({
        dates: dates,
        showtimes: currentDraftShowtimes,
        total_showtimes: currentDraftShowtimes.length,
        golden_hour_count: goldenCount
    });
}

// Save All Generated Showtimes to Database
function saveAutoScheduleToDb() {
    if (currentDraftShowtimes.length === 0) {
        alert('Không có suất chiếu nào để lưu!');
        return;
    }

    if (!confirm(`Bạn có chắc chắn muốn lưu ${currentDraftShowtimes.length} suất chiếu này vào hệ thống?`)) {
        return;
    }

    const btnSave = document.getElementById('btn-save-ai-schedule');
    btnSave.disabled = true;
    btnSave.innerHTML = `<span>Đang lưu...</span>`;

    fetch('{{ route("admin.showtimes.save_auto") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            showtimes: currentDraftShowtimes
        })
    })
    .then(res => res.json())
    .then(data => {
        btnSave.disabled = false;
        btnSave.innerHTML = `<span>Lưu</span>`;

        if (data.success) {
            alert(data.message || 'Lưu lịch chiếu thành công!');
            closeAutoSchedulerModal();
            window.location.reload();
        } else {
            alert(data.message || 'Có lỗi xảy ra khi lưu.');
        }
    })
    .catch(err => {
        btnSave.disabled = false;
        btnSave.innerHTML = `<span>Lưu</span>`;
        console.error(err);
        alert('Lỗi kết nối khi lưu lịch chiếu.');
    });
}
</script>
@endsection
