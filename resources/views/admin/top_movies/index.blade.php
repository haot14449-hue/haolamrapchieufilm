@extends('admin.layouts.app')

@section('title', 'Quản lý Top 10 Phim Hot - Admin HCTV')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <div class="flex items-center gap-3">
            <h1 class="text-2xl font-bold text-gray-900">Quản Lý Top 10 Phim Hot Trang Chủ</h1>
            <span class="bg-amber-100 text-amber-800 text-xs font-bold px-2.5 py-0.5 rounded-full border border-amber-300">
                10 Phim Tối Đa
            </span>
        </div>
        <p class="text-sm text-gray-500 mt-1">Cấu hình bảng xếp hạng Top 10 phim nổi bật nhất hiển thị trên slider trang chủ HCTV (theo thiết kế rạp chiếu & Netflix).</p>
    </div>
    
    <div class="flex flex-wrap items-center gap-2">
        <form action="{{ route('admin.top_movies.auto_populate') }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn tự động tạo lại danh sách Top 10 từ các phim đang chiếu nổi bật nhất?');">
            @csrf
            <button type="submit" class="px-3.5 py-2 bg-white text-gray-700 hover:bg-gray-50 border border-gray-300 text-sm font-medium rounded-lg shadow-2xs transition flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Tự Động Điền Top 10
            </button>
        </form>

        <button type="button" onclick="openAddModal()" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-gray-900 font-bold text-sm rounded-lg shadow transition flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Thêm Phim Vào Top 10
        </button>

        <a href="{{ route('home') }}" target="_blank" class="px-3.5 py-2 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-lg shadow-2xs transition flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
            </svg>
            Xem Trang Chủ
        </a>
    </div>
</div>

<!-- Stats & Information Banner -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-gradient-to-r from-amber-500/10 via-amber-400/5 to-transparent border border-amber-200/60 rounded-xl p-4 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-amber-500/20 border border-amber-300 flex items-center justify-center shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12.75 2.042a.75.75 0 00-.75 0 11.233 11.233 0 00-3.666 4.708.75.75 0 00.99 1.011c.915-.558 1.93-.976 3.001-1.229a10.963 10.963 0 01-1.325 5.568.75.75 0 00.58 1.1c1.554.24 3.018.966 4.195 2.062a.75.75 0 001.275-.544 11.23 11.23 0 00-4.3-12.676z" />
            </svg>
        </div>
        <div>
            <p class="text-xs font-semibold text-amber-700 uppercase tracking-wider">Số Phim Đã Xếp Hạng</p>
            <p class="text-2xl font-black text-gray-900">{{ $topHotMovies->count() }} <span class="text-sm font-normal text-gray-500">/ 10 vị trí</span></p>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl p-4 flex items-center gap-4 shadow-2xs">
        <div class="w-12 h-12 rounded-xl bg-green-50 border border-green-200 flex items-center justify-center shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Đang Hiển Thị</p>
            <p class="text-2xl font-black text-gray-900">{{ $topHotMovies->where('is_active', true)->count() }} <span class="text-sm font-normal text-gray-500">phim kích hoạt</span></p>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl p-4 flex items-center gap-4 shadow-2xs">
        <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-200 flex items-center justify-center shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
            </svg>
        </div>
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Kho Phim Có Sẵn</p>
            <p class="text-2xl font-black text-gray-900">{{ $allMovies->count() }} <span class="text-sm font-normal text-gray-500">bộ phim trong hệ thống</span></p>
        </div>
    </div>
</div>

