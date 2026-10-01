@extends('layouts.app')

@section('title', 'Thanh Toán Quét Mã QR MB Bank - HCTV')

@section('content')
<div class="pt-24 pb-16 bg-cinematic-dark min-h-screen text-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 md:px-12">
        
        <!-- Header -->
        <div class="text-center mb-6">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-blue-500/20 text-blue-300 border border-blue-500/30 mb-2">
                <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                Cổng thanh toán tự động VietQR • SePay
            </span>
            <h1 class="qr-page-title text-2xl sm:text-3xl md:text-4xl font-serif font-bold text-white uppercase tracking-wider">
                Thanh Toán Quét Mã QR MB Bank
            </h1>
            <p class="qr-page-subtitle text-sm text-gray-400 mt-1 max-w-xl mx-auto">
                Hệ thống tự động kích hoạt vé ngay khi tài khoản ngân hàng Quân Đội (MB) nhận được chuyển khoản.
            </p>
        </div>

        <!-- 5-Minute Hold Countdown Banner -->
        <div class="hold-countdown-banner checkout-countdown-banner mb-8 p-4 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-lg">
            <div class="flex items-center gap-3.5">
                <div class="hold-countdown-icon checkout-countdown-icon animate-pulse">
                    ⏱️
                </div>
                <div>
                    <p class="hold-countdown-title checkout-countdown-title flex flex-wrap items-center gap-2">
                        Thời gian giữ ghế còn lại
                        <span class="hold-countdown-seats checkout-countdown-seats">({{ $booking->tickets->map(fn($t) => $t->seat ? ($t->seat->row . $t->seat->number) : '')->filter()->join(', ') }})</span>
                    </p>
                    <p class="hold-countdown-desc checkout-countdown-desc">Vui lòng quét mã và chuyển tiền trước khi đồng hồ về 00:00.</p>
                </div>
            </div>
            <div class="hold-countdown-box checkout-countdown-box">
                <span class="hold-countdown-label checkout-countdown-label">Thời gian còn lại</span>
                <span id="holdCountdown" class="hold-countdown-digits checkout-countdown-digits">05:00</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left: QR Code Box (lg:col-span-5) -->
            <div class="qr-card-box lg:col-span-5 bg-white/5 border border-white/10 rounded-2xl p-6 shadow-2xl relative overflow-hidden backdrop-blur-sm">
                <!-- Top Brand Tag -->
                <div class="flex items-center justify-between pb-4 border-b border-white/10 mb-5">
                    <div class="flex items-center gap-2.5 cursor-pointer select-none" id="secretMbTrigger" onclick="handleSecretClick()">
                        <div class="w-10 h-8 bg-white rounded flex items-center justify-center p-1 shadow border border-blue-200">
                            <span class="font-black text-[#002D72] text-sm leading-none tracking-tighter">MB</span>
                            <span class="text-red-600 font-black text-[10px] leading-none">★</span>
                        </div>
                        <div>
                            <span class="qr-bank-label text-xs uppercase tracking-wider text-gray-400 block leading-tight">Ngân hàng thụ hưởng</span>
                            <span class="qr-bank-name text-sm font-bold text-white">MBBank (Quân Đội)</span>
                        </div>
                    </div>
                    <span class="text-xs px-2.5 py-1 bg-green-500/20 text-green-300 border border-green-500/30 rounded-full font-medium flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-400 animate-ping"></span>
                        VietQR 24/7
                    </span>
                </div>

                <!-- QR Display -->
                <div class="relative bg-white rounded-2xl p-4 shadow-xl mx-auto max-w-[320px] border-4 border-blue-600/30 group">
                    <!-- Scanner Laser Animation -->
                    <div class="absolute inset-x-4 top-4 h-1 bg-gradient-to-r from-transparent via-blue-500 to-transparent shadow-[0_0_12px_#3b82f6] animate-pulse pointer-events-none opacity-80"></div>
                    
                    <img id="qrImage" 
                         src="{{ $sepayQrUrl }}" 
                         alt="Mã QR MB Bank {{ $transferContent }}" 
                         onerror="this.onerror=null; this.src='{{ $vietQrUrl }}';"
                         class="w-full h-auto aspect-square object-contain rounded-lg">
                    
                    <!-- Corner decorative markers -->
                    <div class="absolute top-2 left-2 w-3 h-3 border-t-2 border-l-2 border-blue-600"></div>
                    <div class="absolute top-2 right-2 w-3 h-3 border-t-2 border-r-2 border-blue-600"></div>
                    <div class="absolute bottom-2 left-2 w-3 h-3 border-b-2 border-l-2 border-blue-600"></div>
                    <div class="absolute bottom-2 right-2 w-3 h-3 border-b-2 border-r-2 border-blue-600"></div>
                </div>

                <!-- Status Waiting Badge -->
                <div id="paymentWaitingStatus" class="qr-waiting-box mt-5 p-3.5 bg-blue-950/40 border border-blue-500/30 rounded-xl text-center flex items-center justify-center gap-3">
                    <div class="w-5 h-5 border-2 border-blue-400 border-t-transparent rounded-full animate-spin shrink-0"></div>
                    <span class="qr-waiting-text text-xs sm:text-sm font-semibold text-blue-200">
                        Đang chờ bạn quét mã & chuyển khoản...
                    </span>
                </div>

                <!-- Success Box (Hidden initially) -->
                <div id="paymentSuccessNotice" class="hidden mt-5 p-4 bg-green-950/80 border-2 border-green-500 rounded-xl text-center shadow-lg animate-bounce">
                    <div class="text-3xl mb-1">🎉</div>
                    <h4 class="text-base font-bold text-green-400">Đã nhận được thanh toán!</h4>
                    <p class="text-xs text-green-200 mt-1">Đang hoàn tất đơn hàng và chuyển đến vé của bạn...</p>
                </div>

                <!-- Action Buttons: Download QR & Open App -->
                <div class="mt-5 grid grid-cols-2 gap-2.5">
                    <a href="{{ $sepayQrUrl }}" download="QR_MBBank_HCTV_{{ $booking->id }}.png" target="_blank" class="qr-btn-action py-2.5 px-3 bg-white/10 hover:bg-white/20 text-gray-200 hover:text-white rounded-lg text-xs font-semibold flex items-center justify-center gap-1.5 transition border border-white/10 text-center">
                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <span>Tải ảnh QR</span>
                    </a>
                    <button type="button" onclick="navigator.clipboard.writeText('{{ $transferContent }}'); showToast('Đã sao chép nội dung: {{ $transferContent }}');" class="qr-btn-copy py-2.5 px-3 bg-blue-600/30 hover:bg-blue-600/50 text-blue-300 hover:text-white rounded-lg text-xs font-semibold flex items-center justify-center gap-1.5 transition border border-blue-500/40 text-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        <span>Copy cú pháp</span>
                    </button>
                </div>

                <!-- 3 steps instructions -->
                <div class="qr-instructions mt-5 pt-4 border-t border-white/10 text-[11px] text-gray-400 space-y-1.5 leading-relaxed">
                    <p class="font-bold text-gray-300">Hướng dẫn thanh toán:</p>
                    <p>1. Mở App ngân hàng bất kỳ (MBBank, VCB, Techcombank, MoMo,...).</p>
                    <p>2. Chọn tính năng <strong>Quét mã QR</strong> và quét mã trên.</p>
                    <p>3. Kiểm tra số tiền & bấm <strong>Xác nhận</strong> (không sửa nội dung).</p>
                </div>
            </div>

            <!-- Right: Account Details & Booking Summary (lg:col-span-7) -->
            <div class="lg:col-span-7 space-y-6">
                
                <!-- Bank Info Card with 1-click Copy -->
                <div class="qr-card-box bg-white/5 border border-white/10 rounded-2xl p-6 shadow-xl space-y-4 backdrop-blur-sm">
                    <h3 class="qr-bank-info-title text-base font-bold text-white flex items-center gap-2 select-none cursor-default" ondblclick="toggleDemoBox()">
                        <span class="text-cinematic-gold">🏦</span> Thông tin tài khoản thụ hưởng MB Bank
                    </h3>

                    <div class="space-y-3">
                        <!-- Account Number -->
                        <div class="qr-info-row flex items-center justify-between p-3.5 bg-black/40 border border-white/5 rounded-xl hover:border-blue-500/40 transition">
                            <div>
                                <span class="qr-info-label text-[11px] text-gray-400 block">Số tài khoản:</span>
                                <span class="qr-info-value text-lg font-mono font-bold text-white tracking-wider">{{ $bankAccount }}</span>
                            </div>
                            <button type="button" 
                                    onclick="copyToClipboard('{{ $bankAccount }}', 'Đã sao chép số tài khoản!')" 
                                    class="px-3 py-1.5 bg-blue-600/30 hover:bg-blue-600 text-blue-300 hover:text-white rounded-lg text-xs font-semibold transition border border-blue-500/30 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span>Sao chép</span>
                            </button>
                        </div>

                        <!-- Account Holder -->
                        <div class="qr-info-row flex items-center justify-between p-3.5 bg-black/40 border border-white/5 rounded-xl">
                            <div>
                                <span class="qr-info-label text-[11px] text-gray-400 block">Chủ tài khoản:</span>
                                <span class="qr-info-value text-base font-bold text-white uppercase">{{ $accountHolder }}</span>
                            </div>
                            <span class="qr-bank-sub text-xs text-gray-400 font-mono">MBBank Quân Đội</span>
                        </div>

                        <!-- Amount -->
                        <div class="qr-info-row flex items-center justify-between p-3.5 bg-black/40 border border-white/5 rounded-xl hover:border-cinematic-gold/40 transition">
                            <div>
                                <span class="qr-info-label text-[11px] text-gray-400 block">Số tiền cần thanh toán:</span>
                                <span class="qr-info-value-gold text-2xl font-bold text-cinematic-gold font-mono">{{ number_format($booking->total_price, 0, ',', '.') }} đ</span>
                            </div>
                            <button type="button" 
                                    onclick="copyToClipboard('{{ (int)$booking->total_price }}', 'Đã sao chép số tiền!')" 
                                    class="px-3 py-1.5 bg-yellow-500/20 hover:bg-yellow-500 text-yellow-300 hover:text-black rounded-lg text-xs font-semibold transition border border-yellow-500/30 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span>Sao chép số tiền</span>
                            </button>
                        </div>

                        <!-- Transfer Content (Crucial for SePay) -->
                        <div class="qr-transfer-box p-4 bg-gradient-to-r from-blue-950/60 to-purple-950/40 border-2 border-blue-500/60 rounded-xl relative shadow-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="qr-transfer-label text-xs font-bold text-blue-300 uppercase tracking-wider block flex items-center gap-1.5">
                                        <span>⚠️</span> Nội dung chuyển khoản (Bắt buộc chính xác):
                                    </span>
                                    <span class="qr-transfer-code text-2xl font-black text-cinematic-gold font-mono tracking-widest mt-1 block select-all">
                                        {{ $transferContent }}
                                    </span>
                                </div>
                                <button type="button" 
                                        onclick="copyToClipboard('{{ $transferContent }}', 'Đã sao chép mã nội dung!')" 
                                        class="px-4 py-2 bg-cinematic-gold hover:bg-yellow-400 text-gray-950 font-bold rounded-lg text-xs shadow transition flex items-center gap-1.5 shrink-0 ml-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    <span>Sao chép mã</span>
                                </button>
                            </div>
                            <p class="qr-transfer-note text-[11px] text-gray-300 mt-2">
                                💡 Cổng thanh toán SePay nhận diện vé của bạn qua nội dung <strong class="qr-transfer-bold text-white">{{ $transferContent }}</strong>. Vui lòng giữ nguyên khi chuyển khoản.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Booking Summary Card -->
                <div class="qr-card-box bg-white/5 border border-white/10 rounded-2xl p-6 shadow-xl backdrop-blur-sm">
                    <h3 class="qr-summary-title text-sm font-bold text-gray-300 uppercase tracking-wider mb-3">Tóm tắt đơn hàng #{{ $booking->id }}</h3>
                    <div class="flex items-center gap-4 pb-4 border-b border-white/10">
                        <img src="{{ $booking->showtime->movie->poster_url }}" alt="{{ $booking->showtime->movie->title }}" class="w-14 h-20 object-cover rounded-lg shadow shrink-0">
                        <div class="min-w-0 flex-1">
                            <h4 class="qr-summary-movie-title font-bold text-white text-base truncate">{{ $booking->showtime->movie->title }}</h4>
                            <p class="qr-summary-cinema text-xs text-gray-400 mt-0.5">{{ $booking->showtime->room->cinema->name }} • {{ $booking->showtime->room->name }}</p>
                            <p class="qr-summary-time text-xs text-cinematic-gold mt-0.5">{{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('H:i | d/m/Y') }}</p>
                        </div>
                    </div>

                    <div class="pt-3 text-xs space-y-1.5 text-gray-300">
                        <div class="flex justify-between">
                            <span class="qr-summary-label text-gray-400">Ghế đã chọn:</span>
                            <span class="qr-summary-seats font-bold text-cinematic-gold">
                                {{ $booking->tickets->map(fn($t) => $t->seat ? ($t->seat->row . $t->seat->number) : '')->filter()->join(', ') }}
                            </span>
                        </div>
                        @if(isset($bookingFoods) && $bookingFoods->count() > 0)
                            <div class="flex justify-between">
                                <span class="qr-summary-label text-gray-400">Bắp & Nước:</span>
                                <span class="qr-summary-foods">{{ $bookingFoods->map(fn($f) => $f->quantity . 'x ' . $f->name)->join(', ') }}</span>
                            </div>
                        @endif
                        @if($booking->discount_amount > 0)
                            <div class="flex justify-between text-green-400 font-semibold">
                                <span>Giảm giá điểm tích lũy:</span>
                                <span>-{{ number_format($booking->discount_amount, 0, ',', '.') }} đ</span>
                            </div>
                        @endif
                        <div class="flex justify-between pt-2 border-t border-white/10 text-sm">
                            <span class="qr-summary-total-label text-gray-200 font-semibold">Tổng thanh toán:</span>
                            <span class="qr-summary-total-val text-lg font-bold text-cinematic-gold">{{ number_format($booking->total_price, 0, ',', '.') }} đ</span>
                        </div>
                    </div>
                </div>

                <!-- Simulation & SePay Testing Box (For Localhost / Demo) - Hidden by default -->
                <div id="sepayDemoBox" class="hidden qr-demo-box p-5 rounded-2xl bg-gradient-to-r from-gray-900 via-gray-900 to-blue-950/60 border border-emerald-500/40 shadow-2xl space-y-3 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <span class="qr-demo-title text-xs font-bold text-cinematic-gold uppercase tracking-wider flex items-center gap-1.5">
                            <span>⚡</span> Công cụ Kích Hoạt Nhanh:
                        </span>
                        <div class="flex items-center gap-2">
                            <span class="qr-demo-sub text-[11px] text-gray-400">Dành cho quản trị</span>
                            <button type="button" onclick="toggleDemoBox()" class="text-xs text-gray-400 hover:text-white px-2 py-0.5 rounded bg-white/10 hover:bg-white/20 transition cursor-pointer">✕ Ẩn đi</button>
                        </div>
                    </div>
                    <p class="qr-demo-desc text-xs text-gray-400 leading-relaxed">
                        Bạn có thể bấm nút bên dưới để hệ thống kích hoạt ngay vé xem phim và xác nhận thanh toán thành công:
                    </p>
                    <form action="{{ route('booking.simulate_qr_paid', $booking->id) }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-emerald-600 to-teal-700 hover:from-emerald-500 hover:to-teal-600 text-white font-bold rounded-xl text-xs uppercase tracking-wider shadow transition flex items-center justify-center gap-2 cursor-pointer hover:scale-[1.01]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            <span>Xác Nhận Nhận Tiền Thành Công (Kích Hoạt Ngay)</span>
                        </button>
                    </form>
                </div>

                <!-- Webhook Documentation Link / Info -->
                <div class="text-xs text-gray-400 text-center flex items-center justify-center gap-2">
                    <a href="{{ route('booking.checkout', $booking->id) }}" class="text-gray-400 hover:text-white underline">
                        ← Quay lại chọn phương thức khác
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toastNotification" class="fixed bottom-6 right-6 z-50 transform translate-y-20 opacity-0 transition-all duration-300 pointer-events-none">
    <div class="bg-gray-900 text-white px-5 py-3 rounded-xl shadow-2xl border border-cinematic-gold/40 flex items-center gap-2.5 text-xs font-semibold">
        <span class="text-green-400">✓</span>
        <span id="toastMessage">Đã sao chép!</span>
    </div>
