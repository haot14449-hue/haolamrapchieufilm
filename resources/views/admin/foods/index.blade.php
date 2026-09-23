@extends('admin.layouts.app')

@section('title', 'Quản lý Bắp Nước - Admin HCTV')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Quản lý Đồ Ăn / Combo</h1>
        <p class="text-sm text-gray-500 mt-1">Danh sách bắp nước và combo bán tại rạp</p>
    </div>
    <a href="{{ route('admin.foods.create') }}" class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded shadow hover:bg-gray-800 transition-colors flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
        Thêm Combo Mới
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-500">
                <tr>
                    <th class="px-6 py-3 font-medium tracking-wider">Hình Ảnh & Tên</th>
                    <th class="px-6 py-3 font-medium tracking-wider">Mô Tả</th>
                    <th class="px-6 py-3 font-medium tracking-wider">Giá Bán</th>
                    <th class="px-6 py-3 font-medium tracking-wider text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($foods as $food)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <img src="{{ $food->image_url }}" alt="{{ $food->name }}" class="w-16 h-16 object-cover rounded shadow-sm border border-gray-200">
                            <div>
                                <p class="font-bold text-gray-900">{{ $food->name }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600 max-w-xs truncate">{{ $food->description }}</td>
                    <td class="px-6 py-4 font-semibold text-green-600">{{ number_format($food->price, 0, ',', '.') }} đ</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.foods.edit', $food->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded transition-colors" title="Sửa">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                            </a>
                            <form action="{{ route('admin.foods.destroy', $food->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đồ ăn này?');" class="inline-block">
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
                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">Chưa có đồ ăn/combo nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
