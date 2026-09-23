@extends('admin.layouts.app')

@section('title', isset($promotion) ? 'Sửa Khuyến Mãi - Admin HCTV' : 'Thêm Khuyến Mãi - Admin HCTV')

@section('content')
<div class="mb-6 flex items-center gap-4">
    <a href="{{ route('admin.promotions.index') }}" class="p-2 bg-white rounded-full shadow-sm hover:bg-gray-50 text-gray-500 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-gray-900">{{ isset($promotion) ? 'Sửa Khuyến Mãi' : 'Thêm Khuyến Mãi Mới' }}</h1>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <form action="{{ isset($promotion) ? route('admin.promotions.update', $promotion->id) : route('admin.promotions.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($promotion))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mã Code <span class="text-red-500">*</span></label>
                <input type="text" name="code" value="{{ old('code', $promotion->code ?? '') }}" required class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-gray-900 focus:border-gray-900 uppercase">
                @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tên Chương Trình <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $promotion->title ?? '') }}" required class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-gray-900 focus:border-gray-900">
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phần Trăm Giảm (%)</label>
                <input type="number" name="discount_percent" value="{{ old('discount_percent', $promotion->discount_percent ?? '') }}" min="0" max="100" class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-gray-900 focus:border-gray-900">
                @error('discount_percent') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Số Tiền Giảm (VNĐ)</label>
                <input type="number" name="discount_amount" value="{{ old('discount_amount', $promotion->discount_amount ?? '') }}" min="0" step="1000" class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-gray-900 focus:border-gray-900">
                <p class="text-xs text-gray-500 mt-1">Lưu ý: Chỉ nhập 1 trong 2 loại giảm giá (Phần trăm hoặc Số tiền).</p>
                @error('discount_amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Điểm Đổi (Points) <span class="text-red-500">*</span></label>
                <input type="number" name="points_required" value="{{ old('points_required', $promotion->points_required ?? 0) }}" required min="0" class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-gray-900 focus:border-gray-900">
                @error('points_required') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- ẢNH MINH HỌA (CHỌN TỪ MÁY TÍNH / KÉO THẢ HOẶC LINK) -->
            <div class="col-span-1 md:col-span-2 bg-gray-50 p-5 rounded-xl border border-gray-200">
                <div class="flex items-center justify-between mb-3">
                    <label class="text-sm font-bold text-gray-900">
                        Hình Ảnh Minh Họa <span class="text-red-500">*</span>
                    </label>
                    <span class="text-xs text-gray-400">Khuyến nghị banner tỉ lệ 16:9 hoặc ảnh ngang (VD: 800x450)</span>
                </div>

                <div class="flex flex-col sm:flex-row gap-5 items-start">
                    <!-- Dropzone / Preview -->
                    <div id="promo-dropzone" class="w-48 h-28 shrink-0 rounded-xl overflow-hidden bg-gray-100 border-2 border-dashed border-gray-300 hover:border-gray-800 flex items-center justify-center relative shadow-sm cursor-pointer transition group" title="Bấm để chọn file hoặc kéo thả ảnh vào đây">
                        <img id="promo-preview" 
                             src="{{ old('image_url', $promotion->image_url ?? '') }}" 
                             alt="Preview Promotion" 
                             class="w-full h-full object-cover {{ empty(old('image_url', $promotion->image_url ?? '')) ? 'hidden' : '' }}">
                        <div id="promo-placeholder" class="text-center p-2 {{ !empty(old('image_url', $promotion->image_url ?? '')) ? 'hidden' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 mx-auto text-gray-400 group-hover:text-gray-700 transition-colors mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-[11px] text-gray-500 font-medium block leading-tight">Bấm hoặc Kéo thả ảnh</span>
                        </div>
                    </div>

                    <!-- Input Options -->
                    <div class="flex-1 space-y-3 w-full">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">
                                📁 Chọn ảnh từ máy tính (hoặc kéo thả vào ô bên cạnh):
                            </label>
                            <input type="file" name="image_file" id="image_file"
                                class="block w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gray-900 file:text-white hover:file:bg-gray-800 cursor-pointer border border-gray-300 rounded-lg p-1 bg-white">
                            <p id="promo-file-status" class="text-xs text-green-700 font-medium mt-1 hidden"></p>
                            @error('image_file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="relative flex py-1 items-center">
                            <div class="flex-grow border-t border-gray-200"></div>
                            <span class="flex-shrink mx-2 text-gray-400 text-[11px] uppercase">HOẶC DÁN LINK URL</span>
                            <div class="flex-grow border-t border-gray-200"></div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">
                                🔗 Link ảnh minh họa:
                            </label>
                            <input type="text" name="image_url" id="image_url" 
                                value="{{ old('image_url', $promotion->image_url ?? '') }}" 
                                placeholder="https://images.unsplash.com/photo-..."
                                class="w-full px-3.5 py-2 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-gray-900 focus:border-gray-900">
                            @error('image_url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ngày Bắt Đầu <span class="text-red-500">*</span></label>
                <input type="date" name="start_date" value="{{ old('start_date', isset($promotion) ? \Carbon\Carbon::parse($promotion->start_date)->format('Y-m-d') : '') }}" required class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-gray-900 focus:border-gray-900">
                @error('start_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ngày Kết Thúc <span class="text-red-500">*</span></label>
                <input type="date" name="end_date" value="{{ old('end_date', isset($promotion) ? \Carbon\Carbon::parse($promotion->end_date)->format('Y-m-d') : '') }}" required class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-gray-900 focus:border-gray-900">
                @error('end_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="col-span-1 md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Mô Tả Chi Tiết <span class="text-red-500">*</span></label>
                <textarea name="description" rows="3" required class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-gray-900 focus:border-gray-900">{{ old('description', $promotion->description ?? '') }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-gray-100">
            <a href="{{ route('admin.promotions.index') }}" class="px-6 py-2 border border-gray-300 rounded text-gray-700 font-medium hover:bg-gray-50 transition-colors">Hủy</a>
            <button type="submit" class="px-6 py-2 bg-gray-900 text-white rounded font-medium hover:bg-gray-800 transition-colors">
                {{ isset($promotion) ? 'Lưu Thay Đổi' : 'Thêm Mới' }}
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const fileInput = document.getElementById('image_file');
    const urlInput = document.getElementById('image_url');
    const preview = document.getElementById('promo-preview');
    const placeholder = document.getElementById('promo-placeholder');
    const dropzone = document.getElementById('promo-dropzone');
    const fileStatus = document.getElementById('promo-file-status');

    function updatePreview(src) {
        if (src) {
            preview.src = src;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
        } else {
            preview.classList.add('hidden');
            placeholder.classList.remove('hidden');
        }
    }

    function setFile(file) {
        if (!file) return;
        const objectUrl = URL.createObjectURL(file);
        updatePreview(objectUrl);
        if (fileStatus) {
            fileStatus.textContent = '✓ Đã chọn ảnh: ' + file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
            fileStatus.classList.remove('hidden');
        }
    }

    if (fileInput) {
        fileInput.addEventListener('change', function (e) {
            if (e.target.files && e.target.files[0]) {
                setFile(e.target.files[0]);
            }
        });
    }

    if (urlInput) {
        urlInput.addEventListener('input', function (e) {
            if (e.target.value.trim() !== '') {
                updatePreview(e.target.value.trim());
            }
        });
    }

    if (dropzone && fileInput) {
        dropzone.addEventListener('click', function (e) {
            if (e.target !== fileInput) {
                fileInput.click();
            }
        });

        ['dragenter', 'dragover'].forEach(name => {
            dropzone.addEventListener(name, function (e) {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.add('border-gray-900', 'bg-gray-200');
            });
        });

        ['dragleave', 'drop'].forEach(name => {
            dropzone.addEventListener(name, function (e) {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.remove('border-gray-900', 'bg-gray-200');
            });
        });

        dropzone.addEventListener('drop', function (e) {
            if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files[0]) {
                fileInput.files = e.dataTransfer.files;
                setFile(e.dataTransfer.files[0]);
            }
        });
    }

    // Ctrl + V paste support
    window.addEventListener('paste', function (e) {
        if (e.clipboardData && e.clipboardData.items) {
            for (let i = 0; i < e.clipboardData.items.length; i++) {
                const item = e.clipboardData.items[i];
                if (item.type.indexOf('image') !== -1) {
                    const blob = item.getAsFile();
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(blob);
                    if (fileInput) {
                        fileInput.files = dataTransfer.files;
                        setFile(blob);
                    }
                    break;
                }
            }
        }
    });
});
</script>
@endsection
