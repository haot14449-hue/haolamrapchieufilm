@extends('layouts.app')

@section('title', 'Hồ Sơ Cá Nhân - HCTV')

@section('content')
<div class="pt-24 pb-12 bg-cinematic-dark min-h-screen">
    <div class="max-w-7xl mx-auto px-6 md:px-12 flex flex-col md:flex-row gap-8">
        
        <!-- Sidebar -->
        <div class="w-full md:w-64 shrink-0">
            <div class="bg-white/5 border border-white/10 rounded-xl p-6 sticky top-28 shadow-xl">
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
                        <p class="font-bold text-white truncate text-base">{{ auth()->user()->name }}</p>
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
                    <a href="{{ route('account.tickets') }}" class="block px-4 py-2.5 rounded text-gray-300 hover:bg-white/5 hover:text-white transition-colors flex items-center gap-2.5 text-sm">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                        Vé của tôi
                    </a>
                    <a href="{{ route('account.vouchers') }}" class="block px-4 py-2.5 rounded text-gray-300 hover:bg-white/5 hover:text-white transition-colors flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            <span>Ví voucher</span>
                        </div>
                        <span class="px-2 py-0.5 text-[10px] font-bold bg-cinematic-gold/20 text-cinematic-gold border border-cinematic-gold/40 rounded-full">
                            {{ auth()->user()->activeVouchersCount() }}
                        </span>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 rounded bg-white/10 text-white font-medium border-l-4 border-cinematic-red flex items-center gap-2.5 text-sm">
                        <svg class="w-4 h-4 text-cinematic-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Hồ sơ cá nhân
                    </a>
                    <a href="#passwordSection" class="block px-4 py-2.5 rounded text-gray-400 hover:bg-white/5 hover:text-white transition-colors flex items-center gap-2.5 text-sm">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Đổi mật khẩu
                    </a>
                    <a href="#pointsSection" class="block px-4 py-2.5 rounded text-gray-400 hover:bg-white/5 hover:text-white transition-colors flex items-center gap-2.5 text-sm">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Điểm tích lũy
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}" class="pt-6 border-t border-white/10 mt-6">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2.5 rounded text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-colors flex items-center gap-2.5 text-sm">
                            <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Đăng xuất
                        </button>
                    </form>
                </nav>
            </div>
        </div>

        <!-- Main Content Column -->
        <div class="flex-1 space-y-8">
            
            <!-- Page Header Banner -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/10 pb-6">
                <div>
                    <h1 class="text-3xl font-serif font-bold text-white">Hồ Sơ Cá Nhân</h1>
                    <p class="text-sm text-gray-400 mt-1">Quản lý thông tin tài khoản, ảnh đại diện, bảo mật và điểm thưởng HCTV Cinema</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="px-4 py-2 bg-gradient-to-r from-cinematic-gold/20 to-yellow-600/10 border border-cinematic-gold/30 rounded-xl flex items-center gap-2.5 shadow">
                        <span class="text-xl">💎</span>
                        <div>
                            <span class="text-[11px] text-gray-400 block leading-tight">Điểm tích lũy</span>
                            <span class="text-base font-bold text-cinematic-gold">{{ number_format(auth()->user()->points) }} điểm</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Flash Notifications -->
            @if(session('profile_success'))
                <div class="bg-green-500/20 border border-green-500/50 text-green-200 px-5 py-4 rounded-xl flex items-center gap-3 shadow-lg">
                    <svg class="w-6 h-6 text-green-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('profile_success') }}</span>
                </div>
            @endif

            @if(session('password_success'))
                <div class="bg-green-500/20 border border-green-500/50 text-green-200 px-5 py-4 rounded-xl flex items-center gap-3 shadow-lg">
                    <svg class="w-6 h-6 text-green-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('password_success') }}</span>
                </div>
            @endif

            @if(session('otp_sent'))
                <div class="bg-blue-500/20 border border-blue-500/50 text-blue-200 px-5 py-4 rounded-xl flex items-center gap-3 shadow-lg">
                    <svg class="w-6 h-6 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>{{ session('otp_sent') }}</span>
                </div>
            @endif

            @if(session('otp_error'))
                <div class="bg-red-500/20 border border-red-500/50 text-red-200 px-5 py-4 rounded-xl flex items-center gap-3 shadow-lg">
                    <svg class="w-6 h-6 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('otp_error') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-500/20 border border-red-500/50 text-red-200 px-5 py-4 rounded-xl shadow-lg">
                    <div class="flex items-center gap-2 font-bold mb-2 text-red-300">
                        <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span>Vui lòng kiểm tra lại thông tin:</span>
                    </div>
                    <ul class="list-disc list-inside text-xs space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- 1. Cập nhật thông tin cá nhân & Ảnh đại diện -->
            <div class="bg-white/5 border border-white/10 rounded-2xl p-6 md:p-8 shadow-xl">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-cinematic-red/20 text-cinematic-red flex items-center justify-center font-bold text-lg border border-cinematic-red/30">
                        👤
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">Thông Tin Cá Nhân & Ảnh Đại Diện</h2>
                        <p class="text-xs text-gray-400">Tải ảnh đại diện và cập nhật họ tên, số điện thoại liên hệ</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('patch')

                    <!-- Avatar Upload Area with Live Preview -->
                    <div class="flex flex-col sm:flex-row items-center gap-6 p-5 bg-black/30 rounded-xl border border-white/5">
                        <div class="relative shrink-0">
                            <!-- Avatar Preview Image -->
                            <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-cinematic-red/80 shadow-[0_0_20px_rgba(229,9,20,0.4)] bg-[#181a20] flex items-center justify-center">
                                <img id="avatarPreview" 
                                     src="{{ !empty(auth()->user()->avatar_url) ? auth()->user()->avatar_url : '' }}" 
                                     alt="Avatar" 
                                     class="{{ empty(auth()->user()->avatar_url) ? 'hidden' : '' }} w-full h-full object-cover">
                                <div id="avatarFallback" class="{{ !empty(auth()->user()->avatar_url) ? 'hidden' : '' }} w-full h-full bg-gradient-to-tr from-cinematic-red to-red-800 flex items-center justify-center text-3xl font-bold text-white">
                                    {{ mb_substr(auth()->user()->name, 0, 1) }}
                                </div>
                            </div>
                            <span class="absolute bottom-0 right-0 w-7 h-7 bg-cinematic-red rounded-full flex items-center justify-center text-white text-xs shadow border border-white/30 cursor-pointer pointer-events-none">
                                📷
                            </span>
                        </div>

                        <div class="flex-1 text-center sm:text-left space-y-2">
                            <h4 class="text-white font-semibold text-sm">Ảnh đại diện tài khoản</h4>
                            <p class="text-xs text-gray-400">Chọn bức ảnh đại diện đẹp nhất của bạn. Định dạng hỗ trợ: JPG, PNG, WEBP (Tối đa 3MB).</p>
                            
                            <input type="file" 
                                   name="avatar" 
                                   id="avatarInput" 
                                   accept="image/*" 
                                   class="hidden" 
                                   onchange="previewAvatar(event)">
                            
                            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 pt-1">
                                <button type="button" 
                                        onclick="document.getElementById('avatarInput').click()" 
                                        class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white text-xs font-semibold rounded-lg border border-white/20 transition flex items-center gap-1.5 cursor-pointer">
                                    <svg class="w-4 h-4 text-cinematic-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                    Chọn ảnh từ máy
                                </button>
                                <span id="fileNameDisplay" class="text-xs text-gray-400 italic"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Information Form Inputs -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name Input -->
                        <div>
                            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">
                                Họ và tên <span class="text-cinematic-red">*</span>
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $user->name) }}" 
                                   required 
                                   class="w-full bg-[#131722] border border-white/20 focus:border-cinematic-gold focus:ring-1 focus:ring-cinematic-gold text-white text-sm rounded-xl px-4 py-3 placeholder-gray-500 transition shadow-inner">
                        </div>

                        <!-- Phone Input -->
                        <div>
                            <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">
                                Số điện thoại liên hệ
                            </label>
                            <input type="text" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone', $user->phone) }}" 
                                   placeholder="VD: 0912345678"
                                   class="w-full bg-[#131722] border border-white/20 focus:border-cinematic-gold focus:ring-1 focus:ring-cinematic-gold text-white text-sm rounded-xl px-4 py-3 placeholder-gray-500 transition shadow-inner">
                        </div>
                    </div>

                    <!-- Email Display (Readonly) -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">
                            Địa chỉ Email đăng ký
                        </label>
                        <div class="relative">
                            <input type="email" 
                                   value="{{ $user->email }}" 
                                   readonly 
                                   disabled
                                   class="w-full bg-white/5 border border-white/10 text-gray-400 text-sm rounded-xl px-4 py-3 cursor-not-allowed">
                            <span class="absolute right-3 top-3 text-xs text-green-400 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Đã kích hoạt
                            </span>
                        </div>
                        <p class="text-[11px] text-gray-400 mt-1.5">Email là tài khoản định danh nhận mã vé xem phim và mã xác thực OTP bảo mật.</p>
                    </div>

                    <!-- Submit Profile Changes -->
                    <div class="pt-4 flex justify-end">
                        <button type="submit" 
                                class="px-6 py-3 bg-cinematic-red hover:bg-red-700 text-white font-bold rounded-xl shadow-[0_0_15px_rgba(229,9,20,0.4)] transition text-sm cursor-pointer flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Lưu Thay Đổi Thông Tin
                        </button>
                    </div>
                </form>
            </div>

            <!-- 2. Thay đổi mật khẩu (Gửi mã OTP về email đăng ký) -->
            <div id="passwordSection" class="bg-white/5 border border-white/10 rounded-2xl p-6 md:p-8 shadow-xl">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center font-bold text-lg border border-blue-500/30">
                        🔑
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">Thay Đổi Mật Khẩu</h2>
                        <p class="text-xs text-gray-400">Bảo mật tài khoản bằng mã xác thực OTP gửi về email đăng ký của bạn</p>
                    </div>
                </div>

                <!-- Step 1: Send OTP to Email Banner & Action -->
                <div class="p-5 bg-black/40 rounded-xl border border-white/10 mb-6 space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <p class="text-sm text-gray-200 font-semibold flex items-center gap-2">
                                <span>📧</span> Email nhận mã OTP: <span class="text-cinematic-gold font-bold">{{ $user->email }}</span>
                            </p>
                            <p class="text-xs text-gray-400 mt-1">Hệ thống sẽ gửi mã số gồm 6 chữ số có hiệu lực trong 10 phút để xác minh chính chủ.</p>
                        </div>
                        
                        <div>
                            <button type="button" 
                                    id="btnSendPasswordOtp" 
                                    onclick="sendPasswordOtpAjax()" 
                                    class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-[0_0_15px_rgba(37,99,235,0.4)] transition flex items-center gap-2 cursor-pointer whitespace-nowrap">
                                <svg id="sendOtpSpinner" class="hidden animate-spin h-3.5 w-3.5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span id="sendOtpBtnText">Gửi mã xác nhận về Email</span>
                            </button>
                        </div>
                    </div>

                    <!-- Ajax Status Alert Box -->
                    <div id="otpAjaxStatus" class="hidden text-xs p-3 rounded-lg border"></div>
                </div>

                <!-- Step 2: Form to Enter OTP and New Password -->
                <form method="POST" action="{{ route('profile.password.update_otp') }}" class="space-y-6">
                    @csrf

                    <!-- 6-digit OTP Input -->
                    <div>
                        <label for="otp" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">
                            Mã xác thực OTP (6 chữ số) <span class="text-cinematic-red">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   name="otp" 
                                   id="otp" 
                                   maxlength="6" 
                                   autocomplete="one-time-code"
                                   placeholder="Nhập 6 số được gửi về email..." 
                                   required 
                                   class="w-full bg-[#131722] border border-white/20 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-white font-mono text-base tracking-widest rounded-xl px-4 py-3 placeholder-gray-500 shadow-inner">
                            <span class="absolute right-4 top-3.5 text-xs text-gray-400">⏱️ Hiệu lực 10 phút</span>
                        </div>
                    </div>

                    <!-- New Password Inputs -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">
                                Mật khẩu mới <span class="text-cinematic-red">*</span>
                            </label>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   placeholder="Tối thiểu 8 ký tự..." 
                                   required 
                                   class="w-full bg-[#131722] border border-white/20 focus:border-cinematic-gold focus:ring-1 focus:ring-cinematic-gold text-white text-sm rounded-xl px-4 py-3 placeholder-gray-500 shadow-inner">
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">
                                Nhập lại mật khẩu mới <span class="text-cinematic-red">*</span>
                            </label>
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation" 
                                   placeholder="Nhập lại mật khẩu mới..." 
                                   required 
                                   class="w-full bg-[#131722] border border-white/20 focus:border-cinematic-gold focus:ring-1 focus:ring-cinematic-gold text-white text-sm rounded-xl px-4 py-3 placeholder-gray-500 shadow-inner">
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end">
                        <button type="submit" 
                                class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-[0_0_15px_rgba(37,99,235,0.4)] transition text-sm cursor-pointer flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            Xác Nhận Đổi Mật Khẩu
                        </button>
                    </div>
                </form>
            </div>

            <!-- 3. Thông tin Điểm tích lũy thành viên (HCTV Rewards) -->
            <div id="pointsSection" class="bg-white/5 border border-white/10 rounded-2xl p-6 md:p-8 shadow-xl">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-cinematic-gold/20 text-cinematic-gold flex items-center justify-center font-bold text-lg border border-cinematic-gold/30">
                        💎
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">Chính Sách Điểm Thưởng HCTV</h2>
                        <p class="text-xs text-gray-400">Tích lũy điểm khi xem phim và đổi lấy ưu đãi giảm giá</p>
                    </div>
                </div>

                <!-- 3 Stat Blocks -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                    <div class="p-4 bg-black/40 rounded-xl border border-white/5 text-center">
                        <span class="text-xs text-gray-400 block mb-1">Điểm tích lũy hiện có</span>
                        <span class="text-2xl font-black text-cinematic-gold">{{ number_format(auth()->user()->points) }}</span>
                        <span class="text-xs text-gray-400 block mt-1">điểm</span>
                    </div>
                    <div class="p-4 bg-black/40 rounded-xl border border-white/5 text-center">
                        <span class="text-xs text-gray-400 block mb-1">Giá trị quy đổi</span>
                        <span class="text-2xl font-black text-green-400">{{ number_format(auth()->user()->points * 1000, 0, ',', '.') }}</span>
                        <span class="text-xs text-gray-400 block mt-1">VNĐ</span>
                    </div>
                    <div class="p-4 bg-black/40 rounded-xl border border-white/5 text-center">
                        <span class="text-xs text-gray-400 block mb-1">Tổng vé đã đặt</span>
                        <span class="text-2xl font-black text-white">{{ $totalTickets ?? 0 }}</span>
                        <span class="text-xs text-gray-400 block mt-1">vé xem phim</span>
                    </div>
                </div>

                <!-- Points Rules Detailed Guide -->
                <div class="p-5 bg-gradient-to-r from-yellow-500/10 via-black/40 to-transparent rounded-xl border border-yellow-500/20 space-y-3 text-xs text-gray-300">
                    <div class="flex items-start gap-2.5">
                        <span class="text-base text-cinematic-gold">🎟️</span>
                        <div>
                            <strong class="text-white block text-sm">Cơ chế tích điểm tự động:</strong>
                            Mỗi vé xem phim đặt và thanh toán thành công, hệ thống tự động cộng <strong class="text-cinematic-gold">+10 điểm (= 10.000 đ)</strong> vào tài khoản của bạn.
                        </div>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <span class="text-base text-green-400">💳</span>
                        <div>
                            <strong class="text-white block text-sm">Sử dụng điểm khi thanh toán:</strong>
                            Tại trang thanh toán vé xem phim, bạn có thể nhập số điểm tích lũy muốn trừ trực tiếp vào tổng tiền hóa đơn (tỷ lệ quy đổi: 1 điểm = 1.000 đ). Nếu điểm tích lũy đủ lớn, bạn có thể đổi lấy vé 0đ hoàn toàn miễn phí!
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <a href="{{ route('movies.index') }}" class="px-5 py-2.5 bg-cinematic-gold hover:bg-yellow-500 text-gray-950 font-bold rounded-xl shadow transition text-xs flex items-center gap-1.5">
                        <span>🎬</span> Đặt vé tích điểm ngay
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Scripts for Avatar Preview and OTP Sending -->
<script>
function previewAvatar(event) {
    const input = event.target;
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Show file name
        document.getElementById('fileNameDisplay').textContent = file.name;
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('avatarPreview');
            const fallback = document.getElementById('avatarFallback');
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            if (fallback) fallback.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }
}