<!-- Main Top 10 Table & Visual Layout -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
    <div class="p-5 border-b border-gray-200 bg-gray-50/50 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500 inline-block animate-pulse"></span>
                Danh Sách Xếp Hạng Từ Rank #1 Đến Rank #10
            </h2>
            <p class="text-xs text-gray-500 mt-0.5">Sử dụng nút mũi tên Lên/Xuống để đổi vị trí thứ hạng tức thì</p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-500 border-b border-gray-200 uppercase text-xs tracking-wider">
                <tr>
                    <th class="px-4 py-3.5 text-center w-24">Thứ Hạng</th>
                    <th class="px-3 py-3.5 text-center w-16">Đổi Vị Trí</th>
                    <th class="px-6 py-3.5">Phim & Áp Phích</th>
                    <th class="px-6 py-3.5">Phụ Đề / Tên Tiếng Anh</th>
                    <th class="px-4 py-3.5 text-center">Nhãn Hiển Thị</th>
                    <th class="px-4 py-3.5 text-center">Độ Tuổi</th>
                    <th class="px-4 py-3.5 text-center">Trạng Thái</th>
                    <th class="px-6 py-3.5 text-right">Thao Tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($topHotMovies as $item)
                <tr class="hover:bg-amber-50/30 transition-colors {{ !$item->is_active ? 'opacity-60 bg-gray-50' : '' }}">
                    <!-- Rank Column -->
                    <td class="px-4 py-4 text-center">
                        @if($item->rank == 1)
                            <div class="inline-flex items-center justify-center w-11 h-11 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 text-white font-black text-xl shadow-md border-2 border-amber-300">
                                1
                            </div>
                        @elseif($item->rank == 2)
                            <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br from-gray-300 to-gray-500 text-white font-black text-lg shadow-sm border border-gray-200">
                                2
                            </div>
                        @elseif($item->rank == 3)
                            <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br from-amber-700 to-amber-900 text-white font-black text-lg shadow-sm border border-amber-600">
                                3
                            </div>
                        @else
                            <div class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-gray-100 text-gray-800 font-extrabold text-base border border-gray-200">
                                {{ $item->rank }}
                            </div>
                        @endif
                    </td>

                    <!-- Move Rank Up / Down -->
                    <td class="px-3 py-4 text-center">
                        <div class="flex flex-col items-center justify-center gap-1">
                            @if($item->rank > 1)
                            <form action="{{ route('admin.top_movies.move_rank') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $item->id }}">
                                <input type="hidden" name="direction" value="up">
                                <button type="submit" class="p-1 text-gray-400 hover:text-amber-600 hover:bg-amber-100 rounded transition" title="Lên rank trên">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7" />
                                    </svg>
                                </button>
                            </form>
                            @else
                                <span class="h-6 w-6"></span>
                            @endif

                            @if($item->rank < 10)
                            <form action="{{ route('admin.top_movies.move_rank') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $item->id }}">
                                <input type="hidden" name="direction" value="down">
                                <button type="submit" class="p-1 text-gray-400 hover:text-amber-600 hover:bg-amber-100 rounded transition" title="Xuống rank dưới">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>

                    <!-- Movie & Poster Preview -->
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="relative w-14 h-20 rounded-lg overflow-hidden border border-gray-200 shadow-sm shrink-0 bg-gray-900 group">
                                <img src="{{ $item->display_poster }}" alt="{{ $item->movie->title ?? '' }}" class="w-full h-full object-cover">
                                
                                <!-- Mini bottom badge overlay on poster -->
                                @if($item->badge_text || $item->badge_text_2)
                                <div class="absolute bottom-1 inset-x-1 flex items-center justify-center gap-0.5 pointer-events-none">
                                    @if($item->badge_text)
                                        <span class="bg-black/80 text-[8px] font-bold text-gray-200 px-1 py-0.2 rounded truncate max-w-full">
                                            {{ $item->badge_text }}
                                        </span>
                                    @endif
                                </div>
                                @endif
                            </div>

                            <div>
                                <a href="{{ route('movies.show', $item->movie_id) }}" target="_blank" class="font-bold text-gray-900 hover:text-amber-600 transition flex items-center gap-1.5 text-base">
                                    {{ $item->movie->title ?? 'N/A' }}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                                <p class="text-xs text-gray-500 mt-1 flex items-center gap-2">
                                    <span>⏱ {{ $item->movie->duration ?? 0 }} phút</span>
                                    <span>•</span>
                                    <span>{{ $item->movie->genre ?? 'Chung' }}</span>
                                </p>
                            </div>
                        </div>
                    </td>

                    <!-- Subtitle / English title -->
                    <td class="px-6 py-4">
                        <span class="text-sm font-medium text-gray-700 block">
                            {{ $item->sub_title ?: '—' }}
                        </span>
                        <span class="text-xs text-gray-400">Hiển thị dưới tên tiếng Việt</span>
                    </td>

                    <!-- Badges -->
                    <td class="px-4 py-4 text-center">
                        <div class="flex items-center justify-center gap-1.5 flex-wrap">
                            @if($item->badge_text)
                                <span class="bg-gray-800 text-white text-xs font-semibold px-2 py-0.5 rounded shadow-2xs">
                                    {{ $item->badge_text }}
                                </span>
                            @endif
                            @if($item->badge_text_2)
                                <span class="bg-emerald-600 text-white text-xs font-bold px-2 py-0.5 rounded shadow-2xs">
                                    {{ $item->badge_text_2 }}
                                </span>
                            @endif
                            @if(!$item->badge_text && !$item->badge_text_2)
                                <span class="text-gray-400 text-xs">Mặc định</span>
                            @endif
                        </div>
                    </td>

                    <!-- Age Rating -->
                    <td class="px-4 py-4 text-center">
                        <span class="inline-block px-2 py-0.5 rounded text-xs font-bold 
                            {{ $item->age_rating == 'T18' ? 'bg-red-100 text-red-700 border border-red-200' : '' }}
                            {{ $item->age_rating == 'T16' ? 'bg-orange-100 text-orange-700 border border-orange-200' : '' }}
                            {{ $item->age_rating == 'T13' ? 'bg-amber-100 text-amber-800 border border-amber-200' : '' }}
                            {{ $item->age_rating == 'P' ? 'bg-green-100 text-green-700 border border-green-200' : '' }}
                        ">
                            {{ $item->age_rating ?: 'T13' }}
                        </span>
                    </td>

                    <!-- Status Toggle -->
                    <td class="px-4 py-4 text-center">
                        <form action="{{ route('admin.top_movies.toggle', $item->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold transition cursor-pointer {{ $item->is_active ? 'bg-green-50 text-green-700 hover:bg-green-100 border border-green-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200 border border-gray-300' }}" title="Click để bật/tắt hiển thị">
                                <span class="w-1.5 h-1.5 rounded-full {{ $item->is_active ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                {{ $item->is_active ? 'Hiển thị' : 'Đang ẩn' }}
                            </button>
                        </form>
                    </td>

                    <!-- Actions -->
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button type="button" 
                                onclick='openEditModal(@json($item))'
                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Sửa thông tin">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </button>

                            <form action="{{ route('admin.top_movies.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn gỡ phim này khỏi Top 10?');" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Gỡ khỏi Top 10">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                        <div class="max-w-sm mx-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                            </svg>
                            <p class="font-bold text-gray-700">Chưa có phim nào trong Top 10</p>
                            <p class="text-xs text-gray-400 mt-1">Bấm nút "Tự Động Điền Top 10" hoặc "Thêm Phim Vào Top 10" để bắt đầu.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL: THÊM PHIM VÀO TOP 10 -->
