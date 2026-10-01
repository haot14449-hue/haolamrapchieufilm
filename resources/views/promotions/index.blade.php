@extends('layouts.app')

@section('title', 'Khuyến Mãi & Thành Viên - HCTV')

@section('content')
<div class="pt-24 pb-12 bg-cinematic-dark min-h-screen">
    <!-- Header -->
    <div class="relative py-16 mb-12 flex justify-center items-center overflow-hidden border-b border-white/10">
        <div class="absolute inset-0 bg-cinematic-gold/20 blur-3xl opacity-30"></div>
        <h1 class="text-4xl md:text-5xl font-serif font-bold text-white relative z-10 tracking-widest uppercase flex items-center gap-4">
            <span class="w-12 h-1 bg-cinematic-gold inline-block"></span>
            Ưu Đãi Đặc Quyền
            <span class="w-12 h-1 bg-cinematic-gold inline-block"></span>
        </h1>
    </div>

    <div class="max-w-7xl mx-auto px-6 md:px-12">
        
        <!-- Membership Card (if logged in) -->
        @auth
        <div class="membership-card bg-gradient-to-br from-gray-900 to-black border border-cinematic-gold/30 rounded-2xl p-8 mb-16 shadow-[0_0_30px_rgba(212,175,55,0.15)] relative overflow-hidden">
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-cinematic-gold/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="flex flex-col md:flex-row justify-between items-center gap-8 relative z-10">
                <div class="flex items-center gap-6">
                    <div class="w-20 h-20 bg-cinematic-gold rounded-full flex items-center justify-center text-3xl font-bold text-black shadow-lg">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-gray-400 uppercase tracking-widest text-xs mb-1">Thành viên HCTV</p>
                        <h2 class="text-3xl font-bold text-white">{{ auth()->user()->name }}</h2>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row items-center gap-4 text-center md:text-right border-l md:border-l-0 md:border-l-2 border-white/10 pl-0 md:pl-8">
                    <div>
                        <p class="text-gray-400 mb-1 text-xs">Điểm tích luỹ hiện tại</p>
                        <p class="text-3xl font-bold text-cinematic-gold">{{ number_format(auth()->user()->points) }} <span class="text-sm text-white font-normal">pts</span></p>
                    </div>
                    <a href="{{ route('account.vouchers') }}" class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-cinematic-red to-red-700 hover:from-red-600 hover:to-red-800 text-white font-bold text-xs uppercase tracking-wider transition shadow flex items-center gap-2">
                        <span>🎟️ Mở Ví Voucher</span>
                        <span class="px-1.5 py-0.5 rounded-full bg-white/20 text-white text-[10px]">{{ auth()->user()->activeVouchersCount() }}</span>
                    </a>
                </div>
            </div>
        </div>
        @else
        <div class="bg-white/5 border border-white/10 rounded-2xl p-8 text-center mb-16">
            <h2 class="text-2xl font-bold text-white mb-4">Đăng ký thành viên HCTV</h2>
            <p class="text-gray-400 mb-6">Tích điểm đổi quà, nhận ưu đãi sinh nhật, lưu voucher vào ví và vô vàn đặc quyền khác.</p>
            <a href="{{ route('register') }}" class="px-8 py-3 bg-cinematic-gold text-black font-bold uppercase rounded hover:bg-yellow-500 transition-colors shadow-[0_0_15px_rgba(212,175,55,0.4)]">
                Tham Gia Ngay
            </a>
        </div>
        @endauth

        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl md:text-3xl font-serif font-bold text-white border-l-4 border-cinematic-red pl-4">Chương Trình Khuyến Mãi</h2>
            @auth
            <a href="{{ route('account.vouchers') }}" class="text-xs text-cinematic-gold hover:underline font-semibold flex items-center gap-1">
                <span>Xem ví voucher đã lưu ({{ auth()->user()->activeVouchersCount() }})</span>
                <span>→</span>
            </a>
            @endauth
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 text-sm flex items-center gap-3 shadow">
                <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($promotions as $promo)
            @php
                $isSaved = auth()->check() && $promo->isSavedByUser(auth()->id());
            @endphp
            <div class="voucher-promo-card bg-white/5 border border-white/10 rounded-2xl overflow-hidden group hover:border-white/30 transition-all duration-300 shadow-lg hover:shadow-2xl flex flex-col justify-between">
                <div>
                    <div class="aspect-video relative overflow-hidden bg-gray-900">
                        <img src="{{ $promo->image_url }}" alt="{{ $promo->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                        <div class="absolute top-3 left-3">
                            <span class="px-2.5 py-1 rounded-lg bg-cinematic-red text-white text-xs font-black shadow-lg">
                                {{ $promo->formattedDiscount() }}
                            </span>
                        </div>
                        <div class="absolute bottom-3 right-3">
                            <span class="voucher-card-badge">
                                <svg class="w-3.5 h-3.5 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                <span class="voucher-code-text">{{ $promo->code }}</span>
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-white mb-2 line-clamp-2 group-hover:text-cinematic-gold transition-colors">{{ $promo->title }}</h3>
                        <p class="text-gray-400 text-sm mb-4 line-clamp-2 leading-relaxed">{{ $promo->description }}</p>
                        
                        <!-- Dedicated Voucher Code Strip with 1-Click Copy -->
                        <div class="voucher-code-box mb-4">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="voucher-code-label">MÃ ƯU ĐÃI:</span>
                                <span class="voucher-code-pill">{{ $promo->code }}</span>
                            </div>
                            <button type="button" 
                                    onclick="copyPromotionCode('{{ $promo->code }}', this)" 
                                    class="voucher-copy-btn"
                                    title="Sao chép mã voucher">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span>Sao chép</span>
                            </button>
                        </div>

                        <div class="flex justify-between items-center pt-3 border-t border-white/10 text-xs text-gray-400">
                            <div>
                                <span class="block text-[10px] text-gray-500 uppercase">Hạn dùng</span>
                                <span class="font-mono font-bold text-white">{{ $promo->end_date ? \Carbon\Carbon::parse($promo->end_date)->format('d/m/Y') : 'Dài hạn' }}</span>
                            </div>
                            @if($promo->points_required > 0)
                                <span class="text-cinematic-gold font-semibold">💎 {{ $promo->points_required }} điểm</span>
                            @else
                                <span class="text-green-400 font-semibold">Miễn phí lưu</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="p-6 pt-0">
                    @auth
                        @if($isSaved)
                            <a href="{{ route('account.vouchers') }}" class="w-full py-2.5 px-4 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded-xl text-center text-xs font-bold flex items-center justify-center gap-1.5 shadow hover:bg-emerald-500/30 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Đã có trong Ví voucher (Mở xem)</span>
                            </a>
                        @else
                            <form action="{{ route('account.vouchers.save') }}" method="POST" class="m-0">
                                @csrf
                                <input type="hidden" name="promotion_id" value="{{ $promo->id }}">
                                <button type="submit" class="w-full py-2.5 px-4 bg-gradient-to-r from-cinematic-red to-red-700 hover:from-red-600 hover:to-red-800 text-white font-bold rounded-xl text-xs uppercase tracking-wider shadow transition-all flex items-center justify-center gap-2 cursor-pointer hover:scale-[1.01]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    <span>Lưu vào Ví voucher</span>
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="w-full py-2.5 px-4 bg-white/10 hover:bg-white/20 text-white font-bold rounded-xl text-xs uppercase tracking-wider shadow transition-all flex items-center justify-center gap-2">
                            <span>Đăng nhập để lưu vào ví</span>
                        </a>
                    @endauth
                </div>
            </div>
            @empty
            <div class="col-span-full py-16 text-center bg-white/5 border border-white/10 rounded-2xl p-8 shadow-xl">
                <div class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center text-3xl mx-auto mb-4">🎟️</div>
                <h3 class="text-xl font-bold text-white mb-2">Hiện chưa có khuyến mãi nào đang diễn ra</h3>
                <p class="text-gray-400 text-sm max-w-md mx-auto">Các chương trình ưu đãi hiện tại đã hết hạn hoặc đang được cập nhật. Bạn vui lòng quay lại sau để nhận thêm các ưu đãi hấp dẫn nhé!</p>
            </div>
            @endforelse
        </div>
        
    </div>
</div>

<!-- Toast Feedback Notification -->
<div id="voucherToast" class="fixed bottom-6 right-6 z-50 transform translate-y-20 opacity-0 transition-all duration-300 pointer-events-none">
    <div class="bg-gray-900 text-white border border-amber-400/50 px-5 py-3 rounded-xl shadow-2xl flex items-center gap-3">
        <span class="text-lg">✨</span>
        <span id="voucherToastMessage" class="text-sm font-medium"></span>
    </div>
</div>

<script>
function copyPromotionCode(code, btn) {
    if (!navigator.clipboard) {
        const el = document.createElement('textarea');
        el.value = code;
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
    } else {
        navigator.clipboard.writeText(code);
    }

    if (btn) {
        const origHtml = btn.innerHTML;
        btn.classList.add('copied');
        btn.innerHTML = `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span>Đã chép!</span>`;
        setTimeout(() => {
            btn.classList.remove('copied');
            btn.innerHTML = origHtml;
        }, 2000);
    }

    showVoucherToast(`Đã sao chép mã "${code}" vào khay nhớ tạm!`);
}

function showVoucherToast(msg) {
    const toast = document.getElementById('voucherToast');
    const text = document.getElementById('voucherToastMessage');
    if (!toast || !text) return;
    text.textContent = msg;
    toast.classList.remove('translate-y-20', 'opacity-0');
    toast.classList.add('translate-y-0', 'opacity-100');
    setTimeout(() => {
        toast.classList.remove('translate-y-0', 'opacity-100');
        toast.classList.add('translate-y-20', 'opacity-0');
    }, 2600);
}
</script>
@endsection
