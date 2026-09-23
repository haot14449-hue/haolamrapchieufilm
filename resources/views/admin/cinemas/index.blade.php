@extends('admin.layouts.app')

@section('title', 'Quản lý Rạp & Phòng Chiếu - Admin HCTV')

@section('content')
<!-- Header -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Quản lý Rạp & Phòng Chiếu</h1>
        <p class="text-sm text-gray-500 mt-1">Quản lý hệ thống cụm rạp, phòng chiếu và thiết kế bố cục sơ đồ ghế tương tác dạng lưới</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.cinemas.create') }}" class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg shadow hover:bg-gray-800 transition-colors flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Thêm Rạp Mới
        </a>
    </div>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>{{ session('success') }}</span>
        </div>
        <button type="button" onclick="this.parentElement.remove();" class="text-emerald-500 hover:text-emerald-700 font-bold text-sm">&times;</button>
    </div>
@endif

@if(session('error'))
    <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>{{ session('error') }}</span>
        </div>
        <button type="button" onclick="this.parentElement.remove();" class="text-rose-500 hover:text-rose-700 font-bold text-sm">&times;</button>
    </div>
@endif

<!-- Cinema & Rooms List -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-12">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm border-collapse">
            <thead class="bg-gray-50 text-gray-500 border-b border-gray-200 font-semibold text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-4">Tên Rạp</th>
                    <th class="px-6 py-4">Địa Điểm</th>
                    <th class="px-6 py-4">Số Phòng Chiếu</th>
                    <th class="px-6 py-4 text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($cinemas as $cinema)
                <!-- Cinema Row -->
                <tr class="hover:bg-gray-50/80 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-900 text-base flex items-center gap-2.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-orange-500"></span>
                            {{ $cinema->name }}
                        </div>
                        @if($cinema->city)
                            <span class="text-xs text-gray-400 mt-0.5 inline-block">{{ $cinema->city }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-600 max-w-xs truncate" title="{{ $cinema->location }}">
                        {{ $cinema->location }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $cinema->rooms_count > 0 ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 'bg-gray-100 text-gray-500' }}">
                                {{ $cinema->rooms_count }} phòng
                            </span>
                            <button type="button" 
                                    onclick="toggleRoomsAccordion({{ $cinema->id }})" 
                                    class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold flex items-center gap-1 ml-1 cursor-pointer">
                                <span id="accordion-text-{{ $cinema->id }}">Xem phòng</span>
                                <svg id="accordion-icon-{{ $cinema->id }}" class="w-3.5 h-3.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <!-- Add Room Button for this Cinema -->
                            <button type="button" 
                                    onclick="openAddRoomModal({{ $cinema->id }}, '{{ addslashes($cinema->name) }}')"
                                    class="px-2.5 py-1.5 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 rounded-lg text-xs font-bold transition flex items-center gap-1"
                                    title="Thêm phòng chiếu cho rạp này">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <span>Thêm phòng</span>
                            </button>

                            <a href="{{ route('admin.cinemas.edit', $cinema->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Sửa rạp">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                            </a>
                            <form action="{{ route('admin.cinemas.destroy', $cinema->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa rạp này?');" class="inline-block m-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Xóa rạp">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                <!-- Expandable Sub-table: Screening Rooms of this Cinema -->
                <tr id="rooms-accordion-{{ $cinema->id }}" class="hidden bg-gray-50/50">
                    <td colspan="4" class="px-8 py-5 border-t border-b border-gray-200">
                        <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
                            <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                                    <h4 class="font-bold text-gray-900 text-sm">Danh sách phòng chiếu thuộc {{ $cinema->name }}</h4>
                                </div>
                                <button type="button" 
                                        onclick="openAddRoomModal({{ $cinema->id }}, '{{ addslashes($cinema->name) }}')"
                                        class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    <span>Tạo phòng chiếu mới</span>
                                </button>
                            </div>

                            @if($cinema->rooms->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                                    @foreach($cinema->rooms as $room)
                                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 hover:border-indigo-300 transition-all shadow-sm flex flex-col justify-between">
                                        <div>
                                            <div class="flex items-start justify-between gap-2 mb-2">
                                                <h5 class="font-bold text-gray-900 text-base">{{ $room->name }}</h5>
                                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[11px] font-bold rounded-full">
                                                    <span id="room-cap-badge-{{ $room->id }}">{{ $room->capacity }}</span> ghế
                                                </span>
                                            </div>
                                            <p class="text-xs text-gray-500 mb-3">
                                                Kích thước lưới: <strong>{{ $room->total_rows ?? 10 }} hàng × {{ $room->total_columns ?? 16 }} cột</strong>
                                            </p>
                                        </div>

                                        <div class="pt-3 border-t border-gray-200 flex items-center justify-between gap-2">
                                            <!-- THE KEY BUTTON: Thiết kế sơ đồ ghế dạng lưới -->
                                            <button type="button" 
                                                    onclick="openSeatDesigner({{ $room->id }}, '{{ addslashes($room->name) }}', '{{ addslashes($cinema->name) }}')"
                                                    class="flex-1 py-2 px-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-lg text-xs font-bold transition shadow flex items-center justify-center gap-1.5 cursor-pointer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                                                <span>Thiết kế ghế</span>
                                            </button>

                                            <!-- Delete Room -->
                                            <form action="{{ route('admin.rooms.destroy', $room->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa phòng chiếu này?');" class="m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition" title="Xóa phòng">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-6 text-gray-500 text-xs">
                                    <p>Chưa có phòng chiếu nào tại rạp này.</p>
                                    <button type="button" 
                                            onclick="openAddRoomModal({{ $cinema->id }}, '{{ addslashes($cinema->name) }}')"
                                            class="mt-2 text-indigo-600 hover:underline font-bold">
                                        + Thêm phòng chiếu đầu tiên
                                    </button>
                                </div>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">Chưa có rạp nào. Hãy thêm rạp mới!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL 1: THIẾT KẾ BỐ CỤC GHẾ NGỒI (GRID LAYOUT DESIGNER - MATCHING IMAGE 2) -->
<!-- ========================================================================= -->
<div id="seat-designer-modal" class="fixed inset-0 z-[99999] hidden items-center justify-center bg-black/75 backdrop-blur-sm p-2 sm:p-4 overflow-y-auto">
    <div class="bg-white w-full max-w-[1400px] max-h-[95vh] rounded-2xl shadow-2xl flex flex-col overflow-hidden border border-gray-200">
        
        <!-- Modal Top Bar -->
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-white shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center shrink-0 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900 leading-tight">Thiết kế bố cục ghế ngồi</h3>
                    <p class="text-xs text-purple-600 mt-0.5 flex items-center gap-1" id="modal-subtitle">
                        <span>Click để chọn loại ghế, kéo rê chuột để tô nhiều ghế, nhấp đúp tạo lối đi</span>
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <span id="designer-total-badge" class="px-4 py-1.5 rounded-full bg-purple-50 border border-purple-200 text-purple-700 text-xs font-bold tracking-wide">
                    Tổng: 0 ghế
                </span>
                <button type="button" onclick="closeSeatDesigner()" class="p-1.5 text-gray-400 hover:text-gray-700 rounded-lg hover:bg-gray-100 transition" title="Đóng">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        </div>

        <!-- Modal Body (2 Columns Layout matching Image 2) -->
        <div class="flex-1 overflow-y-auto flex flex-col lg:flex-row divide-y lg:divide-y-0 lg:divide-x divide-gray-200 bg-gray-50/50">
            
            <!-- Left Sidebar Controls (~300px) -->
            <div class="w-full lg:w-80 shrink-0 p-5 space-y-5 bg-white overflow-y-auto">
                
                <!-- 1. Kích thước lưới (Sliders) -->
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                    <div class="flex items-center gap-2 mb-3 text-gray-800 font-bold text-sm">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                        <span>Kích thước lưới</span>
                    </div>

                    <!-- Số hàng slider -->
                    <div class="mb-4">
                        <div class="flex justify-between text-xs text-gray-600 font-semibold mb-1">
                            <span>Số hàng:</span>
                            <span id="rows-display" class="text-purple-600 font-bold text-sm">10</span>
                        </div>
                        <input type="range" id="rows-slider" min="4" max="22" value="10" 
                               class="w-full h-1.5 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-purple-600">
                    </div>

                    <!-- Số cột slider -->
                    <div class="mb-4">
                        <div class="flex justify-between text-xs text-gray-600 font-semibold mb-1">
                            <span>Số cột:</span>
                            <span id="cols-display" class="text-purple-600 font-bold text-sm">16</span>
                        </div>
                        <input type="range" id="cols-slider" min="6" max="28" value="16" 
                               class="w-full h-1.5 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-purple-600">
                    </div>

                    <button type="button" id="btn-resize-grid" class="w-full py-1.5 bg-purple-50 hover:bg-purple-100 text-purple-700 border border-purple-200 rounded-lg text-xs font-bold transition flex items-center justify-center gap-1">
                        Áp dụng kích thước
                    </button>
                </div>

                <!-- 2. Công cụ vẽ (Brush Palette) -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2.5">Công cụ vẽ loại ghế</h4>
                    
                    <div class="space-y-2" id="brush-tools">
                        <!-- Standard -->
                        <div class="brush-item active flex items-center justify-between p-3 rounded-xl border-2 border-purple-600 bg-purple-50/50 cursor-pointer transition shadow-sm"
                             data-type="standard">
                            <div class="flex items-center gap-3">
                                <span class="w-4 h-4 rounded-full bg-purple-600 shadow shrink-0"></span>
                                <div>
                                    <p class="text-xs font-bold text-gray-900">Standard - 1 Ghế</p>
                                    <p class="text-[11px] text-gray-500">Ghế tiêu chuẩn</p>
                                </div>
                            </div>
                            <span class="w-2 h-2 rounded-full bg-purple-600"></span>
                        </div>

                        <!-- VIP -->
                        <div class="brush-item flex items-center justify-between p-3 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 cursor-pointer transition"
                             data-type="vip">
                            <div class="flex items-center gap-3">
                                <span class="w-4 h-4 rounded-full bg-rose-600 shadow shrink-0"></span>
                                <div>
                                    <p class="text-xs font-bold text-gray-900">VIP - 1 Ghế</p>
                                    <p class="text-[11px] text-gray-500">Vị trí trung tâm đẹp</p>
                                </div>
                            </div>
                            <span class="w-2 h-2 rounded-full bg-transparent"></span>
                        </div>

                        <!-- Sweetbox / Ghế đôi -->
                        <div class="brush-item flex items-center justify-between p-3 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 cursor-pointer transition"
                             data-type="sweetbox">
                            <div class="flex items-center gap-3">
                                <span class="w-4 h-4 rounded-full bg-sky-600 shadow shrink-0"></span>
                                <div>
                                    <p class="text-xs font-bold text-gray-900">Sweetbox - Ghế Đôi</p>
                                    <p class="text-[11px] text-gray-500">Dành cho 2 người</p>
                                </div>
                            </div>
                            <span class="w-2 h-2 rounded-full bg-transparent"></span>
                        </div>

                        <!-- Deluxe -->
                        <div class="brush-item flex items-center justify-between p-3 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 cursor-pointer transition"
                             data-type="deluxe">
                            <div class="flex items-center gap-3">
                                <span class="w-4 h-4 rounded-full bg-amber-500 shadow shrink-0"></span>
                                <div>
                                    <p class="text-xs font-bold text-gray-900">Deluxe - 1 Ghế</p>
                                    <p class="text-[11px] text-gray-500">Ghế thương gia</p>
                                </div>
                            </div>
                            <span class="w-2 h-2 rounded-full bg-transparent"></span>
                        </div>

                        <!-- Eraser / Lối đi -->
                        <div class="brush-item flex items-center justify-between p-3 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 cursor-pointer transition"
                             data-type="empty">
                            <div class="flex items-center gap-3">
                                <span class="w-4 h-4 rounded border-2 border-dashed border-gray-400 bg-gray-100 shrink-0"></span>
                                <div>
                                    <p class="text-xs font-bold text-gray-700">Lối đi / Trống</p>
                                    <p class="text-[11px] text-gray-400">Xóa ô tạo khoảng trống</p>
                                </div>
                            </div>
                            <span class="w-2 h-2 rounded-full bg-transparent"></span>
                        </div>
                    </div>
                </div>

                <!-- 3. Thao tác tạo nhanh (Presets) -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Thao tác nhanh</h4>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <button type="button" id="btn-fill-standard" class="p-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition text-left">
                            Tô đầy Standard
                        </button>
                        <button type="button" id="btn-fill-vip-middle" class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg font-medium transition text-left">
                            Tô VIP ở giữa
                        </button>
                        <button type="button" id="btn-create-aisles" class="p-2 bg-sky-50 hover:bg-sky-100 text-sky-700 rounded-lg font-medium transition text-left">
                            Tạo lối đi đôi
                        </button>
                        <button type="button" id="btn-clear-grid" class="p-2 bg-red-50 hover:bg-red-100 text-red-700 rounded-lg font-medium transition text-left">
                            Xóa trắng
                        </button>
                    </div>
                </div>

                <!-- Tip -->
                <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-[11px] text-amber-800 leading-relaxed">
                    💡 <strong>Mẹo thao tác:</strong> Nhấn giữ chuột và kéo qua các ô để tô nhanh nhiều ghế. Nhấp vào ghế để đổi kiểu theo công cụ đang chọn.
                </div>
            </div>

            <!-- Right Canvas Area (Screen + Grid) -->
            <div class="flex-1 p-6 overflow-x-auto bg-[#f8fafc]" style="scrollbar-width: thin;">
                <div class="w-max min-w-full flex flex-col items-center">
                    
                    <!-- Cinema Screen Header (MÀN HÌNH CHIẾU) -->
                    <div class="w-full max-w-2xl mb-8 flex flex-col items-center">
                        <div class="w-3/4 h-12 bg-gray-900 rounded-xl shadow-lg flex items-center justify-center text-white gap-2 text-xs font-bold tracking-widest uppercase border border-gray-700 relative overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent animate-pulse"></div>
                            <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            <span>MÀN HÌNH CHIẾU</span>
                        </div>
                        <div class="w-4/5 h-2 bg-gradient-to-b from-gray-300 to-transparent mt-1 rounded-full opacity-60"></div>
                    </div>

                    <!-- The Interactive Grid Canvas -->
                    <div class="p-4 bg-white rounded-2xl shadow-sm border border-gray-200 inline-block">
                        <div id="designer-grid-canvas" class="select-none flex flex-col gap-1.5">
                            <!-- Dynamically populated via JS -->
                        </div>
                    </div>

                    <!-- Bottom Axis Label -->
                    <div class="mt-4 flex items-center justify-between w-full max-w-xl text-xs text-gray-400 font-medium">
                        <span id="grid-row-summary">Hàng A &rarr; J</span>
                        <span id="grid-col-summary">Cột 1 &rarr; 16</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 bg-white border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-4 shrink-0">
            <!-- Statistics Legend -->
            <div class="flex flex-wrap items-center gap-4 text-xs text-gray-600">
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-purple-600 shadow-sm"></span>
                    <span>Standard: <strong id="stat-count-standard" class="text-gray-900 font-bold">0</strong></span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-rose-600 shadow-sm"></span>
                    <span>VIP: <strong id="stat-count-vip" class="text-gray-900 font-bold">0</strong></span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-sky-600 shadow-sm"></span>
                    <span>Sweetbox: <strong id="stat-count-sweetbox" class="text-gray-900 font-bold">0</strong></span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded border border-dashed border-gray-400 bg-gray-100"></span>
                    <span>Lối đi: <strong id="stat-count-empty" class="text-gray-900 font-bold">0</strong></span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-3">
                <button type="button" onclick="closeSeatDesigner()" class="px-5 py-2.5 rounded-xl border border-gray-300 hover:bg-gray-100 text-gray-700 text-sm font-semibold transition">
                    Hủy
                </button>
                <button type="button" id="btn-save-layout" class="px-6 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold shadow-md hover:shadow-lg transition flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span id="btn-save-text">Lưu bố cục (0 ghế)</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL 2: THÊM PHÒNG CHIẾU MỚI CHO RẠP -->
<!-- ========================================================================= -->
<div id="add-room-modal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-6 border border-gray-200">
        <div class="flex justify-between items-center mb-5 pb-3 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                <span>Thêm Phòng Chiếu Mới</span>
            </h3>
            <button type="button" onclick="closeAddRoomModal()" class="text-gray-400 hover:text-gray-600 text-lg font-bold">&times;</button>
        </div>

        <form id="add-room-form" method="POST" action="">
            @csrf
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Rạp chiếu</label>
                <input type="text" id="add-room-cinema-name" readonly class="w-full bg-gray-100 border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-600 cursor-not-allowed">
            </div>

            <div class="mb-4">
                <label for="room_name" class="block text-xs font-bold text-gray-700 uppercase mb-1">Tên phòng chiếu <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="room_name" placeholder="Ví dụ: Phòng 01 (IMAX), Phòng 02 (2D)" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>

            <div class="grid grid-cols-2 gap-3 mb-6">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Số hàng khởi tạo</label>
                    <input type="number" name="total_rows" value="10" min="4" max="22" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Số cột khởi tạo</label>
                    <input type="number" name="total_columns" value="16" min="6" max="28" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeAddRoomModal()" class="px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg transition">Hủy</button>
                <button type="submit" class="px-5 py-2 text-sm font-bold bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition shadow">Tạo phòng chiếu</button>
            </div>
        </form>
    </div>
</div>

<script>
// Toggle Room Accordion for each cinema
function toggleRoomsAccordion(cinemaId) {
    const row = document.getElementById(`rooms-accordion-${cinemaId}`);
    const text = document.getElementById(`accordion-text-${cinemaId}`);
    const icon = document.getElementById(`accordion-icon-${cinemaId}`);
    if (!row) return;

    const isHidden = row.classList.contains('hidden');
    if (isHidden) {
        row.classList.remove('hidden');
        if (text) text.textContent = 'Ẩn phòng';
        if (icon) icon.classList.add('rotate-180');
    } else {
        row.classList.add('hidden');
        if (text) text.textContent = 'Xem phòng';
        if (icon) icon.classList.remove('rotate-180');
    }
}

// Add Room Modal Open/Close
function openAddRoomModal(cinemaId, cinemaName) {
    const modal = document.getElementById('add-room-modal');
    const form = document.getElementById('add-room-form');
    const nameInput = document.getElementById('add-room-cinema-name');
    
    if (form) form.action = `/admin/cinemas/${cinemaId}/rooms`;
    if (nameInput) nameInput.value = cinemaName;
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}
function closeAddRoomModal() {
    const modal = document.getElementById('add-room-modal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

// =====================================================================
// GRID SEAT DESIGNER ENGINE (Interactive Canvas matching Image 2)
// =====================================================================
let currentRoomId = null;
let currentRows = 10;
let currentCols = 16;
let gridMatrix = []; // 2D array of cells: [ [ { type, row, number, code } ] ]
let activeBrush = 'standard'; // standard | vip | sweetbox | deluxe | empty
let isMouseDown = false;

const ROW_LETTERS = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];

// Brush Styles Configuration
const BRUSH_CONFIG = {
    standard: {
        bg: 'bg-purple-600 hover:bg-purple-700',
        border: 'border-purple-600',
        text: 'text-white',
        label: 'Standard',
        colorHex: '#9333ea'
    },
    vip: {
        bg: 'bg-rose-600 hover:bg-rose-700',
        border: 'border-rose-600',
        text: 'text-white',
        label: 'VIP',
        colorHex: '#e11d48'
    },
    sweetbox: {
        bg: 'bg-sky-600 hover:bg-sky-700',
        border: 'border-sky-600',
        text: 'text-white',
        label: 'Sweetbox',
        colorHex: '#0284c7'
    },
    deluxe: {
        bg: 'bg-amber-500 hover:bg-amber-600',
        border: 'border-amber-500',
        text: 'text-gray-900 font-bold',
        label: 'Deluxe',
        colorHex: '#f59e0b'
    },
    empty: {
        bg: 'bg-transparent hover:bg-gray-100',
        border: 'border border-dashed border-gray-300',
        text: 'text-transparent',
        label: 'Trống',
        colorHex: 'transparent'
    }
};

// Open the Seat Designer Modal
function openSeatDesigner(roomId, roomName, cinemaName) {
    currentRoomId = roomId;
    const modal = document.getElementById('seat-designer-modal');
    document.getElementById('modal-subtitle').innerHTML = `Phòng: <strong>${roomName}</strong> &bull; Rạp: ${cinemaName}`;
    
    // Fetch layout from server
    fetch(`/admin/rooms/${roomId}/layout`)
        .then(res => res.json())
        .then(data => {
            if (data.success && data.room) {
                currentRows = data.room.total_rows || 10;
                currentCols = data.room.total_columns || 16;
                document.getElementById('rows-slider').value = currentRows;
                document.getElementById('cols-slider').value = currentCols;
                document.getElementById('rows-display').textContent = currentRows;
                document.getElementById('cols-display').textContent = currentCols;

                if (data.room.layout && data.room.layout.length > 0) {
                    loadExistingLayout(data.room.layout, currentRows, currentCols);
                } else {
                    generateFreshGrid(currentRows, currentCols);
                }
            } else {
                generateFreshGrid(currentRows, currentCols);
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            renderGrid();
        })
        .catch(err => {
            console.error('Lỗi tải layout:', err);
            generateFreshGrid(10, 16);
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            renderGrid();
        });
}

function closeSeatDesigner() {
    const modal = document.getElementById('seat-designer-modal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    currentRoomId = null;
}

// Initialize Fresh Grid
function generateFreshGrid(rows, cols) {
    gridMatrix = [];
    for (let r = 0; r < rows; r++) {
        const rowLetter = ROW_LETTERS[r] || `R${r+1}`;
        const rowCells = [];
        let seatNum = 1;
        for (let c = 0; c < cols; c++) {
            let type = 'standard';
            if (r >= 4 && r <= 7) {
                type = 'vip';
            } else if (r === rows - 1) {
                type = 'sweetbox';
            }

            rowCells.push({
                type: type,
                row: rowLetter,
                number: seatNum,
                code: `${rowLetter}${seatNum}`
            });
            seatNum++;
        }
        gridMatrix.push(rowCells);
    }
    recalculateSeatNumbers();
}

// Load from Existing Layout Array
function loadExistingLayout(savedLayout, rows, cols) {
    gridMatrix = [];
    for (let r = 0; r < rows; r++) {
        const rowLetter = ROW_LETTERS[r] || `R${r+1}`;
        const rowCells = [];
        for (let c = 0; c < cols; c++) {
            const existingCell = savedLayout[r] ? savedLayout[r][c] : null;
            if (existingCell) {
                rowCells.push({
                    type: existingCell.type || 'standard',
                    row: rowLetter,
                    number: existingCell.number || 0,
                    code: existingCell.code || ''
                });
            } else {
                rowCells.push({
                    type: 'standard',
                    row: rowLetter,
                    number: 0,
                    code: ''
                });
            }
        }
        gridMatrix.push(rowCells);
    }
    recalculateSeatNumbers();
}

// Recalculate numbering row-by-row (skipping 'empty' cells)
function recalculateSeatNumbers() {
    let stats = { standard: 0, vip: 0, sweetbox: 0, deluxe: 0, empty: 0, totalSeats: 0 };

    gridMatrix.forEach((rowCells, rIdx) => {
        const rowLetter = ROW_LETTERS[rIdx] || `R${rIdx+1}`;
        let seatNum = 1;

        rowCells.forEach(cell => {
            cell.row = rowLetter;
            if (cell.type !== 'empty') {
                cell.number = seatNum;
                cell.code = `${rowLetter}${seatNum}`;
                seatNum++;
                stats.totalSeats++;

                if (stats[cell.type] !== undefined) {
                    stats[cell.type]++;
                }
            } else {
                cell.number = 0;
                cell.code = '';
                stats.empty++;
            }
        });
    });

    // Update stats UI
    document.getElementById('stat-count-standard').textContent = stats.standard;
    document.getElementById('stat-count-vip').textContent = stats.vip;
    document.getElementById('stat-count-sweetbox').textContent = stats.sweetbox;
    document.getElementById('stat-count-empty').textContent = stats.empty;

    const totalStr = `Tổng: ${stats.totalSeats} ghế`;
    document.getElementById('designer-total-badge').textContent = totalStr;
    document.getElementById('btn-save-text').textContent = `Lưu bố cục (${stats.totalSeats} ghế)`;
    document.getElementById('grid-row-summary').textContent = `Hàng A → ${ROW_LETTERS[gridMatrix.length - 1] || 'Z'}`;
    document.getElementById('grid-col-summary').textContent = `Cột 1 → ${gridMatrix[0] ? gridMatrix[0].length : 16}`;
}

// Render the 2D HTML Grid on the Canvas
function renderGrid() {
    const canvas = document.getElementById('designer-grid-canvas');
    if (!canvas) return;

    canvas.innerHTML = '';
    const numCols = gridMatrix[0] ? gridMatrix[0].length : 16;

    // Header Row with Column Numbers
    const colHeader = document.createElement('div');
    colHeader.className = 'flex items-center gap-1.5 mb-1';
    
    // Left empty spacer for row letter
    const spacerL = document.createElement('div');
    spacerL.className = 'w-6 text-center text-xs font-bold text-gray-400';
    colHeader.appendChild(spacerL);

    for (let c = 0; c < numCols; c++) {
        const colNum = document.createElement('div');
        colNum.className = 'w-7 sm:w-8 text-center text-[10px] font-bold text-gray-400';
        colNum.textContent = c + 1;
        colHeader.appendChild(colNum);
    }
    canvas.appendChild(colHeader);

    // Grid Rows
    gridMatrix.forEach((rowCells, rIdx) => {
        const rowDiv = document.createElement('div');
        rowDiv.className = 'flex items-center gap-1.5';

        // Row Letter Left
        const rowLabelL = document.createElement('div');
        rowLabelL.className = 'w-6 text-center text-xs font-bold text-gray-600 select-none';
        rowLabelL.textContent = ROW_LETTERS[rIdx] || `R${rIdx+1}`;
        rowDiv.appendChild(rowLabelL);

        // Seat Cells
        rowCells.forEach((cell, cIdx) => {
            const cellBtn = document.createElement('div');
            const cfg = BRUSH_CONFIG[cell.type] || BRUSH_CONFIG.standard;
            
            cellBtn.className = `w-7 h-7 sm:w-8 sm:h-8 rounded-lg flex items-center justify-center text-[10px] font-bold cursor-pointer select-none transition-transform duration-100 ${cfg.bg} ${cfg.border} ${cfg.text}`;
            cellBtn.textContent = cell.type !== 'empty' ? cell.number : '';
            cellBtn.dataset.row = rIdx;
            cellBtn.dataset.col = cIdx;
            cellBtn.title = cell.type !== 'empty' ? `${cell.code} (${cfg.label})` : 'Lối đi / Trống';

            // Interaction: Mouse Down
            cellBtn.addEventListener('mousedown', (e) => {
                isMouseDown = true;
                paintCell(rIdx, cIdx);
            });

            // Interaction: Mouse Over (Drag-to-paint)
            cellBtn.addEventListener('mouseenter', (e) => {
                if (isMouseDown) {
                    paintCell(rIdx, cIdx);
                }
            });

            // Interaction: Double click to toggle empty
            cellBtn.addEventListener('dblclick', (e) => {
                cell.type = (cell.type === 'empty') ? 'standard' : 'empty';
                recalculateSeatNumbers();
                updateRowUI(rIdx);
            });

            rowDiv.appendChild(cellBtn);
        });

        // Row Letter Right
        const rowLabelR = document.createElement('div');
        rowLabelR.className = 'w-6 text-center text-xs font-bold text-gray-600 select-none';
        rowLabelR.textContent = ROW_LETTERS[rIdx] || `R${rIdx+1}`;
        rowDiv.appendChild(rowLabelR);

        canvas.appendChild(rowDiv);
    });
}

// Update all cells in a specific row (keeps numbering in sync)
function updateRowUI(rIdx) {
    if (!gridMatrix[rIdx]) return;
    gridMatrix[rIdx].forEach((cell, cIdx) => {
        const cellEl = document.querySelector(`[data-row="${rIdx}"][data-col="${cIdx}"]`);
        if (cellEl) {
            updateCellUI(cellEl, cell);
        }
    });
}

// Paint a single cell with active brush
function paintCell(rIdx, cIdx) {
    if (!gridMatrix[rIdx] || !gridMatrix[rIdx][cIdx]) return;
    const cell = gridMatrix[rIdx][cIdx];
    if (cell.type === activeBrush) return;
    cell.type = activeBrush;
    recalculateSeatNumbers();
    updateRowUI(rIdx);
}

// Update DOM cell appearance
function updateCellUI(cellEl, cell) {
    const cfg = BRUSH_CONFIG[cell.type] || BRUSH_CONFIG.standard;
    cellEl.className = `w-7 h-7 sm:w-8 sm:h-8 rounded-lg flex items-center justify-center text-[10px] font-bold cursor-pointer select-none transition-transform duration-100 ${cfg.bg} ${cfg.border} ${cfg.text}`;
    cellEl.textContent = cell.type !== 'empty' ? cell.number : '';
    cellEl.title = cell.type !== 'empty' ? `${cell.code} (${cfg.label})` : 'Lối đi / Trống';
}

// Global Mouse Up for drag painting
document.addEventListener('mouseup', () => {
    isMouseDown = false;
});

// Brush Selector Events
document.querySelectorAll('.brush-item').forEach(item => {
    item.addEventListener('click', function() {
        document.querySelectorAll('.brush-item').forEach(b => {
            b.classList.remove('active', 'border-2', 'border-purple-600', 'bg-purple-50/50');
            b.classList.add('border', 'border-gray-200', 'bg-white');
            b.querySelector('span:last-child').className = 'w-2 h-2 rounded-full bg-transparent';
        });

        this.classList.add('active', 'border-2', 'border-purple-600', 'bg-purple-50/50');
        this.classList.remove('border', 'border-gray-200', 'bg-white');
        this.querySelector('span:last-child').className = 'w-2 h-2 rounded-full bg-purple-600';
        
        activeBrush = this.dataset.type;
    });
});

// Sliders Events
const rowsSlider = document.getElementById('rows-slider');
const colsSlider = document.getElementById('cols-slider');

if (rowsSlider) {
    rowsSlider.addEventListener('input', function() {
        document.getElementById('rows-display').textContent = this.value;
    });
}
if (colsSlider) {
    colsSlider.addEventListener('input', function() {
        document.getElementById('cols-display').textContent = this.value;
    });
}

// Apply new dimensions button
const resizeBtn = document.getElementById('btn-resize-grid');
if (resizeBtn) {
    resizeBtn.addEventListener('click', () => {
        const newRows = parseInt(rowsSlider.value);
        const newCols = parseInt(colsSlider.value);

        // Resize matrix preserving existing cells
        const newMatrix = [];
        for (let r = 0; r < newRows; r++) {
            const rowLetter = ROW_LETTERS[r] || `R${r+1}`;
            const rowCells = [];
            for (let c = 0; c < newCols; c++) {
                if (gridMatrix[r] && gridMatrix[r][c]) {
                    rowCells.push(gridMatrix[r][c]);
                } else {
                    rowCells.push({
                        type: 'standard',
                        row: rowLetter,
                        number: 0,
                        code: ''
                    });
                }
            }
            newMatrix.push(rowCells);
        }

        gridMatrix = newMatrix;
        currentRows = newRows;
        currentCols = newCols;
        recalculateSeatNumbers();
        renderGrid();
    });
}

// Preset: Fill standard
document.getElementById('btn-fill-standard')?.addEventListener('click', () => {
    gridMatrix.forEach(row => row.forEach(cell => cell.type = 'standard'));
    recalculateSeatNumbers();
    renderGrid();
});

// Preset: Fill VIP middle
document.getElementById('btn-fill-vip-middle')?.addEventListener('click', () => {
    const total = gridMatrix.length;
    const start = Math.floor(total * 0.35);
    const end = Math.floor(total * 0.75);
    gridMatrix.forEach((row, rIdx) => {
        if (rIdx >= start && rIdx <= end) {
            row.forEach((cell, cIdx) => {
                if (cIdx >= 2 && cIdx < row.length - 2 && cell.type !== 'empty') {
                    cell.type = 'vip';
                }
            });
        }
    });
    recalculateSeatNumbers();
    renderGrid();
});

// Preset: Create aisles
document.getElementById('btn-create-aisles')?.addEventListener('click', () => {
    const cols = gridMatrix[0] ? gridMatrix[0].length : 16;
    const aisle1 = Math.floor(cols * 0.25);
    const aisle2 = Math.floor(cols * 0.75);
    gridMatrix.forEach(row => {
        if (row[aisle1]) row[aisle1].type = 'empty';
        if (row[aisle2]) row[aisle2].type = 'empty';
    });
    recalculateSeatNumbers();
    renderGrid();
});

// Preset: Clear grid
document.getElementById('btn-clear-grid')?.addEventListener('click', () => {
    if (!confirm('Bạn có chắc muốn xóa tất cả thành ô trống?')) return;
    gridMatrix.forEach(row => row.forEach(cell => cell.type = 'empty'));
    recalculateSeatNumbers();
    renderGrid();
});

// Save Layout Button (POST to server)
const saveLayoutBtn = document.getElementById('btn-save-layout');
if (saveLayoutBtn) {
    saveLayoutBtn.addEventListener('click', function() {
        if (!currentRoomId) return;

        this.disabled = true;
        this.innerHTML = `
            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Đang lưu...</span>
        `;

        fetch(`/admin/rooms/${currentRoomId}/layout`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                total_rows: gridMatrix.length,
                total_columns: gridMatrix[0] ? gridMatrix[0].length : 16,
                grid: gridMatrix
            })
        })
        .then(res => res.json())
        .then(data => {
            saveLayoutBtn.disabled = false;
            saveLayoutBtn.innerHTML = `
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Lưu bố cục (${data.capacity || 0} ghế)</span>
            `;

            if (data.success) {
                alert(data.message || 'Đã lưu bố cục ghế thành công!');
                // Update badge in room card
                const capBadge = document.getElementById(`room-cap-badge-${currentRoomId}`);
                if (capBadge) capBadge.textContent = data.capacity;
                closeSeatDesigner();
            } else {
                alert(data.message || 'Lỗi khi lưu sơ đồ ghế.');
            }
        })
        .catch(err => {
            saveLayoutBtn.disabled = false;
            saveLayoutBtn.innerHTML = `<span>Lưu lại</span>`;
            console.error(err);
            alert('Có lỗi xảy ra trong quá trình lưu bố cục.');
        });
    });
}
</script>
@endsection