<!-- ========================================== -->
<div id="addModal" class="fixed inset-0 z-50 overflow-y-auto hidden bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl relative border border-gray-100">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <span class="w-2.5 h-6 bg-amber-500 rounded"></span>
                Thêm Phim Vào Top 10 Hot
            </h3>
            <button type="button" onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="{{ route('admin.top_movies.store') }}" method="POST" enctype="multipart/form-data" class="mt-5 space-y-4">
            @csrf

            <!-- Chọn Phim -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wider">Chọn Phim <span class="text-red-500">*</span></label>
                <select name="movie_id" required class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    <option value="">-- Chọn một phim từ hệ thống --</option>
                    @foreach($allMovies as $m)
                        <option value="{{ $m->id }}">
                            {{ $m->title }} ({{ $m->duration }}p - {{ $m->genre }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Thứ Hạng (1 - 10) -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wider">Thứ Hạng (Rank) <span class="text-red-500">*</span></label>
                    <select name="rank" required class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm font-bold text-amber-600 focus:ring-2 focus:ring-amber-500">
                        @for($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}" {{ $i == ($topHotMovies->count() + 1) ? 'selected' : '' }}>
                                Top #{{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wider">Độ Tuổi <span class="text-red-500">*</span></label>
                    <select name="age_rating" required class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-amber-500">
                        <option value="P">P (Mọi lứa tuổi)</option>
                        <option value="T13" selected>T13 (Trên 13 tuổi)</option>
                        <option value="T16">T16 (Trên 16 tuổi)</option>
                        <option value="T18">T18 (Trên 18 tuổi)</option>
                        <option value="C">C (Cấm phổ biến)</option>
                    </select>
                </div>
            </div>

            <!-- Tên Phụ / Subtitle -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wider">Tên Tiếng Anh / Phụ Đề (Subtitle)</label>
                <input type="text" name="sub_title" placeholder="VD: Against The Current / Marvel Avengers..." class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-amber-500">
                <span class="text-[11px] text-gray-400">Xuất hiện ngay dưới tên phim chính</span>
            </div>

            <!-- Nhãn hiển thị trên Poster -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wider">Nhãn 1 (Poster)</label>
                    <input type="text" name="badge_text" placeholder="VD: PD. 30, IMAX, 2D" class="w-full px-3.5 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-amber-500">
                    <span class="text-[10px] text-gray-400">VD: PD. 8, PD. 30</span>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wider">Nhãn 2 (Xanh lá)</label>
                    <input type="text" name="badge_text_2" placeholder="VD: TM. 16, 3D" class="w-full px-3.5 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-amber-500">
                    <span class="text-[10px] text-gray-400">VD: TM. 16, Lồng tiếng</span>
                </div>
            </div>

            <!-- Trạng thái kích hoạt -->
            <div class="flex items-center gap-3 pt-2">
                <input type="checkbox" name="is_active" id="add_is_active" value="1" checked class="h-4 w-4 text-amber-600 rounded border-gray-300 focus:ring-amber-500">
                <label for="add_is_active" class="text-sm font-medium text-gray-700 cursor-pointer">Hiển thị ngay trên bảng xếp hạng trang chủ</label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeAddModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50">Hủy</button>
                <button type="submit" class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-gray-900 font-bold rounded-lg text-sm shadow">Lưu Vào Top 10</button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL: SỬA PHIM TRONG TOP 10 -->
<!-- ========================================== -->
<div id="editModal" class="fixed inset-0 z-50 overflow-y-auto hidden bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl relative border border-gray-100">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <span class="w-2.5 h-6 bg-blue-600 rounded"></span>
                Chỉnh Sửa Thông Tin Top Phim Hot
            </h3>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="editForm" action="" method="POST" enctype="multipart/form-data" class="mt-5 space-y-4">
            @csrf
            @method('PUT')

            <!-- Chọn Phim -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wider">Phim Được Chọn <span class="text-red-500">*</span></label>
                <select name="movie_id" id="edit_movie_id" required class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @foreach($allMovies as $m)
                        <option value="{{ $m->id }}">{{ $m->title }} ({{ $m->duration }}p - {{ $m->genre }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Thứ Hạng (1 - 10) -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wider">Thứ Hạng (Rank) <span class="text-red-500">*</span></label>
                    <select name="rank" id="edit_rank" required class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm font-bold text-amber-600 focus:ring-2 focus:ring-amber-500">
                        @for($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}">Top #{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wider">Độ Tuổi <span class="text-red-500">*</span></label>
                    <select name="age_rating" id="edit_age_rating" required class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="P">P (Mọi lứa tuổi)</option>
                        <option value="T13">T13 (Trên 13 tuổi)</option>
                        <option value="T16">T16 (Trên 16 tuổi)</option>
                        <option value="T18">T18 (Trên 18 tuổi)</option>
                        <option value="C">C (Cấm phổ biến)</option>
                    </select>
                </div>
            </div>

            <!-- Tên Phụ / Subtitle -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wider">Tên Tiếng Anh / Phụ Đề (Subtitle)</label>
                <input type="text" name="sub_title" id="edit_sub_title" placeholder="VD: Against The Current" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Nhãn hiển thị trên Poster -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wider">Nhãn 1 (Poster)</label>
                    <input type="text" name="badge_text" id="edit_badge_text" placeholder="VD: PD. 30" class="w-full px-3.5 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wider">Nhãn 2 (Xanh lá)</label>
                    <input type="text" name="badge_text_2" id="edit_badge_text_2" placeholder="VD: TM. 16" class="w-full px-3.5 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- Trạng thái kích hoạt -->
            <div class="flex items-center gap-3 pt-2">
                <input type="checkbox" name="is_active" id="edit_is_active" value="1" class="h-4 w-4 text-amber-600 rounded border-gray-300 focus:ring-amber-500">
                <label for="edit_is_active" class="text-sm font-medium text-gray-700 cursor-pointer">Hiển thị trên bảng xếp hạng trang chủ</label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50">Hủy</button>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg text-sm shadow">Lưu Cập Nhật</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById('addModal').classList.remove('hidden');
    }

    function closeAddModal() {
        document.getElementById('addModal').classList.add('hidden');
    }

    function openEditModal(item) {
        document.getElementById('editForm').action = "/admin/top-movies/" + item.id;
        document.getElementById('edit_movie_id').value = item.movie_id;
        document.getElementById('edit_rank').value = item.rank;
        document.getElementById('edit_sub_title').value = item.sub_title || '';
        document.getElementById('edit_badge_text').value = item.badge_text || '';
        document.getElementById('edit_badge_text_2').value = item.badge_text_2 || '';
        document.getElementById('edit_age_rating').value = item.age_rating || 'T13';
        document.getElementById('edit_is_active').checked = !!item.is_active;

        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    // Close modal on Escape or backdrop click
    window.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAddModal();
            closeEditModal();
        }
    });
</script>
@endsection
