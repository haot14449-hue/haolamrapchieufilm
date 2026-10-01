@extends('layouts.app')

@section('title', 'Thanh Toán - HCTV')

@section('content')
<div class="pt-24 pb-12 bg-cinematic-dark min-h-screen">
    <div class="max-w-5xl mx-auto px-6 md:px-12">
        
        <h2 class="checkout-page-title text-3xl font-serif font-bold text-white mb-6 text-center uppercase tracking-widest">Thanh Toán</h2>

        @if(session('error'))
            <div class="bg-red-500/20 border border-red-500 text-red-200 px-5 py-4 rounded-xl mb-6 shadow-lg flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-500/20 border border-green-500 text-green-200 px-5 py-4 rounded-xl mb-6 shadow-lg flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

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
                    <p class="hold-countdown-desc checkout-countdown-desc">Vui lòng hoàn tất thanh toán trước khi hết thời gian giữ chỗ để không bị mất ghế.</p>
                </div>
            </div>
            <div class="hold-countdown-box checkout-countdown-box">
                <span class="hold-countdown-label checkout-countdown-label">Thời gian còn lại</span>
                <span id="holdCountdown" class="hold-countdown-digits checkout-countdown-digits">05:00</span>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Payment Methods & Reward Points -->
            <div class="flex-1 space-y-6">
                <!-- Voucher & Promo Code Card -->
                <div id="voucherSection" class="checkout-discount-card bg-white/5 border border-white/10 rounded-xl p-6 md:p-8 shadow-xl">
                    <div class="flex items-center justify-between pb-4 border-b border-white/10 mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-cinematic-red/20 text-cinematic-red flex items-center justify-center font-bold text-lg border border-cinematic-red/30">
                                🎟️
                            </div>
                            <div>
                                <h3 class="checkout-card-heading text-xl font-bold text-white flex items-center gap-2">
                                    Mã Voucher & Ưu Đãi
                                    <span class="px-2 py-0.5 rounded-full bg-cinematic-gold/20 text-cinematic-gold border border-cinematic-gold/40 text-[10px] font-bold">Giảm giá</span>
                                </h3>
                                <p class="text-xs text-gray-400">Chọn voucher từ ưu đãi của rạp hoặc nhập mã khuyến mãi để giảm giá đơn hàng</p>
                            </div>
                        </div>
                        <a href="{{ route('account.vouchers') }}" target="_blank" class="text-xs text-cinematic-gold hover:underline font-semibold flex items-center gap-1 shrink-0">
                            <span>Ví Voucher của tôi</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </div>

                    @if(!empty($booking->promotion_id) && $booking->promotion)
                        <!-- Voucher Already Applied -->
                        <div class="p-4 bg-emerald-500/15 border border-emerald-500/30 rounded-xl flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <svg class="w-6 h-6 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <div>
                                    <p class="text-white text-sm font-bold flex items-center gap-2 flex-wrap">
                                        Đã áp dụng mã: <span class="checkout-voucher-code">{{ $booking->promotion->code }}</span>
                                        <span class="text-emerald-400 font-extrabold">(-{{ number_format($booking->discount_amount, 0, ',', '.') }} đ)</span>
                                    </p>
                                    <p class="text-xs text-gray-300 mt-0.5">{{ $booking->promotion->title }}</p>
                                </div>
                            </div>
                            <form action="{{ route('booking.remove_voucher', $booking->id) }}" method="POST" class="shrink-0 m-0">
                                @csrf
                                <button type="submit" class="px-3.5 py-1.5 bg-white/10 hover:bg-red-500/20 text-gray-200 hover:text-red-300 text-xs font-semibold rounded-lg border border-white/15 hover:border-red-500/30 transition cursor-pointer">
                                    Hủy áp dụng
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- Form to Apply Voucher -->
                        <div class="space-y-4">
                            <!-- Direct Code Input -->
                            <form action="{{ route('booking.apply_voucher', $booking->id) }}" method="POST" class="m-0">
                                @csrf
                                <div class="flex gap-2">
                                    <div class="relative flex-1">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                            <svg class="w-4 h-4 text-cinematic-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                        </div>
                                        <input type="text" 
                                               name="code" 
                                               id="voucherCodeInput"
                                               placeholder="Nhập mã ưu đãi (Ví dụ: WELCOME2026, HCTVHSSV...)"
                                               class="w-full pl-10 pr-3.5 py-2.5 bg-black/40 border border-white/20 focus:border-cinematic-gold focus:ring-1 focus:ring-cinematic-gold text-white text-xs sm:text-sm rounded-xl uppercase font-mono tracking-wider outline-none transition">
                                    </div>
                                    <button type="submit" class="px-5 py-2.5 bg-cinematic-red hover:bg-red-700 text-white text-xs font-bold rounded-xl shadow transition uppercase tracking-wider whitespace-nowrap cursor-pointer">
                                        Áp dụng
                                    </button>
                                </div>
                            </form>

                            <!-- Tab Navigation for Vouchers -->
                            <div class="pt-2">
                                <div class="flex items-center gap-2 mb-3">
                                    <button type="button" 
                                            id="checkoutTabCinemaBtn" 
                                            onclick="switchCheckoutVoucherTab('cinema')"
                                            class="checkout-v-tab-btn active">
                                        <span>🌟 Ưu đãi rạp HCTV</span>
                                        <span class="px-1.5 py-0.2 rounded-full bg-white/20 text-[10px]">{{ $availablePromotions->count() }}</span>
                                    </button>
                                    <button type="button" 
                                            id="checkoutTabWalletBtn" 
                                            onclick="switchCheckoutVoucherTab('wallet')"
                                            class="checkout-v-tab-btn">
                                        <span>🎟️ Ví voucher của tôi</span>
                                        <span class="px-1.5 py-0.2 rounded-full bg-white/10 text-[10px]">{{ $userVouchers->count() }}</span>
                                    </button>
                                </div>

                                <!-- Tab 1: Available Cinema Promotions -->
                                <div id="checkoutTabCinemaContent" class="space-y-2">
                                    @if($availablePromotions->isEmpty())
                                        <div class="p-4 rounded-xl bg-black/20 border border-white/10 text-center text-xs text-gray-400">
                                            Hiện chưa có chương trình khuyến mãi nào đang diễn ra.
                                        </div>
                                    @else
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 max-h-72 overflow-y-auto pr-1">
                                            @foreach($availablePromotions as $promo)
                                                <div class="checkout-voucher-item flex items-center justify-between gap-3">
                                                    <div class="min-w-0 flex-1">
                                                        <div class="flex items-center gap-1.5 flex-wrap">
                                                            <span class="checkout-voucher-code text-[11px]">{{ $promo->code }}</span>
                                                            <span class="px-2 py-0.5 rounded bg-cinematic-red text-white text-[10px] font-black">{{ $promo->formattedDiscount() }}</span>
                                                        </div>
                                                        <h4 class="text-xs font-bold text-white mt-1 truncate">{{ $promo->title }}</h4>
                                                        <p class="text-[11px] text-gray-400 mt-0.5 flex items-center gap-1">
                                                            <span>📅 HSD:</span>
                                                            <span>{{ $promo->end_date ? \Carbon\Carbon::parse($promo->end_date)->format('d/m/Y') : 'Dài hạn' }}</span>
                                                        </p>
                                                    </div>
                                                    <form action="{{ route('booking.apply_voucher', $booking->id) }}" method="POST" class="m-0 shrink-0">
                                                        @csrf
                                                        <input type="hidden" name="promotion_id" value="{{ $promo->id }}">
                                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-cinematic-gold hover:bg-yellow-500 text-gray-950 font-bold text-xs uppercase tracking-wider transition shadow cursor-pointer">
                                                            Áp dụng
                                                        </button>
                                                    </form>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <!-- Tab 2: User's Wallet Vouchers -->
                                <div id="checkoutTabWalletContent" class="hidden space-y-2">
                                    @if($userVouchers->isEmpty())
                                        <div class="p-5 rounded-xl bg-black/20 border border-white/10 text-center">
                                            <p class="text-xs text-gray-400 mb-2">Ví voucher của bạn hiện chưa có voucher nào đã lưu.</p>
                                            <button type="button" onclick="switchCheckoutVoucherTab('cinema')" class="text-xs text-cinematic-gold hover:underline font-semibold">
                                                ← Xem danh sách ưu đãi rạp bên cạnh để áp dụng ngay
                                            </button>
                                        </div>
                                    @else
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 max-h-72 overflow-y-auto pr-1">
                                            @foreach($userVouchers as $uv)
                                                @php $vp = $uv->promotion; @endphp
                                                @if($vp)
                                                    <div class="checkout-voucher-item flex items-center justify-between gap-3">
                                                        <div class="min-w-0 flex-1">
                                                            <div class="flex items-center gap-1.5 flex-wrap">
                                                                <span class="checkout-voucher-code text-[11px]">{{ $vp->code }}</span>
                                                                <span class="px-2 py-0.5 rounded bg-cinematic-red text-white text-[10px] font-black">{{ $vp->formattedDiscount() }}</span>
                                                            </div>
                                                            <h4 class="text-xs font-bold text-white mt-1 truncate">{{ $vp->title }}</h4>
                                                            <p class="text-[11px] text-gray-400 mt-0.5 flex items-center gap-1">
                                                                <span>📅 HSD:</span>
                                                                <span>{{ $vp->end_date ? \Carbon\Carbon::parse($vp->end_date)->format('d/m/Y') : 'Dài hạn' }}</span>
                                                            </p>
                                                        </div>
                                                        <form action="{{ route('booking.apply_voucher', $booking->id) }}" method="POST" class="m-0 shrink-0">
                                                            @csrf
                                                            <input type="hidden" name="promotion_id" value="{{ $vp->id }}">
                                                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-cinematic-gold hover:bg-yellow-500 text-gray-950 font-bold text-xs uppercase tracking-wider transition shadow cursor-pointer">
                                                                Áp dụng
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Reward Points Redemption Card -->
                <div class="bg-white/5 border border-white/10 rounded-xl p-6 md:p-8 shadow-xl">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-cinematic-gold/20 text-cinematic-gold flex items-center justify-center font-bold text-lg border border-cinematic-gold/30">
                                💎
                            </div>
                            <div>
                                <h3 class="checkout-card-heading text-xl font-bold text-white">Điểm tích lũy HCTV</h3>
                                <p class="text-xs text-gray-400">10 điểm = 10.000 đ giảm giá trực tiếp vào hóa đơn</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-gray-400 block">Số dư hiện tại:</span>
                            <span class="text-xl font-black text-cinematic-gold">{{ number_format($userPoints) }}</span>
                            <span class="text-xs text-gray-300 font-semibold">điểm</span>
                            <span class="text-[11px] text-gray-400 block">({{ number_format($userPoints * 1000, 0, ',', '.') }} đ)</span>
                        </div>
                    </div>

                    @if($booking->points_used > 0)
                        <!-- Points Already Applied Notice -->
                        <div class="p-4 bg-green-500/10 border border-green-500/30 rounded-xl flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <svg class="w-6 h-6 text-green-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <div>
                                    <p class="checkout-applied-text text-white text-sm font-bold">
                                        Đã áp dụng: <span class="text-cinematic-gold">{{ $booking->points_used }} điểm</span>
                                        (Giảm <span class="text-green-400">{{ number_format($booking->discount_amount, 0, ',', '.') }} đ</span>)
                                    </p>
                                    <p class="text-xs text-gray-400">Số tiền được trừ trực tiếp vào tổng thanh toán của đơn hàng.</p>
                                </div>
                            </div>
                            <form action="{{ route('booking.remove_points', $booking->id) }}" method="POST" class="shrink-0 m-0">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 bg-white/10 hover:bg-red-500/20 text-gray-300 hover:text-red-300 text-xs font-semibold rounded-lg border border-white/10 hover:border-red-500/30 transition">
                                    Hủy áp dụng
                                </button>
                            </form>
                        </div>
                    @elseif($userPoints > 0)
                        <!-- Form to Redeem Points -->
                        <form action="{{ route('booking.apply_points', $booking->id) }}" method="POST" class="space-y-3">
                            @csrf
                            <div class="checkout-points-box p-4 bg-black/40 rounded-xl border border-white/5 space-y-3">
                                <label class="checkout-points-label block text-xs font-semibold text-gray-300">
                                    Nhập số điểm muốn sử dụng (Tối đa: <span class="text-cinematic-gold font-bold">{{ $maxUsablePoints }} điểm</span>):
                                </label>
                                <div class="flex gap-2">
                                    <div class="relative flex-1">
                                        <input type="number" 
                                               name="points" 
                                               id="pointsInput" 
                                               min="1" 
                                               max="{{ $maxUsablePoints }}" 
                                               value="{{ $maxUsablePoints }}"
                                               placeholder="Nhập số điểm..."
                                               class="checkout-points-input w-full bg-[#131722] border border-white/20 focus:border-cinematic-gold focus:ring-1 focus:ring-cinematic-gold text-white text-sm rounded-lg px-4 py-2.5">
                                        <span class="absolute right-3 top-2.5 text-xs text-gray-400">điểm</span>
                                    </div>
                                    <button type="button" 
                                            onclick="document.getElementById('pointsInput').value = {{ $maxUsablePoints }}"
                                            class="checkout-points-max-btn px-3 py-2 bg-white/10 hover:bg-white/20 text-xs text-cinematic-gold font-semibold rounded-lg transition whitespace-nowrap">
                                        Dùng tối đa
                                    </button>
                                    <button type="submit" 
                                            class="px-5 py-2 bg-cinematic-gold hover:bg-yellow-500 text-gray-950 text-xs font-bold rounded-lg shadow transition whitespace-nowrap">
                                        Áp dụng
                                    </button>
                                </div>
                                <p class="text-[11px] text-gray-400 flex items-center gap-1.5">
                                    <span>💡</span> Bạn có thể đổi điểm để giảm tối đa 100% hóa đơn.
                                </p>
                            </div>
                        </form>
                    @else
                        <!-- No points yet -->
                        <div class="checkout-no-points-box p-4 bg-black/30 rounded-xl border border-white/5 flex items-center gap-3">
                            <span class="text-gray-500 text-xl">ℹ️</span>
                            <div class="text-xs text-gray-400 leading-relaxed">
                                Bạn hiện có <strong class="checkout-points-bold text-white">0 điểm tích lũy</strong>. Hoàn tất đặt vé đơn hàng này để nhận ngay <strong class="text-cinematic-gold">+{{ $earnedPoints }} điểm</strong> (= {{ number_format($earnedPoints * 1000, 0, ',', '.') }} đ) cho những lần xem phim tiếp theo!
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Payment Methods Card -->
                <div class="bg-white/5 border border-white/10 rounded-xl p-6 md:p-8 shadow-xl">
                    <h3 class="checkout-card-heading text-xl font-bold text-white mb-6 flex items-center justify-between">
                        <span>Phương thức thanh toán</span>
                        <span class="text-xs font-normal text-gray-400">Chọn 1 phương thức</span>
                    </h3>
                    
                    <form id="paymentForm" action="{{ route('booking.process_payment', $booking->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="payment_method" id="selectedPaymentMethod" value="MB_QR">

                        @if($booking->total_price <= 0)
                            @if(!empty($booking->promotion_id) && $booking->promotion)
                                <!-- 100% Voucher Covered -->
                                <div class="p-5 border-2 border-emerald-500 bg-emerald-500/10 rounded-2xl relative shadow-lg">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            <div class="w-16 h-10 bg-emerald-500/20 rounded-lg flex items-center justify-center font-bold text-emerald-400 text-2xl border border-emerald-500/30">
                                                 🎟️
                                            </div>
                                            <div>
                                                <span class="text-white font-bold block text-lg flex items-center gap-2">
                                                    Thanh toán bằng Voucher Khuyến Mãi
                                                    <span class="px-2.5 py-0.5 bg-emerald-500/30 text-emerald-300 text-[11px] font-semibold rounded-full uppercase">Tài trợ 100%</span>
                                                </span>
                                                <span class="text-gray-400 text-xs">Voucher "{{ $booking->promotion->title }}" (Mã: {{ $booking->promotion->code }}) đã tài trợ toàn bộ số tiền đơn hàng (0 đ). Bấm nút bên dưới để nhận vé ngay lập tức!</span>
                                            </div>
                                        </div>
                                        <div class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <!-- 100% Points Covered -->
                                <div class="p-5 border-2 border-green-500 bg-green-500/10 rounded-2xl relative shadow-lg">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            <div class="w-16 h-10 bg-green-500/20 rounded-lg flex items-center justify-center font-bold text-green-400 text-2xl border border-green-500/30">
                                                 💎
                                            </div>
                                            <div>
                                                <span class="text-white font-bold block text-lg flex items-center gap-2">
                                                    Thanh toán bằng Điểm Tích Lũy
                                                    <span class="px-2.5 py-0.5 bg-green-500/30 text-green-300 text-[11px] font-semibold rounded-full uppercase">Được tài trợ 100%</span>
                                                </span>
                                                <span class="text-gray-400 text-xs">Tổng số tiền cần thanh toán là 0 đ. Bấm nút bên dưới để nhận vé ngay lập tức!</span>
                                            </div>
                                        </div>
                                        <div class="w-6 h-6 rounded-full bg-green-500 text-white flex items-center justify-center shrink-0 shadow">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="space-y-4">
                                <!-- Option 1: MB Bank QR Payment (Khuyên dùng) -->
                                <div id="method-mb" onclick="selectPaymentMethod('MB_QR')" class="checkout-payment-option method-mb-option is-active-mb p-5 border-2 border-blue-500 bg-blue-950/30 rounded-2xl relative shadow-lg cursor-pointer transition-all">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            <!-- MB Bank Logo Badge -->
                                            <div class="w-16 h-12 bg-white rounded-lg flex flex-col items-center justify-center p-1 shadow border border-blue-200 shrink-0">
                                                <div class="flex items-center gap-0.5">
                                                    <span class="font-black text-[#002D72] text-lg leading-none tracking-tighter">MB</span>
                                                    <span class="text-red-600 font-black text-xs leading-none">★</span>
                                                </div>
                                                <span class="text-[9px] font-bold text-[#002D72] uppercase tracking-tighter">MBBank</span>
                                            </div>
                                            <div>
                                                <span class="checkout-method-title text-white font-bold block text-base sm:text-lg flex items-center gap-2 flex-wrap">
                                                    Quét mã QR Ngân Hàng MB (VietQR)
                                                    <span class="checkout-badge-gold px-2 py-0.5 bg-cinematic-gold/20 text-cinematic-gold border border-cinematic-gold/40 text-[10px] font-bold rounded-full uppercase tracking-wider">Khuyên dùng</span>
                                                    <span class="checkout-badge-green px-2 py-0.5 bg-green-500/20 text-green-400 border border-green-500/30 text-[10px] font-semibold rounded-full uppercase">Tự động SePay</span>
                                                </span>
                                                <p class="checkout-method-desc text-gray-300 text-xs mt-1">
                                                    STK: <strong class="checkout-strong-highlight text-white font-mono">031205090305</strong> - <strong class="checkout-strong-highlight text-white">TRẦN VĂN HẢO</strong>. Mọi app ngân hàng đều quét được. Tự động xác nhận thành công sau vài giây!
                                                </p>
                                            </div>
                                        </div>
                                        <div id="check-mb" class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center shrink-0 shadow ml-3">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                    </div>
                                    <div class="checkout-mb-footer mt-3 pt-3 border-t border-blue-500/20 flex flex-wrap items-center gap-2 text-[11px] text-blue-200">
                                        <span class="flex items-center gap-1">⚡ <span>Tự động kích hoạt vé qua SePay</span></span>
                                        <span>•</span>
                                        <span class="flex items-center gap-1">🏦 <span>Hỗ trợ tất cả ứng dụng ngân hàng</span></span>
                                        <span>•</span>
                                        <span class="flex items-center gap-1">🔒 <span>Không tốn phí giao dịch</span></span>
                                    </div>
                                </div>

                                <!-- Option 2: VNPay -->
                                <div id="method-vnpay" onclick="selectPaymentMethod('VNPay')" class="checkout-payment-option method-vnpay-option is-inactive p-5 border-2 border-white/10 bg-white/5 rounded-2xl relative shadow-lg cursor-pointer transition-all">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            <div class="w-16 h-12 bg-white rounded-lg flex items-center justify-center p-1.5 shadow-md border border-gray-200 shrink-0">
                                                <span class="font-black text-blue-700 text-base tracking-tight">VN<span class="text-red-600">PAY</span></span>
                                            </div>
                                            <div>
                                                <span class="checkout-method-title text-white font-bold block text-base sm:text-lg flex items-center gap-2 flex-wrap">
                                                    Cổng thanh toán VNPAY
                                                    <span class="checkout-badge-blue px-2 py-0.5 bg-blue-500/20 text-blue-400 border border-blue-500/30 text-[10px] font-semibold rounded-full uppercase">Trực tuyến</span>
                                                </span>
                                                <span class="checkout-method-desc text-gray-400 text-xs">Hỗ trợ cổng thanh toán VNPAY (thẻ ATM nội địa, thẻ quốc tế Visa/MasterCard, VNPAY-QR)</span>
                                            </div>
                                        </div>
                                        <div id="check-vnpay" class="w-6 h-6 rounded-full border border-gray-500 text-transparent flex items-center justify-center shrink-0 shadow ml-3">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Summary Sidebar -->
            <div class="w-full lg:w-96 shrink-0">
                <div class="checkout-summary-card bg-white/5 border border-white/10 rounded-xl overflow-hidden shadow-2xl sticky top-28">
                    <!-- Movie Image & Title -->
                    <div class="h-32 relative">
                        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ $booking->showtime->movie->backdrop_url ?? $booking->showtime->movie->poster_url }}');"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-[#121520] via-black/60 to-transparent"></div>
                        <div class="relative z-10 p-5 flex flex-col justify-end h-full">
                            <h3 class="checkout-movie-title text-xl font-bold text-white leading-tight drop-shadow">{{ $booking->showtime->movie->title }}</h3>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="mb-4 text-sm space-y-1">
                            <p class="text-gray-400">Rạp: <span class="checkout-detail-value text-white font-semibold ml-1">{{ $booking->showtime->room->cinema->name }}</span></p>
                            <p class="text-gray-400">Phòng chiếu: <span class="checkout-detail-value text-white font-semibold ml-1">{{ $booking->showtime->room->name }}</span></p>
                            <p class="text-gray-400">Suất chiếu: <span class="checkout-detail-value text-white font-semibold ml-1">{{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('H:i | d/m/Y') }}</span></p>
                        </div>
                        
                        <div class="border-t border-white/10 py-4 text-sm">
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-400">Ghế đã chọn ({{ $booking->tickets->count() }}):</span>
                                <span class="checkout-seats-value text-white font-bold text-right text-cinematic-gold">
                                    {{ $booking->tickets->map(function($t) { return $t->seat ? ($t->seat->row . $t->seat->number) : ''; })->filter()->join(', ') }}
                                </span>
                            </div>

                            @if(isset($ticketTotal))
                            <div class="flex justify-between mb-2 text-xs text-gray-400">
                                <span>Tiền vé:</span>
                                <span class="checkout-detail-value text-white font-medium">{{ number_format($ticketTotal, 0, ',', '.') }} đ</span>
                            </div>
                            @endif
                            
                            @if(isset($bookingFoods) && $bookingFoods->count() > 0)
                            <div class="mt-3 pt-3 border-t border-white/5 text-gray-400 text-xs">Bắp nước & Combo:</div>
                                @foreach($bookingFoods as $f)
                                <div class="flex justify-between mt-1 text-xs">
                                    <span class="text-gray-300">{{ $f->quantity }}x {{ $f->name }}</span>
                                    <span class="checkout-detail-value text-white font-medium">{{ number_format($f->price * $f->quantity, 0, ',', '.') }} đ</span>
                                </div>
                                @endforeach
                            @endif

                            @if($booking->discount_amount > 0)
                                @if($booking->promotion)
                                <div class="mt-3 pt-3 border-t border-white/5">
                                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 text-xs">
                                        <div class="flex items-center gap-1.5 min-w-0">
                                            <span>🎟️</span>
                                            <span class="font-bold truncate">Mã <span class="checkout-voucher-code text-[11px]">{{ $booking->promotion->code }}</span>:</span>
                                        </div>
                                        <div class="flex items-center gap-2 shrink-0">
                                            <span class="font-extrabold text-emerald-300">-{{ number_format($booking->discount_amount, 0, ',', '.') }} đ</span>
                                            <form action="{{ route('booking.remove_voucher', $booking->id) }}" method="POST" class="m-0">
                                                @csrf
                                                <button type="submit" class="text-red-400 hover:text-red-300 text-[11px] underline cursor-pointer" title="Hủy áp dụng voucher">Hủy</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="mt-3 pt-3 border-t border-white/5">
                                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-amber-500/15 border border-amber-500/30 text-amber-400 text-xs">
                                        <div class="flex items-center gap-1.5 min-w-0">
                                            <span>💎</span>
                                            <span class="font-bold">Điểm ({{ $booking->points_used }} pts):</span>
                                        </div>
                                        <div class="flex items-center gap-2 shrink-0">
                                            <span class="font-extrabold text-amber-300">-{{ number_format($booking->discount_amount, 0, ',', '.') }} đ</span>
                                            <form action="{{ route('booking.remove_points', $booking->id) }}" method="POST" class="m-0">
                                                @csrf
                                                <button type="submit" class="text-red-400 hover:text-red-300 text-[11px] underline cursor-pointer" title="Hủy dùng điểm">Hủy</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @else
                                <div class="mt-3 pt-3 border-t border-white/5">
                                    <a href="#voucherSection" onclick="document.getElementById('voucherCodeInput')?.focus()" class="flex items-center justify-between p-2 rounded-xl border border-dashed border-amber-400/40 bg-amber-400/5 hover:bg-amber-400/15 text-amber-300 text-xs font-semibold transition group">
                                        <span class="flex items-center gap-1.5">
                                            <span>🎟️</span>
                                            <span>Áp dụng Mã Voucher</span>
                                        </span>
                                        <span class="text-cinematic-gold text-[11px] font-bold group-hover:translate-x-0.5 transition-transform">Chọn mã →</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                        
                        <div class="mt-2 pt-4 border-t border-white/20">
                            <div class="flex justify-between items-center">
                                <span class="checkout-total-label text-gray-300 font-medium">Tổng thanh toán:</span>
                                <span class="text-2xl md:text-3xl font-bold {{ $booking->total_price <= 0 ? 'text-green-400' : 'text-cinematic-gold' }}">
                                    {{ number_format($booking->total_price, 0, ',', '.') }} đ
                                </span>
                            </div>
                        </div>

                        <!-- Points to be awarded after payment -->
                        <div class="mt-3 py-2 px-3 bg-cinematic-gold/10 border border-cinematic-gold/20 rounded-lg text-xs flex items-center justify-between text-cinematic-gold">
                            <span class="flex items-center gap-1.5">
                                <span>✨</span> Điểm thưởng nhận được:
                            </span>
                            <span class="font-bold">+{{ $earnedPoints }} điểm (={{ number_format($earnedPoints * 1000, 0, ',', '.') }}đ)</span>
                        </div>

                        <div class="mt-3 text-xs text-gray-400 text-center flex items-center justify-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                              <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>Mã vé sẽ được gửi về: <strong class="checkout-email-value text-white">{{ auth()->user()->email }}</strong></span>
                        </div>
                        
                        <button type="submit" form="paymentForm" id="btnSubmitPayment" class="w-full mt-6 py-4 {{ $booking->total_price <= 0 ? 'bg-green-600 hover:bg-green-500 shadow-[0_0_20px_rgba(34,197,94,0.5)]' : 'bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-500 hover:to-indigo-600 shadow-[0_0_20px_rgba(37,99,235,0.4)]' }} text-white font-bold rounded-xl transition-all uppercase tracking-wider cursor-pointer flex items-center justify-center gap-2 text-sm md:text-base hover:scale-[1.02]">
                            @if($booking->total_price <= 0)
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Xác Nhận Đặt Vé (0 đ)</span>
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                <span id="btnSubmitText">Quét Mã QR MB Bank ({{ number_format($booking->total_price, 0, ',', '.') }} đ)</span>
                            @endif
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function switchCheckoutVoucherTab(tab) {
    const btnCinema = document.getElementById('checkoutTabCinemaBtn');
    const btnWallet = document.getElementById('checkoutTabWalletBtn');
    const contentCinema = document.getElementById('checkoutTabCinemaContent');
    const contentWallet = document.getElementById('checkoutTabWalletContent');

    if (tab === 'cinema') {
        if (btnCinema) {
            btnCinema.className = 'checkout-v-tab-btn active';
        }
        if (btnWallet) {
            btnWallet.className = 'checkout-v-tab-btn';
        }
        if (contentCinema) contentCinema.classList.remove('hidden');
        if (contentWallet) contentWallet.classList.add('hidden');
    } else {
        if (btnWallet) {
            btnWallet.className = 'checkout-v-tab-btn active';
        }
        if (btnCinema) {
            btnCinema.className = 'checkout-v-tab-btn';
        }
        if (contentWallet) contentWallet.classList.remove('hidden');
        if (contentCinema) contentCinema.classList.add('hidden');
    }
}

