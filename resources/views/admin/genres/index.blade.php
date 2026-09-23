@extends('admin.layouts.app')

@section('title', 'Quản lý Thể loại Phim - Admin HCTV')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Quản Lý Thể Loại Phim</h1>
        <p class="text-sm text-gray-500 mt-1">Danh sách tất cả thể loại phim trong hệ thống rạp chiếu HCTV</p>
    </div>
    <button type="button" onclick="openAddModal()" class="px-4 py-2.5 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-lg shadow-sm transition-colors flex items-center gap-2 self-start sm:self-auto">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Thêm Thể Loại Mới
    </button>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-500 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-3.5 font-semibold tracking-wider">#</th>
                    <th class="px-6 py-3.5 font-semibold tracking-wider">Tên Thể Loại</th>
                    <th class="px-6 py-3.5 font-semibold tracking-wider">Đường dẫn (Slug)</th>
                    <th class="px-6 py-3.5 font-semibold tracking-wider">Mô Tả</th>
                    <th class="px-6 py-3.5 font-semibold tracking-wider text-center">Số Lượng Phim</th>
                    <th class="px-6 py-3.5 font-semibold tracking-wider text-right">Thao Tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($genres as $index => $genre)
                <tr class="hover:bg-gray-50/80 transition-colors">
                    <td class="px-6 py-4 text-gray-400 font-medium text-xs">{{ $index + 1 }}</td>
                    <td class="px-6 py-4">
                        <span class="font-bold text-gray-900 text-sm">{{ $genre->name }}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-xs font-mono">
                        <span class="bg-gray-100 px-2 py-0.5 rounded border border-gray-200">{{ $genre->slug }}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-600 text-xs max-w-xs truncate">
                        {{ $genre->description ?? 'Chưa có mô tả' }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-200">
                            {{ $genre->movies_count }} phim
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <!-- Nút Sửa -->
                            <button type="button" 
                                onclick="openEditModal({{ $genre->id }}, '{{ addslashes($genre->name) }}', '{{ addslashes($genre->description ?? '') }}')"
                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Sửa thể loại">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </button>

                            <!-- Nút Xóa -->
                            <form action="{{ route('admin.genres.destroy', $genre->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa thể loại &quot;{{ $genre->name }}&quot; không?');" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Xóa thể loại">
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
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        <p class="text-base font-medium text-gray-600">Chưa có thể loại nào</p>
                        <p class="text-xs text-gray-400 mt-1">Bấm nút "Thêm Thể Loại Mới" để bắt đầu</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL THÊM THỂ LOẠI -->
<div id="modal-add" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-gray-100 transform transition-all">
        <div class="flex items-center justify-between pb-3 mb-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900">Thêm Thể Loại Mới</h3>
            <button type="button" onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <form action="{{ route('admin.genres.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Tên Thể Loại <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required placeholder="Ví dụ: Chiến tranh, Cổ trang..." class="w-full px-3.5 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gray-900 focus:border-gray-900">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Mô Tả (Tùy chọn)</label>
                    <textarea name="description" rows="3" placeholder="Giới thiệu sơ lược về thể loại phim này..." class="w-full px-3.5 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gray-900 focus:border-gray-900"></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeAddModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">Hủy</button>
                <button type="submit" class="px-5 py-2 bg-gray-900 hover:bg-gray-800 text-white rounded-lg text-sm font-bold shadow transition">Lưu Thể Loại</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL SỬA THỂ LOẠI -->
<div id="modal-edit" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-gray-100 transform transition-all">
        <div class="flex items-center justify-between pb-3 mb-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900">Chỉnh Sửa Thể Loại</h3>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <form id="form-edit" action="" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Tên Thể Loại <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="edit-name" required class="w-full px-3.5 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gray-900 focus:border-gray-900">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Mô Tả (Tùy chọn)</label>
                    <textarea name="description" id="edit-description" rows="3" class="w-full px-3.5 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gray-900 focus:border-gray-900"></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">Hủy</button>
                <button type="submit" class="px-5 py-2 bg-gray-900 hover:bg-gray-800 text-white rounded-lg text-sm font-bold shadow transition">Cập Nhật</button>
            </div>
        </form>
    </div>
</div>

<script>
    const modalAdd = document.getElementById('modal-add');
    const modalEdit = document.getElementById('modal-edit');
    const formEdit = document.getElementById('form-edit');
    const editName = document.getElementById('edit-name');
    const editDescription = document.getElementById('edit-description');

    function openAddModal() {
        modalAdd.classList.remove('hidden');
        modalAdd.classList.add('flex');
    }

    function closeAddModal() {
        modalAdd.classList.add('hidden');
        modalAdd.classList.remove('flex');
    }

    function openEditModal(id, name, description) {
        formEdit.action = `/admin/genres/${id}`;
        editName.value = name;
        editDescription.value = description;

        modalEdit.classList.remove('hidden');
        modalEdit.classList.add('flex');
    }

    function closeEditModal() {
        modalEdit.classList.add('hidden');
        modalEdit.classList.remove('flex');
    }

    window.addEventListener('click', function(e) {
        if (e.target === modalAdd) closeAddModal();
        if (e.target === modalEdit) closeEditModal();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAddModal();
            closeEditModal();
        }
    });
</script>
@endsection
