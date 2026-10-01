<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'HCTV Cinematic')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
        @endif

        <!-- Anti-FOUC Theme Script (Tránh chớp giật giao diện khi tải trang) -->
        <script>
            (function() {
                try {
                    const theme = localStorage.getItem('hctv_theme');
                    if (theme === 'light') {
                        document.documentElement.classList.add('light-mode');
                    } else {
                        document.documentElement.classList.remove('light-mode');
                    }
                } catch (e) {}
            })();
        </script>
    </head>
    <body class="font-sans antialiased bg-cinematic-dark text-white selection:bg-cinematic-red selection:text-white transition-colors duration-300">
        <!-- Navbar -->
        <header class="absolute top-0 left-0 w-full z-50 py-4 px-4 md:px-8 lg:px-12 bg-gradient-to-b from-black/90 via-black/50 to-transparent" style="position: absolute; top: 0; left: 0; width: 100%; z-index: 99999;">
            <div class="max-w-[1680px] mx-auto flex items-center justify-between gap-3 lg:gap-6">
                <!-- Left Group: Logo HCTV + Search Bar -->
                <div class="flex items-center gap-3 lg:gap-4 shrink-0">
                    <!-- Logo -->
                    <a href="{{ route('home') }}" class="text-2xl md:text-3xl font-serif font-bold tracking-wider text-white shrink-0 hover:opacity-90 transition mr-1">
                        HC<span class="text-cinematic-red">TV</span>
                    </a>

                    <!-- Search Bar -->
                    <div class="relative w-52 sm:w-64 md:w-72 lg:w-80 xl:w-96" id="header-search-container">
                        <form action="{{ route('movies.index') }}" method="GET" id="header-search-form" class="relative flex items-center m-0">
                            <input type="hidden" name="genre" id="header-search-genre-hidden" value="{{ request('genre', '') }}">
                            
                            <div class="relative w-full flex items-center bg-[#131722]/90 hover:bg-[#131722] focus-within:bg-[#131722] border border-white/25 hover:border-white/40 focus-within:border-cinematic-gold focus-within:ring-2 focus-within:ring-cinematic-gold/30 rounded-full px-4 py-2.5 transition-all shadow-lg">
                                <!-- Search Icon -->
                                <svg class="w-5 h-5 text-gray-300 shrink-0 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                
                                <input type="text" 
                                       name="search" 
                                       id="header-search-input" 
                                       value="{{ request('search', '') }}"
                                       placeholder="Tìm kiếm phim, diễn viên" 
                                       autocomplete="off"
                                       class="w-full bg-transparent text-white text-sm placeholder-gray-400 focus:outline-none border-none p-0 focus:ring-0">
                                       
                                <!-- Loading Spinner -->
                                <div id="header-search-loading" class="hidden shrink-0 ml-2">
                                    <svg class="animate-spin h-4 w-4 text-cinematic-gold" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                                
                                <!-- Clear Button -->
                                <button type="button" id="header-search-clear" class="{{ request('search') ? '' : 'hidden' }} text-gray-400 hover:text-white p-0.5 ml-1 transition shrink-0" title="Xóa tìm kiếm">
                                    <svg class="w-5 h-5 text-gray-300 hover:text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                </button>
                            </div>
                        </form>

                        <!-- Suggestions Dropdown Floating Box -->
                        <div id="header-search-suggestions" 
                             class="hidden absolute left-0 top-full mt-2 w-full min-w-[340px] md:min-w-[420px] rounded-2xl shadow-[0_30px_70px_rgba(0,0,0,0.95)] overflow-hidden z-50 max-h-[480px] overflow-y-auto"
                             style="position: absolute; top: 100%; left: 0; z-index: 99999; background-color: #131722; border: 1px solid rgba(255, 255, 255, 0.12);">
                        </div>
                    </div>
                </div>

                <!-- Navigation Toolbar (Thanh công cụ luôn hiển thị trên PC & Laptop) -->
                <nav class="hidden md:flex items-center space-x-4 lg:space-x-6 text-xs lg:text-sm font-bold tracking-wider shrink-0">
                    <a href="{{ route('home') }}" class="hover:text-cinematic-gold transition-colors duration-300 {{ request()->routeIs('home') ? 'text-cinematic-gold' : 'text-gray-200' }}">TRANG CHỦ</a>
                    <a href="{{ route('movies.index') }}" class="hover:text-cinematic-gold transition-colors duration-300 {{ request()->routeIs('movies.*') ? 'text-cinematic-gold' : 'text-gray-200' }}">PHIM</a>
                    <a href="{{ route('showtimes') }}" class="hover:text-cinematic-gold transition-colors duration-300 {{ request()->routeIs('showtimes') ? 'text-cinematic-gold' : 'text-gray-200' }}">LỊCH CHIẾU</a>
                    <a href="{{ route('cinemas.index') }}" class="hover:text-cinematic-gold transition-colors duration-300 {{ request()->routeIs('cinemas.*') ? 'text-cinematic-gold' : 'text-gray-200' }}">RẠP PHIM</a>
                    <a href="{{ route('promotions.index') }}" class="hover:text-cinematic-gold transition-colors duration-300 {{ request()->routeIs('promotions.*') ? 'text-cinematic-gold' : 'text-gray-200' }}">KHUYẾN MÃI</a>
                </nav>

                <!-- Auth Buttons & Mobile Menu Toggle -->
                <div class="flex items-center space-x-3 shrink-0">
                    <!-- Theme Toggle Button (Light/Dark Mode) -->
                    <button type="button" 
                            id="theme-toggle" 
                            title="Đổi chế độ sáng / tối" 
                            aria-label="Đổi chế độ sáng / tối" 
                            class="flex items-center justify-center w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 border border-white/20 focus:outline-none focus:ring-2 focus:ring-cinematic-gold transition-all duration-300 shadow-sm cursor-pointer group">
                        <!-- Sun Icon (hiển thị ở dark mode để bấm chuyển sang sáng) -->
                        <svg id="theme-icon-sun" class="w-5 h-5 text-cinematic-gold group-hover:rotate-45 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <!-- Moon Icon (hiển thị ở light mode để bấm chuyển sang tối) -->
                        <svg id="theme-icon-moon" class="hidden w-5 h-5 text-indigo-600 group-hover:-rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>

                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" title="Quản trị" class="flex items-center justify-center w-9 h-9 rounded-full bg-white/10 hover:bg-cinematic-red/80 border border-white/20 focus:outline-none focus:ring-2 focus:ring-cinematic-gold transition-all duration-300 group">
                                <svg class="w-5 h-5 text-gray-200 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            </a>
                        @endif
                        <!-- User Dropdown Menu -->
                        <div class="relative group">
                            <!-- Dropdown Trigger (User Icon or Avatar) -->
                            <button type="button" class="flex items-center justify-center w-9 h-9 rounded-full overflow-hidden bg-white/10 hover:bg-white/20 border border-white/20 focus:outline-none focus:ring-2 focus:ring-cinematic-gold transition-all duration-300">
                                @if(!empty(auth()->user()->avatar_url))
                                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-5 h-5 text-gray-200 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                @endif
                            </button>
                            
                            <!-- Dropdown Content -->
                            <div class="user-dropdown-menu absolute right-0 mt-2 w-60 bg-[#18181b]/95 backdrop-blur-xl border border-white/10 rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.8)] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 transform origin-top-right scale-95 group-hover:scale-100 overflow-hidden before:absolute before:-top-3 before:right-0 before:w-16 before:h-4 before:bg-transparent">
                                <!-- User Info Header -->
                                <div class="user-dropdown-header px-4 py-3 border-b border-white/10 flex items-center gap-3">
                                    @if(!empty(auth()->user()->avatar_url))
                                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-full object-cover shrink-0 border border-cinematic-red shadow">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-cinematic-gold to-yellow-600 flex items-center justify-center shrink-0 shadow-inner">
                                            <span class="text-white font-bold text-sm">{{ mb_substr(auth()->user()->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="user-dropdown-name text-sm font-bold text-white truncate">{{ auth()->user()->name }}</p>
                                        <p class="user-dropdown-points text-xs text-cinematic-gold font-semibold flex items-center gap-1">💎 {{ number_format(auth()->user()->points) }} Điểm</p>
                                    </div>
                                </div>
                                
                                <!-- Menu Links -->
                                <div class="py-2">
                                    <a href="{{ route('profile.edit') }}" class="user-dropdown-item flex items-center gap-3 px-4 py-2.5 text-sm text-gray-300 hover:text-white hover:bg-white/10 transition-colors group/item">
                                        <svg class="w-4 h-4 text-gray-400 group-hover/item:text-cinematic-gold transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <span>Hồ sơ cá nhân</span>
                                    </a>
                                    <a href="{{ route('account.tickets') }}" class="user-dropdown-item flex items-center gap-3 px-4 py-2.5 text-sm text-gray-300 hover:text-white hover:bg-white/10 transition-colors group/item">
                                        <svg class="w-4 h-4 text-gray-400 group-hover/item:text-cinematic-gold transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                                        <span>Lịch sử đặt vé</span>
                                    </a>
                                    <a href="{{ route('account.vouchers') }}" class="user-dropdown-item flex items-center justify-between px-4 py-2.5 text-sm text-gray-300 hover:text-white hover:bg-white/10 transition-colors group/item">
                                        <div class="flex items-center gap-3">
                                            <svg class="w-4 h-4 text-gray-400 group-hover/item:text-cinematic-gold transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                            </svg>
                                            <span>Ví voucher</span>
                                        </div>
                                        <span class="user-dropdown-voucher-badge px-2 py-0.5 text-[10px] font-bold bg-cinematic-gold/20 text-cinematic-gold border border-cinematic-gold/40 rounded-full">
                                            {{ auth()->user()->activeVouchersCount() > 0 ? auth()->user()->activeVouchersCount() : 'Ưu đãi' }}
                                        </span>
                                    </a>
                                    <a href="{{ route('profile.edit') }}#pointsSection" class="user-dropdown-item flex items-center gap-3 px-4 py-2.5 text-sm text-gray-300 hover:text-white hover:bg-white/10 transition-colors group/item">
                                        <svg class="w-4 h-4 text-gray-400 group-hover/item:text-cinematic-gold transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <span>Điểm thưởng & Ưu đãi</span>
                                    </a>
                                </div>
                                
                                <!-- Logout Action -->
                                <div class="user-dropdown-footer border-t border-white/10 py-1.5 bg-black/20">
                                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                                        @csrf
                                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center gap-3 px-4 py-2 text-sm text-red-400 hover:text-red-300 hover:bg-red-500/10 transition-colors group/item w-full text-left">
                                             <svg class="w-4 h-4 text-red-500/80 group-hover/item:text-red-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                            <span>Đăng xuất</span>
                                        </a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 bg-cinematic-red text-white text-xs md:text-sm font-bold rounded-lg hover:bg-red-700 transition-colors shadow-sm">Đăng nhập</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-cinematic-red text-white text-xs md:text-sm font-bold rounded-lg hover:bg-red-700 transition-colors shadow-sm">Đăng ký</a>
                    @endauth

                    <!-- Mobile Menu Hamburger Button -->
                    <button type="button" id="mobile-menu-toggle" class="md:hidden p-2 text-gray-300 hover:text-white focus:outline-none ml-1" title="Menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation Drawer (Hiện thanh công cụ khi bấm menu trên điện thoại) -->
            <div id="mobile-nav-menu" class="hidden md:hidden mt-3 bg-[#141416]/95 backdrop-blur-2xl border border-white/10 rounded-xl p-4 space-y-3 shadow-2xl">
                <a href="{{ route('home') }}" class="block text-sm font-bold tracking-wider hover:text-cinematic-gold transition {{ request()->routeIs('home') ? 'text-cinematic-gold' : 'text-gray-200' }}">TRANG CHỦ</a>
                <a href="{{ route('movies.index') }}" class="block text-sm font-bold tracking-wider hover:text-cinematic-gold transition {{ request()->routeIs('movies.*') ? 'text-cinematic-gold' : 'text-gray-200' }}">PHIM</a>
                <a href="{{ route('showtimes') }}" class="block text-sm font-bold tracking-wider hover:text-cinematic-gold transition {{ request()->routeIs('showtimes') ? 'text-cinematic-gold' : 'text-gray-200' }}">LỊCH CHIẾU</a>
                <a href="{{ route('cinemas.index') }}" class="block text-sm font-bold tracking-wider hover:text-cinematic-gold transition {{ request()->routeIs('cinemas.*') ? 'text-cinematic-gold' : 'text-gray-200' }}">RẠP PHIM</a>
                <a href="{{ route('promotions.index') }}" class="block text-sm font-bold tracking-wider hover:text-cinematic-gold transition {{ request()->routeIs('promotions.*') ? 'text-cinematic-gold' : 'text-gray-200' }}">KHUYẾN MÃI</a>
                @auth
                <div class="pt-2 border-t border-white/10 space-y-2">
                    <a href="{{ route('account.vouchers') }}" class="flex items-center justify-between text-sm font-bold tracking-wider hover:text-cinematic-gold transition {{ request()->routeIs('account.vouchers') ? 'text-cinematic-gold' : 'text-gray-200' }}">
                        <span>🎟️ VÍ VOUCHER CỦA TÔI</span>
                        <span class="px-2 py-0.5 text-[10px] bg-cinematic-gold/20 text-cinematic-gold border border-cinematic-gold/40 rounded-full">{{ auth()->user()->activeVouchersCount() }}</span>
                    </a>
                    <a href="{{ route('account.tickets') }}" class="block text-sm font-bold tracking-wider hover:text-cinematic-gold transition {{ request()->routeIs('account.tickets') ? 'text-cinematic-gold' : 'text-gray-200' }}">🎟️ VÉ CỦA TÔI</a>
                    <a href="{{ route('profile.edit') }}" class="block text-sm font-bold tracking-wider hover:text-cinematic-gold transition {{ request()->routeIs('profile.edit') ? 'text-cinematic-gold' : 'text-gray-200' }}">👤 HỒ SƠ CÁ NHÂN</a>
                </div>
                @endauth
                <div class="pt-3 border-t border-white/10 flex items-center justify-between">
                    <span class="text-xs font-semibold text-gray-400">Giao diện</span>
                    <button type="button" id="mobile-theme-toggle" class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-xs font-medium text-white transition">
                        <svg id="mobile-theme-icon-sun" class="w-4 h-4 text-cinematic-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        <svg id="mobile-theme-icon-moon" class="hidden w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                        <span id="mobile-theme-text">Giao diện sáng</span>
                    </button>
                </div>
            </div>
        </header>

        <main>
            @yield('content')
        </main>

        <footer class="site-footer bg-[#0c1322] border-t-2 border-cinematic-red mt-20 text-sm text-slate-300 font-sans shadow-2xl">
            <!-- Brand Line -->
            <div class="footer-brand-bar bg-[#060a12] border-b border-white/10 py-4 overflow-x-auto">
                <div class="max-w-7xl mx-auto px-6 flex flex-wrap justify-center gap-4 md:gap-7 font-bold text-xs md:text-sm tracking-widest uppercase whitespace-nowrap">
                    <span class="hover:text-white cursor-pointer transition text-slate-200">4DX</span>
                    <span class="hover:text-white cursor-pointer transition text-sky-400">IMAX</span>
                    <span class="hover:text-white cursor-pointer transition text-amber-400">STARIUM</span>
                    <span class="hover:text-white cursor-pointer transition text-yellow-500">GOLD CLASS</span>
                    <span class="hover:text-white cursor-pointer transition text-rose-300">L'AMOUR</span>
                    <span class="hover:text-white cursor-pointer transition text-pink-400">SWEETBOX</span>
                    <span class="hover:text-white cursor-pointer transition text-red-500">PREMIUM CINEMA</span>
                    <span class="hover:text-white cursor-pointer transition text-slate-200">SCREENX</span>
                    <span class="hover:text-white cursor-pointer transition text-emerald-400">CINE & FORÊT</span>
                    <span class="hover:text-white cursor-pointer transition text-purple-400">CINE SUITE</span>
                </div>
            </div>

            <!-- 4 Columns -->
            <div class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="font-bold text-white mb-4 text-base tracking-wide flex items-center gap-2">
                        <span class="w-1.5 h-4 bg-cinematic-red rounded-full"></span> HCTV Việt Nam
                    </h3>
                    <ul class="space-y-2 text-slate-300">
                        <li><a href="{{ route('pages.about') }}" class="hover:text-cinematic-red hover:translate-x-1 inline-block transition">Giới Thiệu</a></li>
                        <li><a href="{{ route('pages.online_services') }}" class="hover:text-cinematic-red hover:translate-x-1 inline-block transition">Tiện Ích Online</a></li>
                        <li><a href="{{ route('pages.gift_cards') }}" class="hover:text-cinematic-red hover:translate-x-1 inline-block transition">Thẻ Quà Tặng</a></li>
                        <li><a href="{{ route('pages.careers') }}" class="hover:text-cinematic-red hover:translate-x-1 inline-block transition">Tuyển Dụng</a></li>
                        <li><a href="{{ route('pages.advertising') }}" class="hover:text-cinematic-red hover:translate-x-1 inline-block transition">Liên Hệ Quảng Cáo HCTV</a></li>
                        <li><a href="{{ route('pages.partners') }}" class="hover:text-cinematic-red hover:translate-x-1 inline-block transition">Dành cho đối tác</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-bold text-white mb-4 text-base tracking-wide flex items-center gap-2">
                        <span class="w-1.5 h-4 bg-cinematic-red rounded-full"></span> Điều khoản sử dụng
                    </h3>
                    <ul class="space-y-2 text-slate-300">
                        <li><a href="{{ route('pages.terms') }}" class="hover:text-cinematic-red hover:translate-x-1 inline-block transition">Điều Khoản Chung</a></li>
                        <li><a href="{{ route('pages.terms_transaction') }}" class="hover:text-cinematic-red hover:translate-x-1 inline-block transition">Điều Khoản Giao Dịch</a></li>
                        <li><a href="{{ route('pages.payment_policy') }}" class="hover:text-cinematic-red hover:translate-x-1 inline-block transition">Chính Sách Thanh Toán</a></li>
                        <li><a href="{{ route('pages.privacy_policy') }}" class="hover:text-cinematic-red hover:translate-x-1 inline-block transition">Chính Sách Bảo Mật</a></li>
                        <li><a href="{{ route('pages.cinema_rules') }}" class="hover:text-cinematic-red hover:translate-x-1 inline-block transition">Những Quy Định Tại Rạp Phim</a></li>
                        <li><a href="{{ route('pages.faq') }}" class="hover:text-cinematic-red hover:translate-x-1 inline-block transition">Câu Hỏi Thường Gặp</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-bold text-white mb-4 text-base tracking-wide flex items-center gap-2">
                        <span class="w-1.5 h-4 bg-cinematic-red rounded-full"></span> Kết nối với chúng tôi
                    </h3>
                    <div class="flex gap-4 mb-6">
                        <!-- Facebook -->
                        <a href="#" class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center hover:opacity-90 hover:scale-105 transition shadow"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22.675 0h-21.35C.597 0 0 .597 0 1.325v21.351C0 23.403.597 24 1.325 24H12.82v-9.294H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12V24h6.116c.73 0 1.323-.597 1.323-1.324V1.325C24 .597 23.403 0 22.675 0z"/></svg></a>
                        <!-- Youtube -->
                        <a href="#" class="w-10 h-10 bg-red-600 text-white rounded-lg flex items-center justify-center hover:opacity-90 hover:scale-105 transition shadow"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M21.582 6.186a2.665 2.665 0 00-1.875-1.881C18.053 3.86 12 3.86 12 3.86s-6.053 0-7.707.445A2.665 2.665 0 002.418 6.186C2 7.846 2 12 2 12s0 4.154.418 5.814a2.665 2.665 0 001.875 1.881C5.947 20.14 12 20.14 12 20.14s6.053 0 7.707-.445a2.665 2.665 0 001.875-1.881C22 16.154 22 12 22 12s0-4.154-.418-5.814zM9.99 15.402V8.598l6.565 3.402-6.565 3.402z"/></svg></a>
                        <!-- Instagram -->
                        <a href="#" class="w-10 h-10 bg-gradient-to-tr from-yellow-400 via-red-500 to-purple-500 text-white rounded-lg flex items-center justify-center hover:opacity-90 hover:scale-105 transition shadow"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.203 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg></a>
                        <!-- Zalo (Text fallback) -->
                        <a href="#" class="w-10 h-10 bg-blue-500 text-white rounded-lg flex items-center justify-center font-bold text-xs hover:opacity-90 hover:scale-105 transition shadow">Zalo</a>
                    </div>
                    <div>
                        <!-- BCT badge mockup -->
                        <div class="inline-flex items-center gap-2 border border-blue-400/80 bg-blue-500/10 rounded-lg p-1.5">
                            <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center text-white text-[8px] font-bold">BCT</div>
                            <div class="text-blue-300 text-[10px] font-bold leading-tight">
                                ĐÃ THÔNG BÁO<br>BỘ CÔNG THƯƠNG
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <h3 class="font-bold text-white mb-4 text-base tracking-wide flex items-center gap-2">
                        <span class="w-1.5 h-4 bg-cinematic-red rounded-full"></span> Chăm sóc khách hàng
                    </h3>
                    <div class="space-y-2 text-slate-300">
                        <p>Hotline: <strong class="footer-hotline text-cinematic-gold text-lg font-bold ml-1">1900 6017</strong></p>
                        <p>Giờ làm việc: <span class="text-slate-200">8:00 - 22:00</span> (Tất cả các ngày bao gồm cả Lễ Tết)</p>
                        <p>Email hỗ trợ: <a href="mailto:hoidap@hctv.vn" class="text-cinematic-red hover:underline font-medium">hoidap@hctv.vn</a></p>
                    </div>
                </div>
            </div>

            <!-- Company Info -->
            <div class="footer-divider border-t border-white/10 py-8">
                <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row gap-8 items-center md:items-start">
                    <div class="flex-shrink-0">
                        <a href="{{ route('home') }}" class="text-4xl font-serif font-bold tracking-wider footer-logo-main text-white drop-shadow">
                            HC<span class="text-cinematic-red">TV</span>
                        </a>
                    </div>
                    <div class="text-center md:text-left text-slate-300 space-y-1">
                        <h3 class="font-bold text-white mb-2 uppercase text-base tracking-wide">CÔNG TY TNHH HCTV VIỆT NAM</h3>
                        <p class="footer-company-desc text-xs text-slate-300 leading-relaxed">Giấy Chứng nhận đăng ký doanh nghiệp: 0303675393 đăng ký lần đầu ngày 31/7/2008, được cấp bởi Sở Kế hoạch và Đầu tư Thành phố Hồ Chí Minh</p>
                        <p class="footer-company-desc text-xs text-slate-300 leading-relaxed">Địa chỉ: Lầu 2, số 7/28, đường Thành Thái, phường Diên Hồng, Quận 10, Thành phố Hồ Chí Minh, Việt Nam</p>
                        <p class="footer-company-desc text-xs text-slate-300 leading-relaxed">Đường dây nóng (Hotline): <span class="footer-hotline text-cinematic-gold font-semibold">1900 6017</span></p>
                        <p class="mt-2 text-xs text-slate-400">COPYRIGHT 2026 HCTV VIETNAM CO., LTD. ALL RIGHTS RESERVED</p>
                    </div>
                </div>
            </div>
            
            <!-- Brick Pattern Footer Edge -->
            <div class="h-10 w-full opacity-80" style="background-image: 
                linear-gradient(335deg, rgba(255,255,255,0.2) 23px, transparent 23px),
                linear-gradient(155deg, rgba(255,255,255,0.2) 23px, transparent 23px),
                linear-gradient(335deg, rgba(255,255,255,0.2) 23px, transparent 23px),
                linear-gradient(155deg, rgba(255,255,255,0.2) 23px, transparent 23px);
                background-size: 58px 58px;
                background-position: 0px 2px, 4px 35px, 29px 31px, 34px 6px;
                background-color: #c0392b;">
            </div>
        </footer>

        <!-- Live Search & Suggestion Scripts -->
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('header-search-input');
            const genreSelect = document.getElementById('header-genre-select');
            const genreHidden = document.getElementById('header-search-genre-hidden');
            const suggestionsBox = document.getElementById('header-search-suggestions');
            const clearBtn = document.getElementById('header-search-clear');
            const loadingSpinner = document.getElementById('header-search-loading');
            const searchForm = document.getElementById('header-search-form');
            const searchContainer = document.getElementById('header-search-container');

            if (!searchInput || !suggestionsBox) return;

            let debounceTimer = null;
            let currentAbortController = null;

            function escapeHtml(str) {
                if (!str) return '';
                const div = document.createElement('div');
                div.textContent = str;
                return div.innerHTML;
            }

            function showLoading() {
                if (loadingSpinner) loadingSpinner.classList.remove('hidden');
                if (clearBtn) clearBtn.classList.add('hidden');
            }

            function hideLoading() {
                if (loadingSpinner) loadingSpinner.classList.add('hidden');
                if (clearBtn && searchInput.value.trim() !== '') clearBtn.classList.remove('hidden');
            }

            function closeSuggestions() {
                suggestionsBox.classList.add('hidden');
                suggestionsBox.innerHTML = '';
            }

            function openSuggestions() {
                suggestionsBox.classList.remove('hidden');
            }

            function fetchSuggestions(query, genre) {
                if (currentAbortController) {
                    currentAbortController.abort();
                }
                currentAbortController = new AbortController();

                showLoading();

                const url = new URL("{{ route('movies.suggest') }}", window.location.origin);
                url.searchParams.set('q', query);
                if (genre) {
                    url.searchParams.set('genre', genre);
                }

                fetch(url.toString(), {
                    signal: currentAbortController.signal,
                    headers: { 'Accept': 'application/json' }
                })
                .then(res => res.json())
                .then(data => {
                    hideLoading();
                    renderSuggestions(data, query, genre);
                })
                .catch(err => {
                    if (err.name !== 'AbortError') {
                        hideLoading();
                        console.error('Lỗi tìm kiếm gợi ý:', err);
                    }
                });
            }

            function renderSuggestions(data, query, genre) {
                const movies = data.movies || [];
                const actors = data.actors || [];

                if (movies.length === 0 && actors.length === 0) {
                    suggestionsBox.innerHTML = `
                        <div class="p-6 text-center text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-gray-500 mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                            </svg>
                            <p class="text-sm text-gray-300">Không tìm thấy phim hoặc diễn viên nào phù hợp với "<span class="text-white font-semibold">${escapeHtml(query)}</span>"${genre ? ' trong thể loại ' + escapeHtml(genre) : ''}.</p>
                            <p class="text-xs text-gray-500 mt-1">Hãy thử tìm với từ khóa khác hoặc chuyển sang "Tất cả thể loại".</p>
                        </div>
                    `;
                    openSuggestions();
                    return;
                }

                let html = '';

                // Movies Section
                if (movies.length > 0) {
                    html += `
                        <div class="py-2" style="background-color: #131722;">
                            <div class="px-5 pt-3 pb-1.5 text-[13px] font-normal text-gray-400" style="color: #9ca3af;">
                                Danh sách phim
                            </div>
                            <div class="flex flex-col">
                    `;

                    movies.forEach(movie => {
                        html += `
                            <a href="${movie.url}" class="suggestion-item flex items-center gap-3.5 px-5 py-2.5 hover:bg-[#1c2233] transition group cursor-pointer border-b border-white/[0.04] last:border-0" style="text-decoration: none;">
                                <img src="${movie.poster_url || '/images/default-poster.jpg'}" 
                                     alt="${escapeHtml(movie.title)}" 
                                     class="object-cover rounded-md shadow shrink-0"
                                     style="width: 46px; height: 66px; object-fit: cover; border-radius: 6px;"
                                     onerror="this.src='https://placehold.co/100x150/202020/white?text=Film'">
                                <div class="flex-1 min-w-0" style="min-width: 0;">
                                    <h4 class="text-[15px] font-semibold text-white group-hover:text-cinematic-gold transition truncate" style="margin: 0; line-height: 1.3;">${escapeHtml(movie.title)}</h4>
                                    <p class="text-[13px] text-gray-400 truncate mt-0.5" style="margin: 2px 0 0 0; color: #9ca3af;">${escapeHtml(movie.genre || 'Phim chiếu rạp')}</p>
                                    <div class="flex items-center gap-3 text-xs mt-1" style="margin-top: 4px; display: flex; align-items: center; gap: 10px;">
                                        <span class="text-gray-400" style="color: #9ca3af;">${movie.year || '2026'}</span>
                                        <span class="font-bold text-xs" style="color: #fbbf24; font-weight: 700;">HD</span>
                                        <span class="text-xs font-medium" style="color: #34d399; font-weight: 500;">Vietsub</span>
                                    </div>
                                </div>
                            </a>
                        `;
                    });

                    html += `</div></div>`;
                }

                // Actors Section
                if (actors.length > 0) {
                    html += `
                        <div class="border-t border-white/10 py-2" style="background-color: #131722; border-top: 1px solid rgba(255,255,255,0.08);">
                            <div class="px-5 pt-2.5 pb-1.5 text-[13px] font-normal text-gray-400" style="color: #9ca3af;">
                                Diễn viên
                            </div>
                            <div class="flex flex-col">
                    `;

                    actors.forEach(actor => {
                        html += `
                            <a href="${actor.movie_url || '#'}" class="suggestion-item flex items-center gap-3.5 px-5 py-2.5 hover:bg-[#1c2233] transition group cursor-pointer border-b border-white/[0.04] last:border-0" style="text-decoration: none;">
                                <img src="${actor.avatar_url}" 
                                     alt="${escapeHtml(actor.name)}" 
                                     class="object-cover rounded-full shadow shrink-0"
                                     style="width: 42px; height: 42px; border-radius: 9999px; object-fit: cover;"
                                     onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(actor.name)}&background=333&color=fff'">
                                <div class="flex-1 min-w-0" style="min-width: 0;">
                                    <h4 class="text-[15px] font-semibold text-white truncate" style="margin: 0;">${escapeHtml(actor.name)}</h4>
                                    <p class="text-[13px] text-gray-400 mt-0.5 truncate" style="margin: 2px 0 0 0; color: #9ca3af;">
                                        ${actor.role ? `Vai ${escapeHtml(actor.role)}` : ''}${actor.movie_title ? ` trong phim ${escapeHtml(actor.movie_title)}` : ''}
                                    </p>
                                </div>
                                <div class="text-xs text-gray-400 group-hover:text-cinematic-gold transition shrink-0" style="font-size: 12px; color: #9ca3af;">
                                    Xem phim &rarr;
                                </div>
                            </a>
                        `;
                    });

                    html += `</div></div>`;
                }

                // Footer Action
                html += `
                    <div class="bg-[#181c28] hover:bg-[#202538] transition border-t border-white/10 text-center" style="background-color: #181c28; border-top: 1px solid rgba(255,255,255,0.08);">
                        <button type="submit" form="header-search-form" class="w-full py-3.5 text-sm text-gray-200 hover:text-white font-medium flex items-center justify-center cursor-pointer transition" style="background: none; border: none; width: 100%; color: #e5e7eb; font-weight: 500; cursor: pointer; padding: 12px 0;">
                            Toàn bộ kết quả
                        </button>
                    </div>
                `;

                suggestionsBox.innerHTML = html;
                openSuggestions();
            }


            // Input Event (Debounced Search)
            searchInput.addEventListener('input', function () {
                const q = this.value.trim();
                const genre = genreSelect ? genreSelect.value : '';

                if (q !== '') {
                    clearBtn.classList.remove('hidden');
                } else {
                    clearBtn.classList.add('hidden');
                    closeSuggestions();
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    fetchSuggestions(q, genre);
                }, 220);
            });

            // Focus Event
            searchInput.addEventListener('focus', function () {
                const q = this.value.trim();
                if (q !== '' && suggestionsBox.innerHTML.trim() !== '') {
                    openSuggestions();
                }
            });

            // Clear Button Event
            if (clearBtn) {
                clearBtn.addEventListener('click', function () {
                    searchInput.value = '';
                    clearBtn.classList.add('hidden');
                    closeSuggestions();
                    searchInput.focus();
                });
            }

            // Genre Dropdown Change Event
            if (genreSelect) {
                genreSelect.addEventListener('change', function () {
                    if (genreHidden) genreHidden.value = this.value;
                    const q = searchInput.value.trim();
                    if (q !== '') {
                        fetchSuggestions(q, this.value);
                    } else if (this.value !== '') {
                        // Navigate to movies page filtered by genre if on another page or directly
                        window.location.href = "{{ route('movies.index') }}?genre=" + encodeURIComponent(this.value);
                    } else if (window.location.pathname.includes('/movies')) {
                        window.location.href = "{{ route('movies.index') }}";
                    }
                });
            }

            // Close when clicking outside
            document.addEventListener('click', function (e) {
                if (!searchContainer.contains(e.target) && (!genreSelect || !genreSelect.contains(e.target))) {
                    closeSuggestions();
                }
            });

            // Keyboard navigation (Escape key)
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    closeSuggestions();
                }
            });

            // Mobile Menu Toggle
            const mobileToggle = document.getElementById('mobile-menu-toggle');
            const mobileNav = document.getElementById('mobile-nav-menu');
            if (mobileToggle && mobileNav) {
                mobileToggle.addEventListener('click', function () {
                    mobileNav.classList.toggle('hidden');
                });
            }

            // Theme Toggle Logic (Sáng / Tối)
            function updateThemeUI(isLight) {
                const sunIcon = document.getElementById('theme-icon-sun');
                const moonIcon = document.getElementById('theme-icon-moon');
                const themeBtn = document.getElementById('theme-toggle');

                const mobileSun = document.getElementById('mobile-theme-icon-sun');
                const mobileMoon = document.getElementById('mobile-theme-icon-moon');
                const mobileText = document.getElementById('mobile-theme-text');

                if (isLight) {
                    document.documentElement.classList.add('light-mode');
                    if (sunIcon) sunIcon.classList.add('hidden');
                    if (moonIcon) moonIcon.classList.remove('hidden');
                    if (themeBtn) themeBtn.setAttribute('title', 'Chuyển sang chế độ tối');

                    if (mobileSun) mobileSun.classList.add('hidden');
                    if (mobileMoon) mobileMoon.classList.remove('hidden');
                    if (mobileText) mobileText.textContent = 'Giao diện tối';
                } else {
                    document.documentElement.classList.remove('light-mode');
                    if (sunIcon) sunIcon.classList.remove('hidden');
                    if (moonIcon) moonIcon.classList.add('hidden');
                    if (themeBtn) themeBtn.setAttribute('title', 'Chuyển sang chế độ sáng');

                    if (mobileSun) mobileSun.classList.remove('hidden');
                    if (mobileMoon) mobileMoon.classList.add('hidden');
                    if (mobileText) mobileText.textContent = 'Giao diện sáng';
                }
            }

            function toggleTheme() {
                const isCurrentlyLight = document.documentElement.classList.contains('light-mode');
                const newIsLight = !isCurrentlyLight;
                try {
                    localStorage.setItem('hctv_theme', newIsLight ? 'light' : 'dark');
                } catch (e) {}
                updateThemeUI(newIsLight);
            }

            // Initialize on load
            const currentIsLight = document.documentElement.classList.contains('light-mode');
            updateThemeUI(currentIsLight);

            const desktopThemeBtn = document.getElementById('theme-toggle');
            if (desktopThemeBtn) {
                desktopThemeBtn.addEventListener('click', toggleTheme);
            }

            const mobileThemeBtn = document.getElementById('mobile-theme-toggle');
            if (mobileThemeBtn) {
                mobileThemeBtn.addEventListener('click', toggleTheme);
            }
        });
        </script>
    </body>
</html>
