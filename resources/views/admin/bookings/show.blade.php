@extends('admin.layouts.app')

@section('title', 'Chi tiết Đơn Hàng - Admin HCTV')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.bookings.index') }}" class="p-2 bg-white rounded-full shadow-sm hover:bg-gray-50 text-gray-500 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        </a>
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-gray-900">Đơn hàng: HCTV-{{ sprintf('%06d', $booking->id) }}</h1>
                @if($booking->status == 'paid')
                    <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">Đã thanh toán</span>
                @elseif($booking->status == 'pending')
                    <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">Chờ thanh toán</span>
                @else
                    <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">Đã hủy</span>
                @endif
            </div>
            <p class="text-sm text-gray-500 mt-1">Ngày đặt: {{ $booking->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>
    
    <div>
        <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST" class="flex items-center gap-2">
            @csrf
            @method('PUT')
            <select name="status" class="px-3 py-1.5 border border-gray-300 rounded text-sm focus:ring-gray-900 focus:border-gray-900 bg-white">
                <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Chờ thanh toán</option>
                <option value="paid" {{ $booking->status == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
            </select>
            <button type="submit" class="px-4 py-1.5 bg-gray-900 text-white text-sm font-medium rounded hover:bg-gray-800 transition-colors">
                Cập nhật
            </button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Thông tin khách hàng & Phim -->
    <div class="md:col-span-1 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Thông tin Khách Hàng</h3>
            <div class="space-y-3">
                <p><span class="text-gray-500 text-sm block">Họ tên</span> <span class="font-medium text-gray-900">{{ $booking->user->name }}</span></p>
                <p><span class="text-gray-500 text-sm block">Email</span> <span class="text-gray-900">{{ $booking->user->email }}</span></p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Thông tin Suất Chiếu</h3>
            <div class="space-y-3">
                <p><span class="text-gray-500 text-sm block">Phim</span> <span class="font-medium text-gray-900">{{ $booking->showtime->movie->title ?? 'N/A' }}</span></p>
                <p><span class="text-gray-500 text-sm block">Rạp</span> <span class="text-gray-900">{{ $booking->showtime->room->cinema->name ?? 'N/A' }}</span></p>
                <p><span class="text-gray-500 text-sm block">Phòng</span> <span class="text-gray-900">{{ $booking->showtime->room->name ?? 'N/A' }}</span></p>
                <p><span class="text-gray-500 text-sm block">Thời gian</span> <span class="text-blue-600 font-medium">{{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('H:i - d/m/Y') }}</span></p>
            </div>
        </div>
    </div>

    <!-- Chi tiết vé và đồ ăn -->
    <div class="md:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Chi tiết Đặt Chỗ</h3>
            
            <div class="mb-6">
                <h4 class="text-sm font-semibold text-gray-700 mb-2">Ghế đã chọn:</h4>
                <div class="flex flex-wrap gap-2">
                    @foreach($booking->tickets as $ticket)
                        <span class="px-3 py-1 bg-gray-100 border border-gray-200 rounded font-medium text-gray-800">{{ $ticket->seat->row }}{{ $ticket->seat->number }}</span>
                    @endforeach
                </div>
            </div>

            @if($booking->foods->count() > 0)
            <div class="mb-6">
                <h4 class="text-sm font-semibold text-gray-700 mb-2">Bắp nước & Combo:</h4>
                <ul class="space-y-2">
                    @foreach($booking->foods as $food)
                    <li class="flex justify-between items-center bg-gray-50 p-3 rounded border border-gray-100">
                        <span>{{ $food->name }} x <strong class="text-gray-900">{{ $food->pivot->quantity }}</strong></span>
                        <span class="text-gray-600">{{ number_format($food->pivot->price * $food->pivot->quantity, 0, ',', '.') }} đ</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="mt-6 pt-4 border-t border-gray-200 flex justify-between items-center">
                <span class="text-gray-500 font-medium">Tổng thanh toán:</span>
                <span class="text-2xl font-bold text-red-600">{{ number_format($booking->total_price, 0, ',', '.') }} VNĐ</span>
            </div>
        </div>
    </div>
</div>
@endsection
