<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Xác thực mã OTP - HCTV Cinema</title>

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

        /* 6-digit input styling */
        .otp-input {
            width: 48px;
            height: 56px;
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            background-color: #131722;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            color: #ffffff;
            transition: all 0.2s ease;
            outline: none;
        }

        @media (min-width: 640px) {
            .otp-input {
                width: 56px;
                height: 64px;
                font-size: 28px;
            }
        }

        .otp-input:focus {
            border-color: #e50914;
            box-shadow: 0 0 0 3px rgba(229, 9, 20, 0.3);
            background-color: #1a1e2e;
            transform: scale(1.03);
        }
    </style>
</head>
<body class="h-full bg-[#0a0a0c] text-white antialiased selection:bg-[#e50914] selection:text-white">

    <div class="min-h-screen w-full flex flex-col lg:flex-row">

        <!-- ============================================== -->
        <!-- NỬA BÊN TRÁI: FORM NHẬP MÃ OTP 6 CHỮ SỐ        -->
        <!-- ============================================== -->
        <div class="w-full lg:w-1/2 xl:w-[46%] min-h-screen flex flex-col justify-between p-6 sm:p-10 lg:p-14 xl:p-16 relative z-10 bg-[#0a0a0c]">
            
            <!-- Ánh sáng nền mờ -->
            <div class="pointer-events-none absolute top-0 left-0 w-80 h-80 bg-red-600/10 rounded-full blur-[100px]"></div>
            <div class="pointer-events-none absolute bottom-10 left-10 w-72 h-72 bg-amber-500/5 rounded-full blur-[90px]"></div>

            <!-- Header Top Bar: Đổi thông tin / Về trang đăng ký -->
            <div class="relative z-10 flex items-center justify-between pb-6">
                <a href="{{ route('register.otp.cancel') }}" class="group inline-flex items-center gap-2 text-xs sm:text-sm font-medium text-gray-400 hover:text-white transition-colors duration-200">
                    <span class="p-1.5 rounded-lg bg-white/5 border border-white/10 group-hover:bg-red-600/20 group-hover:border-red-500/40 group-hover:text-red-400 transition-all duration-200">
                        <svg class="w-4 h-4 transform group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </span>
                    <span>Đổi thông tin</span>
                </a>

                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/[0.04] border border-white/10 text-[11px] text-gray-300">
                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                    <span class="font-medium tracking-wide">Xác thực bảo mật</span>
                </div>
            </div>

            <!-- Form Nhập OTP -->
            <div class="relative z-10 my-auto py-6 sm:py-8 max-w-md w-full mx-auto">
                
                <!-- Icon Khóa & Header -->
                <div class="mb-7 text-center sm:text-left">
                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-red-600/10 border border-red-500/30 text-red-500 shadow-[0_0_25px_rgba(229,9,20,0.3)] mb-4">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">
                        Kiểm tra hộp thư của bạn
                    </h1>
                    <p class="text-gray-300 text-sm mt-2 leading-relaxed">
                        Mã OTP gồm 6 chữ số đã được gửi tới:
                    </p>
                    <div class="inline-flex items-center gap-2 mt-1 px-3 py-1.5 rounded-lg bg-white/[0.05] border border-white/10 text-white font-semibold text-sm">
                        <span>📧 {{ $email }}</span>
                    </div>
                    <p class="text-[11px] text-gray-400 mt-2">
                        Vui lòng kiểm tra hộp thư đến (và thư rác/spam). Mã có hiệu lực trong <strong>10 phút</strong>.
                    </p>
                </div>

                <!-- Thông báo Resend Status -->
                @if (session('status'))
                    <div class="mb-5 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-sm flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                <!-- Validation Errors Alert -->
                @if ($errors->any())
                    <div class="mb-5 p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-200 text-sm">
                        <div class="flex items-center gap-2 font-semibold text-red-400 mb-1">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <span>Lỗi xác thực:</span>
                        </div>
                        <ul class="list-disc list-inside text-xs space-y-0.5 text-gray-300 pl-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Form Gửi Mã OTP -->
                <form id="otpForm" method="POST" action="{{ route('register.otp.verify') }}" class="space-y-6">
                    @csrf

                    <!-- 6 Ô Input Nhập Từng Chữ Số -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 text-center mb-3">
                            Nhập mã xác thực 6 chữ số
                        </label>

                        <div class="flex items-center justify-center gap-2 sm:gap-3" id="otpInputsContainer">
                            <input type="text" inputmode="numeric" maxlength="1" name="digit_1" id="digit_1" class="otp-input" autofocus autocomplete="one-time-code">
                            <input type="text" inputmode="numeric" maxlength="1" name="digit_2" id="digit_2" class="otp-input">
                            <input type="text" inputmode="numeric" maxlength="1" name="digit_3" id="digit_3" class="otp-input">
                            <input type="text" inputmode="numeric" maxlength="1" name="digit_4" id="digit_4" class="otp-input">
                            <input type="text" inputmode="numeric" maxlength="1" name="digit_5" id="digit_5" class="otp-input">
                            <input type="text" inputmode="numeric" maxlength="1" name="digit_6" id="digit_6" class="otp-input">
                        </div>

                        <!-- Trường ẩn chứa mã đầy đủ -->
                        <input type="hidden" name="otp" id="fullOtpInput">
                    </div>

                    <!-- Nút Xác nhận -->
                    <div>
                        <button 
                            type="submit" 
                            id="verifyButton"
                            class="w-full relative group overflow-hidden py-3.5 px-6 rounded-xl font-bold tracking-wider uppercase text-white bg-gradient-to-r from-red-600 via-red-600 to-red-700 hover:from-red-500 hover:to-red-600 ambient-glow-button transition-all duration-300 flex items-center justify-center gap-2.5 active:scale-[0.99]"
                        >
                            <div class="absolute inset-0 w-1/2 h-full bg-white/20 skew-x-12 -translate-x-full group-hover:translate-x-[300%] transition-transform duration-1000 ease-out"></div>
                            <span class="relative z-10 text-sm tracking-widest font-extrabold">XÁC NHẬN & ĐĂNG KÝ</span>
                            <svg class="relative z-10 w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>

                </form>

                <!-- Khu vực Gửi lại mã OTP & Đổi thông tin -->
                <div class="mt-6 pt-5 border-t border-white/10 flex flex-col items-center gap-3 text-sm">
                    <div class="flex items-center gap-1.5 text-gray-400">
                        <span>Chưa nhận được mã?</span>
                        <form method="POST" action="{{ route('register.otp.resend') }}" class="inline">
                            @csrf
                            <button 
                                type="submit" 
                                id="resendBtn" 
                                class="text-red-400 hover:text-red-300 font-semibold hover:underline cursor-pointer transition-colors"
                            >
                                Gửi lại mã OTP
                            </button>
                        </form>
                    </div>

                    <a href="{{ route('register.otp.cancel') }}" class="text-xs text-gray-500 hover:text-gray-300 transition-colors">
                        ← Nhập lại email hoặc thông tin khác
                    </a>
                </div>

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
        <!-- NỬA BÊN PHẢI: HERO BANNER ĐIỆN ẢNH (HCTV)      -->
        <!-- ============================================== -->
        <div class="hidden lg:block lg:w-1/2 xl:w-[54%] min-h-screen relative overflow-hidden bg-black">
            
            <div 
                class="absolute inset-0 w-full h-full bg-cover bg-center bg-no-repeat transition-transform duration-700 hover:scale-105"
                style="background-image: url('{{ asset('images/banner_register.png') }}');"
            ></div>

            <div class="absolute inset-0 hero-banner-mask"></div>

            <div class="pointer-events-none absolute -top-24 -right-24 w-96 h-96 bg-red-600/20 rounded-full blur-[120px]"></div>
            <div class="pointer-events-none absolute bottom-0 right-1/4 w-[500px] h-60 bg-red-600/15 rounded-full blur-[100px]"></div>

            <div class="relative z-10 h-full flex flex-col justify-between p-10 xl:p-14">
                
                <div class="flex justify-end">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass-panel text-xs text-white/90 shadow-xl border border-white/10">
                        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <span class="font-semibold tracking-wide uppercase">BẢO MẬT TÀI KHOẢN HCTV</span>
                    </div>
                </div>

                <div class="max-w-xl">
                    <div class="glass-panel p-6 sm:p-8 rounded-3xl border border-white/15 ambient-glow-red">
                        <div class="flex items-center gap-2.5 mb-3">
                            <span class="px-2.5 py-1 rounded-md bg-[#e50914] text-white text-[11px] font-extrabold uppercase tracking-widest">
                                BẢO MẬT 2 LỚP
                            </span>
                            <span class="text-xs text-gray-300 font-medium flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                Kích hoạt tài khoản an toàn
                            </span>
                        </div>

                        <h2 class="text-2xl sm:text-3xl font-serif font-black text-white leading-snug drop-shadow-md">
                            Chỉ còn một bước nữa thôi
                        </h2>

                        <p class="text-gray-300 text-xs sm:text-sm mt-2.5 leading-relaxed">
                            Nhập mã xác thực để kích hoạt tài khoản chính chủ. Thông tin của bạn sẽ được bảo vệ tuyệt đối theo chính sách bảo mật của rạp HCTV.
                        </p>

                        <div class="grid grid-cols-3 gap-2.5 mt-5 pt-4 border-t border-white/10">
                            <div class="text-center p-2 rounded-xl bg-white/[0.04] border border-white/5">
                                <div class="text-[#e50914] font-bold text-sm sm:text-base">MÃ OTP</div>
                                <div class="text-[10px] text-gray-400 uppercase tracking-wider mt-0.5">6 Chữ Số</div>
                            </div>
                            <div class="text-center p-2 rounded-xl bg-white/[0.04] border border-white/5">
                                <div class="text-amber-400 font-bold text-sm sm:text-base">10 PHÚT</div>
                                <div class="text-[10px] text-gray-400 uppercase tracking-wider mt-0.5">Thời Lượng Mã</div>
                            </div>
                            <div class="text-center p-2 rounded-xl bg-white/[0.04] border border-white/5">
                                <div class="text-white font-bold text-sm sm:text-base">MIỄN PHÍ</div>
                                <div class="text-[10px] text-gray-400 uppercase tracking-wider mt-0.5">Xác Thực Ngay</div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Script điều khiển 6 ô số OTP (Auto-advance, Backspace, Paste) -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const inputs = Array.from(document.querySelectorAll('.otp-input'));
            const fullOtpInput = document.getElementById('fullOtpInput');
            const otpForm = document.getElementById('otpForm');

            inputs.forEach((input, index) => {
                // Nhập số -> tự động nhảy sang ô tiếp theo
                input.addEventListener('input', (e) => {
                    const val = e.target.value;
                    // Chỉ giữ số
                    input.value = val.replace(/[^0-9]/g, '');

                    if (input.value.length === 1 && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                    syncFullOtp();
                });

                // Phím Backspace -> nhảy lùi
                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && !input.value && index > 0) {
                        inputs[index - 1].focus();
                    }
                });

                // Xử lý Paste (dán cả mã 6 số cùng lúc)
                input.addEventListener('paste', (e) => {
                    e.preventDefault();
                    const pasteData = (e.clipboardData || window.clipboardData).getData('text').trim().replace(/[^0-9]/g, '');
                    if (pasteData) {
                        const digits = pasteData.slice(0, 6).split('');
                        digits.forEach((digit, i) => {
                            if (inputs[i]) {
                                inputs[i].value = digit;
                            }
                        });
                        const nextFocus = Math.min(digits.length, inputs.length - 1);
                        inputs[nextFocus].focus();
                        syncFullOtp();

                        // Nếu dán đủ 6 số -> có thể tự động submit hoặc chờ user bấm
                        if (digits.length === 6) {
                            // Tự động focus nút xác nhận
                            document.getElementById('verifyButton').focus();
                        }
                    }
                });
            });

            function syncFullOtp() {
                const combined = inputs.map(i => i.value).join('');
                fullOtpInput.value = combined;
            }

            otpForm.addEventListener('submit', () => {
                syncFullOtp();
            });

            // Focus ô đầu tiên
            if (inputs[0]) {
                inputs[0].focus();
            }
        });
    </script>

</body>
</html>
