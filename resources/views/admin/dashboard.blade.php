@extends('admin.layouts.app')

@section('title', 'Admin Dashboard - HCTV')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
    <p class="text-sm text-gray-500 mt-1">Tổng quan hoạt động của hệ thống rạp chiếu phim</p>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center">
        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-xl shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
        </div>
        <div class="ml-4">
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Khách Hàng</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalUsers) }}</p>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center">
        <div class="w-12 h-12 bg-green-100 text-green-600 rounded-lg flex items-center justify-center text-xl shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </div>
        <div class="ml-4">
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Doanh Thu (VNĐ)</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center">
        <div class="w-12 h-12 bg-red-100 text-red-600 rounded-lg flex items-center justify-center text-xl shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" /></svg>
        </div>
        <div class="ml-4">
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Đơn Hàng</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalBookings) }}</p>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center">
        <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center text-xl shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" /></svg>
        </div>
        <div class="ml-4">
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Phim Đang Chiếu</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalMovies) }}</p>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
        <h2 class="font-bold text-gray-800">Đơn hàng gần đây</h2>
        <a href="{{ route('admin.bookings.index') }}" class="text-sm text-red-600 hover:text-red-800 font-medium">Xem tất cả</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-500">
                <tr>
                    <th class="px-6 py-3 font-medium tracking-wider">Mã ĐH</th>
                    <th class="px-6 py-3 font-medium tracking-wider">Khách Hàng</th>
                    <th class="px-6 py-3 font-medium tracking-wider">Phim</th>
                    <th class="px-6 py-3 font-medium tracking-wider">Tổng Tiền</th>
                    <th class="px-6 py-3 font-medium tracking-wider">Trạng Thái</th>
                    <th class="px-6 py-3 font-medium tracking-wider">Thời Gian</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($recentBookings as $booking)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-900">HCTV-{{ sprintf('%06d', $booking->id) }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $booking->user->name }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $booking->showtime->movie->title ?? 'N/A' }}</td>
                    <td class="px-6 py-4 font-semibold text-gray-800">{{ number_format($booking->total_price, 0, ',', '.') }} đ</td>
                    <td class="px-6 py-4">
                        @if($booking->status == 'paid')
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Đã thanh toán</span>
                        @elseif($booking->status == 'pending')
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Chờ thanh toán</span>
                        @else
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">{{ $booking->status }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $booking->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">Chưa có đơn hàng nào</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
