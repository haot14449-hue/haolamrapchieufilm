@extends('admin.layouts.app')

@section('title', 'Admin Dashboard - HCTV')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-sm text-gray-500 mt-1">Tổng quan hoạt động & hiệu suất kinh doanh của hệ thống rạp chiếu phim</p>
    </div>
    <div class="flex items-center gap-2 px-3 py-1.5 bg-white border border-gray-200 rounded-lg shadow-sm text-xs font-medium text-gray-600 shrink-0 self-start sm:self-auto">
        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
        <span>Hôm nay: {{ now()->format('d/m/Y') }}</span>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center justify-between hover:shadow-md transition">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Khách Hàng</p>
            <p class="text-2xl font-black text-gray-900">{{ number_format($totalUsers) }}</p>
            <p class="text-[11px] text-gray-400 mt-1">Tài khoản thành viên</p>
        </div>
        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl shrink-0 border border-blue-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center justify-between hover:shadow-md transition">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Doanh Thu (VNĐ)</p>
            <p class="text-2xl font-black text-gray-900">{{ number_format($totalRevenue, 0, ',', '.') }} đ</p>
            <p class="text-[11px] text-emerald-600 font-medium mt-1">✓ Đã thanh toán</p>
        </div>
        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-xl shrink-0 border border-emerald-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center justify-between hover:shadow-md transition">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Đơn Hàng Thành Công</p>
            <p class="text-2xl font-black text-gray-900">{{ number_format($totalBookings) }}</p>
            <p class="text-[11px] text-red-600 font-semibold mt-1">🎟️ Đã bán {{ number_format($totalTicketsSold) }} vé</p>
        </div>
        <div class="w-12 h-12 bg-red-50 text-red-600 rounded-xl flex items-center justify-center text-xl shrink-0 border border-red-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" /></svg>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center justify-between hover:shadow-md transition">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Phim Trong Rạp</p>
            <p class="text-2xl font-black text-gray-900">{{ number_format($totalMovies) }}</p>
            <p class="text-[11px] text-gray-400 mt-1">Phim đang quản lý</p>
        </div>
        <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center text-xl shrink-0 border border-purple-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" /></svg>
        </div>
    </div>
</div>

