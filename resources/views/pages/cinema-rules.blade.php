@extends('layouts.app')

@section('title', 'Quy Định Tại Rạp Phim - HCTV Cinema')

@section('content')
<div class="pt-24 pb-16 bg-cinematic-dark min-h-screen text-white">
    <!-- Hero Banner -->
    <div class="page-hero-banner relative py-16 mb-10 overflow-hidden border-b">
        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10 text-center">
            <span class="page-hero-badge inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold mb-3">
                🍿 Văn Hóa Thưởng Thức Điện Ảnh
            </span>
            <h1 class="page-hero-title text-3xl sm:text-4xl md:text-5xl font-serif font-bold tracking-wider uppercase mb-3">
                Những Quy Định Tại Cụm Rạp HCTV
            </h1>
            <p class="page-hero-desc text-sm sm:text-base max-w-2xl mx-auto">
                Kính mong quý khán giả cùng phối hợp thực hiện để cùng nhau xây dựng không gian xem phim văn minh, lịch sự và thoải mái nhất.
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
                
                <!-- Age Classification -->
                <div class="bg-white/5 border border-white/10 rounded-2xl p-6 sm:p-8 shadow-xl backdrop-blur-sm space-y-4">
                    <h2 class="text-lg sm:text-xl font-serif font-bold text-white pb-3 border-b border-white/10 flex items-center gap-2">
                        <span>🔞</span> Quy Định Độ Tuổi Phân Loại Phim (Bộ VHTT&DL)
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-300">
                        Theo thông tư của Bộ Văn hóa, Thể thao và Du lịch, nhân viên rạp có quyền yêu cầu xuất trình giấy tờ tùy thân (CCCD, Thẻ học sinh/sinh viên) đối với các phim có giới hạn độ tuổi:
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 pt-2">
                        <div class="p-3.5 bg-black/40 border border-green-500/30 rounded-xl">
                            <span class="inline-block px-2.5 py-0.5 bg-green-500 text-white font-black text-xs rounded mb-1">P</span>
                            <h4 class="text-xs font-bold text-white">Mọi lứa tuổi</h4>
                            <p class="text-[11px] text-gray-400 mt-1">Phim được phép phổ biến rộng rãi đến khán giả ở mọi độ tuổi.</p>
                        </div>

                        <div class="p-3.5 bg-black/40 border border-blue-500/30 rounded-xl">
                            <span class="inline-block px-2.5 py-0.5 bg-blue-500 text-white font-black text-xs rounded mb-1">K</span>
                            <h4 class="text-xs font-bold text-white">Dưới 13 tuổi có giám hộ</h4>
                            <p class="text-[11px] text-gray-400 mt-1">Khán giả dưới 13 tuổi cần đi cùng cha mẹ hoặc người giám hộ.</p>
                        </div>

                        <div class="p-3.5 bg-black/40 border border-yellow-500/30 rounded-xl">
                            <span class="inline-block px-2.5 py-0.5 bg-yellow-500 text-black font-black text-xs rounded mb-1">T13</span>
                            <h4 class="text-xs font-bold text-white">Từ đủ 13 tuổi trở lên</h4>
                            <p class="text-[11px] text-gray-400 mt-1">Cấm khán giả dưới 13 tuổi vào xem (C13).</p>
                        </div>

                        <div class="p-3.5 bg-black/40 border border-orange-500/30 rounded-xl">
                            <span class="inline-block px-2.5 py-0.5 bg-orange-500 text-white font-black text-xs rounded mb-1">T16</span>
                            <h4 class="text-xs font-bold text-white">Từ đủ 16 tuổi trở lên</h4>
                            <p class="text-[11px] text-gray-400 mt-1">Cấm khán giả dưới 16 tuổi vào xem (C16).</p>
                        </div>

                        <div class="p-3.5 bg-black/40 border border-red-500/30 rounded-xl">
                            <span class="inline-block px-2.5 py-0.5 bg-red-600 text-white font-black text-xs rounded mb-1">T18</span>
                            <h4 class="text-xs font-bold text-white">Từ đủ 18 tuổi trở lên</h4>
                            <p class="text-[11px] text-gray-400 mt-1">Cấm khán giả dưới 18 tuổi vào xem (C18).</p>
                        </div>

                        <div class="p-3.5 bg-black/40 border border-purple-500/30 rounded-xl">
                            <span class="inline-block px-2.5 py-0.5 bg-purple-600 text-white font-black text-xs rounded mb-1">C</span>
                            <h4 class="text-xs font-bold text-white">Cấm phổ biến</h4>
                            <p class="text-[11px] text-gray-400 mt-1">Phim không được phép phổ biến tại các rạp chiếu phim.</p>
                        </div>
                    </div>
                </div>

                <!-- Cinema Etiquette Rules -->
                <div class="bg-white/5 border border-white/10 rounded-2xl p-6 sm:p-8 shadow-xl backdrop-blur-sm space-y-4">
                    <h2 class="text-lg sm:text-xl font-serif font-bold text-white pb-3 border-b border-white/10 flex items-center gap-2">
                        <span>📋</span> Nội Quy Trong Khán Phòng Chiếu
                    </h2>

                    <div class="space-y-3 text-xs sm:text-sm text-gray-300">
                        <div class="p-3.5 bg-red-950/20 border border-red-500/30 rounded-xl flex items-start gap-3">
                            <span class="text-xl">🚫</span>
                            <div>
                                <strong class="text-white">Tuyệt đối không quay phim, chụp ảnh, livestream:</strong>
                                <p class="text-xs text-gray-400 mt-0.5">Mọi hành vi sao chép nội dung phim đều vi phạm nghiêm trọng Luật Sở hữu trí tuệ và có thể bị xử lý hình sự.</p>
                            </div>
                        </div>

                        <div class="p-3.5 bg-black/40 border border-white/10 rounded-xl flex items-start gap-3">
                            <span class="text-xl">📴</span>
                            <div>
                                <strong class="text-white">Tắt chuông điện thoại di động:</strong>
                                <p class="text-xs text-gray-400 mt-0.5">Vui lòng chuyển điện thoại sang chế độ Im lặng hoặc Rung, hạ độ sáng màn hình để tránh làm phiền khán giả xung quanh.</p>
                            </div>
                        </div>

                        <div class="p-3.5 bg-black/40 border border-white/10 rounded-xl flex items-start gap-3">
                            <span class="text-xl">🍔</span>
                            <div>
                                <strong class="text-white">Không mang đồ ăn có mùi từ ngoài vào rạp:</strong>
                                <p class="text-xs text-gray-400 mt-0.5">Để đảm bảo không khí phòng chiếu trong lành, xin quý khách không mang thức ăn có mùi nồng (sầu riêng, mít, bún mắm,...) vào rạp.</p>
                            </div>
                        </div>

                        <div class="p-3.5 bg-black/40 border border-white/10 rounded-xl flex items-start gap-3">
                            <span class="text-xl">🚭</span>
                            <div>
                                <strong class="text-white">Nghiêm cấm hút thuốc lá và thuốc lá điện tử (Vape):</strong>
                                <p class="text-xs text-gray-400 mt-0.5">Toàn bộ khuôn viên rạp phim HCTV là khu vực cấm hút thuốc vì an toàn phòng cháy chữa cháy và sức khỏe cộng đồng.</p>
                            </div>
                        </div>

                        <div class="p-3.5 bg-black/40 border border-white/10 rounded-xl flex items-start gap-3">
                            <span class="text-xl">💺</span>
                            <div>
                                <strong class="text-white">Ngồi đúng số ghế in trên vé:</strong>
                                <p class="text-xs text-gray-400 mt-0.5">Vui lòng ngồi đúng hàng và số ghế của bạn để tránh gây nhầm lẫn và bất tiện cho khán giả khác.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection
