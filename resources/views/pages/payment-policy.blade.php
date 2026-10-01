@extends('layouts.app')

@section('title', 'Chính Sách Thanh Toán - HCTV Cinema')

@section('content')
<div class="pt-24 pb-16 bg-cinematic-dark min-h-screen text-white">
    <!-- Hero Banner -->
    <div class="page-hero-banner relative py-16 mb-10 overflow-hidden border-b">
        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10 text-center">
            <span class="page-hero-badge inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold mb-3">
                🛡️ An Toàn & Bảo Mật Giao Dịch
            </span>
            <h1 class="page-hero-title text-3xl sm:text-4xl md:text-5xl font-serif font-bold tracking-wider uppercase mb-3">
                Chính Sách Thanh Toán
            </h1>
            <p class="page-hero-desc text-sm sm:text-base max-w-2xl mx-auto">
                Cam kết bảo mật giao dịch tuyệt đối, hỗ trợ đa dạng phương thức thanh toán không tiền mặt hiện đại và tiện lợi.
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
                            <span class="text-cinematic-gold">1.</span> Các Phương Thức Thanh Toán Hỗ Trợ
                        </h2>
                        <div class="space-y-3 pt-2">
                            <div class="p-4 bg-black/40 rounded-xl border border-white/5">
                                <h3 class="font-bold text-white text-sm flex items-center gap-2 mb-1">
                                    <span class="text-blue-400">🏦</span> Chuyển Khoản Quét Mã VietQR (MB Bank • SePay Tự Động)
                                </h3>
                                <p class="text-xs text-gray-400">
                                    Quý khách mở ứng dụng ngân hàng bất kỳ (MBBank, Vietcombank, BIDV, Techcombank, MoMo,...) quét mã QR hiển thị trên màn hình. Hệ thống kết nối cổng SePay tự động đối soát nội dung chuyển khoản và kích hoạt vé ngay lập tức.
                                </p>
                            </div>

                            <div class="p-4 bg-black/40 rounded-xl border border-white/5">
                                <h3 class="font-bold text-white text-sm flex items-center gap-2 mb-1">
                                    <span class="text-red-400">💳</span> Cổng Thanh Toán Trực Tuyến VNPAY
                                </h3>
                                <p class="text-xs text-gray-400">
                                    Hỗ trợ hơn 40 ngân hàng nội địa (qua thẻ ATM/Internet Banking), thẻ tín dụng/ghi nợ quốc tế Visa, Mastercard, JCB và ví điện tử VNPAY.
                                </p>
                            </div>

                            <div class="p-4 bg-black/40 rounded-xl border border-white/5">
                                <h3 class="font-bold text-white text-sm flex items-center gap-2 mb-1">
                                    <span class="text-cinematic-gold">💎</span> Thanh Toán Bằng Điểm Tích Lũy Thành Viên
                                </h3>
                                <p class="text-xs text-gray-400">
                                    Thành viên có thể sử dụng điểm tích lũy trong tài khoản để thanh toán từ một phần đến 100% giá trị đơn hàng (1 điểm = 1.000 VNĐ).
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-white/10">
                        <h2 class="text-lg sm:text-xl font-serif font-bold text-white mb-2 flex items-center gap-2">
                            <span class="text-cinematic-gold">2.</span> Tiêu Chuẩn Bảo Mật Giao Dịch
                        </h2>
                        <p>
                            Hệ thống thanh toán của HCTV Cinema được bảo vệ bởi tiêu chuẩn mã hóa SSL/TLS 256-bit. Thông tin thẻ ngân hàng hoặc tài khoản của quý khách được xử lý trực tiếp bởi các cổng thanh toán được Ngân hàng Nhà nước Việt Nam cấp phép, HCTV tuyệt đối không lưu trữ bất kỳ thông tin số thẻ hay mã bảo mật CVV nào của khách hàng.
                        </p>
                    </div>

                    <div class="pt-4 border-t border-white/10">
                        <h2 class="text-lg sm:text-xl font-serif font-bold text-white mb-2 flex items-center gap-2">
                            <span class="text-cinematic-gold">3.</span> Hướng Dẫn Xử Lý Sự Cố Thanh Toán
                        </h2>
                        <div class="payment-support-box p-4 rounded-xl bg-blue-950/30 border border-blue-500/30 text-blue-200 space-y-2">
                            <p><strong>Trường hợp tài khoản ngân hàng đã bị trừ tiền nhưng website chưa chuyển sang trạng thái thành công:</strong></p>
                            <p>1. Vui lòng kiểm tra hộp thư đến (và thư mục Spam/Rác) của email xem đã nhận được vé điện tử hay chưa.</p>
                            <p>2. Đăng nhập vào mục <strong>"Lịch sử đặt vé"</strong> trên website để kiểm tra trạng thái vé.</p>
                            <p>3. Nếu sau 5 phút vẫn chưa thấy vé, quý khách chỉ cần chụp lại ảnh màn hình biến động số dư / biên lai chuyển khoản và liên hệ ngay Hotline: <strong class="text-white">1900 6017</strong> hoặc gửi email tới <strong class="text-white">hoidap@hctv.vn</strong> để nhân viên kích hoạt vé thủ công ngay lập tức.</p>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>
@endsection
