<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Đăng ký tài khoản - HCTV Cinema</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&display=swap" rel="stylesheet">

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            'cinematic-dark': '#0a0a0c',
                            'cinematic-red': '#e50914',
                            'cinematic-gold': '#d4af37',
                        },
                        fontFamily: {
                            sans: ['Inter', 'sans-serif'],
                            serif: ['Playfair Display', 'serif'],
                        }
                    }
                }
            }
        </script>
    @endif

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0a0a0c;
            color: #ffffff;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        .font-serif {
            font-family: 'Playfair Display', serif;
        }

        .glass-panel {
            background: rgba(18, 21, 30, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .ambient-glow-red {
            box-shadow: 0 0 45px -10px rgba(229, 9, 20, 0.35);
        }

        .ambient-glow-button {
            box-shadow: 0 0 25px rgba(229, 9, 20, 0.45);
        }

        .ambient-glow-button:hover {
            box-shadow: 0 0 35px rgba(229, 9, 20, 0.75);
        }

        .hero-banner-mask {
            background-image: linear-gradient(to right, #0a0a0c 0%, rgba(10, 10, 12, 0.6) 8%, rgba(10, 10, 12, 0.1) 25%, transparent 40%),
                              linear-gradient(to top, #0a0a0c 0%, rgba(10, 10, 12, 0.7) 12%, transparent 35%),
                              linear-gradient(to bottom, rgba(10, 10, 12, 0.8) 0%, transparent 20%);
        }

        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus {
            -webkit-text-fill-color: #ffffff !important;
            -webkit-box-shadow: 0 0 0px 1000px #131622 inset !important;
            transition: background-color 5000s ease-in-out 0s;
        }
    </style>
</head>
<body class="h-full bg-[#0a0a0c] text-white antialiased selection:bg-[#e50914] selection:text-white">

    <div class="min-h-screen w-full flex flex-col lg:flex-row">

        <!-- ============================================== -->
        <!-- NỬA BÊN TRÁI: FORM ĐĂNG KÝ TÀI KHOẢN          -->
        <!-- ============================================== -->
        <div class="w-full lg:w-1/2 xl:w-[46%] min-h-screen flex flex-col justify-between p-6 sm:p-10 lg:p-14 xl:p-16 relative z-10 bg-[#0a0a0c]">
            
            <!-- Hiệu ứng ánh sáng nền mờ -->
            <div class="pointer-events-none absolute top-0 left-0 w-80 h-80 bg-red-600/10 rounded-full blur-[100px]"></div>
            <div class="pointer-events-none absolute bottom-10 left-10 w-72 h-72 bg-amber-500/5 rounded-full blur-[90px]"></div>

            <!-- Top Header: Về trang chủ & Huy hiệu hệ thống -->
            <div class="relative z-10 flex items-center justify-between pb-6">
                <a href="{{ route('home') }}" class="group inline-flex items-center gap-2 text-xs sm:text-sm font-medium text-gray-400 hover:text-white transition-colors duration-200">
                    <span class="p-1.5 rounded-lg bg-white/5 border border-white/10 group-hover:bg-red-600/20 group-hover:border-red-500/40 group-hover:text-red-400 transition-all duration-200">
                        <svg class="w-4 h-4 transform group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </span>
                    <span>Trang chủ</span>
                </a>

                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/[0.04] border border-white/10 text-[11px] text-gray-300">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="font-medium tracking-wide">HCTV Online Cinema</span>
                </div>
            </div>

            <!-- Form Đăng ký -->
            <div class="relative z-10 my-auto py-6 sm:py-8 max-w-md w-full mx-auto">
                
                <!-- Logo & Lời chào -->
                <div class="mb-7">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-3 group mb-3">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-red-600 via-red-700 to-red-900 flex items-center justify-center shadow-[0_0_20px_rgba(229,9,20,0.5)] border border-red-500/30 group-hover:scale-105 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19.5 4H4.5C3.12 4 2 5.12 2 6.5v11C2 18.88 3.12 20 4.5 20h15c1.38 0 2.5-1.12 2.5-2.5v-11C22 5.12 20.88 4 19.5 4zM4 6.5C4 6.22 4.22 6 4.5 6H7v3H4V6.5zm0 5h3v3H4v-3zm0 6V16h3v3H4.5C4.22 19 4 18.78 4 18.5zm16 0c0 .28-.22.5-.5.5H9v-3h11v2.5zm0-4.5H9v-3h11v3zm0-5H9V6h10.5c.28 0 .5.22.5.5v2.5z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-3xl font-serif font-black tracking-wider text-white">
                                HC<span class="text-[#e50914] drop-shadow-[0_0_12px_rgba(229,9,20,0.6)]">TV</span>
                            </div>
                            <span class="block text-[10px] tracking-[0.3em] text-gray-400 uppercase font-semibold -mt-1">
                                Cinemas & Entertainment
                            </span>
                        </div>
                    </a>

                    <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight mt-3">
                        Đăng ký tài khoản
                    </h1>
                    <p class="text-gray-400 text-sm mt-1.5 leading-relaxed">
                        Đăng ký thành viên để nhận mã OTP xác thực, đặt vé nhanh chóng và hưởng nhiều quyền lợi VIP.
                    </p>
                </div>

                <!-- Validation Errors Alert -->
                @if ($errors->any())
                    <div class="mb-5 p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-200 text-sm">
                        <div class="flex items-center gap-2 font-semibold text-red-400 mb-1">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <span>Thông tin chưa hợp lệ:</span>
                        </div>
                        <ul class="list-disc list-inside text-xs space-y-0.5 text-gray-300 pl-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <!-- Họ và tên -->
                    <div>
                        <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-gray-300 mb-1.5">
                            Họ và tên <span class="text-red-500">*</span>
                        </label>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <input 
                                id="name" 
                                type="text" 
                                name="name" 
                                value="{{ old('name') }}" 
                                required 
                                autofocus 
                                autocomplete="name"
                                placeholder="Nguyễn Văn A"
                                class="w-full pl-11 pr-4 py-3 bg-[#131722] text-white placeholder-gray-500 rounded-xl border border-white/15 focus:border-red-500 focus:ring-2 focus:ring-red-500/25 transition-all duration-200 outline-none text-sm font-normal @error('name') border-red-500 ring-2 ring-red-500/20 @enderror"
                            >
                        </div>
                    </div>

                    <!-- Địa chỉ Email -->
                    <div>
                        <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-gray-300 mb-1.5">
                            Địa chỉ Email <span class="text-red-500">*</span>
                        </label>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                            </div>
                            <input 
                                id="email" 
                                type="email" 
                                name="email" 
                                value="{{ old('email') }}" 
                                required 
                                autocomplete="username"
                                placeholder="name@domain.com"
                                class="w-full pl-11 pr-4 py-3 bg-[#131722] text-white placeholder-gray-500 rounded-xl border border-white/15 focus:border-red-500 focus:ring-2 focus:ring-red-500/25 transition-all duration-200 outline-none text-sm font-normal @error('email') border-red-500 ring-2 ring-red-500/20 @enderror"
                            >
                        </div>
                        <p class="text-[11px] text-gray-400 mt-1 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-amber-400 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <span>Hệ thống sẽ gửi mã OTP gồm 6 chữ số tới email này để xác thực.</span>
                        </p>
                    </div>

                    <!-- Mật khẩu -->
                    <div>
                        <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-gray-300 mb-1.5">
                            Mật khẩu <span class="text-red-500">*</span>
                        </label>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input 
                                id="password" 
                                type="password" 
                                name="password" 
                                required 
                                autocomplete="new-password"
                                placeholder="Tối thiểu 8 ký tự"
                                class="w-full pl-11 pr-11 py-3 bg-[#131722] text-white placeholder-gray-500 rounded-xl border border-white/15 focus:border-red-500 focus:ring-2 focus:ring-red-500/25 transition-all duration-200 outline-none text-sm font-normal @error('password') border-red-500 ring-2 ring-red-500/20 @enderror"
                            >
                            <button 
                                type="button" 
                                onclick="togglePasswordVisibility('password', 'eyeOpen1', 'eyeClosed1')" 
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-white transition-colors cursor-pointer"
                                aria-label="Hiện mật khẩu"
                            >
                                <svg id="eyeOpen1" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg id="eyeClosed1" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Xác nhận Mật khẩu -->
                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-wider text-gray-300 mb-1.5">
                            Xác nhận Mật khẩu <span class="text-red-500">*</span>
                        </label>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <input 
                                id="password_confirmation" 
                                type="password" 
                                name="password_confirmation" 
                                required 
                                autocomplete="new-password"
                                placeholder="Nhập lại mật khẩu"
                                class="w-full pl-11 pr-11 py-3 bg-[#131722] text-white placeholder-gray-500 rounded-xl border border-white/15 focus:border-red-500 focus:ring-2 focus:ring-red-500/25 transition-all duration-200 outline-none text-sm font-normal @error('password_confirmation') border-red-500 ring-2 ring-red-500/20 @enderror"
                            >
                            <button 
                                type="button" 
                                onclick="togglePasswordVisibility('password_confirmation', 'eyeOpen2', 'eyeClosed2')" 
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-white transition-colors cursor-pointer"
                                aria-label="Hiện mật khẩu xác nhận"
                            >
                                <svg id="eyeOpen2" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg id="eyeClosed2" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Nút Gửi mã OTP -->
                    <div class="pt-3">
                        <button 
                            type="submit" 
                            class="w-full relative group overflow-hidden py-3.5 px-6 rounded-xl font-bold tracking-wider uppercase text-white bg-gradient-to-r from-red-600 via-red-600 to-red-700 hover:from-red-500 hover:to-red-600 ambient-glow-button transition-all duration-300 flex items-center justify-center gap-2.5 active:scale-[0.99]"
                        >
                            <div class="absolute inset-0 w-1/2 h-full bg-white/20 skew-x-12 -translate-x-full group-hover:translate-x-[300%] transition-transform duration-1000 ease-out"></div>
                            <span class="relative z-10 text-sm tracking-widest font-extrabold">NHẬN MÃ XÁC THỰC OTP</span>
                            <svg class="relative z-10 w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Link Đã có tài khoản -->
                    <div class="text-center pt-2">
                        <p class="text-sm text-gray-400">
                            Đã có tài khoản HCTV?
                            <a href="{{ route('login') }}" class="text-red-400 hover:text-red-300 font-semibold hover:underline ms-1 transition-colors">
                                Đăng nhập ngay
                            </a>
                        </p>
                    </div>

                </form>

            </div>

            <!-- Footer Bản quyền -->
            <div class="relative z-10 pt-6 border-t border-white/5 flex flex-col sm:flex-row items-center justify-between text-xs text-gray-500 gap-2">
                <div>
                    &copy; {{ date('Y') }} HCTV Cinema. Bản quyền thuộc về HCTV.
                </div>
                <div class="flex items-center gap-4 text-gray-400">
                    <a href="#" class="hover:text-gray-300 transition-colors">Điều khoản</a>
                    <span>•</span>
                    <a href="#" class="hover:text-gray-300 transition-colors">Bảo mật</a>
                    <span>•</span>
                    <a href="#" class="hover:text-gray-300 transition-colors">Hỗ trợ</a>
                </div>
            </div>

        </div>


        <!-- ============================================== -->
        <!-- NỬA BÊN PHẢI: HERO BANNER ĐĂNG KÝ (HCTV)       -->
        <!-- ============================================== -->
        <div class="hidden lg:block lg:w-1/2 xl:w-[54%] min-h-screen relative overflow-hidden bg-black">
            
            <!-- Banner Đăng Ký (Bắp nước, vé xem phim, phòng chiếu) -->
            <div 
                class="absolute inset-0 w-full h-full bg-cover bg-center bg-no-repeat transition-transform duration-700 hover:scale-105"
                style="background-image: url('{{ asset('images/banner_register.png') }}');"
            ></div>

            <!-- Gradient che mờ biên trái để hòa trộn vào form -->
            <div class="absolute inset-0 hero-banner-mask"></div>

            <!-- Ánh sáng đỏ rạp chiếu phim -->
            <div class="pointer-events-none absolute -top-24 -right-24 w-96 h-96 bg-red-600/20 rounded-full blur-[120px]"></div>
            <div class="pointer-events-none absolute bottom-0 right-1/4 w-[500px] h-60 bg-red-600/15 rounded-full blur-[100px]"></div>

            <!-- Nội dung Overlay kính mờ phía trên Banner -->
            <div class="relative z-10 h-full flex flex-col justify-between p-10 xl:p-14">
                
                <!-- Huy hiệu góc trên bên phải -->
                <div class="flex justify-end">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass-panel text-xs text-white/90 shadow-xl border border-white/10">
                        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <span class="font-semibold tracking-wide uppercase">ĐẶC QUYỀN HCTV CLUB</span>
                    </div>
                </div>

                <!-- Thẻ quyền lợi thành viên ở đáy banner -->
                <div class="max-w-xl">
                    <div class="glass-panel p-6 sm:p-8 rounded-3xl border border-white/15 ambient-glow-red">
                        <div class="flex items-center gap-2.5 mb-3">
                            <span class="px-2.5 py-1 rounded-md bg-[#e50914] text-white text-[11px] font-extrabold uppercase tracking-widest">
                                VIP MEMBER
                            </span>
                            <span class="text-xs text-gray-300 font-medium flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                Nhận trọn vẹn ưu đãi rạp phim
                            </span>
                        </div>

                        <h2 class="text-2xl sm:text-3xl font-serif font-black text-white leading-snug drop-shadow-md">
                            Gia nhập HCTV Club ngay hôm nay
                        </h2>

                        <p class="text-gray-300 text-xs sm:text-sm mt-2.5 leading-relaxed">
                            Mở khóa hàng loạt ưu đãi vé xem phim, combo bắp nước miễn phí trong tuần lễ sinh nhật và tích lũy điểm thưởng không giới hạn.
                        </p>

                        <!-- Các huy hiệu đặc quyền -->
                        <div class="grid grid-cols-3 gap-2.5 mt-5 pt-4 border-t border-white/10">
                            <div class="text-center p-2 rounded-xl bg-white/[0.04] border border-white/5">
                                <div class="text-[#e50914] font-bold text-sm sm:text-base">TÍCH ĐIỂM 10%</div>
                                <div class="text-[10px] text-gray-400 uppercase tracking-wider mt-0.5">Mỗi Giao Dịch</div>
                            </div>
                            <div class="text-center p-2 rounded-xl bg-white/[0.04] border border-white/5">
                                <div class="text-amber-400 font-bold text-sm sm:text-base">VÉ 45.000Đ</div>
                                <div class="text-[10px] text-gray-400 uppercase tracking-wider mt-0.5">Thứ 3 Hàng Tuần</div>
                            </div>
                            <div class="text-center p-2 rounded-xl bg-white/[0.04] border border-white/5">
                                <div class="text-white font-bold text-sm sm:text-base">BẮP NƯỚC FREE</div>
                                <div class="text-[10px] text-gray-400 uppercase tracking-wider mt-0.5">Tuần Sinh Nhật</div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Script tương tác ẩn/hiện mật khẩu -->
    <script>
        function togglePasswordVisibility(inputId, openIconId, closedIconId) {
            const input = document.getElementById(inputId);
            const openIcon = document.getElementById(openIconId);
            const closedIcon = document.getElementById(closedIconId);

            if (input.type === 'password') {
                input.type = 'text';
                openIcon.classList.add('hidden');
                closedIcon.classList.remove('hidden');
            } else {
                input.type = 'password';
                openIcon.classList.remove('hidden');
                closedIcon.classList.add('hidden');
            }
        }
    </script>

</body>
</html>
