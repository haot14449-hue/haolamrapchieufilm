@extends('admin.layouts.app')

@section('title', 'Quản lý Đơn Hàng - Admin HCTV')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Quản lý Đơn Hàng</h1>
    <p class="text-sm text-gray-500 mt-1">Danh sách vé đã đặt và giao dịch của khách hàng</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-500">
                <tr>
                    <th class="px-6 py-3 font-medium tracking-wider">Mã ĐH</th>
                    <th class="px-6 py-3 font-medium tracking-wider">Khách Hàng</th>
                    <th class="px-6 py-3 font-medium tracking-wider">Phim & Suất Chiếu</th>
                    <th class="px-6 py-3 font-medium tracking-wider">Tổng Tiền</th>
                    <th class="px-6 py-3 font-medium tracking-wider">Trạng Thái</th>
                    <th class="px-6 py-3 font-medium tracking-wider text-right">Chi tiết</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($bookings as $booking)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-bold text-gray-900">HCTV-{{ sprintf('%06d', $booking->id) }}</td>
                    <td class="px-6 py-4 text-gray-600">
                        <p class="font-medium">{{ $booking->user->name }}</p>
                        <p class="text-xs text-gray-400">{{ $booking->user->email }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-medium text-gray-900">{{ $booking->showtime->movie->title ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500">
                            {{ $booking->showtime->room->cinema->name ?? '' }} | 
                            {{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('H:i d/m/Y') }}
                        </p>
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-900">{{ number_format($booking->total_price, 0, ',', '.') }} đ</td>
                    <td class="px-6 py-4">
                        @if($booking->status == 'paid')
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Đã thanh toán</span>
                        @elseif($booking->status == 'pending')
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Chờ thanh toán</span>
                        @else
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Đã hủy</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.bookings.show', $booking->id) }}" class="inline-flex items-center gap-1 px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded text-xs font-medium transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            Xem
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">Chưa có đơn hàng nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
