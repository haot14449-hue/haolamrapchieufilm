@extends('layouts.app')

@section('title', 'Tiện Ích Online - HCTV Cinema')

@section('content')
<div class="pt-24 pb-16 bg-cinematic-dark min-h-screen text-white">
    <!-- Hero Banner -->
    <div class="page-hero-banner relative py-16 mb-10 overflow-hidden border-b">
        <div class="absolute inset-0 bg-blue-500/10 blur-3xl opacity-40 pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10 text-center">
            <span class="page-hero-badge inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold mb-3">
                ⚡ Trải Nghiệm Số Không Giới Hạn
            </span>
            <h1 class="page-hero-title text-3xl sm:text-4xl md:text-5xl font-serif font-bold tracking-wider uppercase mb-3">
                Tiện Ích Trực Tuyến HCTV Online
            </h1>
            <p class="page-hero-desc text-sm sm:text-base max-w-2xl mx-auto">
                Tận hưởng hệ sinh thái tiện ích trực tuyến thông minh, tối ưu trải nghiệm xem phim từ bước đặt vé, chọn bắp nước đến thanh toán chỉ trong 1 phút.
            </p>
        </div>
    </div>

    <!-- Main Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Sidebar Navigation -->
            <div class="lg:col-span-4 xl:col-span-3">
                @include('pages._sidebar')
            </div>

            <!-- Right Main Content -->
            <div class="lg:col-span-8 xl:col-span-9 space-y-8">
                
                <!-- 6 Great Online Utilities -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Utility 1: Đặt vé thông minh 24/7 -->
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-6 shadow-xl backdrop-blur-sm space-y-3 hover:border-cinematic-gold/40 transition">
                        <div class="w-12 h-12 rounded-xl bg-cinematic-gold/20 text-cinematic-gold flex items-center justify-center text-2xl font-bold border border-cinematic-gold/30">
                            🎟️
                        </div>
                        <h3 class="text-lg font-bold text-white">Đặt Vé Online Siêu Tốc 24/7</h3>
                        <p class="text-gray-300 text-xs sm:text-sm leading-relaxed">
                            Tra cứu suất chiếu của toàn bộ các cụm rạp HCTV theo thời gian thực. Đặt vé trước bất cứ lúc nào, bất cứ nơi đâu mà không bao giờ phải xếp hàng chờ đợi tại quầy vé.
                        </p>
                    </div>

                    <!-- Utility 2: Chọn ghế trực quan thời gian thực -->
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-6 shadow-xl backdrop-blur-sm space-y-3 hover:border-cinematic-red/40 transition">
                        <div class="w-12 h-12 rounded-xl bg-cinematic-red/20 text-red-400 flex items-center justify-center text-2xl font-bold border border-cinematic-red/30">
                            💺
                        </div>
                        <h3 class="text-lg font-bold text-white">Sơ Đồ Ghế Ngồi Tương Tác</h3>
                        <p class="text-gray-300 text-xs sm:text-sm leading-relaxed">
                            Xem trực quan vị trí màn hình và từng loại ghế: Ghế đơn Tiêu chuẩn (Standard), Ghế VIP trung tâm, Ghế đôi Sweetbox dành riêng cho cặp đôi. Hệ thống tự động giữ ghế an toàn trong 5 phút.
                        </p>
                    </div>

                    <!-- Utility 3: Đặt trước Combo Bắp & Nước Fast-track -->
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-6 shadow-xl backdrop-blur-sm space-y-3 hover:border-yellow-500/40 transition">
                        <div class="w-12 h-12 rounded-xl bg-yellow-500/20 text-yellow-400 flex items-center justify-center text-2xl font-bold border border-yellow-500/30">
                            🍿
                        </div>
                        <h3 class="text-lg font-bold text-white">Đặt Bắp Nước Ưu Tiên (Fast-track)</h3>
                        <p class="text-gray-300 text-xs sm:text-sm leading-relaxed">
                            Lựa chọn hương vị bắp rang bơ (Phô mai, Caramel, Truyền thống) và đồ uống yêu thích kèm theo ưu đãi tiết kiệm tới 20% so với mua trực tiếp. Đến rạp nhận ngay tại line ưu tiên.
                        </p>
                    </div>

                    <!-- Utility 4: Thanh toán tự động VietQR SePay & VNPay -->
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-6 shadow-xl backdrop-blur-sm space-y-3 hover:border-blue-500/40 transition">
                        <div class="w-12 h-12 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center text-2xl font-bold border border-blue-500/30">
                            💳
                        </div>
                        <h3 class="text-lg font-bold text-white">Thanh Toán Đa Kênh Tức Thì</h3>
                        <p class="text-gray-300 text-xs sm:text-sm leading-relaxed">
                            Quét mã QR MB Bank tự động xác nhận qua SePay trong vài giây với tất cả ngân hàng, cổng thanh toán VNPAY an toàn bảo mật, cùng thanh toán 100% bằng điểm thưởng thành viên.
                        </p>
                    </div>

                    <!-- Utility 5: Vé điện tử E-Ticket mã QR gửi về Gmail -->
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-6 shadow-xl backdrop-blur-sm space-y-3 hover:border-green-500/40 transition">
                        <div class="w-12 h-12 rounded-xl bg-green-500/20 text-green-400 flex items-center justify-center text-2xl font-bold border border-green-500/30">
                            📱
                        </div>
                        <h3 class="text-lg font-bold text-white">Vé Điện Tử QR Code Tiện Lợi</h3>
                        <p class="text-gray-300 text-xs sm:text-sm leading-relaxed">
                            Mã vé điện tử bảo mật kèm thông tin suất chiếu được tự động gửi qua email và lưu trữ trong mục "Lịch sử vé". Khi vào phòng chiếu, bạn chỉ cần đưa điện thoại cho nhân viên quét mã.
                        </p>
                    </div>

                    <!-- Utility 6: Tích lũy điểm thưởng & Đổi vé miễn phí -->
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-6 shadow-xl backdrop-blur-sm space-y-3 hover:border-purple-500/40 transition">
                        <div class="w-12 h-12 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center text-2xl font-bold border border-purple-500/30">
                            💎
                        </div>
                        <h3 class="text-lg font-bold text-white">Tích Điểm Tự Động & Đổi Vé</h3>
                        <p class="text-gray-300 text-xs sm:text-sm leading-relaxed">
                            Cộng ngay <strong class="text-cinematic-gold">+10 điểm</strong> cho mỗi vé xem phim thanh toán thành công (1 điểm = 1.000đ). Dễ dàng trừ trực tiếp vào hóa đơn cho các lần đặt vé tiếp theo.
                        </p>
                    </div>

                </div>

                <!-- How it works flow -->
                <div class="bg-white/5 border border-white/10 rounded-2xl p-6 sm:p-8 shadow-xl backdrop-blur-sm space-y-6">
                    <h2 class="text-xl font-serif font-bold text-white pb-3 border-b border-white/10 flex items-center gap-2">
                        <span>🚀</span> Quy Trình 4 Bước Đặt Vé Trực Tuyến Cực Dễ
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-center">
                        <div class="p-4 rounded-xl bg-black/40 border border-white/10">
                            <span class="w-8 h-8 rounded-full bg-cinematic-red text-white font-bold flex items-center justify-center mx-auto mb-2 text-sm">1</span>
                            <h4 class="font-bold text-white text-sm">Chọn Phim & Suất</h4>
                            <p class="text-xs text-gray-400 mt-1">Lựa chọn bộ phim yêu thích và rạp chiếu gần bạn nhất.</p>
                        </div>
                        <div class="p-4 rounded-xl bg-black/40 border border-white/10">
                            <span class="w-8 h-8 rounded-full bg-cinematic-red text-white font-bold flex items-center justify-center mx-auto mb-2 text-sm">2</span>
                            <h4 class="font-bold text-white text-sm">Chọn Chỗ & Bắp Nước</h4>
                            <p class="text-xs text-gray-400 mt-1">Chọn ghế ưng ý trên sơ đồ và thêm combo snack ưa thích.</p>
                        </div>
                        <div class="p-4 rounded-xl bg-black/40 border border-white/10">
                            <span class="w-8 h-8 rounded-full bg-cinematic-red text-white font-bold flex items-center justify-center mx-auto mb-2 text-sm">3</span>
                            <h4 class="font-bold text-white text-sm">Quét Mã QR Trả Tiền</h4>
                            <p class="text-xs text-gray-400 mt-1">Quét mã QR ngân hàng hoặc VNPay hoàn tất trong 10 giây.</p>
                        </div>
                        <div class="p-4 rounded-xl bg-black/40 border border-white/10">
                            <span class="w-8 h-8 rounded-full bg-cinematic-red text-white font-bold flex items-center justify-center mx-auto mb-2 text-sm">4</span>
                            <h4 class="font-bold text-white text-sm">Vào Rạp Xem Phim</h4>
                            <p class="text-xs text-gray-400 mt-1">Mở email hoặc màn hình vé thành công để quét mã vào cửa.</p>
                        </div>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="text-center pt-4">
                    <a href="{{ route('showtimes') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-cinematic-red to-red-700 hover:from-red-600 hover:to-red-800 text-white font-bold rounded-xl text-sm uppercase tracking-wider transition shadow-2xl">
                        <span>🎬</span> Trải Nghiệm Đặt Vé Online Ngay
                    </a>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection
