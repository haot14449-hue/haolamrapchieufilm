@extends('layouts.app')

@section('title', 'Dành Cho Đối Tác - HCTV Cinema')

@section('content')
<div class="pt-24 pb-16 bg-cinematic-dark min-h-screen text-white">
    <!-- Hero Banner -->
    <div class="page-hero-banner relative py-16 mb-10 overflow-hidden border-b">
        <div class="absolute inset-0 bg-blue-500/15 blur-3xl opacity-40 pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10 text-center">
            <span class="page-hero-badge inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold mb-3">
                🤝 Hợp Tác Cùng Phát Triển
            </span>
            <h1 class="page-hero-title text-3xl sm:text-4xl md:text-5xl font-serif font-bold tracking-wider uppercase mb-3">
                Chương Trình Hợp Tác Doanh Nghiệp & Đối Tác
            </h1>
            <p class="page-hero-desc text-sm sm:text-base max-w-2xl mx-auto">
                Cùng HCTV Cinema kết nối giá trị, kiến tạo những chương trình hợp tác điện ảnh đột phá và nâng tầm thương hiệu.
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
                
                <!-- 4 Strategic Partnership Sectors -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-6 shadow-xl space-y-3 hover:border-cinematic-gold/40 transition">
                        <span class="text-3xl block">🎞️</span>
                        <h3 class="text-lg font-bold text-white">Nhà Sản Xuất & Phát Hành Phim</h3>
                        <p class="text-xs sm:text-sm text-gray-300 leading-relaxed">
                            Hợp tác phát hành các tác phẩm điện ảnh trong nước và quốc tế. Tổ chức các buổi họp báo ra mắt phim (Press Junket), công chiếu thảm đỏ (Premiere) và giao lưu đoàn làm phim (Cinematour).
                        </p>
                    </div>

                    <div class="bg-white/5 border border-white/10 rounded-2xl p-6 shadow-xl space-y-3 hover:border-cinematic-red/40 transition">
                        <span class="text-3xl block">🏢</span>
                        <h3 class="text-lg font-bold text-white">Doanh Nghiệp & Khách Hàng B2B</h3>
                        <p class="text-xs sm:text-sm text-gray-300 leading-relaxed">
                            Cung cấp vé xem phim điện tử E-Voucher số lượng lớn, thẻ quà tặng doanh nghiệp làm phúc lợi cho cán bộ công nhân viên, quà tặng khách hàng tri ân với mức chiết khấu hấp dẫn lên đến 20%.
                        </p>
                    </div>

                    <div class="bg-white/5 border border-white/10 rounded-2xl p-6 shadow-xl space-y-3 hover:border-blue-500/40 transition">
                        <span class="text-3xl block">💳</span>
                        <h3 class="text-lg font-bold text-white">Ngân Hàng & Ví Điện Tử</h3>
                        <p class="text-xs sm:text-sm text-gray-300 leading-relaxed">
                            Đồng tổ chức các chương trình khuyến mãi độc quyền: Giảm giá ngày cuối tuần, hoàn tiền cho chủ thẻ tín dụng/ghi nợ, đổi điểm loyalty từ ứng dụng ngân hàng lấy vé xem phim HCTV.
                        </p>
                    </div>

                    <div class="bg-white/5 border border-white/10 rounded-2xl p-6 shadow-xl space-y-3 hover:border-green-500/40 transition">
                        <span class="text-3xl block">🥤</span>
                        <h3 class="text-lg font-bold text-white">Đối Tác Cung Ứng Thực Phẩm & F&B</h3>
                        <p class="text-xs sm:text-sm text-gray-300 leading-relaxed">
                            Hợp tác với các nhãn hàng thực phẩm, nước giải khát, đồ ăn nhanh đạt chuẩn an toàn vệ sinh thực phẩm quốc tế để phục vụ tại chuỗi quầy bar bắp nước HCTV.
                        </p>
                    </div>

                </div>

                <!-- Process -->
                <div class="bg-white/5 border border-white/10 rounded-2xl p-6 sm:p-8 shadow-xl backdrop-blur-sm space-y-4">
                    <h3 class="text-lg font-serif font-bold text-white pb-3 border-b border-white/10 flex items-center gap-2">
                        <span>⚡</span> Quy Trình Hợp Tác Nhanh Chóng
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs text-gray-300">
                        <div class="p-4 bg-black/40 rounded-xl border border-white/10">
                            <span class="text-cinematic-gold font-bold block mb-1">Bước 1: Tiếp nhận yêu cầu</span>
                            <p class="text-gray-400">Gửi thông tin nhu cầu qua email hoặc liên hệ hotline phòng Phát triển Kinh doanh.</p>
                        </div>
                        <div class="p-4 bg-black/40 rounded-xl border border-white/10">
                            <span class="text-cinematic-gold font-bold block mb-1">Bước 2: Tư vấn & Đề xuất</span>
                            <p class="text-gray-400">Đội ngũ HCTV sẽ xây dựng gói giải pháp ưu đãi tối ưu ngân sách cho đối tác trong vòng 24h.</p>
                        </div>
                        <div class="p-4 bg-black/40 rounded-xl border border-white/10">
                            <span class="text-cinematic-gold font-bold block mb-1">Bước 3: Ký kết & Triển khai</span>
                            <p class="text-gray-400">Ký kết hợp đồng nhanh gọn, bàn giao mã voucher điện tử hoặc kích hoạt chương trình truyền thông.</p>
                        </div>
                    </div>
                </div>

                <!-- Contact B2B Box -->
                <div class="page-cta-banner p-6 sm:p-8 rounded-2xl border space-y-3">
                    <h3 class="text-lg font-bold">Liên Hệ Bộ Phận Phát Triển Đối Tác B2B</h3>
                    <p class="text-xs sm:text-sm leading-relaxed">
                        Để được hỗ trợ hợp đồng và cấp tài khoản đối tác doanh nghiệp, xin vui lòng gửi email về: <a href="mailto:doitac@hctv.vn" class="text-blue-500 font-bold hover:underline">doitac@hctv.vn</a> hoặc gọi trực tiếp: <strong class="text-cinematic-gold">1900 6017 (Nhánh 5)</strong>.
                    </p>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection
