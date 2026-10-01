@extends('layouts.app')

@section('title', 'Điều Khoản Giao Dịch - HCTV Cinema')

@section('content')
<div class="pt-24 pb-16 bg-cinematic-dark min-h-screen text-white">
    <!-- Hero Banner -->
    <div class="page-hero-banner relative py-16 mb-10 overflow-hidden border-b">
        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10 text-center">
            <span class="page-hero-badge inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold mb-3">
                💳 Quy Trình Đặt Vé & Thanh Toán
            </span>
            <h1 class="page-hero-title text-3xl sm:text-4xl md:text-5xl font-serif font-bold tracking-wider uppercase mb-3">
                Điều Khoản Giao Dịch
            </h1>
            <p class="page-hero-desc text-sm sm:text-base max-w-2xl mx-auto">
                Quy định chi tiết về quy trình đặt vé, thanh toán, xác nhận đơn hàng và các chính sách liên quan tại HCTV Cinema.
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
            <div class="lg:col-span-8 xl:col-span-9 space-y-6">
                
                <div class="bg-white/5 border border-white/10 rounded-2xl p-6 sm:p-8 shadow-xl backdrop-blur-sm space-y-6 text-xs sm:text-sm text-gray-300 leading-relaxed">
                    
                    <div>
                        <h2 class="text-lg sm:text-xl font-serif font-bold text-white mb-2 flex items-center gap-2">
                            <span class="text-cinematic-gold">1.</span> Quy Trình Giao Dịch Đặt Vé
                        </h2>
                        <p class="space-y-1.5">
                            Quý khách thực hiện đặt vé theo các bước sau:<br>
                            1. Lựa chọn bộ phim, ngày chiếu, cụm rạp và khung giờ suất chiếu mong muốn.<br>
                            2. Chọn vị trí ghế ngồi trên sơ đồ phòng chiếu. Hệ thống sẽ tạm giữ ghế trong <strong class="text-white">05 phút</strong>.<br>
                            3. Lựa chọn combo bắp & nước (nếu có nhu cầu).<br>
                            4. Kiểm tra tóm tắt đơn hàng, áp dụng điểm tích lũy hoặc mã giảm giá (nếu có).<br>
                            5. Chọn phương thức thanh toán và hoàn tất giao dịch.
                        </p>
                    </div>

                    <div class="pt-4 border-t border-white/10">
                        <h2 class="text-lg sm:text-xl font-serif font-bold text-white mb-2 flex items-center gap-2">
                            <span class="text-cinematic-gold">2.</span> Giá Vé & Xác Nhận Đơn Hàng
                        </h2>
                        <p>
                            Giá vé hiển thị trên website là giá đã bao gồm thuế Giá trị gia tăng (VAT). Sau khi hệ thống nhận được thanh toán thành công, màn hình sẽ hiển thị thông báo "Đặt vé thành công", đồng thời hệ thống tự động gửi vé điện tử (E-ticket) có mã QR tới địa chỉ email đã đăng ký của quý khách.
                        </p>
                    </div>

                    <div class="pt-4 border-t border-white/10">
                        <h2 class="text-lg sm:text-xl font-serif font-bold text-white mb-2 flex items-center gap-2">
                            <span class="text-cinematic-red">3.</span> Chính Sách Đổi / Hủy Vé & Hoàn Tiền
                        </h2>
                        <div class="transaction-warning-box p-4 rounded-xl bg-red-950/30 border border-red-500/30 text-red-200 space-y-2">
                            <p><strong>Lưu ý đặc thù ngành chiếu phim:</strong></p>
                            <p>- Do đặc thù dịch vụ mang tính thời điểm và hệ thống khóa ghế theo thời gian thực, vé xem phim và bắp nước đã thanh toán thành công <strong class="text-white underline">KHÔNG ĐƯỢC PHÉP HỦY, ĐỔI HOẶC HOÀN TIỀN</strong> dưới bất kỳ hình thức nào.</p>
                            <p>- Quý khách vui lòng kiểm tra thật kỹ các thông tin: Tên phim, Rạp chiếu, Ngày chiếu, Suất chiếu và Vị trí ghế trước khi bấm xác nhận thanh toán.</p>
                            <p>- Trường hợp suất chiếu bị hủy do sự cố kỹ thuật bất khả kháng từ phía rạp, HCTV sẽ hoàn tiền 100% hoặc hỗ trợ đổi sang suất chiếu khác tương đương theo thỏa thuận với quý khách.</p>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-white/10">
                        <h2 class="text-lg sm:text-xl font-serif font-bold text-white mb-2 flex items-center gap-2">
                            <span class="text-cinematic-gold">4.</span> Nhận Vé Tại Rạp
                        </h2>
                        <p>
                            Khi đến rạp xem phim, quý khách chỉ cần mở ứng dụng hoặc email chứa mã QR vé điện tử đưa cho nhân viên tại cửa soát vé để quét mã vào phòng chiếu, hoặc quét mã tại quầy in vé tự động (Kiosk) để in vé giấy lưu niệm.
                        </p>
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>
@endsection
