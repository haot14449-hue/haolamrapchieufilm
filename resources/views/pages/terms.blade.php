@extends('layouts.app')

@section('title', 'Điều Khoản Chung - HCTV Cinema')

@section('content')
<div class="pt-24 pb-16 bg-cinematic-dark min-h-screen text-white">
    <!-- Hero Banner -->
    <div class="page-hero-banner relative py-16 mb-10 overflow-hidden border-b">
        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10 text-center">
            <span class="page-hero-badge inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold mb-3">
                📜 Chính Sách & Quy Định Pháp Lý
            </span>
            <h1 class="page-hero-title text-3xl sm:text-4xl md:text-5xl font-serif font-bold tracking-wider uppercase mb-3">
                Điều Khoản Sử Dụng Chung
            </h1>
            <p class="page-hero-desc text-sm sm:text-base max-w-2xl mx-auto">
                Các quy định và thỏa thuận ràng buộc khi quý khách truy cập, đăng ký tài khoản và sử dụng dịch vụ trên nền tảng HCTV Cinema.
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
                            <span class="text-cinematic-gold">1.</span> Chấp Thuận Điều Khoản
                        </h2>
                        <p>
                            Khi truy cập, duyệt xem hoặc thực hiện giao dịch đặt vé trên website/ứng dụng HCTV Cinema, quý khách được xem là đã đọc, hiểu và đồng ý hoàn toàn với các điều khoản và điều kiện được nêu tại đây. Nếu quý khách không đồng ý với bất kỳ phần nào của các điều khoản này, vui lòng ngừng sử dụng dịch vụ.
                        </p>
                    </div>

                    <div class="pt-4 border-t border-white/10">
                        <h2 class="text-lg sm:text-xl font-serif font-bold text-white mb-2 flex items-center gap-2">
                            <span class="text-cinematic-gold">2.</span> Tài Khoản Người Dùng & Bảo Mật
                        </h2>
                        <p class="space-y-2">
                            <span>- Khi đăng ký tài khoản thành viên HCTV, quý khách có trách nhiệm cung cấp thông tin chính xác, đầy đủ và cập nhật (Họ tên, Email, Số điện thoại).</span><br>
                            <span>- Quý khách có trách nhiệm tự bảo mật mật khẩu và thông tin xác thực tài khoản. Mọi hành vi thực hiện dưới tài khoản của quý khách sẽ do quý khách chịu trách nhiệm.</span><br>
                            <span>- Trường hợp phát hiện tài khoản bị truy cập trái phép, quý khách cần thông báo ngay cho HCTV qua hotline 1900 6017 để được hỗ trợ khóa tài khoản kịp thời.</span>
                        </p>
                    </div>

                    <div class="pt-4 border-t border-white/10">
                        <h2 class="text-lg sm:text-xl font-serif font-bold text-white mb-2 flex items-center gap-2">
                            <span class="text-cinematic-gold">3.</span> Quyền Sở Hữu Trí Tuệ
                        </h2>
                        <p>
                            Toàn bộ hình ảnh, nội dung, biểu tượng thương hiệu, trailer phim, thiết kế giao diện và mã nguồn phần mềm trên hệ thống HCTV đều thuộc quyền sở hữu của Công ty TNHH HCTV Việt Nam hoặc các đối tác cấp phép. Nghiêm cấm mọi hành vi sao chép, phân phối hoặc sử dụng cho mục đích thương mại khi chưa có sự đồng ý bằng văn bản từ HCTV.
                        </p>
                    </div>

                    <div class="pt-4 border-t border-white/10">
                        <h2 class="text-lg sm:text-xl font-serif font-bold text-white mb-2 flex items-center gap-2">
                            <span class="text-cinematic-gold">4.</span> Quyền Sửa Đổi Điều Khoản
                        </h2>
                        <p>
                            HCTV có quyền điều chỉnh, bổ sung các điều khoản này tại bất kỳ thời điểm nào nhằm phù hợp với quy định pháp luật và chính sách vận hành. Các sửa đổi sẽ có hiệu lực ngay khi được đăng tải công khai trên website.
                        </p>
                    </div>

                    <div class="pt-4 border-t border-white/10">
                        <h2 class="text-lg sm:text-xl font-serif font-bold text-white mb-2 flex items-center gap-2">
                            <span class="text-cinematic-gold">5.</span> Luật Áp Dụng & Giải Quyết Tranh Chấp
                        </h2>
                        <p>
                            Các điều khoản này chịu sự điều chỉnh của Pháp luật nước Cộng hòa Xã hội Chủ nghĩa Việt Nam. Mọi tranh chấp phát sinh sẽ được ưu tiên thương lượng hòa giải; trường hợp không đạt được thỏa thuận, tranh chấp sẽ được đưa ra Tòa án có thẩm quyền tại TP. Hồ Chí Minh để giải quyết.
                        </p>
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>
@endsection
