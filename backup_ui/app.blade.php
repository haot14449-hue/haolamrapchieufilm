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
    </head>
    <body class="font-sans antialiased bg-cinematic-dark text-white selection:bg-cinematic-red selection:text-white">
        <!-- Navbar -->
        <header class="absolute top-0 left-0 w-full z-50 py-4 px-6 md:px-12 flex justify-between items-center">
            <div class="text-2xl font-serif font-bold tracking-wider text-white">
                HC<span class="text-cinematic-red">TV</span>
            </div>
            <nav class="hidden md:flex space-x-8 text-sm font-medium">
                <a href="#" class="hover:text-cinematic-gold transition-colors duration-300">TRANG CHỦ</a>
                <a href="#" class="hover:text-cinematic-gold transition-colors duration-300">LỊCH CHIẾU</a>
                <a href="#" class="hover:text-cinematic-gold transition-colors duration-300">RẠP PHIM</a>
                <a href="#" class="hover:text-cinematic-gold transition-colors duration-300">KHUYẾN MÃI</a>
            </nav>
            <div class="flex items-center space-x-4">
                <a href="#" class="text-sm font-medium hover:text-gray-300 transition-colors">Đăng nhập</a>
            </div>
        </header>

        <main>
            @yield('content')
        </main>

        <footer class="bg-black/90 py-12 px-6 border-t border-white/10 mt-20">
            <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="text-2xl font-serif font-bold tracking-wider text-white mb-4">
                        HC<span class="text-cinematic-red">TV</span>
                    </div>
                    <p class="text-gray-400 text-sm">Trải nghiệm điện ảnh đỉnh cao.</p>
                </div>
                <div>
                    <h3 class="font-bold mb-4">Quy định</h3>
                    <ul class="text-gray-400 text-sm space-y-2">
                        <li><a href="#" class="hover:text-white">Điều khoản chung</a></li>
                        <li><a href="#" class="hover:text-white">Chính sách thanh toán</a></li>
                        <li><a href="#" class="hover:text-white">Chính sách bảo mật</a></li>
                    </ul>
                </div>
            </div>
        </footer>
    </body>
</html>
