@extends('layouts.app')

@section('title', 'Câu Hỏi Thường Gặp (FAQ) - HCTV Cinema')

@section('content')
<div class="pt-24 pb-16 bg-cinematic-dark min-h-screen text-white">
    <!-- Hero Banner -->
    <div class="page-hero-banner relative py-16 mb-10 overflow-hidden border-b">
        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10 text-center">
            <span class="page-hero-badge inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold mb-3">
                ❓ Trung Tâm Hỗ Trợ Khách Hàng
            </span>
            <h1 class="page-hero-title text-3xl sm:text-4xl md:text-5xl font-serif font-bold tracking-wider uppercase mb-3">
                Câu Hỏi Thường Gặp (FAQ)
            </h1>
            <p class="page-hero-desc text-sm sm:text-base max-w-2xl mx-auto">
                Giải đáp chi tiết các thắc mắc phổ biến nhất về đặt vé, thanh toán, đổi điểm thành viên và quy định xem phim tại HCTV Cinema.
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
                
                <!-- FAQ Accordion List -->
                <div class="space-y-4" id="faqAccordion">
                    
                    <!-- FAQ 1 -->
                    <div class="faq-item bg-white/5 border border-white/10 rounded-2xl overflow-hidden transition-all duration-300">
                        <button type="button" onclick="toggleFaq(this)" class="w-full p-5 text-left flex items-center justify-between gap-4 font-bold text-sm sm:text-base text-white hover:text-cinematic-gold transition">
                            <span class="flex items-center gap-3">
                                <span class="text-cinematic-red text-lg">Q1.</span> Tôi đã đặt vé và thanh toán online thành công, làm thế nào để vào phòng chiếu?
                            </span>
                            <span class="faq-icon text-xl text-gray-400 transition-transform duration-300">+</span>
                        </button>
                        <div class="faq-content hidden px-5 pb-5 text-xs sm:text-sm text-gray-300 border-t border-white/5 pt-3 leading-relaxed">
                            Sau khi thanh toán thành công, hệ thống sẽ gửi một vé điện tử (E-ticket) có mã QR về địa chỉ email của bạn và hiển thị trong mục "Lịch sử đặt vé" trên tài khoản cá nhân. Khi đến rạp, bạn chỉ cần xuất trình mã QR trên điện thoại cho nhân viên soát vé quét để vào phòng chiếu, hoặc quét mã tại quầy Kiosk tự động để in vé giấy nếu muốn lưu giữ kỷ niệm.
                        </div>
                    </div>

                    <!-- FAQ 2 -->
                    <div class="faq-item bg-white/5 border border-white/10 rounded-2xl overflow-hidden transition-all duration-300">
                        <button type="button" onclick="toggleFaq(this)" class="w-full p-5 text-left flex items-center justify-between gap-4 font-bold text-sm sm:text-base text-white hover:text-cinematic-gold transition">
                            <span class="flex items-center gap-3">
                                <span class="text-cinematic-gold text-lg">Q2.</span> Điểm tích lũy thành viên được tính như thế nào và dùng ra sao?
                            </span>
                            <span class="faq-icon text-xl text-gray-400 transition-transform duration-300">+</span>
                        </button>
                        <div class="faq-content hidden px-5 pb-5 text-xs sm:text-sm text-gray-300 border-t border-white/5 pt-3 leading-relaxed">
                            Mỗi vé xem phim đặt thành công, bạn sẽ được tự động cộng <strong class="text-cinematic-gold">+10 điểm</strong> vào tài khoản (quy đổi: 1 điểm = 1.000 VNĐ). Khi đặt các đơn vé sau, tại trang thanh toán bạn có thể nhập số điểm muốn sử dụng để trừ tiền trực tiếp vào hóa đơn (giảm tối đa lên đến 100% tổng tiền vé!).
                        </div>
                    </div>

                    <!-- FAQ 3 -->
                    <div class="faq-item bg-white/5 border border-white/10 rounded-2xl overflow-hidden transition-all duration-300">
                        <button type="button" onclick="toggleFaq(this)" class="w-full p-5 text-left flex items-center justify-between gap-4 font-bold text-sm sm:text-base text-white hover:text-cinematic-gold transition">
                            <span class="text-cinematic-red text-lg">Q3.</span> Tôi có thể đổi hoặc hủy vé sau khi đã thanh toán thành công không?
                        </span>
                            <span class="faq-icon text-xl text-gray-400 transition-transform duration-300">+</span>
                        </button>
                        <div class="faq-content hidden px-5 pb-5 text-xs sm:text-sm text-gray-300 border-t border-white/5 pt-3 leading-relaxed">
                            Do đặc thù dịch vụ chiếu phim trực tuyến và hệ thống khóa ghế theo thời gian thực, vé xem phim đã thanh toán thành công hiện không thể đổi hoặc hủy hoàn tiền. Quý khách vui lòng kiểm tra kỹ thông tin suất chiếu, tên phim và số ghế trước khi xác nhận giao dịch.
                        </div>
                    </div>

                    <!-- FAQ 4 -->
                    <div class="faq-item bg-white/5 border border-white/10 rounded-2xl overflow-hidden transition-all duration-300">
                        <button type="button" onclick="toggleFaq(this)" class="w-full p-5 text-left flex items-center justify-between gap-4 font-bold text-sm sm:text-base text-white hover:text-cinematic-gold transition">
                            <span class="text-cinematic-gold text-lg">Q4.</span> Tài khoản ngân hàng đã bị trừ tiền nhưng tôi chưa thấy mã vé thì làm thế nào?
                        </span>
                            <span class="faq-icon text-xl text-gray-400 transition-transform duration-300">+</span>
                        </button>
                        <div class="faq-content hidden px-5 pb-5 text-xs sm:text-sm text-gray-300 border-t border-white/5 pt-3 leading-relaxed">
                            Trong trường hợp mạng ngân hàng bị trễ hoặc sai cú pháp chuyển khoản, bạn chỉ cần liên hệ Hotline CSKH: <strong class="text-cinematic-gold">1900 6017</strong> hoặc nhắn tin qua Zalo/Email đính kèm biên lai chuyển khoản thành công, nhân viên kỹ thuật sẽ kiểm tra giao dịch và kích hoạt vé cho bạn trong vòng 5 - 10 phút.
                        </div>
                    </div>

                    <!-- FAQ 5 -->
                    <div class="faq-item bg-white/5 border border-white/10 rounded-2xl overflow-hidden transition-all duration-300">
                        <button type="button" onclick="toggleFaq(this)" class="w-full p-5 text-left flex items-center justify-between gap-4 font-bold text-sm sm:text-base text-white hover:text-cinematic-gold transition">
                            <span class="text-cinematic-red text-lg">Q5.</span> Trẻ em đi cùng có cần phải mua vé xem phim riêng không?
                        </span>
                            <span class="faq-icon text-xl text-gray-400 transition-transform duration-300">+</span>
                        </button>
                        <div class="faq-content hidden px-5 pb-5 text-xs sm:text-sm text-gray-300 border-t border-white/5 pt-3 leading-relaxed">
                            Trẻ em có chiều cao dưới 0.7m được miễn phí vé khi ngồi chung ghế với người lớn (áp dụng cho phim phân loại P hoặc K). Trẻ em từ 0.7m trở lên cần mua vé riêng theo quy định giá vé ưu đãi dành cho trẻ em/học sinh tại quầy vé.
                        </div>
                    </div>

                    <!-- FAQ 6 -->
                    <div class="faq-item bg-white/5 border border-white/10 rounded-2xl overflow-hidden transition-all duration-300">
                        <button type="button" onclick="toggleFaq(this)" class="w-full p-5 text-left flex items-center justify-between gap-4 font-bold text-sm sm:text-base text-white hover:text-cinematic-gold transition">
                            <span class="text-cinematic-gold text-lg">Q6.</span> Ghế đôi Sweetbox có gì khác biệt so với ghế đơn thông thường?
                        </span>
                            <span class="faq-icon text-xl text-gray-400 transition-transform duration-300">+</span>
                        </button>
                        <div class="faq-content hidden px-5 pb-5 text-xs sm:text-sm text-gray-300 border-t border-white/5 pt-3 leading-relaxed">
                            Ghế Sweetbox được bố trí ở hàng ghế cuối cùng của phòng chiếu với vách ngăn cao hai bên tạo không gian hoàn toàn riêng tư cho các cặp đôi. Ghế liền không vách ngăn giữa, đệm da êm ái và không gian rộng rãi để quý khách tận hưởng trọn vẹn từng khoảnh khắc điện ảnh lãng mạn.
                        </div>
                    </div>

                </div>

                <!-- Still have questions? -->
                <div class="page-cta-banner p-6 rounded-2xl border flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div>
                        <h4 class="font-bold text-base">Vẫn chưa tìm thấy câu trả lời bạn cần?</h4>
                        <p class="text-xs mt-1">Đội ngũ hỗ trợ của chúng tôi luôn túc trực 24/7 để lắng nghe bạn.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="mailto:hoidap@hctv.vn" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg text-xs font-semibold transition border border-white/20">
                            Gửi Email
                        </a>
                        <a href="tel:19006017" class="px-4 py-2 bg-cinematic-gold hover:bg-yellow-500 text-black rounded-lg text-xs font-bold transition shadow">
                            Gọi 1900 6017
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<script>
function toggleFaq(btn) {
    const item = btn.closest('.faq-item');
    const content = item.querySelector('.faq-content');
    const icon = item.querySelector('.faq-icon');
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.textContent = '−';
        icon.classList.add('rotate-180', 'text-cinematic-gold');
        item.classList.add('border-cinematic-gold/40', 'bg-white/10');
    } else {
        content.classList.add('hidden');
        icon.textContent = '+';
        icon.classList.remove('rotate-180', 'text-cinematic-gold');
        item.classList.remove('border-cinematic-gold/40', 'bg-white/10');
    }
}
</script>
@endsection
