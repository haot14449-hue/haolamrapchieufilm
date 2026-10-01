@extends('admin.layouts.app')

@section('title', 'Quản lý Phim - Admin HCTV')

@section('content')
<div class="flex flex-wrap justify-between items-center gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Quản lý Phim</h1>
        <p class="text-sm text-gray-500 mt-1">Danh sách phim trong hệ thống</p>
    </div>
    <div class="flex items-center gap-2.5">
        <a href="{{ route('admin.top_movies.index') }}" class="px-3.5 py-2 bg-amber-500/10 hover:bg-amber-500/20 text-amber-800 border border-amber-300 text-sm font-semibold rounded-lg shadow-2xs transition flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-600" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12.75 2.042a.75.75 0 00-.75 0 11.233 11.233 0 00-3.666 4.708.75.75 0 00.99 1.011c.915-.558 1.93-.976 3.001-1.229a10.963 10.963 0 01-1.325 5.568.75.75 0 00.58 1.1c1.554.24 3.018.966 4.195 2.062a.75.75 0 001.275-.544 11.23 11.23 0 00-4.3-12.676zM7.5 9.75a.75.75 0 00-.75-.75A11.26 11.26 0 003 14.25c0 5.385 4.365 9.75 9.75 9.75s9.75-4.365 9.75-9.75c0-2.316-.807-4.444-2.158-6.12a.75.75 0 00-1.168.04 7.48 7.48 0 01-4.074 2.83.75.75 0 00-.51.933c.31 1.05.46 2.146.46 3.317a7.25 7.25 0 11-14.5 0c0-1.745.545-3.376 1.487-4.733a.75.75 0 00.053-.767z" />
            </svg>
            Quản Lý Top 10 Phim Hot
        </a>
        <a href="{{ route('admin.movies.create') }}" class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg shadow hover:bg-gray-800 transition-colors flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Thêm Phim Mới
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-500">
                <tr>
                    <th class="px-6 py-3 font-medium tracking-wider">Phim</th>
                    <th class="px-6 py-3 font-medium tracking-wider">Thời lượng</th>
                    <th class="px-6 py-3 font-medium tracking-wider">Thể loại</th>
                    <th class="px-6 py-3 font-medium tracking-wider">Khởi chiếu</th>
                    <th class="px-6 py-3 font-medium tracking-wider text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($movies as $movie)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="w-12 h-16 object-cover rounded shadow-sm border border-gray-200">
                            <div>
                                <div class="flex items-center gap-2">
                                    <p class="font-bold text-gray-900">{{ $movie->title }}</p>
                                    @if($movie->topHot)
                                        <a href="{{ route('admin.top_movies.index') }}" class="inline-flex items-center gap-1 bg-amber-100 text-amber-800 hover:bg-amber-200 text-[11px] font-bold px-2 py-0.5 rounded-full border border-amber-300 transition" title="Xem trong Top 10">
                                            🔥 Top #{{ $movie->topHot->rank }}
                                        </a>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    <span class="inline-flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {{ $movie->actors_count ?? $movie->actors()->count() }} diễn viên
                                    </span>
                                </p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $movie->duration }} phút</td>
                    <td class="px-6 py-4 text-gray-600">{{ $movie->genre }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ \Carbon\Carbon::parse($movie->release_date)->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.movies.edit', $movie->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded transition-colors" title="Sửa">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                            </a>
                            <form action="{{ route('admin.movies.destroy', $movie->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa phim này?');" class="inline-block">
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
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">Chưa có phim nào. Hãy thêm phim mới!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