// Ajax send password OTP with countdown
let otpCooldownTimer = null;
function sendPasswordOtpAjax() {
    const btn = document.getElementById('btnSendPasswordOtp');
    const btnText = document.getElementById('sendOtpBtnText');
    const spinner = document.getElementById('sendOtpSpinner');
    const statusBox = document.getElementById('otpAjaxStatus');

    btn.disabled = true;
    spinner.classList.remove('hidden');
    btnText.textContent = 'Đang gửi mã...';

    fetch("{{ route('profile.password.send_otp') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json"
        }
    })
    .then(res => res.json().then(data => ({ status: res.status, body: data })))
    .then(({ status, body }) => {
        spinner.classList.add('hidden');
        statusBox.classList.remove('hidden');

        if (status === 200 && body.success) {
            statusBox.className = "text-xs p-3 rounded-lg border bg-green-500/10 border-green-500/40 text-green-300";
            statusBox.innerHTML = `<strong>Thành công:</strong> ${body.message}`;
            document.getElementById('otp').focus();

            // Start 60s cooldown timer
            let seconds = 60;
            btnText.textContent = `Gửi lại sau (${seconds}s)`;
            otpCooldownTimer = setInterval(() => {
                seconds--;
                if (seconds <= 0) {
                    clearInterval(otpCooldownTimer);
                    btn.disabled = false;
                    btnText.textContent = 'Gửi lại mã OTP';
                } else {
                    btnText.textContent = `Gửi lại sau (${seconds}s)`;
                }
            }, 1000);
        } else {
            statusBox.className = "text-xs p-3 rounded-lg border bg-red-500/10 border-red-500/40 text-red-300";
            statusBox.innerHTML = `<strong>Lỗi:</strong> ${body.message || 'Không thể gửi mã xác thực. Vui lòng thử lại sau.'}`;
            btn.disabled = false;
            btnText.textContent = 'Gửi lại mã OTP';
        }
    })
    .catch(err => {
        spinner.classList.add('hidden');
        btn.disabled = false;
        btnText.textContent = 'Gửi lại mã OTP';
        statusBox.classList.remove('hidden');
        statusBox.className = "text-xs p-3 rounded-lg border bg-red-500/10 border-red-500/40 text-red-300";
        statusBox.innerHTML = '<strong>Lỗi kết nối:</strong> Không thể kết nối tới máy chủ. Vui lòng kiểm tra lại đường truyền.';
    });
}
</script>
@endsection
