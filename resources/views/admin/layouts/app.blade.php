<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Dashboard - HCTV')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
    
    <!-- Alpine.js (for simple dropdowns/modals) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Enforce clean light mode for Admin panel -->
    <script>
        document.documentElement.classList.remove('dark');
        document.documentElement.classList.add('light');
    </script>
</head>
<body class="font-sans antialiased bg-gray-100 text-gray-900">
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-white shrink-0 hidden md:flex flex-col">
            <div class="h-16 flex items-center px-6 border-b border-gray-800 shrink-0">
                <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold tracking-wider">
                    HC<span class="text-red-500">TV</span> ADMIN
                </a>
            </div>
            
            <nav class="flex-1 overflow-y-auto py-4 space-y-1 px-3">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                    Dashboard
                </a>
                
                <p class="px-3 pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Quản Lý Rạp</p>
                <a href="{{ route('admin.movies.index') }}" class="flex items-center gap-3 px-3 py-2 rounded text-sm font-medium {{ request()->routeIs('admin.movies.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" /></svg>
                    Phim
                </a>
                <a href="{{ route('admin.top_movies.index') }}" class="flex items-center justify-between px-3 py-2 rounded text-sm font-medium {{ request()->routeIs('admin.top_movies.*') ? 'bg-amber-500/20 text-amber-300 font-semibold border-l-4 border-amber-400' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ request()->routeIs('admin.top_movies.*') ? 'text-amber-400' : 'text-amber-400/80' }}" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.75 2.042a.75.75 0 00-.75 0 11.233 11.233 0 00-3.666 4.708.75.75 0 00.99 1.011c.915-.558 1.93-.976 3.001-1.229a10.963 10.963 0 01-1.325 5.568.75.75 0 00.58 1.1c1.554.24 3.018.966 4.195 2.062a.75.75 0 001.275-.544 11.23 11.23 0 00-4.3-12.676zM7.5 9.75a.75.75 0 00-.75-.75A11.26 11.26 0 003 14.25c0 5.385 4.365 9.75 9.75 9.75s9.75-4.365 9.75-9.75c0-2.316-.807-4.444-2.158-6.12a.75.75 0 00-1.168.04 7.48 7.48 0 01-4.074 2.83.75.75 0 00-.51.933c.31 1.05.46 2.146.46 3.317a7.25 7.25 0 11-14.5 0c0-1.745.545-3.376 1.487-4.733a.75.75 0 00.053-.767z" />
                        </svg>
                        <span>Top 10 Phim Hot</span>
                    </div>
                    <span class="bg-amber-400/20 text-amber-300 text-[10px] font-bold px-1.5 py-0.5 rounded uppercase tracking-wider">Mới</span>
                </a>
                <a href="{{ route('admin.genres.index') }}" class="flex items-center gap-3 px-3 py-2 rounded text-sm font-medium {{ request()->routeIs('admin.genres.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                    Thể Loại Phim
                </a>
                <a href="{{ route('admin.cinemas.index') }}" class="flex items-center gap-3 px-3 py-2 rounded text-sm font-medium {{ request()->routeIs('admin.cinemas.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    Rạp & Phòng Chiếu
                </a>
                <a href="{{ route('admin.showtimes.index') }}" class="flex items-center gap-3 px-3 py-2 rounded text-sm font-medium {{ request()->routeIs('admin.showtimes.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    Lịch Chiếu
                </a>
                <a href="{{ route('admin.foods.index') }}" class="flex items-center gap-3 px-3 py-2 rounded text-sm font-medium {{ request()->routeIs('admin.foods.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    Bắp Nước & Combo
                </a>
                <a href="{{ route('admin.promotions.index') }}" class="flex items-center gap-3 px-3 py-2 rounded text-sm font-medium {{ request()->routeIs('admin.promotions.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                    Khuyến Mãi
                </a>
                
                <p class="px-3 pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kinh Doanh</p>
                <a href="{{ route('pos.index') }}" class="flex items-center gap-3 px-3 py-2 rounded text-sm font-semibold bg-emerald-950/40 text-emerald-300 border border-emerald-500/30 hover:bg-emerald-900/60 hover:text-white transition-all shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                    Quầy Bán Vé (POS)
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="flex items-center gap-3 px-3 py-2 rounded text-sm font-medium {{ request()->routeIs('admin.bookings.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                    Đơn Hàng / Vé
                </a>

                @if(auth()->user()->role === 'admin')
                <p class="px-3 pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Hệ Thống</p>
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2 rounded text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Quản Lý Tài Khoản
                </a>
                @endif
            </nav>
            
            <div class="p-4 border-t border-gray-800">
                <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 px-3 py-2 rounded text-sm font-medium text-gray-400 hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                    Xem Trang Web
                </a>
            </div>
        </aside>

        <!-- Main content -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Header -->
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 shrink-0 transition-colors duration-200">
                <div class="flex items-center md:hidden">
                    <button class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>
                    <span class="ml-4 text-xl font-bold tracking-wider">HC<span class="text-red-500">TV</span> ADMIN</span>
                </div>
                
                <div class="hidden md:flex items-center gap-2">
                    <span class="text-xs font-semibold text-gray-400">Khu vực:</span>
                    <span class="text-xs font-bold px-2 py-0.5 rounded bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                        {{ auth()->user()->role === 'admin' ? 'Quản trị viên Hệ Thống' : 'Bàn Làm Việc Nhân Viên' }}
                    </span>
                </div>
                
                <div class="flex items-center gap-3 sm:gap-4">
                    <!-- Nút sang Quầy Bán Vé Trực Tiếp (POS) -->
                    <a href="{{ route('pos.index') }}" 
                       class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-600/15 hover:bg-emerald-600 text-emerald-600 hover:text-white border border-emerald-500/30 text-xs font-bold transition shadow-sm group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-500 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                        <span>Quầy Vé (POS)</span>
                    </a>

                    <!-- User Name & Role -->
                    <div class="text-right pl-2 border-l border-gray-200">
                        <span class="block text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</span>
                        <span class="inline-block text-[11px] font-semibold px-2 py-0.5 rounded-full {{ auth()->user()->role === 'admin' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                            {{ auth()->user()->role === 'admin' ? 'Quản trị viên' : 'Nhân viên' }}
                        </span>
                    </div>

                    <!-- Logout Button -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-xs font-semibold text-red-600 hover:text-red-700 px-2.5 py-1.5 rounded-lg hover:bg-red-50 transition">Đăng xuất</button>
                    </form>
                </div>
            </header>

            <!-- Main content area -->
            <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
