@extends('admin.layouts.app')

@section('title', isset($cinema) ? 'Sửa Rạp - Admin HCTV' : 'Thêm Rạp - Admin HCTV')

@section('content')
<div class="mb-6 flex items-center gap-4">
    <a href="{{ route('admin.cinemas.index') }}" class="p-2 bg-white rounded-full shadow-sm hover:bg-gray-50 text-gray-500 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-gray-900">{{ isset($cinema) ? 'Sửa Rạp' : 'Thêm Rạp Mới' }}</h1>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <form action="{{ isset($cinema) ? route('admin.cinemas.update', $cinema->id) : route('admin.cinemas.store') }}" method="POST">
        @csrf
        @if(isset($cinema))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tên Rạp <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $cinema->name ?? '') }}" required class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-gray-900 focus:border-gray-900">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Vị trí / Địa chỉ <span class="text-red-500">*</span></label>
                <input type="text" name="location" value="{{ old('location', $cinema->location ?? '') }}" required class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-gray-900 focus:border-gray-900">
                @error('location') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-gray-100">
            <a href="{{ route('admin.cinemas.index') }}" class="px-6 py-2 border border-gray-300 rounded text-gray-700 font-medium hover:bg-gray-50 transition-colors">Hủy</a>
            <button type="submit" class="px-6 py-2 bg-gray-900 text-white rounded font-medium hover:bg-gray-800 transition-colors">
                {{ isset($cinema) ? 'Lưu Thay Đổi' : 'Thêm Rạp' }}
            </button>
        </div>
    </form>
</div>
@endsection