</div>

<script>
// Clipboard Helper
function copyToClipboard(text, message) {
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text).then(() => {
            showToast(message || 'Đã sao chép vào bộ nhớ đệm!');
        }).catch(() => {
            fallbackCopy(text, message);
        });
    } else {
        fallbackCopy(text, message);
    }
}

function fallbackCopy(text, message) {
    const tempInput = document.createElement('textarea');
    tempInput.value = text;
    document.body.appendChild(tempInput);
    tempInput.select();
    try {
        document.execCommand('copy');
        showToast(message || 'Đã sao chép vào bộ nhớ đệm!');
    } catch (err) {
        alert('Nội dung: ' + text);
    }
    document.body.removeChild(tempInput);
}

function showToast(msg) {
    const toast = document.getElementById('toastNotification');
    const toastMsg = document.getElementById('toastMessage');
    if (!toast || !toastMsg) return;
    toastMsg.textContent = msg;
    toast.classList.remove('translate-y-20', 'opacity-0');
    toast.classList.add('translate-y-0', 'opacity-100');
    setTimeout(() => {
        toast.classList.remove('translate-y-0', 'opacity-100');
        toast.classList.add('translate-y-20', 'opacity-0');
    }, 2500);
}

// 5-Minute Hold Timer
document.addEventListener('DOMContentLoaded', function() {
    let holdSeconds = {{ max(0, $booking->remaining_seconds) }};
    const countdownEl = document.getElementById('holdCountdown');

    function updateHoldTimer() {
        if (holdSeconds <= 0) {
            if (countdownEl) countdownEl.textContent = '00:00';
            clearInterval(holdInterval);
            clearInterval(pollInterval);
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

    // Auto Polling to Check SePay Payment Status every 2.5s
    let isRedirecting = false;
    async function checkPaymentStatus() {
        if (isRedirecting) return;
        try {
            const res = await fetch("{{ route('booking.check_status', $booking->id) }}", {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            if (!res.ok) return;
            const data = await res.json();
            if (data.is_paid) {
                isRedirecting = true;
                clearInterval(pollInterval);
                clearInterval(holdInterval);

                const waitingStatus = document.getElementById('paymentWaitingStatus');
                const successNotice = document.getElementById('paymentSuccessNotice');
                if (waitingStatus) waitingStatus.classList.add('hidden');
                if (successNotice) successNotice.classList.remove('hidden');

                showToast('🎉 Thanh toán thành công! Đang chuyển hướng...');
                setTimeout(() => {
                    window.location.href = data.redirect_url || "{{ route('booking.success', $booking->id) }}";
                }, 1200);
            }
        } catch (e) {
            console.warn('Checking payment status...', e);
        }
    }

    const pollInterval = setInterval(checkPaymentStatus, 2500);

    // ==========================================
    // CƠ CHẾ BÍ MẬT: BẬT / TẮT NÚT GIẢ LẬP DEMO
    // ==========================================
    // 1. Kiểm tra tham số URL (?demo=1 hoặc ?test=1)
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('demo') === '1' || urlParams.get('test') === '1') {
        const box = document.getElementById('sepayDemoBox');
        if (box) box.classList.remove('hidden');
    }

    // 2. Phím tắt bàn phím: Ctrl + Shift + D hoặc phím F2
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey && e.shiftKey && (e.key === 'D' || e.key === 'd')) || e.key === 'F2') {
            e.preventDefault();
            toggleDemoBox();
        }
    });
});

// Hàm bật/tắt hiển thị hộp thoại giả lập
function toggleDemoBox() {
    const box = document.getElementById('sepayDemoBox');
    if (!box) return;
    if (box.classList.contains('hidden')) {
        box.classList.remove('hidden');
        showToast('⚡ Công cụ kích hoạt nhanh đã sẵn sàng!');
        setTimeout(() => {
            box.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }, 100);
    } else {
        box.classList.add('hidden');
        showToast('🔒 Đã ẩn công cụ kích hoạt nhanh.');
    }
}

// Click 3 lần liên tiếp vào logo MB Bank để mở bí mật
let secretClickCount = 0;
let secretClickTimer = null;
function handleSecretClick() {
    secretClickCount++;
    clearTimeout(secretClickTimer);
    secretClickTimer = setTimeout(() => {
        secretClickCount = 0;
    }, 1500);

    if (secretClickCount >= 3) {
        secretClickCount = 0;
        toggleDemoBox();
    }
}
</script>
@endsection