<!-- Analytics Row: Chart + Movie Ranking -->
<div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mb-8">
    
    <!-- Left Column: Biểu Đồ Doanh Thu Theo Ngày -->
    <div class="xl:col-span-7 bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
        <div>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-gray-100 gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-red-50 text-red-600 flex items-center justify-center font-bold text-lg">
                        📈
                    </div>
                    <div>
                        <h2 class="font-bold text-gray-900 text-base">Thống Kê Doanh Thu Theo Ngày</h2>
                        <p class="text-xs text-gray-500">Biểu đồ biến động doanh thu phòng vé theo mốc thời gian</p>
                    </div>
                </div>

                <!-- Range Switcher Buttons -->
                <div class="flex items-center gap-1 bg-gray-100/80 p-1 rounded-lg self-start sm:self-auto">
                    <button type="button" onclick="switchPeriod('7_days', this)" class="period-btn px-3 py-1 text-xs font-semibold rounded-md transition-all duration-200 bg-gray-900 text-white shadow-sm">
                        7 ngày
                    </button>
                    <button type="button" onclick="switchPeriod('14_days', this)" class="period-btn px-3 py-1 text-xs font-semibold rounded-md transition-all duration-200 text-gray-600 hover:bg-gray-200">
                        14 ngày
                    </button>
                    <button type="button" onclick="switchPeriod('30_days', this)" class="period-btn px-3 py-1 text-xs font-semibold rounded-md transition-all duration-200 text-gray-600 hover:bg-gray-200">
                        30 ngày
                    </button>
                </div>
            </div>

            <!-- Period Metrics Mini Bar -->
            <div class="grid grid-cols-3 gap-3 my-4 p-3 bg-gray-50 rounded-xl border border-gray-100 text-center">
                <div>
                    <span class="block text-[11px] font-medium text-gray-500 uppercase">Tổng doanh thu kỳ</span>
                    <span id="periodTotal" class="text-base sm:text-lg font-bold text-gray-900">0 đ</span>
                </div>
                <div class="border-x border-gray-200">
                    <span class="block text-[11px] font-medium text-gray-500 uppercase">Trung bình / ngày</span>
                    <span id="periodAverage" class="text-base sm:text-lg font-bold text-emerald-600">0 đ/ngày</span>
                </div>
                <div>
                    <span class="block text-[11px] font-medium text-gray-500 uppercase">Tổng đơn hàng</span>
                    <span id="periodOrders" class="text-base sm:text-lg font-bold text-blue-600">0 đơn</span>
                </div>
            </div>
        </div>

        <!-- Canvas Container -->
        <div class="relative w-full h-72 sm:h-80 pt-2">
            <canvas id="dailyRevenueChart"></canvas>
        </div>
    </div>

    <!-- Right Column: Bảng Xếp Hạng Phim Bán Chạy Nhất -->
    <div class="xl:col-span-5 bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg">
                        🏆
                    </div>
                    <div>
                        <h2 class="font-bold text-gray-900 text-base">Phim Bán Chạy Nhất</h2>
                        <p class="text-xs text-gray-500">Xếp hạng theo số lượng vé đã bán ra</p>
                    </div>
                </div>
                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-red-50 text-red-700 border border-red-200">
                    {{ $totalTicketsSold }} vé đã bán
                </span>
            </div>

            <!-- Rankings List -->
            @php
                $maxTickets = max($movieRankings->max('tickets_sold'), 1);
            @endphp
            <div class="divide-y divide-gray-100 mt-2">
                @forelse($movieRankings as $index => $movie)
                @php
                    $rank = $index + 1;
                    $percent = round(($movie->tickets_sold / $maxTickets) * 100);
                @endphp
                <div class="py-3.5 flex items-center gap-3 group hover:bg-gray-50/80 rounded-xl px-2 transition-colors">
                    <!-- Rank Badge -->
                    <div class="shrink-0 w-7 h-7 rounded-lg flex items-center justify-center font-black text-xs
                        @if($rank === 1) bg-amber-400 text-amber-950 shadow-sm ring-2 ring-amber-200/80
                        @elseif($rank === 2) bg-slate-200 text-slate-800
                        @elseif($rank === 3) bg-amber-700/20 text-amber-800 border border-amber-300
                        @else bg-gray-100 text-gray-500
                        @endif">
                        @if($rank === 1) 🥇
                        @elseif($rank === 2) 🥈
                        @elseif($rank === 3) 🥉
                        @else #{{ $rank }}
                        @endif
                    </div>

                    <!-- Poster Thumbnail -->
                    <div class="w-11 h-14 rounded-lg overflow-hidden bg-gray-900 shrink-0 border border-gray-200 shadow-sm relative">
                        <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="w-full h-full object-cover">
                    </div>

                    <!-- Movie Info -->
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-sm text-gray-900 truncate group-hover:text-red-600 transition-colors" title="{{ $movie->title }}">
                            {{ $movie->title }}
                        </h3>
                        <p class="text-[11px] text-gray-500 truncate mt-0.5">
                            {{ $movie->genre ?? 'Chưa phân loại' }} • {{ $movie->duration ?? 0 }} phút
                        </p>
                        
                        <!-- Progress bar -->
                        <div class="w-full bg-gray-100 rounded-full h-1.5 mt-2 overflow-hidden">
                            <div class="bg-gradient-to-r from-red-500 to-amber-500 h-1.5 rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>

                    <!-- Ticket & Revenue Stats -->
                    <div class="text-right shrink-0">
                        <span class="inline-block px-2.5 py-1 rounded-lg text-xs font-extrabold {{ $movie->tickets_sold > 0 ? 'bg-red-50 text-red-600 border border-red-200' : 'bg-gray-100 text-gray-500' }}">
                            {{ number_format($movie->tickets_sold) }} vé
                        </span>
                        <span class="block text-[11px] text-gray-400 mt-1 font-medium">
                            {{ number_format($movie->total_revenue, 0, ',', '.') }} đ
                        </span>
                    </div>
                </div>
                @empty
                <div class="py-8 text-center text-gray-400 text-sm">
                    Chưa có dữ liệu phim nào.
                </div>
                @endforelse
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-between items-center text-xs text-gray-500">
            <span>Dữ liệu tính trên các đơn hàng đã thanh toán</span>
            <a href="{{ route('admin.movies.index') }}" class="font-medium text-red-600 hover:text-red-700 hover:underline">Quản lý phim &rarr;</a>
        </div>
    </div>

</div>