function selectPaymentMethod(method) {
    const input = document.getElementById('selectedPaymentMethod');
    if (!input) return;
    input.value = method;

    const methodMb = document.getElementById('method-mb');
    const checkMb = document.getElementById('check-mb');
    const methodVnpay = document.getElementById('method-vnpay');
    const checkVnpay = document.getElementById('check-vnpay');
    const vnpayGuide = document.getElementById('vnpay-guide');
    const btnSubmit = document.getElementById('btnSubmitPayment');
    const btnText = document.getElementById('btnSubmitText');
    const totalPriceFormatted = "{{ number_format($booking->total_price, 0, ',', '.') }} đ";

    if (method === 'MB_QR') {
        if (methodMb) {
            methodMb.classList.add('is-active-mb', 'border-blue-500', 'bg-blue-950/30');
            methodMb.classList.remove('is-inactive', 'border-white/10', 'bg-white/5');
        }
        if (checkMb) {
            checkMb.className = 'w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center shrink-0 shadow ml-3';
        }
        if (methodVnpay) {
            methodVnpay.classList.add('is-inactive', 'border-white/10', 'bg-white/5');
            methodVnpay.classList.remove('is-active-vnpay', 'border-cinematic-red', 'bg-red-500/10');
        }
        if (checkVnpay) {
            checkVnpay.className = 'w-6 h-6 rounded-full border border-gray-500 text-transparent flex items-center justify-center shrink-0 shadow ml-3';
        }
        if (vnpayGuide) vnpayGuide.classList.add('hidden');

        if (btnSubmit) {
            btnSubmit.className = "w-full mt-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-500 hover:to-indigo-600 shadow-[0_0_20px_rgba(37,99,235,0.4)] text-white font-bold rounded-xl transition-all uppercase tracking-wider cursor-pointer flex items-center justify-center gap-2 text-sm md:text-base hover:scale-[1.02]";
        }
        if (btnText) {
            btnText.textContent = `Quét Mã QR MB Bank (${totalPriceFormatted})`;
        }
    } else if (method === 'VNPay') {
        if (methodVnpay) {
            methodVnpay.classList.add('is-active-vnpay', 'border-cinematic-red', 'bg-red-500/10');
            methodVnpay.classList.remove('is-inactive', 'border-white/10', 'bg-white/5');
        }
        if (checkVnpay) {
            checkVnpay.className = 'w-6 h-6 rounded-full bg-cinematic-red text-white flex items-center justify-center shrink-0 shadow ml-3';
        }
        if (methodMb) {
            methodMb.classList.add('is-inactive', 'border-white/10', 'bg-white/5');
            methodMb.classList.remove('is-active-mb', 'border-blue-500', 'bg-blue-950/30');
        }
        if (checkMb) {
            checkMb.className = 'w-6 h-6 rounded-full border border-gray-500 text-transparent flex items-center justify-center shrink-0 shadow ml-3';
        }
        if (btnSubmit) {
            btnSubmit.className = "w-full mt-6 py-4 bg-cinematic-red hover:bg-red-700 shadow-[0_0_20px_rgba(229,9,20,0.5)] text-white font-bold rounded-xl transition-all uppercase tracking-wider cursor-pointer flex items-center justify-center gap-2 text-sm md:text-base hover:scale-[1.02]";
        }
        if (btnText) {
            btnText.textContent = `Thanh Toán Qua VNPAY (${totalPriceFormatted})`;
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
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

