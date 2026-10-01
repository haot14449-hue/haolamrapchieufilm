@extends('layouts.app')

@section('title', 'Chọn Bắp Nước - HCTV')

@section('content')
<div class="pt-24 pb-12 bg-cinematic-dark min-h-screen">
    <div class="max-w-6xl mx-auto px-6 md:px-12">
        
        <!-- Header Info -->
        <div class="bg-white/5 border border-white/10 rounded-xl p-6 mb-6 flex flex-col md:flex-row justify-between items-center gap-4 shadow-lg">
            <div>
                <h2 class="text-2xl font-serif font-bold text-white">Chọn Bắp Nước & Combo</h2>
                <p class="text-gray-400 mt-1">
                    {{ $booking->showtime->movie->title }} | {{ $booking->showtime->room->cinema->name ?? 'HCTV Cinema' }}
                </p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-400">Tạm tính (Chưa gồm bắp nước)</p>
                <p class="text-xl font-bold text-cinematic-gold">{{ number_format($ticketTotal ?? $booking->total_price, 0, ',', '.') }} VNĐ</p>
            </div>
        </div>

        <!-- 5-Minute Seat Hold Countdown Banner -->
        <div class="hold-countdown-banner checkout-countdown-banner mb-8 p-4 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-lg">
            <div class="flex items-center gap-3.5">
                <div class="hold-countdown-icon checkout-countdown-icon animate-pulse">
                    ⏱️
                </div>
                <div>
                    <p class="hold-countdown-title checkout-countdown-title flex flex-wrap items-center gap-2">
                        Ghế đang được giữ chỗ trong 5 phút
                        <span class="hold-countdown-seats checkout-countdown-seats">({{ $booking->tickets->map(fn($t) => $t->seat ? ($t->seat->row . $t->seat->number) : '')->filter()->join(', ') }})</span>
                    </p>
                    <p class="hold-countdown-desc checkout-countdown-desc">Vui lòng hoàn tất đặt vé trước khi hết thời gian giữ chỗ để không bị mất ghế.</p>
                </div>
            </div>
            <div class="hold-countdown-box checkout-countdown-box">
                <span class="hold-countdown-label checkout-countdown-label">Thời gian còn lại</span>
                <span id="holdCountdown" class="hold-countdown-digits checkout-countdown-digits">05:00</span>
            </div>
        </div>

        <form id="foodForm" action="{{ route('booking.process_food', $booking->id) }}" method="POST">
            @csrf
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Food List -->
                <div class="flex-1 space-y-4">
                    @foreach($foods as $food)
                    <div class="bg-white/5 border border-white/10 rounded-xl p-4 flex gap-4 items-center shadow-md hover:bg-white/10 transition-colors">
                        <div class="w-24 h-24 shrink-0 rounded-lg overflow-hidden bg-black/40 border border-white/10">
                            @if($food->image_url)
                                <img src="{{ $food->image_url }}" alt="{{ $food->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-500 font-bold">HCTV</div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-white">{{ $food->name }}</h3>
                            <p class="text-gray-400 text-sm mt-1">{{ $food->description }}</p>
                            <p class="text-cinematic-gold font-bold mt-2">{{ number_format($food->price, 0, ',', '.') }} VNĐ</p>
                        </div>
                        <div class="food-stepper flex items-center gap-4 bg-black/50 rounded-lg p-2 border border-white/10">
                            <button type="button" class="btn-decrease food-stepper-btn w-8 h-8 rounded bg-white/10 text-white flex items-center justify-center hover:bg-white/20 transition-colors cursor-pointer select-none font-bold text-lg">-</button>
                            <input type="number" name="foods[{{ $food->id }}]" value="0" min="0" max="10" 
                                   class="food-quantity food-stepper-input w-12 text-center bg-transparent border-none text-white font-bold focus:ring-0 select-none" 
                                   data-price="{{ $food->price }}" 
                                   data-name="{{ $food->name }}">
                            <button type="button" class="btn-increase food-stepper-btn w-8 h-8 rounded bg-white/10 text-white flex items-center justify-center hover:bg-white/20 transition-colors cursor-pointer select-none font-bold text-lg">+</button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Summary Sidebar -->
                <div class="w-full lg:w-80 shrink-0">
                    <div class="bg-white/5 border border-white/10 rounded-xl p-6 shadow-xl sticky top-28">
                        <h3 class="text-xl font-bold text-white mb-6 border-b border-white/10 pb-4">Tóm tắt đơn hàng</h3>
                        
                        <div class="mb-4">
                            <p class="text-gray-400 text-sm">Tiền vé:</p>
                            <p class="food-ticket-price text-white font-bold mt-1 text-lg" id="ticketPrice" data-value="{{ $ticketTotal ?? $booking->total_price }}">
                                {{ number_format($ticketTotal ?? $booking->total_price, 0, ',', '.') }} VNĐ
                            </p>
                        </div>

                        <div class="mb-4 border-t border-white/10 pt-4 hidden" id="foodSummaryWrapper">
                            <p class="text-gray-400 text-sm mb-2 font-medium">Bắp nước đã chọn:</p>
                            <div id="foodSummaryList" class="food-summary-list space-y-1.5 text-sm text-white">
                                <!-- JS dynamic items will go here -->
                            </div>
                        </div>
                        
                        <div class="mb-8 border-t border-white/10 pt-4">
                            <p class="text-gray-400 text-sm">Tổng cộng:</p>
                            <p id="totalPriceLabel" class="text-3xl font-bold text-cinematic-red mt-1">
                                {{ number_format($ticketTotal ?? $booking->total_price, 0, ',', '.') }} VNĐ
                            </p>
                        </div>
                        
                        <button type="submit" class="w-full py-4 bg-cinematic-red text-white font-bold rounded shadow-[0_0_15px_rgba(229,9,20,0.4)] hover:bg-red-700 transition-colors uppercase tracking-wider cursor-pointer">
                            Thanh Toán
                        </button>
                        
                        <a href="{{ route('booking.checkout', $booking->id) }}" class="block text-center mt-4 text-sm text-gray-400 hover:text-white transition-colors">
                            Bỏ qua chọn bắp nước
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ticketPriceEl = document.getElementById('ticketPrice');
        const ticketPrice = parseFloat(ticketPriceEl ? ticketPriceEl.dataset.value : 0) || 0;
        const totalPriceLabel = document.getElementById('totalPriceLabel');
        const foodSummaryWrapper = document.getElementById('foodSummaryWrapper');
        const foodSummaryList = document.getElementById('foodSummaryList');
        
        function updateSummary() {
            let foodTotal = 0;
            let foodItemsHTML = '';
            
            document.querySelectorAll('.food-quantity').forEach(input => {
                const qty = parseInt(input.value) || 0;
                if (qty > 0) {
                    const price = parseFloat(input.dataset.price) || 0;
                    const name = input.dataset.name || 'Món ăn';
                    const itemTotal = qty * price;
                    foodTotal += itemTotal;
                    
                    foodItemsHTML += `<div class="flex justify-between items-center text-sm py-1 border-b border-white/5">
                        <span class="text-gray-300">${qty}x ${name}</span>
                        <span class="font-semibold text-cinematic-gold">${new Intl.NumberFormat('vi-VN').format(itemTotal)} đ</span>
                    </div>`;
                }
            });
            
            if (foodTotal > 0) {
                foodSummaryWrapper.classList.remove('hidden');
                foodSummaryList.innerHTML = foodItemsHTML;
            } else {
                foodSummaryWrapper.classList.add('hidden');
                foodSummaryList.innerHTML = '';
            }
            
            const finalTotal = ticketPrice + foodTotal;
            totalPriceLabel.textContent = new Intl.NumberFormat('vi-VN').format(finalTotal) + ' VNĐ';
        }
        
        document.querySelectorAll('.btn-decrease').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const input = this.parentElement.querySelector('.food-quantity');
                if (input) {
                    let currentVal = parseInt(input.value) || 0;
                    if (currentVal > 0) {
                        input.value = currentVal - 1;
                        updateSummary();
                    }
                }
            });
        });
        
        document.querySelectorAll('.btn-increase').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const input = this.parentElement.querySelector('.food-quantity');
                if (input) {
                    let currentVal = parseInt(input.value) || 0;
                    if (currentVal < 10) {
                        input.value = currentVal + 1;
                        updateSummary();
                    }
                }
            });
        });
        
        document.querySelectorAll('.food-quantity').forEach(input => {
            input.addEventListener('input', function() {
                let val = parseInt(this.value) || 0;
                if (val < 0) this.value = 0;
                if (val > 10) this.value = 10;
                updateSummary();
            });
            input.addEventListener('change', updateSummary);
        });

        // Run initial update
        updateSummary();

        // 5-Minute Seat Hold Countdown Timer
        let holdSeconds = {{ max(0, $booking->remaining_seconds) }};
        const countdownEl = document.getElementById('holdCountdown');

        function updateHoldTimer() {
            if (holdSeconds <= 0) {
                if (countdownEl) countdownEl.textContent = '00:00';
                clearInterval(holdInterval);
                alert('Thời gian giữ ghế (5 phút) đã hết! Ghế đã được hoàn trả về trạng thái trống. Vui lòng chọn lại ghế.');
                window.location.href = "{{ route('booking.seats', $booking->showtime_id) }}";
                return;
            }
            const mins = Math.floor(holdSeconds / 60);
            const secs = holdSeconds % 60;
            if (countdownEl) {
                countdownEl.textContent = `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
                if (holdSeconds <= 60) {
                    countdownEl.classList.remove('text-cinematic-gold');
                    countdownEl.classList.add('text-red-500', 'animate-pulse');
                }
            }
            holdSeconds--;
        }

        updateHoldTimer();
        const holdInterval = setInterval(updateHoldTimer, 1000);
    });
</script>
@endsection