<!-- Recent Orders Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
        <div>
            <h2 class="font-bold text-gray-900">Đơn Hàng Gần Đây</h2>
            <p class="text-xs text-gray-500 mt-0.5">Các giao dịch đặt vé mới nhất trong hệ thống</p>
        </div>
        <a href="{{ route('admin.bookings.index') }}" class="text-xs sm:text-sm text-red-600 hover:text-red-800 font-semibold flex items-center gap-1">
            <span>Xem tất cả đơn hàng</span>
            <span>&rarr;</span>
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3 font-semibold">Mã ĐH</th>
                    <th class="px-6 py-3 font-semibold">Khách Hàng</th>
                    <th class="px-6 py-3 font-semibold">Phim</th>
                    <th class="px-6 py-3 font-semibold">Tổng Tiền</th>
                    <th class="px-6 py-3 font-semibold">Trạng Thái</th>
                    <th class="px-6 py-3 font-semibold">Thời Gian</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($recentBookings as $booking)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-mono font-bold text-gray-900 text-xs">
                        HCTV-{{ sprintf('%06d', $booking->id) }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $booking->user->name ?? 'Khách vãng lai' }}</div>
                        <div class="text-xs text-gray-400">{{ $booking->user->email ?? '' }}</div>
                    </td>
                    <td class="px-6 py-4 text-gray-700 font-medium">
                        {{ $booking->showtime->movie->title ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 font-bold text-gray-900">
                        {{ number_format($booking->total_price, 0, ',', '.') }} đ
                    </td>
                    <td class="px-6 py-4">
                        @if($booking->status == 'paid')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Đã thanh toán
                            </span>
                        @elseif($booking->status == 'pending')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                Chờ thanh toán
                            </span>
                        @elseif($booking->status == 'cancelled')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                Đã hủy
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                {{ $booking->status_label ?? $booking->status }}
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-xs">
                        {{ $booking->created_at->format('d/m/Y H:i') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span>Chưa có đơn hàng nào trong hệ thống.</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const rawData = @json($chartData);
    let currentPeriod = '7_days';

    const canvas = document.getElementById('dailyRevenueChart');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');

    // Create gradient fill
    const gradient = ctx.createLinearGradient(0, 0, 0, 320);
    gradient.addColorStop(0, 'rgba(220, 38, 38, 0.22)');
    gradient.addColorStop(1, 'rgba(220, 38, 38, 0.00)');

    const config = {
        type: 'line',
        data: {
            labels: rawData[currentPeriod].labels,
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: rawData[currentPeriod].revenues,
                borderColor: '#dc2626',
                borderWidth: 2.5,
                backgroundColor: gradient,
                fill: true,
                tension: 0.35,
                pointBackgroundColor: '#dc2626',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 7,
                pointHoverBackgroundColor: '#b91c1c',
                pointHoverBorderColor: '#ffffff',
                pointHoverBorderWidth: 2.5,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index',
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(17, 24, 39, 0.95)',
                    titleColor: '#ffffff',
                    bodyColor: '#f3f4f6',
                    borderColor: 'rgba(255, 255, 255, 0.1)',
                    borderWidth: 1,
                    padding: 12,
                    boxPadding: 6,
                    usePointStyle: true,
                    callbacks: {
                        title: function(items) {
                            if (!items.length) return '';
                            const idx = items[0].dataIndex;
                            return 'Ngày: ' + (rawData[currentPeriod].fullLabels[idx] || '');
                        },
                        label: function(context) {
                            const val = context.raw || 0;
                            const idx = context.dataIndex;
                            const orders = rawData[currentPeriod].orders[idx] || 0;
                            return [
                                ' Doanh thu: ' + new Intl.NumberFormat('vi-VN').format(val) + ' đ',
                                ' Đơn thành công: ' + orders + ' đơn'
                            ];
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false,
                        drawBorder: false,
                    },
                    ticks: {
                        color: '#6b7280',
                        font: {
                            family: 'Inter, sans-serif',
                            size: 11
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(243, 244, 246, 1)',
                        drawBorder: false,
                    },
                    ticks: {
                        color: '#6b7280',
                        font: {
                            family: 'Inter, sans-serif',
                            size: 11
                        },
                        callback: function(value) {
                            if (value >= 1000000) {
                                return (value / 1000000).toFixed(1).replace('.0', '') + ' Tr';
                            }
                            if (value >= 1000) {
                                return (value / 1000).toFixed(0) + 'k';
                            }
                            return value;
                        }
                    }
                }
            }
        }
    };

    const revenueChart = new Chart(ctx, config);

    function updateSummary(periodKey) {
        const p = rawData[periodKey];
        const days = periodKey === '7_days' ? 7 : (periodKey === '14_days' ? 14 : 30);
        const avg = Math.round(p.total / days);

        document.getElementById('periodTotal').textContent = new Intl.NumberFormat('vi-VN').format(p.total) + ' đ';
        document.getElementById('periodAverage').textContent = new Intl.NumberFormat('vi-VN').format(avg) + ' đ/ngày';
        document.getElementById('periodOrders').textContent = p.totalOrders + ' đơn hàng';
    }

    // Initialize summary for default period
    updateSummary('7_days');

    // Global period switcher
    window.switchPeriod = function(periodKey, btn) {
        currentPeriod = periodKey;
        
        // Update tab buttons
        document.querySelectorAll('.period-btn').forEach(b => {
            b.classList.remove('bg-gray-900', 'text-white', 'shadow-sm');
            b.classList.add('text-gray-600', 'hover:bg-gray-200');
        });
        btn.classList.remove('text-gray-600', 'hover:bg-gray-200');
        btn.classList.add('bg-gray-900', 'text-white', 'shadow-sm');

        // Update Chart
        revenueChart.data.labels = rawData[periodKey].labels;
        revenueChart.data.datasets[0].data = rawData[periodKey].revenues;
        revenueChart.update();

        // Update summary numbers
        updateSummary(periodKey);
    };
});
</script>
@endpush
