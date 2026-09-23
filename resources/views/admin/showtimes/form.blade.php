@extends('admin.layouts.app')

@section('title', isset($showtime) ? 'Sửa Lịch Chiếu - Admin HCTV' : 'Thêm Lịch Chiếu - Admin HCTV')

@section('content')
<div class="mb-6 flex items-center gap-4">
    <a href="{{ route('admin.showtimes.index') }}" class="p-2 bg-white rounded-full shadow-sm hover:bg-gray-50 text-gray-500 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-gray-900">{{ isset($showtime) ? 'Sửa Lịch Chiếu' : 'Thêm Lịch Chiếu Mới' }}</h1>
        <p class="text-xs text-gray-500 mt-0.5">Chọn rạp và tích chọn phòng chiếu để sắp xếp suất chiếu</p>
    </div>
</div>

@php
    $selectedRoomId = old('room_id', $showtime->room_id ?? '');
    $selectedCinemaId = '';
    if ($selectedRoomId) {
        $foundRoom = $rooms->firstWhere('id', $selectedRoomId);
        if ($foundRoom) {
            $selectedCinemaId = $foundRoom->cinema_id;
        }
    }

    $roomsJson = $rooms->map(function($r) {
        return [
            'id' => $r->id,
            'cinema_id' => $r->cinema_id,
            'cinema_name' => $r->cinema->name ?? '',
            'name' => $r->name,
            'capacity' => $r->capacity ?? 0,
            'total_rows' => $r->total_rows ?? 10,
            'total_columns' => $r->total_columns ?? 16,
        ];
    })->values();

    $cinemasJson = $cinemas->map(function($c) {
        return [
            'id' => $c->id,
            'name' => $c->name,
            'city' => $c->city ?? '',
            'location' => $c->location ?? '',
            'rooms_count' => $c->rooms->count(),
        ];
    })->values();
