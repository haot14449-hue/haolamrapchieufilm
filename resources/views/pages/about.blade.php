@extends('layouts.app')

@section('title', 'Giới Thiệu - HCTV Cinema Việt Nam')

@section('content')
<div class="pt-24 pb-16 bg-cinematic-dark min-h-screen text-white">
    <!-- Hero Banner -->
    <div class="page-hero-banner relative py-16 mb-10 overflow-hidden border-b">
        <div class="absolute inset-0 bg-cinematic-red/10 blur-3xl opacity-40 pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10 text-center">
            <span class="page-hero-badge inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold mb-3">
                🎬 HCTV Cinema Việt Nam
            </span>
            <h1 class="page-hero-title text-3xl sm:text-4xl md:text-5xl font-serif font-bold tracking-wider uppercase mb-3">
                Khám Phá Thế Giới Điện Ảnh HCTV
            </h1>
            <p class="page-hero-desc text-sm sm:text-base max-w-2xl mx-auto">
                Hệ thống rạp chiếu phim hiện đại hàng đầu Việt Nam, mang đến trải nghiệm nghe nhìn vượt trội với công nghệ quốc tế và dịch vụ chuẩn 5 sao.
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
                
                <!-- Story & Vision -->
                <div class="bg-white/5 border border-white/10 rounded-2xl p-6 sm:p-8 shadow-xl backdrop-blur-sm space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b border-white/10">
                        <span class="text-3xl">🌟</span>
                        <div>
                            <h2 class="text-xl sm:text-2xl font-serif font-bold text-white">Câu Chuyện & Tầm Nhìn Thương Hiệu</h2>
                            <p class="text-xs text-cinematic-gold">Hành trình kiến tạo không gian điện ảnh đỉnh cao</p>
                        </div>
                    </div>

                    <p class="text-gray-300 text-sm sm:text-base leading-relaxed">
                        Thành lập với sứ mệnh định hình lại văn hóa thưởng thức điện ảnh tại Việt Nam, <strong class="text-white">HCTV Cinema</strong> không ngừng tiên phong ứng dụng những công nghệ âm thanh, hình ảnh tiên tiến nhất thế giới. Từ màn chiếu siêu nét, hệ thống âm thanh vòm sống động đến không gian phòng chiếu sang trọng, mỗi khoảnh khắc tại HCTV đều là một chuyến phiêu lưu cảm xúc trọn vẹn.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                        <div class="p-4 rounded-xl bg-black/40 border border-white/10 text-center">
                            <span class="text-2xl sm:text-3xl font-black text-cinematic-red font-mono block">50+</span>
                            <span class="text-xs text-gray-400 uppercase tracking-wider mt-1 block">Cụm rạp toàn quốc</span>
                        </div>
                        <div class="p-4 rounded-xl bg-black/40 border border-white/10 text-center">
                            <span class="text-2xl sm:text-3xl font-black text-cinematic-gold font-mono block">300+</span>
                            <span class="text-xs text-gray-400 uppercase tracking-wider mt-1 block">Phòng chiếu hiện đại</span>
                        </div>
                        <div class="p-4 rounded-xl bg-black/40 border border-white/10 text-center">
                            <span class="text-2xl sm:text-3xl font-black text-blue-400 font-mono block">10 Triệu+</span>
                            <span class="text-xs text-gray-400 uppercase tracking-wider mt-1 block">Khán giả tin chọn mỗi năm</span>
                        </div>
                    </div>
                </div>

                <!-- Technologies -->
                <div class="bg-white/5 border border-white/10 rounded-2xl p-6 sm:p-8 shadow-xl backdrop-blur-sm space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b border-white/10">
                        <span class="text-3xl">📽️</span>
                        <div>
                            <h2 class="text-xl sm:text-2xl font-serif font-bold text-white">Công Nghệ Chiếu Phim Đỉnh Cao</h2>
                            <p class="text-xs text-cinematic-gold">Trải nghiệm những chuẩn mực rạp chiếu danh tiếng toàn cầu</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-5 rounded-xl bg-black/40 border border-sky-500/20 hover:border-sky-500/50 transition">
                            <span class="text-xs font-bold text-sky-400 tracking-widest uppercase">IMAX® LASER</span>
                            <h3 class="text-base font-bold text-white mt-1">Đắm chìm vào màn hình khổng lồ</h3>
                            <p class="text-xs text-gray-400 mt-2 leading-relaxed">Độ phân giải siêu cao kết hợp máy chiếu Laser tân tiến mang lại hình ảnh sắc nét đến từng chi tiết và độ tương phản ngoạn mục.</p>
                        </div>
                        <div class="p-5 rounded-xl bg-black/40 border border-red-500/20 hover:border-red-500/50 transition">
                            <span class="text-xs font-bold text-red-400 tracking-widest uppercase">4DX® MOTION</span>
                            <h3 class="text-base font-bold text-white mt-1">Thức tỉnh mọi giác quan</h3>
                            <p class="text-xs text-gray-400 mt-2 leading-relaxed">Ghế chuyển động đa chiều đồng bộ theo từng phân cảnh phim, kết hợp các hiệu ứng môi trường chân thực như gió, mưa, sấm chớp và hương thơm.</p>
                        </div>
                        <div class="p-5 rounded-xl bg-black/40 border border-purple-500/20 hover:border-purple-500/50 transition">
                            <span class="text-xs font-bold text-purple-400 tracking-widest uppercase">DOLBY ATMOS®</span>
                            <h3 class="text-base font-bold text-white mt-1">Âm thanh vòm 360 độ sống động</h3>
                            <p class="text-xs text-gray-400 mt-2 leading-relaxed">Hàng chục loa vệ tinh phân bố xung quanh và trên trần nhà tạo nên trường âm thanh chuyển động ba chiều chính xác và trung thực tuyệt đối.</p>
                        </div>
                        <div class="p-5 rounded-xl bg-black/40 border border-yellow-500/20 hover:border-yellow-500/50 transition">
                            <span class="text-xs font-bold text-yellow-400 tracking-widest uppercase">GOLD CLASS & SWEETBOX</span>
                            <h3 class="text-base font-bold text-white mt-1">Không gian riêng tư & đẳng cấp</h3>
                            <p class="text-xs text-gray-400 mt-2 leading-relaxed">Ghế sofa bọc da cao cấp có thể ngả 180 độ, vách ngăn riêng tư dành cho cặp đôi cùng dịch vụ phục vụ đồ uống trực tiếp tại chỗ.</p>
                        </div>
                    </div>
                </div>

                <!-- Core Values -->
                <div class="bg-white/5 border border-white/10 rounded-2xl p-6 sm:p-8 shadow-xl backdrop-blur-sm space-y-4">
                    <h2 class="text-xl font-serif font-bold text-white pb-3 border-b border-white/10">Giá Trị Cốt Lõi HCTV</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs sm:text-sm text-gray-300">
                        <div class="space-y-1.5 p-3 rounded-lg bg-black/20">
                            <span class="text-cinematic-gold font-bold text-base block">1. Khách hàng là trung tâm</span>
                            <p class="text-gray-400 text-xs">Mọi dịch vụ từ đặt vé online, quầy bắp nước đến hỗ trợ tại rạp đều hướng đến sự thuận tiện và hài lòng tối đa.</p>
                        </div>
                        <div class="space-y-1.5 p-3 rounded-lg bg-black/20">
                            <span class="text-cinematic-red font-bold text-base block">2. Đổi mới công nghệ</span>
                            <p class="text-gray-400 text-xs">Không ngừng nâng cấp hệ thống máy chiếu, âm thanh và chuyển đổi số quy trình đặt vé trực tuyến 4.0.</p>
                        </div>
                        <div class="space-y-1.5 p-3 rounded-lg bg-black/20">
                            <span class="text-blue-400 font-bold text-base block">3. Trách nhiệm cộng đồng</span>
                            <p class="text-gray-400 text-xs">Đồng hành cùng điện ảnh Việt, tổ chức các tuần lễ phim nhân văn và tài trợ các tài năng đạo diễn trẻ.</p>
                        </div>
                    </div>
                </div>

                <!-- Call to Action -->
                <div class="page-cta-banner p-6 rounded-2xl border flex flex-col sm:flex-row items-center justify-between gap-4 shadow-xl">
                    <div>
                        <h3 class="text-lg font-bold">Bạn đã sẵn sàng trải nghiệm điện ảnh hôm nay?</h3>
                        <p class="text-xs mt-1">Xem ngay danh sách phim bom tấn đang chiếu và đặt vé nhanh chóng.</p>
                    </div>
                    <a href="{{ route('movies.index') }}" class="px-6 py-2.5 bg-cinematic-red hover:bg-red-700 text-white font-bold rounded-xl text-xs uppercase tracking-wider transition whitespace-nowrap shadow-lg">
                        Xem Lịch Chiếu & Đặt Vé
                    </a>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection
