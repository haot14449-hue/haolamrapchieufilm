@extends('layouts.app')

@section('title', 'Chính Sách Bảo Mật - HCTV Cinema')

@section('content')
<div class="pt-24 pb-16 bg-cinematic-dark min-h-screen text-white">
    <!-- Hero Banner -->
    <div class="page-hero-banner relative py-16 mb-10 overflow-hidden border-b">
        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10 text-center">
            <span class="page-hero-badge inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold mb-3">
                🔒 Bảo Vệ Dữ Liệu Cá Nhân
            </span>
            <h1 class="page-hero-title text-3xl sm:text-4xl md:text-5xl font-serif font-bold tracking-wider uppercase mb-3">
                Chính Sách Bảo Mật Thông Tin
            </h1>
            <p class="page-hero-desc text-sm sm:text-base max-w-2xl mx-auto">
                HCTV Cinema tôn trọng và cam kết bảo vệ tuyệt đối thông tin riêng tư và dữ liệu cá nhân của quý khách hàng.
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
                            <span class="text-cinematic-gold">1.</span> Mục Đích Thu Thập Dữ Liệu
                        </h2>
                        <p>
                            Chúng tôi thu thập các thông tin bao gồm: Họ và tên, Địa chỉ email, Số điện thoại di động và Ngày sinh nhằm mục đích:
                        </p>
                        <ul class="list-disc list-inside space-y-1 mt-2">
                            <li>Xác nhận và gửi mã vé điện tử QR Code qua email khi đặt vé thành công.</li>
                            <li>Quản lý tài khoản thành viên, tích lũy điểm thưởng và áp dụng ưu đãi sinh nhật.</li>
                            <li>Gửi mã OTP xác thực qua email để bảo vệ an toàn khi quý khách thay đổi mật khẩu hoặc cập nhật tài khoản.</li>
                            <li>Hỗ trợ tra soát và giải quyết các khiếu nại phát sinh trong quá trình sử dụng dịch vụ.</li>
                        </ul>
                    </div>

                    <div class="pt-4 border-t border-white/10">
                        <h2 class="text-lg sm:text-xl font-serif font-bold text-white mb-2 flex items-center gap-2">
                            <span class="text-cinematic-gold">2.</span> Phạm Vi Sử Dụng & Cam Kết Bảo Mật
                        </h2>
                        <p>
                            HCTV cam kết <strong class="text-white">KHÔNG</strong> bán, trao đổi hay chia sẻ dữ liệu cá nhân của quý khách cho bất kỳ bên thứ ba nào vì mục đích thương mại. Thông tin chỉ được cung cấp cho cơ quan chức năng có thẩm quyền khi có yêu cầu hợp pháp theo quy định của pháp luật Việt Nam.
                        </p>
                    </div>

                    <div class="pt-4 border-t border-white/10">
                        <h2 class="text-lg sm:text-xl font-serif font-bold text-white mb-2 flex items-center gap-2">
                            <span class="text-cinematic-gold">3.</span> Thời Gian Lưu Trữ Thông Tin
                        </h2>
                        <p>
                            Thông tin cá nhân của quý khách sẽ được lưu trữ an toàn trên hệ thống máy chủ của HCTV cho đến khi quý khách có yêu cầu hủy bỏ hoặc tự thực hiện thao tác xóa tài khoản tại trang "Hồ sơ cá nhân".
                        </p>
                    </div>

                    <div class="pt-4 border-t border-white/10">
                        <h2 class="text-lg sm:text-xl font-serif font-bold text-white mb-2 flex items-center gap-2">
                            <span class="text-cinematic-gold">4.</span> Quyền Của Khách Hàng Đối Với Dữ Liệu
                        </h2>
                        <p>
                            Quý khách có toàn quyền kiểm tra, cập nhật, điều chỉnh hoặc yêu cầu xóa thông tin cá nhân của mình bằng cách đăng nhập vào tài khoản trên website hoặc liên hệ bộ phận chăm sóc khách hàng: <a href="mailto:hoidap@hctv.vn" class="text-cinematic-gold hover:underline">hoidap@hctv.vn</a>.
                        </p>
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>
@endsection