@endphp

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <form action="{{ isset($showtime) ? route('admin.showtimes.update', $showtime->id) : route('admin.showtimes.store') }}" method="POST">
        @csrf
        @if(isset($showtime))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- 1. Chọn Phim -->
            <div class="col-span-1 md:col-span-2">
                <label class="block text-sm font-bold text-gray-700 mb-1">Phim <span class="text-red-500">*</span></label>
                <select name="movie_id" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-gray-900 bg-white text-sm">
                    <option value="">-- Chọn Phim --</option>
                    @foreach($movies as $movie)
                        <option value="{{ $movie->id }}" {{ old('movie_id', $showtime->movie_id ?? '') == $movie->id ? 'selected' : '' }}>
                            {{ $movie->title }} ({{ $movie->duration }} phút)
                        </option>
                    @endforeach
                </select>
                @error('movie_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- 2. Chọn Rạp Chiếu -->
            <div class="col-span-1">
                <label for="cinema_select" class="block text-sm font-bold text-gray-700 mb-1">
                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-purple-100 text-purple-700 text-xs mr-1 font-bold">1</span>
                    Chọn Rạp Chiếu <span class="text-red-500">*</span>
                </label>
                <select id="cinema_select" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-purple-600 bg-white text-sm font-medium">
                    <option value="">-- Chọn Rạp Chiếu --</option>
                    @foreach($cinemas as $cinema)
                        <option value="{{ $cinema->id }}" {{ (string)$selectedCinemaId === (string)$cinema->id ? 'selected' : '' }}>
                            {{ $cinema->name }} ({{ $cinema->rooms->count() }} phòng)
                        </option>
                    @endforeach
                </select>
                <p id="cinema-address-hint" class="text-xs text-gray-500 mt-1 min-h-[1.2rem]"></p>
            </div>

            <!-- 3. Chọn Phòng Chiếu (Dropdown) -->
            <div class="col-span-1">
                <label for="room_select" class="block text-sm font-bold text-gray-700 mb-1">
                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-purple-100 text-purple-700 text-xs mr-1 font-bold">2</span>
                    Chọn Phòng Chiếu <span class="text-red-500">*</span>
                </label>
                <select name="room_id" id="room_select" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-purple-600 bg-white text-sm font-medium">
                    <option value="">-- Vui lòng chọn Rạp trước --</option>
                </select>
                @error('room_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- 4. Danh sách thẻ phòng chiếu trực quan ("Tích chọn") -->
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center justify-between mb-2.5">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        <span>Hoặc nhấp tích chọn phòng chiếu dưới đây:</span>
                    </span>
                    <span id="room-count-badge" class="text-xs text-purple-700 font-semibold bg-purple-50 px-2.5 py-0.5 rounded-full border border-purple-200 hidden"></span>
                </div>

                <!-- State: Chưa chọn rạp -->
                <div id="state-no-cinema" class="p-6 bg-gray-50 border border-dashed border-gray-300 rounded-xl text-center text-sm text-gray-500">
                    <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span>Vui lòng chọn <strong>Rạp Chiếu</strong> ở bước 1 để hiển thị các phòng chiếu khả dụng.</span>
                </div>

                <!-- State: Rạp không có phòng -->
                <div id="state-cinema-empty" class="hidden p-6 bg-amber-50 border border-amber-200 rounded-xl text-center text-sm text-amber-800">
                    <p class="font-medium">Rạp này hiện chưa có phòng chiếu nào!</p>
                    <a href="{{ route('admin.cinemas.index') }}" target="_blank" class="inline-flex items-center gap-1 mt-2 text-xs font-bold text-purple-700 hover:underline">
                        <span>Đi đến Quản lý Rạp & Phòng Chiếu để tạo phòng mới</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                </div>

                <!-- State: Danh sách các thẻ phòng để tích chọn -->
                <div id="room-cards-container" class="hidden grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                    <!-- Populated by JavaScript -->
                </div>
            </div>

            <!-- 5. Thời Gian Bắt Đầu -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Thời Gian Bắt Đầu <span class="text-red-500">*</span></label>
                <input type="datetime-local" name="start_time" value="{{ old('start_time', isset($showtime) ? \Carbon\Carbon::parse($showtime->start_time)->format('Y-m-d\TH:i') : '') }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-gray-900 text-sm">
                @error('start_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- 6. Giá Vé -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Giá Vé Tiêu Chuẩn (VNĐ) <span class="text-red-500">*</span></label>
                <input type="number" name="price" value="{{ old('price', $showtime->price ?? 100000) }}" required min="0" step="1000" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-gray-900 text-sm">
                @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-gray-100">
            <a href="{{ route('admin.showtimes.index') }}" class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors text-sm">Hủy</a>
            <button type="submit" class="px-6 py-2.5 bg-gray-900 text-white rounded-lg font-medium hover:bg-gray-800 transition-colors text-sm shadow">
                {{ isset($showtime) ? 'Lưu Thay Đổi' : 'Tạo Lịch Chiếu' }}
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const allRooms = @json($roomsJson);
    const allCinemas = @json($cinemasJson);
    let initialRoomId = '{{ $selectedRoomId }}';
    let initialCinemaId = '{{ $selectedCinemaId }}';

    const cinemaSelect = document.getElementById('cinema_select');
    const roomSelect = document.getElementById('room_select');
    const cinemaAddressHint = document.getElementById('cinema-address-hint');
    const roomCountBadge = document.getElementById('room-count-badge');
    const stateNoCinema = document.getElementById('state-no-cinema');
    const stateCinemaEmpty = document.getElementById('state-cinema-empty');
    const roomCardsContainer = document.getElementById('room-cards-container');

    function onCinemaChange(cinemaId, preselectedRoomId = null) {
        if (!cinemaId) {
            cinemaAddressHint.textContent = '';
            roomSelect.innerHTML = '<option value="">-- Vui lòng chọn Rạp trước --</option>';
            roomSelect.disabled = true;
            stateNoCinema.classList.remove('hidden');
            stateCinemaEmpty.classList.add('hidden');
            roomCardsContainer.classList.add('hidden');
            roomCountBadge.classList.add('hidden');
            return;
        }

        const cinema = allCinemas.find(c => String(c.id) === String(cinemaId));
        if (cinema && cinema.location) {
            cinemaAddressHint.textContent = `📍 ${cinema.location}`;
        } else {
            cinemaAddressHint.textContent = '';
        }

        const roomsOfCinema = allRooms.filter(r => String(r.cinema_id) === String(cinemaId));
        roomSelect.disabled = false;

        if (roomsOfCinema.length === 0) {
            roomSelect.innerHTML = '<option value="">-- Rạp này chưa có phòng chiếu --</option>';
            stateNoCinema.classList.add('hidden');
            stateCinemaEmpty.classList.remove('hidden');
            roomCardsContainer.classList.add('hidden');
            roomCountBadge.classList.add('hidden');
            return;
        }

        stateNoCinema.classList.add('hidden');
        stateCinemaEmpty.classList.add('hidden');
        roomCardsContainer.classList.remove('hidden');
        roomCountBadge.classList.remove('hidden');
        roomCountBadge.textContent = `${roomsOfCinema.length} phòng`;

        // Populate dropdown
        let selectHtml = '<option value="">-- Chọn Phòng Chiếu --</option>';
        roomsOfCinema.forEach(r => {
            const isSelected = String(r.id) === String(preselectedRoomId);
            selectHtml += `<option value="${r.id}" ${isSelected ? 'selected' : ''}>
                ${r.name} (${r.capacity} ghế - ${r.total_rows}x${r.total_columns})
            </option>`;
        });
        roomSelect.innerHTML = selectHtml;

        // Render Room Cards
        renderRoomCards(roomsOfCinema, preselectedRoomId);
    }

    function renderRoomCards(rooms, selectedId) {
        roomCardsContainer.innerHTML = '';
        rooms.forEach(r => {
            const isSelected = String(r.id) === String(selectedId);
            const card = document.createElement('div');
            card.dataset.roomId = r.id;
            card.className = `p-4 rounded-xl border-2 cursor-pointer transition-all duration-150 relative select-none flex flex-col justify-between ${
                isSelected 
                    ? 'border-purple-600 bg-purple-50/70 shadow-md ring-2 ring-purple-500/20' 
                    : 'border-gray-200 bg-white hover:border-gray-300 hover:bg-gray-50/80 shadow-sm'
            }`;

            card.innerHTML = `
                <div class="flex items-start justify-between gap-2 mb-2">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 ${
                            isSelected ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-600'
                        }">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-gray-900 leading-tight">${r.name}</h4>
                            <span class="text-[11px] text-gray-500">${r.total_rows} hàng × ${r.total_columns} cột</span>
                        </div>
                    </div>
                    <div class="w-5 h-5 rounded-full border flex items-center justify-center shrink-0 transition ${
                        isSelected ? 'border-purple-600 bg-purple-600 text-white' : 'border-gray-300 bg-white'
                    }">
                        <svg class="w-3 h-3 ${isSelected ? '' : 'hidden'}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2 border-t border-gray-100 mt-1">
                    <span class="text-xs text-gray-500">Sức chứa:</span>
                    <span class="px-2 py-0.5 rounded text-xs font-bold ${
                        isSelected ? 'bg-purple-200 text-purple-900' : 'bg-gray-100 text-gray-700'
                    }">${r.capacity} ghế</span>
                </div>
            `;

            // Card click listener
            card.addEventListener('click', function() {
                selectRoom(r.id);
            });

            roomCardsContainer.appendChild(card);
        });
    }

    function selectRoom(roomId) {
        roomSelect.value = roomId;

        // Update card highlights
        document.querySelectorAll('#room-cards-container > div').forEach(card => {
            const isTarget = String(card.dataset.roomId) === String(roomId);
            if (isTarget) {
                card.className = 'p-4 rounded-xl border-2 cursor-pointer transition-all duration-150 relative select-none flex flex-col justify-between border-purple-600 bg-purple-50/70 shadow-md ring-2 ring-purple-500/20';
                card.querySelector('.w-8.h-8').className = 'w-8 h-8 rounded-lg flex items-center justify-center shrink-0 bg-purple-600 text-white';
                card.querySelector('.w-5.h-5').className = 'w-5 h-5 rounded-full border flex items-center justify-center shrink-0 transition border-purple-600 bg-purple-600 text-white';
                card.querySelector('.w-5.h-5 svg').classList.remove('hidden');
            } else {
                card.className = 'p-4 rounded-xl border-2 cursor-pointer transition-all duration-150 relative select-none flex flex-col justify-between border-gray-200 bg-white hover:border-gray-300 hover:bg-gray-50/80 shadow-sm';
                card.querySelector('.w-8.h-8').className = 'w-8 h-8 rounded-lg flex items-center justify-center shrink-0 bg-gray-100 text-gray-600';
                card.querySelector('.w-5.h-5').className = 'w-5 h-5 rounded-full border flex items-center justify-center shrink-0 transition border-gray-300 bg-white';
                card.querySelector('.w-5.h-5 svg').classList.add('hidden');
            }
        });
    }

    // Dropdown change listener
    roomSelect.addEventListener('change', function() {
        selectRoom(this.value);
    });

    cinemaSelect.addEventListener('change', function() {
        onCinemaChange(this.value);
    });

    // Initialize state
    if (initialCinemaId) {
        cinemaSelect.value = initialCinemaId;
        onCinemaChange(initialCinemaId, initialRoomId);
    } else {
        onCinemaChange(null);
    }
});
</script>
@endsection

