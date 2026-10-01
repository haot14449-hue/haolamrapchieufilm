@extends('layouts.app')

@section('title', 'Thẻ Quà Tặng E-Gift Card - HCTV Cinema')

@section('content')
<div class="pt-24 pb-16 bg-cinematic-dark min-h-screen text-white">
    <!-- Hero Banner -->
    <div class="page-hero-banner relative py-16 mb-10 overflow-hidden border-b">
        <div class="absolute inset-0 bg-cinematic-gold/15 blur-3xl opacity-40 pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10 text-center">
            <span class="page-hero-badge inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold mb-3">
                🎁 Món Quà Điện Ảnh Ý Nghĩa
            </span>
            <h1 class="page-hero-title text-3xl sm:text-4xl md:text-5xl font-serif font-bold tracking-wider uppercase mb-3">
                Thẻ Quà Tặng HCTV E-Gift Card
            </h1>
            <p class="page-hero-desc text-sm sm:text-base max-w-2xl mx-auto">
                Gửi trọn niềm vui và những xúc cảm điện ảnh tuyệt vời nhất đến người thân, bạn bè, đối tác với thẻ quà tặng điện tử HCTV.
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
                
                <!-- Gift Card Tiers -->
                <div>
                    <h2 class="text-xl sm:text-2xl font-serif font-bold text-white mb-2">Các Hạng Thẻ Quà Tặng HCTV</h2>
                    <p class="text-xs sm:text-sm text-gray-400 mb-6">Áp dụng cho tất cả cụm rạp HCTV trên toàn quốc, có hạn sử dụng lên đến 12 tháng.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Card 1: Silver 100K -->
                        <div class="gift-card-box gift-card-silver p-6 rounded-2xl bg-gradient-to-br from-slate-900 via-gray-900 to-black border border-white/20 shadow-xl relative overflow-hidden group hover:border-cinematic-gold/60 transition-all">
                            <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-white/5 rounded-full blur-2xl group-hover:bg-cinematic-gold/20 transition"></div>
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <span class="text-xs uppercase tracking-widest text-slate-400 font-bold block">Thẻ Bạc • Silver Card</span>
                                    <h3 class="text-2xl font-serif font-bold text-white mt-1">100.000 đ</h3>
                                </div>
                                <span class="px-3 py-1 bg-white/10 text-white rounded-full text-xs font-semibold">1 Vé 2D</span>
                            </div>
                            <p class="text-xs text-gray-300 leading-relaxed mb-6">
                                Thích hợp làm món quà nhỏ gửi tặng bạn bè nhân dịp sinh nhật, đổi được 01 vé xem phim 2D tiêu chuẩn tại bất kỳ rạp HCTV nào.
                            </p>
                            <div class="pt-4 border-t border-white/10 flex items-center justify-between">
                                <span class="text-[11px] text-gray-400">HSD: 12 tháng</span>
                                <a href="mailto:hoidap@hctv.vn?subject=Dat_mua_the_qua_tang_100k" class="text-xs font-bold text-cinematic-gold hover:underline">Liên hệ mua →</a>
                            </div>
                        </div>

                        <!-- Card 2: Couple Gold 250K -->
                        <div class="gift-card-box gift-card-gold p-6 rounded-2xl bg-gradient-to-br from-yellow-950/40 via-gray-900 to-black border border-cinematic-gold/40 shadow-xl relative overflow-hidden group hover:border-cinematic-gold transition-all">
                            <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-cinematic-gold/10 rounded-full blur-2xl group-hover:bg-cinematic-gold/25 transition"></div>
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <span class="text-xs uppercase tracking-widest text-cinematic-gold font-bold block">Thẻ Cặp Đôi • Sweetbox Gold</span>
                                    <h3 class="text-2xl font-serif font-bold text-cinematic-gold mt-1">250.000 đ</h3>
                                </div>
                                <span class="px-3 py-1 bg-cinematic-gold/20 text-cinematic-gold rounded-full text-xs font-semibold">2 Vé + Bắp Nước</span>
                            </div>
                            <p class="text-xs text-gray-300 leading-relaxed mb-6">
                                Món quà hoàn hảo cho ngày hẹn hò ngọt ngào: Bao gồm 02 vé xem phim ghế đôi Sweetbox và 01 Combo Bắp Rang Bơ + Nước ngọt mát lạnh.
                            </p>
                            <div class="pt-4 border-t border-white/10 flex items-center justify-between">
                                <span class="text-[11px] text-gray-400">HSD: 12 tháng</span>
                                <a href="mailto:hoidap@hctv.vn?subject=Dat_mua_the_qua_tang_250k" class="text-xs font-bold text-cinematic-gold hover:underline">Liên hệ mua →</a>
                            </div>
                        </div>

                        <!-- Card 3: VIP Platinum 500K -->
                        <div class="gift-card-box gift-card-platinum p-6 rounded-2xl bg-gradient-to-br from-red-950/40 via-gray-900 to-black border border-cinematic-red/40 shadow-xl relative overflow-hidden group hover:border-cinematic-red transition-all">
                            <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-cinematic-red/10 rounded-full blur-2xl group-hover:bg-cinematic-red/25 transition"></div>
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <span class="text-xs uppercase tracking-widest text-red-400 font-bold block">Thẻ VIP Platinum</span>
                                    <h3 class="text-2xl font-serif font-bold text-red-400 mt-1">500.000 đ</h3>
                                </div>
                                <span class="px-3 py-1 bg-cinematic-red/20 text-red-300 rounded-full text-xs font-semibold">Phòng IMAX / 4DX</span>
                            </div>
                            <p class="text-xs text-gray-300 leading-relaxed mb-6">
                                Trải nghiệm đỉnh cao phòng chiếu đặc biệt IMAX Laser hoặc 4DX Motion kèm combo F&B cao cấp dành cho 2 người.
                            </p>
                            <div class="pt-4 border-t border-white/10 flex items-center justify-between">
                                <span class="text-[11px] text-gray-400">HSD: 12 tháng</span>
                                <a href="mailto:hoidap@hctv.vn?subject=Dat_mua_the_qua_tang_500k" class="text-xs font-bold text-cinematic-gold hover:underline">Liên hệ mua →</a>
                            </div>
                        </div>

                        <!-- Card 4: Diamond Corporate 1.000.000K -->
                        <div class="gift-card-box gift-card-diamond p-6 rounded-2xl bg-gradient-to-br from-blue-950/40 via-gray-900 to-black border border-blue-500/40 shadow-xl relative overflow-hidden group hover:border-blue-400 transition-all">
                            <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-blue-500/10 rounded-full blur-2xl group-hover:bg-blue-400/25 transition"></div>
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <span class="text-xs uppercase tracking-widest text-blue-400 font-bold block">Thẻ Doanh Nghiệp • Diamond</span>
                                    <h3 class="text-2xl font-serif font-bold text-blue-400 mt-1">1.000.000 đ</h3>
                                </div>
                                <span class="px-3 py-1 bg-blue-500/20 text-blue-300 rounded-full text-xs font-semibold">Ưu Đãi Doanh Nghiệp</span>
                            </div>
                            <p class="text-xs text-gray-300 leading-relaxed mb-6">
                                Giải pháp phúc lợi và tri ân hoàn hảo cho công đoàn doanh nghiệp, đối tác và khách hàng VIP với mức chiết khấu cực tốt khi mua số lượng lớn.
                            </p>
                            <div class="pt-4 border-t border-white/10 flex items-center justify-between">
                                <span class="text-[11px] text-gray-400">Chiết khấu đến 15%</span>
                                <a href="mailto:hoidap@hctv.vn?subject=Dat_mua_the_doanh_nghiep" class="text-xs font-bold text-cinematic-gold hover:underline">Nhận báo giá B2B →</a>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- How to use -->
                <div class="bg-white/5 border border-white/10 rounded-2xl p-6 sm:p-8 shadow-xl backdrop-blur-sm space-y-4">
                    <h2 class="text-lg font-serif font-bold text-white pb-3 border-b border-white/10 flex items-center gap-2">
                        <span>💡</span> Hướng Dẫn Sử Dụng Thẻ Quà Tặng
                    </h2>
                    <ul class="space-y-3 text-xs sm:text-sm text-gray-300 leading-relaxed list-disc list-inside">
                        <li><strong class="text-white">Khi đặt vé online:</strong> Nhập mã voucher quà tặng tại bước thanh toán để giảm trừ trực tiếp số tiền tương ứng.</li>
                        <li><strong class="text-white">Khi mua tại quầy vé:</strong> Xuất trình mã QR thẻ quà tặng được gửi trong email hoặc tin nhắn SMS cho nhân viên quầy thu ngân.</li>
                        <li>Mã quà tặng có thể sử dụng nhiều lần cho đến khi hết hạn mức số dư.</li>
                        <li>Không hoàn lại tiền mặt nếu giá trị đơn hàng thấp hơn mệnh giá thẻ.</li>
                    </ul>
                </div>

                <!-- Corporate CTA -->
                <div class="page-cta-banner p-6 rounded-2xl border flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div>
                        <h4 class="text-base font-bold">Bạn là Doanh nghiệp muốn mua số lượng lớn?</h4>
                        <p class="text-xs mt-1">Liên hệ ngay hotline chuyên viên khách hàng doanh nghiệp để nhận mức chiết khấu tốt nhất.</p>
                    </div>
                    <a href="tel:19006017" class="px-6 py-2.5 bg-cinematic-gold hover:bg-yellow-500 text-black font-bold rounded-xl text-xs uppercase tracking-wider transition whitespace-nowrap shadow-lg">
                        Gọi Ngay 1900 6017
                    </a>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection
