@extends('layouts.app')

@section('title', 'Ví Voucher & Ưu Đãi - HCTV')

@section('content')
<div class="pt-24 pb-16 bg-cinematic-dark min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row gap-8">
        
        <!-- Sidebar -->
        <div class="w-full md:w-64 shrink-0">
            <div class="account-sidebar-card bg-white/5 border border-white/10 rounded-2xl p-6 sticky top-28 shadow-xl">
                <!-- User Profile Summary in Sidebar -->
                <div class="flex items-center gap-4 mb-8">
                    <div class="relative group shrink-0">
                        @if(!empty(auth()->user()->avatar_url))
                            <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-14 h-14 rounded-full object-cover border-2 border-cinematic-red shadow-[0_0_15px_rgba(229,9,20,0.5)]">
                        @else
                            <div class="w-14 h-14 bg-gradient-to-tr from-cinematic-red to-red-800 rounded-full flex items-center justify-center text-xl font-bold text-white shadow-[0_0_15px_rgba(229,9,20,0.5)] border border-white/20">
                                {{ mb_substr(auth()->user()->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <p class="account-user-name font-bold text-white truncate text-base">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-cinematic-gold font-semibold flex items-center gap-1 mt-0.5">
                            <span>💎</span> {{ number_format(auth()->user()->points) }} Điểm
                        </p>
                        <span class="inline-block mt-1 px-2 py-0.5 bg-white/10 text-gray-300 text-[10px] rounded uppercase font-semibold">
                            {{ auth()->user()->role === 'admin' ? 'Quản trị viên' : 'Thành viên' }}
                        </span>
                    </div>
                </div>
                
                <!-- Navigation Links -->
                <nav class="space-y-2">
                    <a href="{{ route('account.tickets') }}" class="account-nav-link block px-4 py-2.5 rounded text-gray-300 hover:bg-white/5 hover:text-white transition-colors flex items-center gap-2.5 text-sm">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                        Vé của tôi
                    </a>
                    
                    <!-- Ví Voucher (Active) -->
                    <a href="{{ route('account.vouchers') }}" class="account-nav-link-active block px-4 py-2.5 rounded bg-white/10 text-white font-medium border-l-4 border-cinematic-red flex items-center justify-between text-sm shadow-sm">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-cinematic-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            <span>Ví voucher</span>
                        </div>
                        <span class="px-2 py-0.5 text-[10px] font-bold bg-cinematic-gold/20 text-cinematic-gold border border-cinematic-gold/40 rounded-full">
                            {{ $availableVouchers->count() }}
                        </span>
                    </a>

                    <a href="{{ route('profile.edit') }}" class="account-nav-link block px-4 py-2.5 rounded text-gray-300 hover:bg-white/5 hover:text-white transition-colors flex items-center gap-2.5 text-sm">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Hồ sơ cá nhân
                    </a>
                    <a href="{{ route('profile.edit') }}#passwordSection" class="account-nav-link block px-4 py-2.5 rounded text-gray-300 hover:bg-white/5 hover:text-white transition-colors flex items-center gap-2.5 text-sm">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Đổi mật khẩu
                    </a>
                    <a href="{{ route('profile.edit') }}#pointsSection" class="account-nav-link block px-4 py-2.5 rounded text-gray-300 hover:bg-white/5 hover:text-white transition-colors flex items-center gap-2.5 text-sm">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Điểm tích lũy
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}" class="pt-6 border-t border-white/10 mt-6">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2.5 rounded text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-colors flex items-center gap-2.5 text-sm cursor-pointer">
                            <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Đăng xuất
                        </button>
                    </form>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 min-w-0">
            
            <!-- Page Header -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="voucher-page-title text-2xl sm:text-3xl font-serif font-bold text-white flex items-center gap-3">
                            <span class="w-2.5 h-7 bg-cinematic-red rounded-full inline-block"></span>
                            Ví Voucher Của Tôi
                        </h1>
                        <p class="voucher-page-subtitle text-xs sm:text-sm text-gray-400 mt-1.5">
                            Lưu và sử dụng các mã giảm giá, khuyến mãi độc quyền từ hệ thống rạp HCTV Cinema
                        </p>
                    </div>

                    <a href="{{ route('promotions.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/10 hover:bg-white/15 text-xs font-semibold text-white border border-white/15 transition-all shadow shrink-0 self-start sm:self-auto">
                        <span>🎁</span> Khám phá ưu đãi rạp
                    </a>
                </div>
            </div>

            <!-- Flash Notifications -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 text-sm flex items-center gap-3 animate-fade-in shadow">
                    <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-500/15 border border-red-500/30 text-red-300 text-sm flex items-center gap-3 animate-fade-in shadow">
                    <svg class="w-5 h-5 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            @if(session('info'))
                <div class="mb-6 p-4 rounded-xl bg-blue-500/15 border border-blue-500/30 text-blue-300 text-sm flex items-center gap-3 animate-fade-in shadow">
                    <svg class="w-5 h-5 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            <!-- Quick Add Voucher Bar -->
            <div class="voucher-input-card bg-gradient-to-r from-gray-900 via-[#18181b] to-black border border-white/10 rounded-2xl p-4 sm:p-5 mb-8 shadow-xl">
                <form action="{{ route('account.vouchers.save') }}" method="POST" class="flex flex-col sm:flex-row gap-3 items-stretch">
                    @csrf
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        </div>
                        <input type="text" 
                               name="code" 
                               required
                               placeholder="Nhập mã voucher (Ví dụ: WELCOME2026, COMBOBAP50...)" 
                               class="voucher-code-input w-full pl-10 pr-4 py-3 bg-white/5 border border-white/15 focus:border-cinematic-gold focus:ring-1 focus:ring-cinematic-gold rounded-xl text-white placeholder-gray-500 text-sm uppercase tracking-wider font-mono transition-all outline-none">
                    </div>
                    <button type="submit" 
                            class="px-6 py-3 bg-gradient-to-r from-cinematic-red to-red-700 hover:from-red-600 hover:to-red-800 text-white font-bold rounded-xl text-sm uppercase tracking-wider shadow-[0_0_20px_rgba(229,9,20,0.4)] transition-all cursor-pointer flex items-center justify-center gap-2 hover:scale-[1.01] shrink-0">
                        <span>Lưu vào ví</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </button>
                </form>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                <div class="voucher-stat-card p-4 rounded-xl bg-white/5 border border-white/10 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-500/30">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <span class="voucher-stat-number text-2xl font-bold text-white block">{{ $availableVouchers->count() }}</span>
                        <span class="voucher-stat-label text-xs text-gray-400">Voucher khả dụng</span>
                    </div>
                </div>

                <div class="voucher-stat-card p-4 rounded-xl bg-white/5 border border-white/10 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center shrink-0 border border-blue-500/30">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <div>
                        <span class="voucher-stat-number text-2xl font-bold text-white block">{{ $allPromotions->count() }}</span>
                        <span class="voucher-stat-label text-xs text-gray-400">Kho ưu đãi của rạp</span>
                    </div>
                </div>

                <div class="voucher-stat-card p-4 rounded-xl bg-white/5 border border-white/10 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center shrink-0 border border-amber-500/30">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <span class="voucher-stat-number text-2xl font-bold text-cinematic-gold block">{{ number_format(auth()->user()->points) }} pts</span>
                        <span class="voucher-stat-label text-xs text-gray-400">Điểm tích luỹ đổi voucher</span>
                    </div>
                </div>
            </div>

            <!-- Tab Switcher Navigation -->
            <div class="voucher-tabs-bar flex items-center gap-2 border-b border-white/10 pb-3 mb-6 overflow-x-auto">
                <button type="button" 
                        onclick="switchVoucherTab('my-vouchers')" 
                        id="tabBtn-my-vouchers"
                        class="voucher-tab-btn active px-4 py-2.5 rounded-xl font-bold text-xs sm:text-sm tracking-wide transition flex items-center gap-2 bg-cinematic-red text-white shadow">
                    <span>🎟️ Voucher của tôi</span>
                    <span class="px-2 py-0.5 text-[11px] rounded-full bg-white/20 text-white font-bold">{{ $availableVouchers->count() }}</span>
                </button>

                <button type="button" 
                        onclick="switchVoucherTab('web-offers')" 
                        id="tabBtn-web-offers"
                        class="voucher-tab-btn px-4 py-2.5 rounded-xl font-bold text-xs sm:text-sm tracking-wide transition flex items-center gap-2 text-gray-400 hover:text-white hover:bg-white/5">
                    <span>🌟 Kho ưu đãi của Web</span>
                    <span class="px-2 py-0.5 text-[11px] rounded-full bg-white/10 text-gray-300 font-bold">{{ $allPromotions->count() }}</span>
                </button>

                <button type="button" 
                        onclick="switchVoucherTab('history-vouchers')" 
                        id="tabBtn-history-vouchers"
                        class="voucher-tab-btn px-4 py-2.5 rounded-xl font-bold text-xs sm:text-sm tracking-wide transition flex items-center gap-2 text-gray-400 hover:text-white hover:bg-white/5">
                    <span>⌛ Đã dùng / Hết hạn</span>
                    <span class="px-2 py-0.5 text-[11px] rounded-full bg-white/10 text-gray-300 font-bold">{{ $usedOrExpiredVouchers->count() }}</span>
                </button>
            </div>

            <!-- ========================================== -->
            <!-- TAB 1: VOUCHER CỦA TÔI (ĐÃ LƯU & KHẢ DỤNG) -->
            <!-- ========================================== -->
            <div id="tabContent-my-vouchers" class="space-y-4">
                @if($availableVouchers->isEmpty())
                    <div class="voucher-empty-card bg-white/5 border border-white/10 rounded-2xl p-12 text-center">
                        <div class="w-20 h-20 mx-auto rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-4xl mb-4 text-gray-500">
                            🎟️
                        </div>
                        <h3 class="voucher-empty-title text-xl font-bold text-white mb-2">Ví voucher của bạn đang trống</h3>
                        <p class="voucher-empty-desc text-sm text-gray-400 max-w-md mx-auto mb-6">
                            Bạn chưa lưu voucher nào. Hãy khám phá ngay các ưu đãi giảm giá vé và bắp nước siêu hời của rạp!
                        </p>
                        <button type="button" onclick="switchVoucherTab('web-offers')" class="px-6 py-2.5 bg-cinematic-red text-white font-bold rounded-xl text-xs uppercase tracking-wider hover:bg-red-700 transition shadow cursor-pointer">
                            Khám phá & Lưu ưu đãi ngay
                        </button>
                    </div>
                @else
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        @foreach($availableVouchers as $uv)
                            @php $p = $uv->promotion; @endphp
                            @if($p)
                            <div class="voucher-ticket-card group relative bg-gradient-to-r from-[#1c1d22] to-[#121316] border border-white/10 hover:border-cinematic-gold/50 rounded-2xl overflow-hidden shadow-lg transition-all duration-300 flex flex-col justify-between">
                                <!-- Top/Bottom Notch Cuts for realistic Cinema Ticket Stub -->
                                <div class="absolute -top-3 left-28 sm:left-32 w-6 h-6 bg-cinematic-dark rounded-full border border-white/10 z-10 pointer-events-none"></div>
                                <div class="absolute -bottom-3 left-28 sm:left-32 w-6 h-6 bg-cinematic-dark rounded-full border border-white/10 z-10 pointer-events-none"></div>

                                <div class="flex items-stretch min-h-[140px]">
                                    <!-- Left Stub (Discount Badge & Brand) -->
                                    <div class="voucher-ticket-stub w-28 sm:w-32 bg-gradient-to-b from-cinematic-red to-red-900 text-white p-3.5 flex flex-col items-center justify-center text-center shrink-0 border-r border-dashed border-white/20 relative">
                                        <span class="text-[9px] uppercase tracking-widest font-black opacity-80 block">HCTV CINEMA</span>
                                        <span class="voucher-stub-discount text-xl sm:text-2xl font-black text-white mt-1 leading-tight drop-shadow">
                                            {{ $p->discount_percent ? "-{$p->discount_percent}%" : "-" . number_format($p->discount_amount / 1000) . "K" }}
                                        </span>
                                        <span class="text-[10px] font-semibold text-red-100 mt-0.5 uppercase tracking-wider">
                                            {{ $p->discount_percent ? 'Giảm vé' : 'Ưu đãi' }}
                                        </span>
                                        <div class="voucher-stub-code mt-2 px-2.5 py-1 rounded-md font-mono font-black text-[11px] tracking-wider shadow-sm flex items-center justify-center">
                                            {{ $p->code }}
                                        </div>
                                    </div>

                                    <!-- Right Content -->
                                    <div class="flex-1 p-4 sm:p-5 flex flex-col justify-between min-w-0">
                                        <div>
                                            <div class="flex items-start justify-between gap-2">
                                                <h3 class="voucher-ticket-title text-sm sm:text-base font-bold text-white line-clamp-1 group-hover:text-cinematic-gold transition-colors">
                                                    {{ $p->title }}
                                                </h3>
                                                <span class="px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 text-[10px] font-bold shrink-0">
                                                    Khả dụng
                                                </span>
                                            </div>
                                            <p class="voucher-ticket-desc text-xs text-gray-400 mt-1 line-clamp-2 leading-relaxed">
                                                {{ $p->description }}
                                            </p>
                                            
                                            <!-- Voucher Code Pill in Ticket Body -->
                                            <div class="voucher-code-box mt-2.5 mb-1">
                                                <div class="flex items-center gap-2 min-w-0">
                                                    <span class="voucher-code-label">MÃ:</span>
                                                    <span class="voucher-code-pill text-xs font-mono font-black">{{ $p->code }}</span>
                                                </div>
                                                <span class="text-[10px] font-medium text-amber-500/80">Sẵn sàng sử dụng</span>
                                            </div>
                                        </div>

                                        <div class="mt-3 pt-2.5 border-t border-white/10 flex flex-wrap items-center justify-between gap-2 text-xs">
                                            <span class="voucher-ticket-date text-[11px] text-gray-400 flex items-center gap-1">
                                                <span>📅 HSD:</span>
                                                <strong class="text-white font-mono">
                                                    {{ $p->end_date ? \Carbon\Carbon::parse($p->end_date)->format('d/m/Y') : 'Vô thời hạn' }}
                                                </strong>
                                            </span>

                                            <div class="flex items-center gap-2">
                                                <button type="button" 
                                                        onclick="copyVoucherCode('{{ $p->code }}')" 
                                                        class="px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-white font-semibold text-[11px] transition flex items-center gap-1 cursor-pointer"
                                                        title="Sao chép mã vào bộ nhớ">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                                    <span>Chép mã</span>
                                                </button>

                                                <a href="{{ route('movies.index') }}" 
                                                   class="px-3 py-1 rounded-lg bg-cinematic-gold hover:bg-yellow-500 text-black font-bold text-[11px] uppercase tracking-wider transition shadow cursor-pointer">
                                                    Dùng ngay
                                                </a>

                                                <form action="{{ route('account.vouchers.remove', $uv->id) }}" method="POST" class="m-0" onsubmit="return confirm('Bạn có chắc muốn bỏ lưu voucher này khỏi ví?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1 text-gray-500 hover:text-red-400 transition" title="Bỏ lưu voucher">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- ========================================== -->
            <!-- TAB 2: KHO ƯU ĐÃI CỦA WEB (KHÁM PHÁ & LƯU) -->
            <!-- ========================================== -->
            <div id="tabContent-web-offers" class="hidden space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($allPromotions as $promo)
                        @php
                            $isSaved = in_array($promo->id, $savedPromotionIds);
                            $isExpired = $promo->isExpired();
                        @endphp
                        <div class="voucher-promo-card bg-white/5 border border-white/10 hover:border-white/20 rounded-2xl overflow-hidden shadow-xl transition-all duration-300 flex flex-col justify-between group">
                            <div>
                                <!-- Image & Discount Badge -->
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

                                <!-- Card Body -->
                                <div class="p-5">
                                    <h3 class="voucher-promo-title text-base font-bold text-white mb-1.5 line-clamp-1 group-hover:text-cinematic-gold transition-colors">
                                        {{ $promo->title }}
                                    </h3>
                                    <p class="voucher-promo-desc text-xs text-gray-400 line-clamp-2 leading-relaxed mb-3">
                                        {{ $promo->description }}
                                    </p>

                                    <!-- Dedicated Voucher Code Strip with 1-Click Copy -->
                                    <div class="voucher-code-box mb-3">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <span class="voucher-code-label">MÃ:</span>
                                            <span class="voucher-code-pill text-xs">{{ $promo->code }}</span>
                                        </div>
                                        <button type="button" 
                                                onclick="copyVoucherCode('{{ $promo->code }}', this)" 
                                                class="voucher-copy-btn"
                                                title="Sao chép mã voucher">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                            <span>Chép</span>
                                        </button>
                                    </div>

                                    <div class="flex items-center justify-between text-[11px] text-gray-400 pt-3 border-t border-white/10">
                                        <span>📅 HSD: <strong class="text-white">{{ $promo->end_date ? \Carbon\Carbon::parse($promo->end_date)->format('d/m/Y') : 'Dài hạn' }}</strong></span>
                                        @if($promo->points_required > 0)
                                            <span class="text-cinematic-gold font-semibold">💎 {{ $promo->points_required }} pts</span>
                                        @else
                                            <span class="text-green-400 font-semibold">Miễn phí lưu</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Footer Action -->
                            <div class="p-5 pt-0">
                                @if($isSaved)
                                    <div class="w-full py-2.5 px-4 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded-xl text-center text-xs font-bold flex items-center justify-center gap-1.5 shadow">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <span>Đã có trong ví của bạn</span>
                                    </div>
                                @elseif($isExpired)
                                    <button disabled class="w-full py-2.5 px-4 bg-white/5 text-gray-500 rounded-xl text-center text-xs font-semibold cursor-not-allowed">
                                        Đã hết hạn
                                    </button>
                                @else
                                    <form action="{{ route('account.vouchers.save') }}" method="POST" class="m-0">
                                        @csrf
                                        <input type="hidden" name="promotion_id" value="{{ $promo->id }}">
                                        <button type="submit" 
                                                class="w-full py-2.5 px-4 bg-gradient-to-r from-cinematic-red to-red-700 hover:from-red-600 hover:to-red-800 text-white font-bold rounded-xl text-xs uppercase tracking-wider shadow transition-all flex items-center justify-center gap-2 cursor-pointer hover:scale-[1.01]">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                            <span>Lưu vào ví voucher</span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- ========================================== -->
            <!-- TAB 3: ĐÃ SỬ DỤNG / HẾT HẠN                 -->
            <!-- ========================================== -->
            <div id="tabContent-history-vouchers" class="hidden space-y-4">
                @if($usedOrExpiredVouchers->isEmpty())
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-12 text-center">
                        <div class="w-16 h-16 mx-auto rounded-full bg-white/5 flex items-center justify-center text-3xl mb-3 text-gray-500">
                            ⌛
                        </div>
                        <p class="text-sm text-gray-400">Bạn chưa có voucher nào đã sử dụng hoặc hết hạn.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        @foreach($usedOrExpiredVouchers as $uv)
                            @php $p = $uv->promotion; @endphp
                            @if($p)
                            <div class="bg-white/5 border border-white/10 rounded-2xl p-4 sm:p-5 opacity-70 hover:opacity-100 transition flex flex-col justify-between">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="voucher-history-code">{{ $p->code }}</span>
                                            <span class="text-xs font-bold text-gray-300">{{ $p->formattedDiscount() }}</span>
                                        </div>
                                        <h4 class="text-sm font-bold text-white mt-1">{{ $p->title }}</h4>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $p->description }}</p>
                                    </div>

                                    @if($uv->is_used)
                                        <span class="px-2.5 py-1 rounded-full bg-gray-500/20 text-gray-300 border border-gray-500/30 text-[10px] font-bold shrink-0">
                                            Đã sử dụng
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full bg-red-500/20 text-red-400 border border-red-500/30 text-[10px] font-bold shrink-0">
                                            Đã hết hạn
                                        </span>
                                    @endif
                                </div>

                                <div class="mt-4 pt-3 border-t border-white/10 flex items-center justify-between text-[11px] text-gray-400">
                                    <span>Lưu ngày: {{ $uv->saved_at ? \Carbon\Carbon::parse($uv->saved_at)->format('d/m/Y') : $uv->created_at->format('d/m/Y') }}</span>
                                    @if($uv->used_at)
                                        <span class="text-gray-400">Dùng ngày: {{ \Carbon\Carbon::parse($uv->used_at)->format('H:i | d/m/Y') }}</span>
                                    @endif
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

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

<!-- JavaScript for Tab Switching and Copying Codes -->
<script>
    function switchVoucherTab(tabId) {
        const tabs = ['my-vouchers', 'web-offers', 'history-vouchers'];
        tabs.forEach(t => {
            const btn = document.getElementById('tabBtn-' + t);
            const content = document.getElementById('tabContent-' + t);
            if (t === tabId) {
                btn.className = "voucher-tab-btn active px-4 py-2.5 rounded-xl font-bold text-xs sm:text-sm tracking-wide transition flex items-center gap-2 bg-cinematic-red text-white shadow";
                content.classList.remove('hidden');
            } else {
                btn.className = "voucher-tab-btn px-4 py-2.5 rounded-xl font-bold text-xs sm:text-sm tracking-wide transition flex items-center gap-2 text-gray-400 hover:text-white hover:bg-white/5";
                content.classList.add('hidden');
            }
        });
    }

    function copyVoucherCode(code, btn) {
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
        }, 2800);
    }
</script>
@endsection
