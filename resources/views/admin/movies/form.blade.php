@extends('admin.layouts.app')

@section('title', isset($movie) ? 'Sửa Phim - Admin HCTV' : 'Thêm Phim - Admin HCTV')

@section('content')
<div class="mb-6 flex items-center gap-4">
    <a href="{{ route('admin.movies.index') }}" class="p-2 bg-white rounded-full shadow-sm hover:bg-gray-50 text-gray-500 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-gray-900">{{ isset($movie) ? 'Sửa Phim' : 'Thêm Phim Mới' }}</h1>
        <p class="text-sm text-gray-500">Cập nhật thông tin chi tiết, hình ảnh, thể loại và danh sách diễn viên</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8">
    <form action="{{ isset($movie) ? route('admin.movies.update', $movie->id) : route('admin.movies.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($movie))
            @method('PUT')
        @endif

        <div class="space-y-8">
            <!-- PHẦN 1: THÔNG TIN CƠ BẢN -->
            <div>
                <h3 class="text-base font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100 uppercase tracking-wider flex items-center gap-2">
                    <span class="w-2 h-5 bg-red-600 rounded"></span>
                    Thông Tin Cơ Bản
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tên Phim <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $movie->title ?? '') }}" required placeholder="Nhập tên phim đầy đủ" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition">
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Thời Lượng (Phút) <span class="text-red-500">*</span></label>
                        <input type="number" name="duration" value="{{ old('duration', $movie->duration ?? '') }}" required min="1" placeholder="Ví dụ: 120" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition">
                        @error('duration') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ngày Khởi Chiếu <span class="text-red-500">*</span></label>
                        <input type="date" name="release_date" value="{{ old('release_date', isset($movie->release_date) ? \Carbon\Carbon::parse($movie->release_date)->format('Y-m-d') : '') }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition">
                        @error('release_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- THỂ LOẠI (LẤY TỪ DATABASE + CHECKBOXES + THÊM/SỬA/XÓA) -->
                    <div class="col-span-1 md:col-span-2">
                        <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Thể Loại Phim <span class="text-red-500">*</span> 
                                <span class="text-xs text-gray-400 font-normal ml-2">(Tích chọn các thể loại phù hợp với bộ phim)</span>
                            </label>
                            <a href="{{ route('admin.genres.index') }}" target="_blank" class="text-xs font-semibold text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 border border-blue-200 px-3 py-1 rounded-lg transition-all inline-flex items-center gap-1.5 shadow-2xs">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                <span>Quản Lý Thể Loại (Thêm / Sửa / Xóa)</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                        </div>

                        @php
                            $availableGenres = isset($genres) && $genres->count() > 0 
                                ? $genres->pluck('name')->toArray() 
                                : [
                                    'Hành động', 'Hài hước', 'Hoạt hình', 'Viễn tưởng', 'Phiêu lưu',
                                    'Kinh dị', 'Tình cảm', 'Tâm lý', 'Giật gân', 'Gia đình',
                                    'Võ thuật', 'Âm nhạc', 'Tội phạm', 'Bí ẩn', 'Tài liệu'
                                ];

                            // Determine existing selected genres
                            $currentGenreString = old('genre', $movie->genre ?? '');
                            $selectedGenres = [];
                            $customGenreVal = old('custom_genre', '');

                            if ($currentGenreString) {
                                $parts = preg_split('/[,\\/]+/', $currentGenreString);
                                foreach ($parts as $p) {
                                    $pTrim = trim($p);
                                    if (empty($pTrim)) continue;

                                    $matched = false;
                                    foreach ($availableGenres as $avail) {
                                        if (mb_strtolower($pTrim) === mb_strtolower($avail)) {
                                            $selectedGenres[] = $avail;
                                            $matched = true;
                                            break;
                                        }
                                    }
                                    if (!$matched && !in_array($pTrim, $selectedGenres)) {
                                        $customGenreVal = $customGenreVal ? $customGenreVal . ', ' . $pTrim : $pTrim;
                                    }
                                }
                            }
                            if (old('genres')) {
                                $selectedGenres = old('genres');
                            }
                        @endphp

                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3 p-4 bg-gray-50 border border-gray-200 rounded-xl max-h-64 overflow-y-auto">
                            @foreach($availableGenres as $genreItem)
                                <label class="flex items-center gap-2.5 p-2.5 rounded-lg border cursor-pointer select-none transition-all {{ in_array($genreItem, $selectedGenres) ? 'bg-red-50 border-red-300 text-red-900 font-semibold' : 'bg-white border-gray-200 text-gray-700 hover:bg-gray-100' }}">
                                    <input type="checkbox" name="genres[]" value="{{ $genreItem }}" 
                                        {{ in_array($genreItem, $selectedGenres) ? 'checked' : '' }}
                                        class="w-4 h-4 text-red-600 rounded border-gray-300 focus:ring-red-500">
                                    <span class="text-sm">{{ $genreItem }}</span>
                                </label>
                            @endforeach
                        </div>

                        <div class="mt-3">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Thể loại khác (Nếu có, nhập cách nhau bởi dấu phẩy):</label>
                            <input type="text" name="custom_genre" value="{{ $customGenreVal }}" placeholder="Ví dụ: Chiến tranh, Cổ trang..." class="w-full px-3.5 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gray-900 focus:border-gray-900">
                        </div>
                        @error('genres') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- MÔ TẢ -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mô Tả Nội Dung Phim <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="4" required placeholder="Tóm tắt nội dung cốt truyện của bộ phim..." class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition">{{ old('description', $movie->description ?? '') }}</textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- PHẦN 2: HÌNH ẢNH & MEDIA (POSTER, BACKDROP, TRAILER) -->
            <div>
                <h3 class="text-base font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100 uppercase tracking-wider flex items-center gap-2">
                    <span class="w-2 h-5 bg-red-600 rounded"></span>
                    Hình Ảnh & Media
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- POSTER (DỌC) -->
                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                        <div class="flex items-center justify-between mb-3">
                            <label class="text-sm font-bold text-gray-900">
                                Poster (Ảnh Dọc) <span class="text-red-500">*</span>
                            </label>
                            <span class="text-xs text-gray-400">Khuyến nghị tỉ lệ 2:3 (VD: 500x750)</span>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4 items-start">
                            <!-- Preview Box / Dropzone -->
                            <div id="poster-dropzone" class="w-32 h-44 shrink-0 rounded-lg overflow-hidden bg-gray-100 border-2 border-dashed border-gray-300 hover:border-gray-800 flex items-center justify-center relative shadow-sm cursor-pointer transition group" title="Bấm để chọn file hoặc kéo thả ảnh vào đây">
                                <img id="poster-preview" 
                                     src="{{ old('poster_url', $movie->poster_url ?? '') }}" 
                                     alt="Preview Poster" 
                                     class="w-full h-full object-cover {{ empty(old('poster_url', $movie->poster_url ?? '')) ? 'hidden' : '' }}">
                                <div id="poster-placeholder" class="text-center p-2 {{ !empty(old('poster_url', $movie->poster_url ?? '')) ? 'hidden' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto text-gray-400 group-hover:text-gray-700 transition-colors mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-[11px] text-gray-500 font-medium block leading-tight">Bấm hoặc Kéo thả ảnh vào đây</span>
                                </div>
                            </div>

                            <!-- Input Options -->
                            <div class="flex-1 space-y-3 w-full">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">
                                        📁 Chọn ảnh từ máy tính (hoặc kéo thả vào ô bên cạnh):
                                    </label>
                                    <input type="file" name="poster_file" id="poster_file"
                                        class="block w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gray-900 file:text-white hover:file:bg-gray-800 cursor-pointer border border-gray-300 rounded-lg p-1 bg-white">
                                    <p id="poster-file-status" class="text-xs text-green-700 font-medium mt-1 hidden"></p>
                                    @error('poster_file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div class="relative flex py-1 items-center">
                                    <div class="flex-grow border-t border-gray-200"></div>
                                    <span class="flex-shrink mx-2 text-gray-400 text-[11px] uppercase">HOẶC DÁN LINK</span>
                                    <div class="flex-grow border-t border-gray-200"></div>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">
                                        🔗 Link ảnh URL:
                                    </label>
                                    <input type="text" name="poster_url" id="poster_url" 
                                        value="{{ old('poster_url', $movie->poster_url ?? '') }}" 
                                        placeholder="https://image.tmdb.org/t/p/w500/..."
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-gray-900 focus:border-gray-900">
                                    @error('poster_url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- BACKDROP (NGANG) -->
                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                        <div class="flex items-center justify-between mb-3">
                            <label class="text-sm font-bold text-gray-900">
                                Backdrop (Ảnh Ngang) <span class="text-red-500">*</span>
                            </label>
                            <span class="text-xs text-gray-400">Khuyến nghị tỉ lệ 16:9 (VD: 1280x720)</span>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4 items-start">
                            <!-- Preview Box / Dropzone -->
                            <div id="backdrop-dropzone" class="w-48 h-28 shrink-0 rounded-lg overflow-hidden bg-gray-100 border-2 border-dashed border-gray-300 hover:border-gray-800 flex items-center justify-center relative shadow-sm cursor-pointer transition group" title="Bấm để chọn file hoặc kéo thả ảnh vào đây">
                                <img id="backdrop-preview" 
                                     src="{{ old('backdrop_url', $movie->backdrop_url ?? '') }}" 
                                     alt="Preview Backdrop" 
                                     class="w-full h-full object-cover {{ empty(old('backdrop_url', $movie->backdrop_url ?? '')) ? 'hidden' : '' }}">
                                <div id="backdrop-placeholder" class="text-center p-2 {{ !empty(old('backdrop_url', $movie->backdrop_url ?? '')) ? 'hidden' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto text-gray-400 group-hover:text-gray-700 transition-colors mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-[11px] text-gray-500 font-medium block leading-tight">Bấm hoặc Kéo thả ảnh vào đây</span>
                                </div>
                            </div>

                            <!-- Input Options -->
                            <div class="flex-1 space-y-3 w-full">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">
                                        📁 Chọn ảnh từ máy tính (hoặc kéo thả vào ô bên cạnh):
                                    </label>
                                    <input type="file" name="backdrop_file" id="backdrop_file"
                                        class="block w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gray-900 file:text-white hover:file:bg-gray-800 cursor-pointer border border-gray-300 rounded-lg p-1 bg-white">
                                    <p id="backdrop-file-status" class="text-xs text-green-700 font-medium mt-1 hidden"></p>
                                    @error('backdrop_file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div class="relative flex py-1 items-center">
                                    <div class="flex-grow border-t border-gray-200"></div>
                                    <span class="flex-shrink mx-2 text-gray-400 text-[11px] uppercase">HOẶC DÁN LINK</span>
                                    <div class="flex-grow border-t border-gray-200"></div>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">
                                        🔗 Link ảnh URL:
                                    </label>
                                    <input type="text" name="backdrop_url" id="backdrop_url" 
                                        value="{{ old('backdrop_url', $movie->backdrop_url ?? '') }}" 
                                        placeholder="https://image.tmdb.org/t/p/w1280/..."
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-gray-900 focus:border-gray-900">
                                    @error('backdrop_url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TRAILER URL -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Link Trailer Youtube <span class="text-red-500">*</span></label>
                        <input type="text" name="trailer_url" value="{{ old('trailer_url', $movie->trailer_url ?? '') }}" required placeholder="https://www.youtube.com/watch?v=..." class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition">
                        @error('trailer_url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- PHẦN 3: DANH SÁCH DIỄN VIÊN & VAI DIỄN -->
            <div>
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4 pb-2 border-b border-gray-100">
                    <div>
                        <h3 class="text-base font-bold text-gray-900 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-2 h-5 bg-red-600 rounded"></span>
                            Danh Sách Diễn Viên & Nhân Vật
                        </h3>
                        <p class="text-xs text-gray-500 mt-1">Thêm diễn viên để khách hàng có thể bấm vào xem thông tin và hình ảnh chi tiết</p>
                    </div>
                    <button type="button" id="btn-add-actor" class="px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white rounded-lg text-sm font-medium transition flex items-center gap-2 shadow-sm self-start sm:self-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Thêm Diễn Viên
                    </button>
                </div>

                <div id="actors-list" class="space-y-4">
                    @php
                        $existingActors = isset($movie) ? $movie->actors : collect();
                    @endphp

                    @forelse($existingActors as $index => $actor)
                        <div class="actor-card bg-gray-50 border border-gray-200 rounded-xl p-5 relative transition shadow-sm hover:border-gray-300" data-index="{{ $index }}">
                            <input type="hidden" name="actors[{{ $index }}][id]" value="{{ $actor->id }}">
                            <button type="button" class="btn-remove-actor absolute top-4 right-4 text-gray-400 hover:text-red-500 transition p-1.5 rounded-lg hover:bg-red-50" title="Xóa diễn viên">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>

                            <div class="grid grid-cols-1 md:grid-cols-12 gap-5 items-start">
                                <!-- Avatar Preview & File / URL -->
                                <div class="md:col-span-4 flex gap-4 items-center">
                                    <div class="w-20 h-20 shrink-0 rounded-full overflow-hidden bg-gray-200 border-2 border-gray-300 shadow-sm relative flex items-center justify-center">
                                        <img src="{{ $actor->avatar_url }}" alt="{{ $actor->name }}" class="actor-avatar-preview w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1 space-y-2">
                                        <div>
                                            <label class="block text-[11px] font-semibold text-gray-600 mb-0.5">📁 Tải ảnh từ máy tính:</label>
                                            <input type="file" name="actors[{{ $index }}][avatar_file]"
                                                class="actor-file-input block w-full text-[11px] text-gray-500 file:mr-2 file:py-1 file:px-2.5 file:rounded file:border-0 file:text-[11px] file:font-medium file:bg-gray-800 file:text-white hover:file:bg-gray-700 cursor-pointer border border-gray-300 rounded p-1 bg-white">
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-semibold text-gray-600 mb-0.5">🔗 Hoặc link URL:</label>
                                            <input type="text" name="actors[{{ $index }}][avatar_url]" value="{{ $actor->avatar }}" placeholder="https://..."
                                                class="actor-url-input w-full px-2.5 py-1 border border-gray-300 rounded text-xs focus:ring-1 focus:ring-gray-900 focus:border-gray-900">
                                        </div>
                                    </div>
                                </div>

                                <!-- Actor Details -->
                                <div class="md:col-span-8 space-y-3">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-700 mb-1">Tên Diễn Viên <span class="text-red-500">*</span></label>
                                            <input type="text" name="actors[{{ $index }}][name]" value="{{ $actor->name }}" required placeholder="Ví dụ: Timothée Chalamet"
                                                class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gray-900 focus:border-gray-900">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-700 mb-1">Vai Diễn trong phim</label>
                                            <input type="text" name="actors[{{ $index }}][role]" value="{{ $actor->role }}" placeholder="Ví dụ: Paul Atreides"
                                                class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gray-900 focus:border-gray-900">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">Tiểu Sử / Giới Thiệu (Khách hàng click để đọc)</label>
                                        <textarea name="actors[{{ $index }}][bio]" rows="2" placeholder="Tóm tắt về diễn viên, sự nghiệp, giải thưởng hoặc thông tin nhân vật..."
                                            class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-gray-900 focus:border-gray-900">{{ $actor->bio }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div id="no-actors-placeholder" class="text-center py-8 bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <p class="text-sm font-medium text-gray-600">Chưa có diễn viên nào</p>
                            <p class="text-xs text-gray-400 mt-1">Bấm nút "Thêm Diễn Viên" ở trên để bổ sung thông tin diễn viên cho phim</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-4 mt-10 pt-6 border-t border-gray-100">
            <a href="{{ route('admin.movies.index') }}" class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">Hủy</a>
            <button type="submit" class="px-8 py-2.5 bg-gray-900 text-white rounded-lg font-bold hover:bg-gray-800 transition-colors shadow-sm">
                {{ isset($movie) ? 'Lưu Thay Đổi' : 'Thêm Phim Mới' }}
            </button>
        </div>
    </form>
</div>

<!-- TEMPLATE CHO DIỄN VIÊN MỚI (JAVASCRIPT) -->
<template id="actor-template">
    <div class="actor-card bg-gray-50 border border-gray-200 rounded-xl p-5 relative transition shadow-sm hover:border-gray-300" data-index="__INDEX__">
        <button type="button" class="btn-remove-actor absolute top-4 right-4 text-gray-400 hover:text-red-500 transition p-1.5 rounded-lg hover:bg-red-50" title="Xóa diễn viên">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </button>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-5 items-start">
            <div class="md:col-span-4 flex gap-4 items-center">
                <div class="w-20 h-20 shrink-0 rounded-full overflow-hidden bg-gray-200 border-2 border-gray-300 shadow-sm relative flex items-center justify-center">
                    <img src="https://ui-avatars.com/api/?name=Actor&background=1f2937&color=f59e0b&size=200" alt="Preview" class="actor-avatar-preview w-full h-full object-cover">
                </div>
                <div class="flex-1 space-y-2">
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-600 mb-0.5">📁 Tải ảnh từ máy tính:</label>
                        <input type="file" name="actors[__INDEX__][avatar_file]"
                            class="actor-file-input block w-full text-[11px] text-gray-500 file:mr-2 file:py-1 file:px-2.5 file:rounded file:border-0 file:text-[11px] file:font-medium file:bg-gray-800 file:text-white hover:file:bg-gray-700 cursor-pointer border border-gray-300 rounded p-1 bg-white">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-600 mb-0.5">🔗 Hoặc link URL:</label>
                        <input type="text" name="actors[__INDEX__][avatar_url]" placeholder="https://..."
                            class="actor-url-input w-full px-2.5 py-1 border border-gray-300 rounded text-xs focus:ring-1 focus:ring-gray-900 focus:border-gray-900">
                    </div>
                </div>
            </div>

            <div class="md:col-span-8 space-y-3">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Tên Diễn Viên <span class="text-red-500">*</span></label>
                        <input type="text" name="actors[__INDEX__][name]" required placeholder="Ví dụ: Timothée Chalamet"
                            class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gray-900 focus:border-gray-900">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Vai Diễn trong phim</label>
                        <input type="text" name="actors[__INDEX__][role]" placeholder="Ví dụ: Paul Atreides"
                            class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gray-900 focus:border-gray-900">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tiểu Sử / Giới Thiệu (Khách hàng click để đọc)</label>
                    <textarea name="actors[__INDEX__][bio]" rows="2" placeholder="Tóm tắt về diễn viên, sự nghiệp, giải thưởng hoặc thông tin nhân vật..."
                        class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-gray-900 focus:border-gray-900"></textarea>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Live preview & Dropzone for Poster
    const posterFileInput = document.getElementById('poster_file');
    const posterUrlInput = document.getElementById('poster_url');
    const posterPreview = document.getElementById('poster-preview');
    const posterPlaceholder = document.getElementById('poster-placeholder');
    const posterDropzone = document.getElementById('poster-dropzone');
    const posterFileStatus = document.getElementById('poster-file-status');

    function updatePosterPreview(src) {
        if (src) {
            posterPreview.src = src;
            posterPreview.classList.remove('hidden');
            posterPlaceholder.classList.add('hidden');
        } else {
            posterPreview.classList.add('hidden');
            posterPlaceholder.classList.remove('hidden');
        }
    }

    function setPosterFile(file) {
        if (!file) return;
        const objectUrl = URL.createObjectURL(file);
        updatePosterPreview(objectUrl);
        if (posterFileStatus) {
            posterFileStatus.textContent = '✓ Đã chọn ảnh: ' + file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
            posterFileStatus.classList.remove('hidden');
        }
    }

    if (posterFileInput) {
        posterFileInput.addEventListener('change', function (e) {
            if (e.target.files && e.target.files[0]) {
                setPosterFile(e.target.files[0]);
            }
        });
    }

    if (posterUrlInput) {
        posterUrlInput.addEventListener('input', function (e) {
            if (e.target.value.trim() !== '') {
                updatePosterPreview(e.target.value.trim());
            }
        });
    }

    if (posterDropzone && posterFileInput) {
        posterDropzone.addEventListener('click', function (e) {
            if (e.target !== posterFileInput) {
                posterFileInput.click();
            }
        });

        ['dragenter', 'dragover'].forEach(name => {
            posterDropzone.addEventListener(name, function (e) {
                e.preventDefault();
                e.stopPropagation();
                posterDropzone.classList.add('border-gray-900', 'bg-gray-200');
            });
        });

        ['dragleave', 'drop'].forEach(name => {
            posterDropzone.addEventListener(name, function (e) {
                e.preventDefault();
                e.stopPropagation();
                posterDropzone.classList.remove('border-gray-900', 'bg-gray-200');
            });
        });

        posterDropzone.addEventListener('drop', function (e) {
            if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files[0]) {
                posterFileInput.files = e.dataTransfer.files;
                setPosterFile(e.dataTransfer.files[0]);
            }
        });
    }

    // 2. Live preview & Dropzone for Backdrop
    const backdropFileInput = document.getElementById('backdrop_file');
    const backdropUrlInput = document.getElementById('backdrop_url');
    const backdropPreview = document.getElementById('backdrop-preview');
    const backdropPlaceholder = document.getElementById('backdrop-placeholder');
    const backdropDropzone = document.getElementById('backdrop-dropzone');
    const backdropFileStatus = document.getElementById('backdrop-file-status');

    function updateBackdropPreview(src) {
        if (src) {
            backdropPreview.src = src;
            backdropPreview.classList.remove('hidden');
            backdropPlaceholder.classList.add('hidden');
        } else {
            backdropPreview.classList.add('hidden');
            backdropPlaceholder.classList.remove('hidden');
        }
    }

    function setBackdropFile(file) {
        if (!file) return;
        const objectUrl = URL.createObjectURL(file);
        updateBackdropPreview(objectUrl);
        if (backdropFileStatus) {
            backdropFileStatus.textContent = '✓ Đã chọn ảnh: ' + file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
            backdropFileStatus.classList.remove('hidden');
        }
    }

    if (backdropFileInput) {
        backdropFileInput.addEventListener('change', function (e) {
            if (e.target.files && e.target.files[0]) {
                setBackdropFile(e.target.files[0]);
            }
        });
    }

    if (backdropUrlInput) {
        backdropUrlInput.addEventListener('input', function (e) {
            if (e.target.value.trim() !== '') {
                updateBackdropPreview(e.target.value.trim());
            }
        });
    }

    if (backdropDropzone && backdropFileInput) {
        backdropDropzone.addEventListener('click', function (e) {
            if (e.target !== backdropFileInput) {
                backdropFileInput.click();
            }
        });

        ['dragenter', 'dragover'].forEach(name => {
            backdropDropzone.addEventListener(name, function (e) {
                e.preventDefault();
                e.stopPropagation();
                backdropDropzone.classList.add('border-gray-900', 'bg-gray-200');
            });
        });

        ['dragleave', 'drop'].forEach(name => {
            backdropDropzone.addEventListener(name, function (e) {
                e.preventDefault();
                e.stopPropagation();
                backdropDropzone.classList.remove('border-gray-900', 'bg-gray-200');
            });
        });

        backdropDropzone.addEventListener('drop', function (e) {
            if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files[0]) {
                backdropFileInput.files = e.dataTransfer.files;
                setBackdropFile(e.dataTransfer.files[0]);
            }
        });
    }

    // 2.1. Paste from clipboard (Ctrl + V) support
    window.addEventListener('paste', function (e) {
        if (e.clipboardData && e.clipboardData.items) {
            for (let i = 0; i < e.clipboardData.items.length; i++) {
                const item = e.clipboardData.items[i];
                if (item.type.indexOf('image') !== -1) {
                    const blob = item.getAsFile();
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(blob);
                    
                    // Default to poster if empty, else backdrop
                    if (posterFileInput && (!posterFileInput.files || posterFileInput.files.length === 0)) {
                        posterFileInput.files = dataTransfer.files;
                        setPosterFile(blob);
                    } else if (backdropFileInput) {
                        backdropFileInput.files = dataTransfer.files;
                        setBackdropFile(blob);
                    }
                    break;
                }
            }
        }
    });

    // 3. Dynamic Actors List
    const actorsContainer = document.getElementById('actors-list');
    const btnAddActor = document.getElementById('btn-add-actor');
    const actorTemplate = document.getElementById('actor-template');
    const noActorsPlaceholder = document.getElementById('no-actors-placeholder');

    let actorIndex = {{ $existingActors->count() > 0 ? $existingActors->count() : 0 }};

    function attachActorListeners(card) {
        const removeBtn = card.querySelector('.btn-remove-actor');
        if (removeBtn) {
            removeBtn.addEventListener('click', function () {
                card.remove();
                if (actorsContainer.querySelectorAll('.actor-card').length === 0 && noActorsPlaceholder) {
                    noActorsPlaceholder.classList.remove('hidden');
                }
            });
        }

        const fileInput = card.querySelector('.actor-file-input');
        const urlInput = card.querySelector('.actor-url-input');
        const previewImg = card.querySelector('.actor-avatar-preview');

        if (fileInput && previewImg) {
            fileInput.addEventListener('change', function (e) {
                if (e.target.files && e.target.files[0]) {
                    previewImg.src = URL.createObjectURL(e.target.files[0]);
                }
            });
        }

        if (urlInput && previewImg) {
            urlInput.addEventListener('input', function (e) {
                if (e.target.value.trim()) {
                    previewImg.src = e.target.value.trim();
                }
            });
        }
    }

    // Attach listeners to existing actor cards
    document.querySelectorAll('.actor-card').forEach(attachActorListeners);

    // Add new actor
    if (btnAddActor) {
        btnAddActor.addEventListener('click', function () {
            if (noActorsPlaceholder) {
                noActorsPlaceholder.classList.add('hidden');
            }

            const html = actorTemplate.innerHTML.replaceAll('__INDEX__', actorIndex);
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = html.trim();
            const newCard = tempDiv.firstElementChild;

            actorsContainer.appendChild(newCard);
            attachActorListeners(newCard);
            actorIndex++;

            // Focus on actor name input
            const nameInput = newCard.querySelector('input[name*="[name]"]');
            if (nameInput) nameInput.focus();
        });
    }
});
</script>
@endsection
