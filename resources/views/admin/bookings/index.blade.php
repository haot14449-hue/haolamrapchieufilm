@extends('admin.layouts.app')

@section('title', 'Quản lý Đơn Hàng - Admin HCTV')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Quản lý Đơn Hàng</h1>
        <p class="text-sm text-gray-500 mt-1">Danh sách vé đã đặt và giao dịch của khách hàng (Tự động hủy vé chưa thanh toán quá 5 phút)</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.bookings.index', request()->query()) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-xs font-medium text-gray-700 hover:bg-gray-50 shadow-sm transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Làm mới dữ liệu
        </a>
    </div>
</div>

<!-- Bộ lọc trạng thái đơn hàng -->
<div class="mb-4 flex flex-wrap items-center gap-2">
    @php
        $currentStatus = request('status', '');
    @endphp
    <a href="{{ route('admin.bookings.index') }}" 
       class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $currentStatus === '' ? 'bg-gray-900 text-white shadow-sm' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
        Tất cả <span class="ml-1 px-1.5 py-0.5 rounded-full text-[10px] {{ $currentStatus === '' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-700' }}">{{ $statusCounts['all'] ?? count($bookings) }}</span>
    </a>
    <a href="{{ route('admin.bookings.index', ['status' => 'paid']) }}" 
       class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $currentStatus === 'paid' ? 'bg-green-600 text-white shadow-sm' : 'bg-white text-green-700 hover:bg-green-50 border border-green-200' }}">
        Đã thanh toán <span class="ml-1 px-1.5 py-0.5 rounded-full text-[10px] {{ $currentStatus === 'paid' ? 'bg-white/20 text-white' : 'bg-green-100 text-green-800' }}">{{ $statusCounts['paid'] ?? 0 }}</span>
    </a>
    <a href="{{ route('admin.bookings.index', ['status' => 'pending']) }}" 
       class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $currentStatus === 'pending' ? 'bg-yellow-500 text-white shadow-sm' : 'bg-white text-yellow-700 hover:bg-yellow-50 border border-yellow-200' }}">
        Chờ thanh toán <span class="ml-1 px-1.5 py-0.5 rounded-full text-[10px] {{ $currentStatus === 'pending' ? 'bg-white/20 text-white' : 'bg-yellow-100 text-yellow-800' }}">{{ $statusCounts['pending'] ?? 0 }}</span>
    </a>
    <a href="{{ route('admin.bookings.index', ['status' => 'cancelled']) }}" 
       class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $currentStatus === 'cancelled' ? 'bg-red-600 text-white shadow-sm' : 'bg-white text-red-700 hover:bg-red-50 border border-red-200' }}">
        Đã hủy <span class="ml-1 px-1.5 py-0.5 rounded-full text-[10px] {{ $currentStatus === 'cancelled' ? 'bg-white/20 text-white' : 'bg-red-100 text-red-800' }}">{{ $statusCounts['cancelled'] ?? 0 }}</span>
    </a>
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
            <tbody class="divide-y divide-gray-100" id="bookingsTableBody">
                @forelse($bookings as $booking)
                <tr class="hover:bg-gray-50 transition-colors" id="booking-row-{{ $booking->id }}">
                    <td class="px-6 py-4 font-bold text-gray-900">HCTV-{{ sprintf('%06d', $booking->id) }}</td>
                    <td class="px-6 py-4 text-gray-600">
                        <p class="font-medium text-gray-900">{{ $booking->user->name ?? 'Khách vãng lai' }}</p>
                        <p class="text-xs text-gray-400">{{ $booking->user->email ?? '' }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-medium text-gray-900">{{ $booking->showtime->movie->title ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500">
                            {{ $booking->showtime->room->cinema->name ?? '' }} | 
                            {{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('H:i d/m/Y') }}
                        </p>
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-900">{{ number_format($booking->total_price, 0, ',', '.') }} đ</td>
                    <td class="px-6 py-4" id="status-cell-{{ $booking->id }}">
                        @if($booking->status == 'paid')
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">Đã thanh toán</span>
                        @elseif($booking->status == 'pending' && !$booking->isExpired())
                            @php
                                $remaining = $booking->remaining_seconds;
                            @endphp
                            <span class="pending-countdown-badge inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200" 
                                  data-booking-id="{{ $booking->id }}" 
                                  data-remaining="{{ $remaining }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></span>
                                Chờ thanh toán (<span class="timer-display">--:--</span>)
                            </span>
                        @else
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">Đã hủy</span>
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
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">Chưa có đơn hàng nào phù hợp bộ lọc.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const badges = document.querySelectorAll('.pending-countdown-badge');
    let hasExpiredAny = false;

    badges.forEach(badge => {
        let remaining = parseInt(badge.getAttribute('data-remaining'), 10);
        const timerDisplay = badge.querySelector('.timer-display');
        const bookingId = badge.getAttribute('data-booking-id');

        function updateBadge() {
            if (remaining <= 0) {
                const cell = document.getElementById('status-cell-' + bookingId);
                if (cell) {
                    cell.innerHTML = '<span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">Đã hủy</span>';
                }
                clearInterval(interval);
                if (!hasExpiredAny) {
                    hasExpiredAny = true;
                    // Reload after 2 seconds to synchronize counts and database
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                }
                return;
            }

            const mins = Math.floor(remaining / 60);
            const secs = remaining % 60;
            if (timerDisplay) {
                timerDisplay.textContent = (mins < 10 ? '0' : '') + mins + ':' + (secs < 10 ? '0' : '') + secs;
            }
            remaining--;
        }

        updateBadge();
        const interval = setInterval(updateBadge, 1000);
    });

    // Auto-refresh page every 45s to keep order statuses synchronized
    setTimeout(() => {
        if (!document.hidden) {
            window.location.reload();
        }
    }, 45000);
});
</script>
@endsection
