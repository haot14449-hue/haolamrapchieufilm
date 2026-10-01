<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cổng thanh toán VNPAY</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'vnpay-blue': '#005baa',
                        'vnpay-red': '#ed1c24',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-[#f2f4f7] font-sans antialiased text-gray-800 min-h-screen flex flex-col justify-between">

    <!-- Top Navigation Header -->
    <header class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-50">
        <div class="max-w-5xl mx-auto px-4 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <!-- VNPay Official Style Logo -->
                <div class="flex items-center">
                    <span class="text-2xl font-black tracking-tighter text-[#005baa]">VN<span class="text-[#ed1c24]">PAY</span></span>
                </div>
                <div class="h-6 w-px bg-gray-300 hidden sm:block"></div>
                <div class="hidden sm:flex flex-col">
                    <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">Cổng Thanh Toán Điện Tử</span>
                    <span class="text-[10px] text-green-600 font-semibold flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                        Cổng Thanh Toán Trực Tuyến
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-4 text-xs">
                <div class="text-right">
                    <span class="text-gray-500 block">Đơn vị thụ hưởng:</span>
                    <span class="font-bold text-gray-900">HCTV CINEMA</span>
                </div>
                <div class="hidden md:flex items-center gap-1 text-green-600 bg-green-50 px-2.5 py-1 rounded-full border border-green-200">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span class="font-medium text-[11px]">Bảo mật 256-bit SSL</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="max-w-5xl mx-auto px-4 py-6 w-full flex-1">
        
        <!-- Transaction Information Banner -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 sm:p-6 mb-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-gray-100 pb-4">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Thông tin thanh toán</span>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mt-0.5">
                        Thanh toán vé xem phim {{ $booking->showtime->movie->title }}
                    </h1>
                    <p class="text-xs text-gray-600 mt-1 flex flex-wrap items-center gap-x-4 gap-y-1">
                        <span>Rạp: <strong class="text-gray-900">{{ $booking->showtime->room->cinema->name }}</strong> ({{ $booking->showtime->room->name }})</span>
                        <span>Suất: <strong class="text-gray-900">{{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('H:i | d/m/Y') }}</strong></span>
                        <span>Ghế: <strong class="text-blue-700">{{ $booking->tickets->map(fn($t) => $t->seat ? ($t->seat->row . $t->seat->number) : '')->filter()->join(', ') }}</strong></span>
                    </p>
                </div>

                <div class="text-left md:text-right bg-blue-50/70 p-3 sm:p-4 rounded-xl border border-blue-100 shrink-0 w-full md:w-auto">
                    <span class="text-xs text-gray-600 block">Số tiền thanh toán:</span>
                    <span class="text-2xl sm:text-3xl font-extrabold text-[#ed1c24]">{{ number_format($booking->total_price, 0, ',', '.') }} <span class="text-sm font-semibold text-gray-700">VND</span></span>
                    @if($booking->discount_amount > 0)
                        <div class="text-[11px] text-green-700 font-medium mt-0.5">
                            Đã trừ {{ $booking->points_used }} điểm (-{{ number_format($booking->discount_amount, 0, ',', '.') }} đ)
                        </div>
                    @endif
                    <div class="text-[11px] text-gray-500 mt-1 flex items-center justify-start md:justify-end gap-1">
                        <span>Mã đơn:</span>
                        <code class="font-mono text-gray-800 bg-white px-1.5 py-0.5 rounded border border-gray-200">#{{ $booking->id }}</code>
                    </div>
                </div>
            </div>

            <!-- Countdown Timer & Notice -->
            <div class="pt-3 flex flex-wrap items-center justify-between gap-2 text-xs text-gray-500">
                <div class="flex items-center gap-2">
                    <span class="text-gray-600">Thời gian giữ ghế còn lại:</span>
                    <span id="countdownTimer" class="font-mono font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded border border-red-200">05:00</span>
                </div>
                <div class="text-gray-400">Mã giao dịch: <span class="font-mono text-gray-600">{{ $vnp_TxnRef }}</span></div>
            </div>
        </div>

        <!-- Payment Gateway Box -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-md overflow-hidden grid grid-cols-1 lg:grid-cols-12">
            
            <!-- Left Column: Payment Method Tabs -->
            <div class="lg:col-span-4 bg-[#f8fafc] border-b lg:border-b-0 lg:border-r border-gray-200 p-4 sm:p-5">
                <h2 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-3 px-1">Phương thức thanh toán</h2>
                
                <div class="space-y-2" role="tablist">
                    <!-- Tab 1: Thẻ nội địa ATM (Default) -->
                    <button type="button" 
                            id="tabBtn-atm"
                            onclick="switchTab('atm')" 
                            class="w-full flex items-center justify-between p-3.5 rounded-xl border transition-all text-left group bg-white border-[#005baa] shadow-sm text-[#005baa]">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-blue-50 text-[#005baa] flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            </div>
                            <div>
                                <span class="font-bold text-sm block">Thẻ ATM nội địa</span>
                                <span class="text-[11px] text-gray-500">Khuyên dùng (NCB)</span>
                            </div>
                        </div>
                        <span class="w-2.5 h-2.5 rounded-full bg-[#005baa]"></span>
                    </button>

                    <!-- Tab 2: VNPAY-QR -->
                    <button type="button" 
                            id="tabBtn-qr"
                            onclick="switchTab('qr')" 
                            class="w-full flex items-center justify-between p-3.5 rounded-xl border border-gray-200 hover:border-gray-300 hover:bg-gray-50/80 transition-all text-left group text-gray-700 bg-transparent">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-gray-100 text-gray-600 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                            </div>
                            <div>
                                <span class="font-bold text-sm block">VNPAY-QR</span>
                                <span class="text-[11px] text-gray-500">Quét qua App ngân hàng</span>
                            </div>
                        </div>
                        <span class="w-2.5 h-2.5 rounded-full bg-transparent"></span>
                    </button>

                    <!-- Tab 3: Thẻ quốc tế -->
                    <button type="button" 
                            id="tabBtn-card"
                            onclick="switchTab('card')" 
                            class="w-full flex items-center justify-between p-3.5 rounded-xl border border-gray-200 hover:border-gray-300 hover:bg-gray-50/80 transition-all text-left group text-gray-700 bg-transparent">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-gray-100 text-gray-600 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                            </div>
                            <div>
                                <span class="font-bold text-sm block">Thẻ quốc tế</span>
                                <span class="text-[11px] text-gray-500">Visa, MasterCard, JCB</span>
                            </div>
                        </div>
                        <span class="w-2.5 h-2.5 rounded-full bg-transparent"></span>
                    </button>
                </div>

                <!-- Info Box -->
                <div class="mt-6 p-3.5 bg-blue-50 rounded-xl border border-blue-200 text-blue-900 text-xs space-y-1.5">
                    <p class="font-bold flex items-center gap-1">
                        <span>💡</span> Hướng dẫn thanh toán:
                    </p>
                    <p class="text-[11px] leading-relaxed text-blue-800">
                        Chọn ngân hàng <strong>NCB</strong>, số thẻ đã được điền sẵn hoặc nhấn <strong>Điền thông tin thẻ</strong> để tiếp tục xác thực OTP.
                    </p>
                </div>
            </div>

            <!-- Right Column: Content per Tab -->
            <div class="lg:col-span-8 p-5 sm:p-8">

                <!-- TAB 1: THẺ ATM NỘI ĐỊA (NCB) -->
                <div id="tabContent-atm" class="space-y-6">
                    
                    <!-- STEP 1: Card Form -->
                    <div id="step-card-form">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-base font-bold text-gray-900">Chọn ngân hàng thanh toán</h3>
                                <p class="text-xs text-gray-500">Hỗ trợ ngân hàng NCB và các ngân hàng liên kết</p>
                            </div>
                            <button type="button" onclick="autoFillTestCard()" class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-[#005baa] text-xs font-semibold rounded-lg border border-blue-200 transition flex items-center gap-1.5">
                                <span>⚡</span> Điền thông tin thẻ
                            </button>
                        </div>

                        <!-- Bank Logos Selection -->
                        <div class="grid grid-cols-3 sm:grid-cols-4 gap-2.5 mb-6">
                            <!-- NCB Bank (Active) -->
                            <div class="p-2.5 border-2 border-[#005baa] bg-blue-50/50 rounded-xl text-center cursor-pointer shadow-sm relative">
                                <span class="block font-black text-sm text-[#005baa]">NCB</span>
                                <span class="text-[10px] text-gray-500 block truncate">Ngân hàng NCB</span>
                                <div class="absolute -top-1.5 -right-1.5 w-4 h-4 bg-[#005baa] rounded-full text-white flex items-center justify-center text-[10px]">✓</div>
                            </div>

                            <div class="p-2.5 border border-gray-200 rounded-xl text-center opacity-60 hover:opacity-100 cursor-pointer transition">
                                <span class="block font-bold text-xs text-green-700">VCB</span>
                                <span class="text-[10px] text-gray-400 block truncate">Vietcombank</span>
                            </div>
                            <div class="p-2.5 border border-gray-200 rounded-xl text-center opacity-60 hover:opacity-100 cursor-pointer transition">
                                <span class="block font-bold text-xs text-red-600">TCB</span>
                                <span class="text-[10px] text-gray-400 block truncate">Techcombank</span>
                            </div>
                            <div class="p-2.5 border border-gray-200 rounded-xl text-center opacity-60 hover:opacity-100 cursor-pointer transition">
                                <span class="block font-bold text-xs text-blue-800">BIDV</span>
                                <span class="text-[10px] text-gray-400 block truncate">BIDV</span>
                            </div>
                            <div class="p-2.5 border border-gray-200 rounded-xl text-center opacity-60 hover:opacity-100 cursor-pointer transition">
                                <span class="block font-bold text-xs text-blue-900">MB</span>
                                <span class="text-[10px] text-gray-400 block truncate">MBBank</span>
                            </div>
                            <div class="p-2.5 border border-gray-200 rounded-xl text-center opacity-60 hover:opacity-100 cursor-pointer transition">
                                <span class="block font-bold text-xs text-red-700">AGR</span>
                                <span class="text-[10px] text-gray-400 block truncate">Agribank</span>
                            </div>
                            <div class="p-2.5 border border-gray-200 rounded-xl text-center opacity-60 hover:opacity-100 cursor-pointer transition">
                                <span class="block font-bold text-xs text-blue-700">ACB</span>
                                <span class="text-[10px] text-gray-400 block truncate">ACB</span>
                            </div>
                            <div class="p-2.5 border border-gray-200 rounded-xl text-center opacity-60 hover:opacity-100 cursor-pointer transition">
                                <span class="block font-bold text-xs text-green-600">VPB</span>
                                <span class="text-[10px] text-gray-400 block truncate">VPBank</span>
                            </div>
                        </div>

                        <!-- Card Inputs Form -->
                        <div class="space-y-4 bg-gray-50/60 p-4 sm:p-5 rounded-xl border border-gray-200">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Số thẻ ngân hàng:</label>
                                <div class="relative">
                                    <input type="text" 
                                           id="inputCardNumber" 
                                           value="9704198526191432198" 
                                           placeholder="Nhập số thẻ"
                                           class="w-full px-3.5 py-2.5 rounded-lg border border-gray-300 focus:border-[#005baa] focus:ring-2 focus:ring-blue-100 outline-none text-sm font-mono tracking-wider font-semibold text-gray-900 bg-white shadow-sm">
                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-bold text-[#005baa]">NCB</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Tên chủ thẻ (Không dấu):</label>
                                    <input type="text" 
                                           id="inputCardName" 
                                           value="NGUYEN VAN A" 
                                           placeholder="NGUYEN VAN A"
                                           class="w-full px-3.5 py-2.5 rounded-lg border border-gray-300 focus:border-[#005baa] focus:ring-2 focus:ring-blue-100 outline-none text-sm font-mono uppercase font-semibold text-gray-900 bg-white shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Ngày phát hành (MM/YY):</label>
                                    <input type="text" 
                                           id="inputCardDate" 
                                           value="07/15" 
                                           placeholder="07/15"
                                           class="w-full px-3.5 py-2.5 rounded-lg border border-gray-300 focus:border-[#005baa] focus:ring-2 focus:ring-blue-100 outline-none text-sm font-mono font-semibold text-gray-900 bg-white shadow-sm">
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons Step 1 -->
                        <div class="mt-6 flex flex-col sm:flex-row items-center gap-3">
                            <button type="button" 
                                    onclick="goToOtpStep()" 
                                    class="w-full sm:w-2/3 py-3.5 bg-[#005baa] hover:bg-[#004785] text-white font-bold rounded-xl shadow-md transition-all flex items-center justify-center gap-2 text-sm uppercase tracking-wider cursor-pointer">
                                <span>Tiếp tục xác thực OTP</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                            <a href="{{ route('booking.checkout', $booking->id) }}" 
                               class="w-full sm:w-1/3 py-3.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-center transition text-sm">
                                Quay lại
                            </a>
                        </div>
                    </div>

                    <!-- STEP 2: OTP Verification Modal/Container -->
                    <div id="step-otp-form" class="hidden space-y-6">
                        <div class="bg-blue-50/60 border border-blue-200 rounded-xl p-4 text-xs text-blue-900 flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <div>
                                <span class="font-bold text-sm block mb-0.5">Xác thực mã OTP Ngân hàng</span>
                                <p class="text-blue-800 leading-relaxed">
                                    VNPAY đã gửi mã xác thực giao dịch tới số điện thoại của quý khách. Nhập mã để hoàn tất thanh toán số tiền <strong class="text-red-600">{{ number_format($booking->total_price, 0, ',', '.') }} đ</strong>.
                                </p>
                            </div>
                        </div>

                        <form id="vnpayConfirmForm" action="{{ route('booking.vnpay_return') }}" method="GET" class="space-y-4">
                            <!-- Standard VNPAY Return Parameters -->
                            <input type="hidden" name="vnp_ResponseCode" value="00">
                            <input type="hidden" name="vnp_TxnRef" value="{{ $vnp_TxnRef }}">
                            <input type="hidden" name="vnp_Amount" value="{{ (int)($booking->total_price * 100) }}">
                            <input type="hidden" name="vnp_BankCode" value="NCB">
                            <input type="hidden" name="vnp_TransactionNo" value="VNP_{{ time() }}">

                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label class="text-xs font-semibold text-gray-700">Nhập mã OTP:</label>
                                    <button type="button" onclick="autoFillOtp()" class="text-xs text-[#005baa] hover:underline font-semibold flex items-center gap-1">
                                        <span>Mã OTP:</span>
                                        <code class="bg-blue-50 px-1.5 py-0.5 rounded border border-blue-200 font-bold">123456</code>
                                    </button>
                                </div>
                                <input type="text" 
                                       id="inputOtp" 
                                       name="otp_code" 
                                       value="123456" 
                                       maxlength="6" 
                                       placeholder="123456" 
                                       class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-green-600 focus:ring-2 focus:ring-green-100 outline-none text-center font-mono text-2xl font-bold tracking-[0.5em] text-gray-900 bg-white shadow-sm">
                            </div>

                            <div class="pt-3 flex flex-col sm:flex-row items-center gap-3">
                                <button type="submit" 
                                        class="w-full sm:w-2/3 py-3.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow-lg transition-all flex items-center justify-center gap-2 text-sm uppercase tracking-wider cursor-pointer">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Xác nhận thanh toán</span>
                                </button>
                                
                                <button type="button" 
                                        onclick="backToCardStep()" 
                                        class="w-full sm:w-1/3 py-3.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-center transition text-sm">
                                    Đổi thẻ khác
                                </button>
                            </div>
                        </form>
                    </div>

                </div>

                <!-- TAB 2: VNPAY-QR -->
                <div id="tabContent-qr" class="hidden text-center py-4 space-y-5">
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Quét mã VNPAY-QR để thanh toán</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Sử dụng ứng dụng ngân hàng hoặc ví VNPAY của bạn</p>
                    </div>

                    <!-- Fake QR Container -->
                    <div class="inline-block p-4 bg-white rounded-2xl border-2 border-dashed border-[#005baa] shadow-md relative">
                        <div class="w-52 h-52 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl flex flex-col items-center justify-center relative overflow-hidden border border-gray-200">
                            <!-- SVG QR Graphic Mockup -->
                            <svg class="w-40 h-40 text-gray-900" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M2 2h8v8H2V2zm2 2v4h4V4H4zm8-2h8v8h-8V2zm2 2v4h4V4h-4zM2 14h8v8H2v-8zm2 2v4h4v-4H4zm10-2h2v2h-2v-2zm4 0h2v2h-2v-2zm-4 4h2v2h-2v-2zm4 0h2v2h-2v-2zm-2-2h2v2h-2v-2zm4 4h2v2h-2v-2zm-4 0h2v2h-2v-2zM5 5h2v2H5V5zm10 0h2v2h-2V5zM5 17h2v2H5v-2z"/>
                            </svg>
                            <!-- Mini VNPAY Brand in QR -->
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="w-10 h-7 bg-white rounded shadow-md flex items-center justify-center border border-gray-300">
                                    <span class="font-black text-[#005baa] text-[10px]">VN<span class="text-[#ed1c24]">P</span></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500">Mã QR hết hạn cùng đồng hồ đếm ngược giao dịch</p>
                        <form action="{{ route('booking.vnpay_return') }}" method="GET" class="mt-4">
                            <input type="hidden" name="vnp_ResponseCode" value="00">
                            <input type="hidden" name="vnp_TxnRef" value="{{ $vnp_TxnRef }}">
                            <input type="hidden" name="vnp_Amount" value="{{ (int)($booking->total_price * 100) }}">
                            <input type="hidden" name="vnp_BankCode" value="VNPAYQR">
                            <input type="hidden" name="vnp_TransactionNo" value="QR_{{ time() }}">

                            <button type="submit" class="px-6 py-3 bg-[#005baa] hover:bg-[#004785] text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow transition">
                                ⚡ Xác nhận quét mã thành công
                            </button>
                        </form>
                    </div>
                </div>

                <!-- TAB 3: THẺ QUỐC TẾ -->
                <div id="tabContent-card" class="hidden space-y-5">
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Thanh toán qua Thẻ quốc tế Visa, Mastercard, JCB</h3>
                        <p class="text-xs text-gray-500">Hỗ trợ các loại thẻ thanh toán quốc tế</p>
                    </div>

                    <form action="{{ route('booking.vnpay_return') }}" method="GET" class="space-y-4 bg-gray-50/60 p-4 sm:p-5 rounded-xl border border-gray-200">
                        <input type="hidden" name="vnp_ResponseCode" value="00">
                        <input type="hidden" name="vnp_TxnRef" value="{{ $vnp_TxnRef }}">
                        <input type="hidden" name="vnp_Amount" value="{{ (int)($booking->total_price * 100) }}">
                        <input type="hidden" name="vnp_BankCode" value="VISA">
                        <input type="hidden" name="vnp_TransactionNo" value="VISA_{{ time() }}">

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Số thẻ quốc tế:</label>
                            <input type="text" value="4000 0012 3456 7890" class="w-full px-3.5 py-2.5 rounded-lg border border-gray-300 font-mono text-sm font-semibold text-gray-900 bg-white">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Hạn thẻ (MM/YY):</label>
                                <input type="text" value="12/28" class="w-full px-3.5 py-2.5 rounded-lg border border-gray-300 font-mono text-sm font-semibold text-gray-900 bg-white">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Mã CVV/CVC:</label>
                                <input type="text" value="999" class="w-full px-3.5 py-2.5 rounded-lg border border-gray-300 font-mono text-sm font-semibold text-gray-900 bg-white">
                            </div>
                        </div>

                        <button type="submit" class="w-full mt-4 py-3.5 bg-[#005baa] hover:bg-[#004785] text-white font-bold rounded-xl shadow text-sm uppercase tracking-wider transition">
                            Xác nhận thanh toán thẻ quốc tế
                        </button>
                    </form>
                </div>

            </div>
        </div>

        <!-- Cancel Transaction Action -->
        <div class="mt-6 text-center">
            <a href="{{ route('booking.vnpay_return', ['vnp_ResponseCode' => '24', 'vnp_TxnRef' => $vnp_TxnRef]) }}" 
               onclick="return confirm('Bạn có chắc chắn muốn hủy giao dịch thanh toán này không?');"
               class="text-xs text-red-600 hover:text-red-800 hover:underline font-medium inline-flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                <span>Hủy giao dịch thanh toán & quay về trang chọn vé</span>
            </a>
        </div>
    </main>

    <!-- Footer Security Banner -->
    <footer class="bg-white border-t border-gray-200 py-4 mt-8 text-center text-xs text-gray-500">
        <div class="max-w-5xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-2">
            <div>
                © {{ date('Y') }} VNPAY Payment Gateway. Tích hợp thanh toán trực tuyến cho HCTV Cinema.
            </div>
            <div class="flex items-center gap-4 text-gray-400 text-xs">
                <span>Hỗ trợ kỹ thuật: 1900 55 55 77</span>
                <span>•</span>
                <span>hotrovnpay@vnpay.vn</span>
            </div>
        </div>
    </footer>

    <!-- JavaScript Interactive Flow -->
    <script>
        // Tab Switcher
        function switchTab(tab) {
            const tabs = ['atm', 'qr', 'card'];
            tabs.forEach(t => {
                const btn = document.getElementById('tabBtn-' + t);
                const content = document.getElementById('tabContent-' + t);
                if (t === tab) {
                    btn.classList.add('bg-white', 'border-[#005baa]', 'shadow-sm', 'text-[#005baa]');
                    btn.classList.remove('border-gray-200', 'hover:border-gray-300', 'hover:bg-gray-50/80', 'text-gray-700', 'bg-transparent');
                    btn.querySelector('span:last-child').classList.add('bg-[#005baa]');
                    btn.querySelector('span:last-child').classList.remove('bg-transparent');
                    content.classList.remove('hidden');
                } else {
                    btn.classList.remove('bg-white', 'border-[#005baa]', 'shadow-sm', 'text-[#005baa]');
                    btn.classList.add('border-gray-200', 'hover:border-gray-300', 'hover:bg-gray-50/80', 'text-gray-700', 'bg-transparent');
                    btn.querySelector('span:last-child').classList.remove('bg-[#005baa]');
                    btn.querySelector('span:last-child').classList.add('bg-transparent');
                    content.classList.add('hidden');
                }
            });
        }

        // Card form to OTP step transition
        function goToOtpStep() {
            const cardNum = document.getElementById('inputCardNumber').value.trim();
            if (!cardNum) {
                alert('Vui lòng nhập số thẻ ngân hàng!');
                return;
            }
            document.getElementById('step-card-form').classList.add('hidden');
            document.getElementById('step-otp-form').classList.remove('hidden');
            document.getElementById('inputOtp').focus();
        }

        function backToCardStep() {
            document.getElementById('step-otp-form').classList.add('hidden');
            document.getElementById('step-card-form').classList.remove('hidden');
        }

        function autoFillTestCard() {
            document.getElementById('inputCardNumber').value = '9704198526191432198';
            document.getElementById('inputCardName').value = 'NGUYEN VAN A';
            document.getElementById('inputCardDate').value = '07/15';
        }

        function autoFillOtp() {
            document.getElementById('inputOtp').value = '123456';
        }

        // 5-Minute Seat Hold Countdown Timer
        let secondsRemaining = {{ max(0, $booking->remaining_seconds) }};
        const timerElement = document.getElementById('countdownTimer');
        function updateTimer() {
            if (secondsRemaining <= 0) {
                if (timerElement) timerElement.textContent = '00:00';
                clearInterval(timerInterval);
                alert('Thời gian giữ ghế (5 phút) đã hết! Ghế đã được hoàn trả về trạng thái trống. Vui lòng chọn lại ghế.');
                window.location.href = "{{ route('booking.seats', $booking->showtime_id) }}";
                return;
            }
            const minutes = Math.floor(secondsRemaining / 60);
            const seconds = secondsRemaining % 60;
            if (timerElement) {
                timerElement.textContent = (minutes < 10 ? '0' : '') + minutes + ':' + (seconds < 10 ? '0' : '') + seconds;
            }
            secondsRemaining--;
        }
        updateTimer();
        const timerInterval = setInterval(updateTimer, 1000);
    </script>
</body>
</html>
