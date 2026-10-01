@extends('layouts.app')

@section('title', 'Liên Hệ Quảng Cáo - HCTV Cinema')

@section('content')
<div class="pt-24 pb-16 bg-cinematic-dark min-h-screen text-white">
    <!-- Hero Banner -->
    <div class="page-hero-banner relative py-16 mb-10 overflow-hidden border-b">
        <div class="absolute inset-0 bg-cinematic-gold/15 blur-3xl opacity-40 pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10 text-center">
            <span class="page-hero-badge inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold mb-3">
                📢 Cinema Advertising Solutions
            </span>
            <h1 class="page-hero-title text-3xl sm:text-4xl md:text-5xl font-serif font-bold tracking-wider uppercase mb-3">
                Liên Hệ Quảng Cáo Tại HCTV Cinema
            </h1>
            <p class="page-hero-desc text-sm sm:text-base max-w-2xl mx-auto">
                Giải pháp truyền thông thương hiệu toàn diện trên hệ thống rạp chiếu phim hiện đại số 1, tiếp cận hàng triệu khách hàng tiềm năng.
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
                
                <!-- Ad Channels -->
                <div>
                    <h2 class="text-xl sm:text-2xl font-serif font-bold text-white mb-2">Các Hình Thức Quảng Cáo Tại Rạp HCTV</h2>
                    <p class="text-xs sm:text-sm text-gray-400 mb-6">Đa dạng hình thức từ màn ảnh rộng đến không gian tương tác trực tiếp tại sảnh rạp.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div class="bg-white/5 border border-white/10 rounded-2xl p-6 shadow-xl space-y-3 hover:border-cinematic-red/40 transition">
                            <span class="text-3xl block">🎬</span>
                            <h3 class="text-lg font-bold text-white">Quảng Cáo Trên Màn Ảnh Rộng (On-Screen Ads)</h3>
                            <p class="text-xs sm:text-sm text-gray-300 leading-relaxed">
                                Phát video clip TVC (15s, 30s, 60s) ngay trước giờ chiếu phim với độ phân giải siêu nét 4K và âm thanh vòm sống động, đảm bảo 100% sự tập trung chú ý của khán giả trong khán phòng tối.
                            </p>
                        </div>

                        <div class="bg-white/5 border border-white/10 rounded-2xl p-6 shadow-xl space-y-3 hover:border-cinematic-gold/40 transition">
                            <span class="text-3xl block">📺</span>
                            <h3 class="text-lg font-bold text-white">Màn Hình Kỹ Thuật Số Sảnh Rạp (Lobby Digital Ads)</h3>
                            <p class="text-xs sm:text-sm text-gray-300 leading-relaxed">
                                Mạng lưới màn hình LED/LCD kích thước lớn lắp đặt tại vị trí trung tâm sảnh chờ, quầy bán vé và quầy bắp nước với lưu lượng khách qua lại dày đặc mỗi ngày.
                            </p>
                        </div>

                        <div class="bg-white/5 border border-white/10 rounded-2xl p-6 shadow-xl space-y-3 hover:border-blue-500/40 transition">
                            <span class="text-3xl block">🎪</span>
                            <h3 class="text-lg font-bold text-white">Booth Trải Nghiệm & Phát Mẫu Thử (Sampling)</h3>
                            <p class="text-xs sm:text-sm text-gray-300 leading-relaxed">
                                Đặt gian hàng giới thiệu sản phẩm (Activation Booth), trưng bày mô hình (Mockup/Standee) và phát quà tặng trải nghiệm trực tiếp cho đối tượng khách hàng mục tiêu.
                            </p>
                        </div>

                        <div class="bg-white/5 border border-white/10 rounded-2xl p-6 shadow-xl space-y-3 hover:border-green-500/40 transition">
                            <span class="text-3xl block">🍿</span>
                            <h3 class="text-lg font-bold text-white">In Logo Lên Ly & Hộp Bắp (Popcorn & Cup Branding)</h3>
                            <p class="text-xs sm:text-sm text-gray-300 leading-relaxed">
                                Nhãn hàng hiện diện trực tiếp trong tay người tiêu dùng suốt thời gian 2-3 tiếng thưởng thức bộ phim thông qua thiết kế ly nước và hộp bắp rang bơ độc quyền.
                            </p>
                        </div>

                    </div>
                </div>

                <!-- Hall Rental & Private Screening -->
                <div class="bg-white/5 border border-white/10 rounded-2xl p-6 sm:p-8 shadow-xl backdrop-blur-sm space-y-4">
                    <h3 class="text-lg font-serif font-bold text-white flex items-center gap-2">
                        <span>🏛️</span> Dịch Vụ Thuê Trọn Phòng Chiếu & Tổ Chức Sự Kiện
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-300 leading-relaxed">
                        HCTV Cinema cung cấp dịch vụ bao rạp trọn gói cho các doanh nghiệp, cơ quan, trường học tổ chức:
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs text-gray-300">
                        <div class="p-3 bg-black/40 rounded-xl border border-white/5">✓ Hội nghị khách hàng & Ra mắt sản phẩm mới</div>
                        <div class="p-3 bg-black/40 rounded-xl border border-white/5">✓ Chiếu phim tri ân cán bộ nhân viên công ty</div>
                        <div class="p-3 bg-black/40 rounded-xl border border-white/5">✓ Họp báo, Talkshow, Gala Dinner cuối năm</div>
                        <div class="p-3 bg-black/40 rounded-xl border border-white/5">✓ Chiếu phim tư liệu, kỷ niệm cá nhân & gia đình</div>
                    </div>
                </div>

                <!-- Contact Info Card -->
                <div class="page-cta-banner p-6 sm:p-8 rounded-2xl border space-y-4 shadow-2xl">
                    <h3 class="text-xl font-bold">Liên Hệ Nhận Báo Giá & Tư Vấn Quảng Cáo</h3>
                    <p class="text-xs sm:text-sm leading-relaxed">
                        Quý đối tác, doanh nghiệp vui lòng liên hệ trực tiếp với Phòng Quảng Cáo & Tiếp Thị HCTV Cinema:
                    </p>
                    <div class="space-y-2 text-xs sm:text-sm">
                        <p>📍 <strong>Trụ sở:</strong> Lầu 2, số 7/28, đường Thành Thái, phường Diên Hồng, Quận 10, TP. Hồ Chí Minh</p>
                        <p>📞 <strong>Hotline Kinh Doanh:</strong> <span class="text-cinematic-gold font-bold text-base">1900 6017 (Nhánh 4)</span> / Mobile: <strong class="text-cinematic-red font-bold">0909 123 456</strong></p>
                        <p>✉️ <strong>Email tiếp nhận hồ sơ quảng cáo:</strong> <a href="mailto:quangcao@hctv.vn" class="text-cinematic-gold font-bold hover:underline">quangcao@hctv.vn</a></p>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection
