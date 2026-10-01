<div class="space-y-6">
    <!-- Group 1: HCTV Việt Nam -->
    <div class="bg-white/5 border border-white/10 rounded-2xl p-5 shadow-xl backdrop-blur-sm">
        <h3 class="font-bold text-white mb-3 text-sm uppercase tracking-wider flex items-center gap-2 pb-3 border-b border-white/10">
            <span class="w-1.5 h-4 bg-cinematic-red rounded-full"></span> HCTV Việt Nam
        </h3>
        <nav class="space-y-1">
            <a href="{{ route('pages.about') }}" 
               class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs md:text-sm font-medium transition-all {{ request()->routeIs('pages.about') ? 'bg-gradient-to-r from-cinematic-red/20 to-transparent text-cinematic-gold border-l-4 border-cinematic-gold font-bold shadow-sm' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
                <span>🎬</span> Giới Thiệu
            </a>
            <a href="{{ route('pages.online_services') }}" 
               class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs md:text-sm font-medium transition-all {{ request()->routeIs('pages.online_services') ? 'bg-gradient-to-r from-cinematic-red/20 to-transparent text-cinematic-gold border-l-4 border-cinematic-gold font-bold shadow-sm' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
                <span>⚡</span> Tiện Ích Online
            </a>
            <a href="{{ route('pages.gift_cards') }}" 
               class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs md:text-sm font-medium transition-all {{ request()->routeIs('pages.gift_cards') ? 'bg-gradient-to-r from-cinematic-red/20 to-transparent text-cinematic-gold border-l-4 border-cinematic-gold font-bold shadow-sm' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
                <span>🎁</span> Thẻ Quà Tặng
            </a>
            <a href="{{ route('pages.careers') }}" 
               class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs md:text-sm font-medium transition-all {{ request()->routeIs('pages.careers') ? 'bg-gradient-to-r from-cinematic-red/20 to-transparent text-cinematic-gold border-l-4 border-cinematic-gold font-bold shadow-sm' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
                <span>💼</span> Tuyển Dụng
            </a>
            <a href="{{ route('pages.advertising') }}" 
               class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs md:text-sm font-medium transition-all {{ request()->routeIs('pages.advertising') ? 'bg-gradient-to-r from-cinematic-red/20 to-transparent text-cinematic-gold border-l-4 border-cinematic-gold font-bold shadow-sm' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
                <span>📢</span> Liên Hệ Quảng Cáo HCTV
            </a>
            <a href="{{ route('pages.partners') }}" 
               class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs md:text-sm font-medium transition-all {{ request()->routeIs('pages.partners') ? 'bg-gradient-to-r from-cinematic-red/20 to-transparent text-cinematic-gold border-l-4 border-cinematic-gold font-bold shadow-sm' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
                <span>🤝</span> Dành Cho Đối Tác
            </a>
        </nav>
    </div>

    <!-- Group 2: Điều khoản & Quy định -->
    <div class="bg-white/5 border border-white/10 rounded-2xl p-5 shadow-xl backdrop-blur-sm">
        <h3 class="font-bold text-white mb-3 text-sm uppercase tracking-wider flex items-center gap-2 pb-3 border-b border-white/10">
            <span class="w-1.5 h-4 bg-cinematic-gold rounded-full"></span> Điều Khoản Sử Dụng
        </h3>
        <nav class="space-y-1">
            <a href="{{ route('pages.terms') }}" 
               class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs md:text-sm font-medium transition-all {{ request()->routeIs('pages.terms') ? 'bg-gradient-to-r from-cinematic-gold/20 to-transparent text-cinematic-gold border-l-4 border-cinematic-gold font-bold shadow-sm' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
                <span>📜</span> Điều Khoản Chung
            </a>
            <a href="{{ route('pages.terms_transaction') }}" 
               class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs md:text-sm font-medium transition-all {{ request()->routeIs('pages.terms_transaction') ? 'bg-gradient-to-r from-cinematic-gold/20 to-transparent text-cinematic-gold border-l-4 border-cinematic-gold font-bold shadow-sm' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
                <span>💳</span> Điều Khoản Giao Dịch
            </a>
            <a href="{{ route('pages.payment_policy') }}" 
               class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs md:text-sm font-medium transition-all {{ request()->routeIs('pages.payment_policy') ? 'bg-gradient-to-r from-cinematic-gold/20 to-transparent text-cinematic-gold border-l-4 border-cinematic-gold font-bold shadow-sm' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
                <span>🛡️</span> Chính Sách Thanh Toán
            </a>
            <a href="{{ route('pages.privacy_policy') }}" 
               class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs md:text-sm font-medium transition-all {{ request()->routeIs('pages.privacy_policy') ? 'bg-gradient-to-r from-cinematic-gold/20 to-transparent text-cinematic-gold border-l-4 border-cinematic-gold font-bold shadow-sm' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
                <span>🔒</span> Chính Sách Bảo Mật
            </a>
            <a href="{{ route('pages.cinema_rules') }}" 
               class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs md:text-sm font-medium transition-all {{ request()->routeIs('pages.cinema_rules') ? 'bg-gradient-to-r from-cinematic-gold/20 to-transparent text-cinematic-gold border-l-4 border-cinematic-gold font-bold shadow-sm' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
                <span>🍿</span> Quy Định Tại Rạp Phim
            </a>
            <a href="{{ route('pages.faq') }}" 
               class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs md:text-sm font-medium transition-all {{ request()->routeIs('pages.faq') ? 'bg-gradient-to-r from-cinematic-gold/20 to-transparent text-cinematic-gold border-l-4 border-cinematic-gold font-bold shadow-sm' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
                <span>❓</span> Câu Hỏi Thường Gặp
            </a>
        </nav>
    </div>

    <!-- Quick Contact Card -->
    <div class="sidebar-support-card bg-gradient-to-br from-red-950/40 to-black/60 border border-red-500/20 rounded-2xl p-5 shadow-lg text-xs space-y-2.5">
        <h4 class="font-bold text-white flex items-center gap-2 text-sm">
            <span>📞</span> Hỗ Trợ Trực Tuyến
        </h4>
        <p class="text-gray-300">Tổng đài CSKH giải đáp mọi thắc mắc 24/7:</p>
        <p class="text-lg font-bold text-cinematic-gold font-mono">1900 6017</p>
        <p class="text-gray-400">Email: <a href="mailto:hoidap@hctv.vn" class="text-red-400 hover:underline">hoidap@hctv.vn</a></p>
    </div>
</div>
