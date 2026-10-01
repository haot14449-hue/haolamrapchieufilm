@extends('layouts.app')

@section('title', 'Tuyển Dụng - HCTV Cinema')

@section('content')
<div class="pt-24 pb-16 bg-cinematic-dark min-h-screen text-white">
    <!-- Hero Banner -->
    <div class="page-hero-banner relative py-16 mb-10 overflow-hidden border-b">
        <div class="absolute inset-0 bg-cinematic-red/15 blur-3xl opacity-40 pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10 text-center">
            <span class="page-hero-badge inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold mb-3">
                💼 Gia Nhập Đội Ngũ HCTV Cinema
            </span>
            <h1 class="page-hero-title text-3xl sm:text-4xl md:text-5xl font-serif font-bold tracking-wider uppercase mb-3">
                Cơ Hội Nghề Nghiệp & Tuyển Dụng
            </h1>
            <p class="page-hero-desc text-sm sm:text-base max-w-2xl mx-auto">
                Khám phá môi trường làm việc trẻ trung, năng động và thỏa sức cháy cùng niềm đam mê điện ảnh tại HCTV Cinema.
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
                
                <!-- Perks & Benefits -->
                <div class="bg-white/5 border border-white/10 rounded-2xl p-6 sm:p-8 shadow-xl backdrop-blur-sm space-y-6">
                    <h2 class="text-xl sm:text-2xl font-serif font-bold text-white pb-3 border-b border-white/10">
                        Đặc Quyền Khi Trở Thành Thành Viên HCTV
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="p-4 rounded-xl bg-black/40 border border-white/10">
                            <span class="text-2xl mb-2 block">🎟️</span>
                            <h4 class="font-bold text-white text-sm">Xem Phim Miễn Phí</h4>
                            <p class="text-xs text-gray-400 mt-1">Được cấp vé xem phim miễn phí hàng tháng và vé mời tham dự các buổi chiếu ra mắt bom tấn (Premiere).</p>
                        </div>
                        <div class="p-4 rounded-xl bg-black/40 border border-white/10">
                            <span class="text-2xl mb-2 block">🚀</span>
                            <h4 class="font-bold text-white text-sm">Lộ Trình Thăng Tiến</h4>
                            <p class="text-xs text-gray-400 mt-1">Cơ hội phát triển nhanh chóng từ nhân viên Part-time lên Trưởng ca, Giám sát và Giám đốc cụm rạp chỉ sau 12-18 tháng.</p>
                        </div>
                        <div class="p-4 rounded-xl bg-black/40 border border-white/10">
                            <span class="text-2xl mb-2 block">💰</span>
                            <h4 class="font-bold text-white text-sm">Đãi Ngộ Hấp Dẫn</h4>
                            <p class="text-xs text-gray-400 mt-1">Mức lương cạnh tranh, thưởng doanh thu bán vé & bắp nước, phụ cấp ca đêm và chế độ bảo hiểm theo quy định.</p>
                        </div>
                    </div>
                </div>

                <!-- Job Openings -->
                <div class="space-y-4">
                    <h2 class="text-xl font-serif font-bold text-white flex items-center gap-2">
                        <span>🔥</span> Các Vị Trí Đang Tuyển Dụng
                    </h2>

                    <!-- Job 1 -->
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-6 hover:border-cinematic-gold/40 transition shadow-lg space-y-3">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-500/20 text-green-400 border border-green-500/30 uppercase">Part-time / Full-time</span>
                                <h3 class="text-lg font-bold text-white mt-1">Nhân Viên Dịch Vụ Khách Hàng (Quầy Vé • Bắp Nước • Soát Vé)</h3>
                            </div>
                            <span class="text-sm font-bold text-cinematic-gold whitespace-nowrap">25.000 - 32.000 đ/giờ</span>
                        </div>
                        <p class="text-xs text-gray-300 leading-relaxed">
                            <strong>Địa điểm:</strong> Tất cả cụm rạp HCTV tại TP.HCM, Hà Nội, Đà Nẵng, Cần Thơ.<br>
                            <strong>Mô tả:</strong> Chào đón khách, bán vé xem phim, pha chế và phục vụ bắp nước, hướng dẫn khách vào phòng chiếu và kiểm soát trật tự phòng chiếu.
                        </p>
                        <div class="pt-2 flex justify-between items-center text-xs">
                            <span class="text-gray-400">Phù hợp cho sinh viên, ca xoay linh hoạt</span>
                            <a href="mailto:tuyendung@hctv.vn?subject=Ung_tuyen_Nhan_vien_dich_vu_khach_hang" class="font-bold text-cinematic-gold hover:underline">Ứng tuyển ngay →</a>
                        </div>
                    </div>

                    <!-- Job 2 -->
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-6 hover:border-cinematic-gold/40 transition shadow-lg space-y-3">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-500/20 text-blue-400 border border-blue-500/30 uppercase">Full-time</span>
                                <h3 class="text-lg font-bold text-white mt-1">Giám Sát Sảnh Rạp Chiếu Phim (Floor Supervisor)</h3>
                            </div>
                            <span class="text-sm font-bold text-cinematic-gold whitespace-nowrap">9.000.000 - 13.000.000 đ</span>
                        </div>
                        <p class="text-xs text-gray-300 leading-relaxed">
                            <strong>Địa điểm:</strong> HCTV Cinema TP.HCM & Hà Nội.<br>
                            <strong>Mô tả:</strong> Quản lý hoạt động vận hành ca trực, phân công nhân sự, xử lý tình huống phát sinh của khách hàng, đảm bảo tiêu chuẩn vệ sinh và an ninh rạp.
                        </p>
                        <div class="pt-2 flex justify-between items-center text-xs">
                            <span class="text-gray-400">Yêu cầu: Có kinh nghiệm F&B/Dịch vụ tối thiểu 6 tháng</span>
                            <a href="mailto:tuyendung@hctv.vn?subject=Ung_tuyen_Giam_sat_sanh_rap" class="font-bold text-cinematic-gold hover:underline">Ứng tuyển ngay →</a>
                        </div>
                    </div>

                    <!-- Job 3 -->
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-6 hover:border-cinematic-gold/40 transition shadow-lg space-y-3">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-500/20 text-purple-400 border border-purple-500/30 uppercase">Full-time</span>
                                <h3 class="text-lg font-bold text-white mt-1">Kỹ Thuật Viên Vận Hành Máy Chiếu & Âm Thanh (Projectionist)</h3>
                            </div>
                            <span class="text-sm font-bold text-cinematic-gold whitespace-nowrap">10.000.000 - 15.000.000 đ</span>
                        </div>
                        <p class="text-xs text-gray-300 leading-relaxed">
                            <strong>Địa điểm:</strong> HCTV Cụm rạp trung tâm.<br>
                            <strong>Mô tả:</strong> Vận hành hệ thống máy chiếu kỹ thuật số Laser, nạp khóa bản quyền phim (KDM), kiểm tra âm thanh Dolby Atmos và bảo trì thiết bị phòng máy.
                        </p>
                        <div class="pt-2 flex justify-between items-center text-xs">
                            <span class="text-gray-400">Yêu cầu: Tốt nghiệp trung cấp/cao đẳng chuyên ngành Điện - Điện tử/CNTT</span>
                            <a href="mailto:tuyendung@hctv.vn?subject=Ung_tuyen_Ky_thuat_vien_chieu_phim" class="font-bold text-cinematic-gold hover:underline">Ứng tuyển ngay →</a>
                        </div>
                    </div>

                </div>

                <!-- How to apply -->
                <div class="page-cta-banner p-6 rounded-2xl border space-y-3">
                    <h3 class="text-base font-bold">Cách Thức Nộp Hồ Sơ Ứng Tuyển</h3>
                    <p class="text-xs sm:text-sm leading-relaxed">
                        Ứng viên gửi CV kèm ảnh chụp cá nhân về hộp thư điện tử Phòng Nhân Sự: <a href="mailto:tuyendung@hctv.vn" class="text-cinematic-gold font-bold hover:underline">tuyendung@hctv.vn</a> với tiêu đề <strong>[HCTV_Ứng Tuyển] - [Vị Trí] - [Họ Và Tên]</strong>. Hoặc liên hệ hotline nhân sự: <strong class="text-cinematic-gold">1900 6017 (Nhánh 3)</strong>.
                    </p>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection
