@extends('admin.layouts.app')

@section('title', 'Thêm Tài Khoản Mới - Admin HCTV')

@section('content')
<div class="max-w-4xl mx-auto" x-data="createAccountForm('{{ old('role', $defaultRole ?? 'staff') }}')">
    <!-- Header -->
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('admin.users.index') }}" class="p-2.5 bg-white rounded-xl shadow-sm hover:bg-gray-50 text-gray-600 hover:text-gray-900 border border-gray-200 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Thêm Tài Khoản Mới</h1>
            <p class="text-sm text-gray-500">Khởi tạo tài khoản nhân viên phục vụ bán vé, soát vé hoặc phân quyền quản trị</p>
        </div>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <form action="{{ route('admin.users.store') }}" method="POST" class="p-6 md:p-8 space-y-6">
            @csrf

            <!-- Section 1: Thông tin cơ bản -->
            <div>
                <h2 class="text-base font-bold text-gray-900 flex items-center gap-2 pb-3 border-b border-gray-100">
                    <span class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-bold">1</span>
                    Thông Tin Tài Khoản
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                    <!-- Tên tài khoản / Họ tên -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                            Tên Tài Khoản / Họ Tên Nhân Viên <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required 
                               placeholder="Ví dụ: Nguyễn Văn Hào (Nhân viên Quầy vé)"
                               class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email đăng nhập -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                            Email Đăng Nhập <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   placeholder="nhanvien@hctv.com"
                                   class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition font-mono">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 absolute left-3.5 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                            </svg>
                        </div>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Số điện thoại -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                            Số Điện Thoại (Tùy chọn)
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   name="phone" 
                                   value="{{ old('phone') }}" 
                                   placeholder="0912345678"
                                   class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 absolute left-3.5 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        @error('phone')
                            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 2: Phân quyền vai trò -->
            <div>
                <h2 class="text-base font-bold text-gray-900 flex items-center gap-2 pb-3 border-b border-gray-100">
                    <span class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-bold">2</span>
                    Vai Trò & Quyền Hạn
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-5">
                    <!-- Staff (Recommended) -->
                    <label class="relative flex flex-col p-4 rounded-xl border-2 cursor-pointer transition-all"
                           :class="selectedRole === 'staff' ? 'border-emerald-500 bg-emerald-50/30 shadow-sm' : 'border-gray-200 hover:border-gray-300 bg-white'">
                        <input type="radio" name="role" value="staff" x-model="selectedRole" class="sr-only">
                        <div class="flex items-center justify-between mb-2">
                            <div class="w-9 h-9 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-md bg-emerald-100 text-emerald-800 uppercase">Khuyên dùng</span>
                        </div>
                        <span class="font-bold text-gray-900 text-sm">Nhân Viên Rạp</span>
                        <p class="text-xs text-gray-500 mt-1 leading-relaxed">Truy cập quản lý đơn hàng/vé, lịch chiếu, phim, bắp nước phục vụ khách.</p>
                        <div class="mt-3 flex items-center text-xs font-semibold" :class="selectedRole === 'staff' ? 'text-emerald-700' : 'text-gray-400'">
                            <span class="inline-block w-3.5 h-3.5 rounded-full border-2 mr-1.5 flex items-center justify-center" :class="selectedRole === 'staff' ? 'border-emerald-600 bg-emerald-600' : 'border-gray-300'"></span>
                            <span x-text="selectedRole === 'staff' ? 'Đã chọn' : 'Chọn vai trò'"></span>
                        </div>
                    </label>

                    <!-- Admin -->
                    <label class="relative flex flex-col p-4 rounded-xl border-2 cursor-pointer transition-all"
                           :class="selectedRole === 'admin' ? 'border-indigo-500 bg-indigo-50/30 shadow-sm' : 'border-gray-200 hover:border-gray-300 bg-white'">
                        <input type="radio" name="role" value="admin" x-model="selectedRole" class="sr-only">
                        <div class="flex items-center justify-between mb-2">
                            <div class="w-9 h-9 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-md bg-indigo-100 text-indigo-800 uppercase">Toàn quyền</span>
                        </div>
                        <span class="font-bold text-gray-900 text-sm">Quản Trị Viên</span>
                        <p class="text-xs text-gray-500 mt-1 leading-relaxed">Có toàn quyền quản trị rạp chiếu, doanh thu, thêm sửa tài khoản nhân viên.</p>
                        <div class="mt-3 flex items-center text-xs font-semibold" :class="selectedRole === 'admin' ? 'text-indigo-700' : 'text-gray-400'">
                            <span class="inline-block w-3.5 h-3.5 rounded-full border-2 mr-1.5 flex items-center justify-center" :class="selectedRole === 'admin' ? 'border-indigo-600 bg-indigo-600' : 'border-gray-300'"></span>
                            <span x-text="selectedRole === 'admin' ? 'Đã chọn' : 'Chọn vai trò'"></span>
                        </div>
                    </label>

                    <!-- Customer -->
                    <label class="relative flex flex-col p-4 rounded-xl border-2 cursor-pointer transition-all"
                           :class="selectedRole === 'customer' ? 'border-blue-500 bg-blue-50/30 shadow-sm' : 'border-gray-200 hover:border-gray-300 bg-white'">
                        <input type="radio" name="role" value="customer" x-model="selectedRole" class="sr-only">
                        <div class="flex items-center justify-between mb-2">
                            <div class="w-9 h-9 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-md bg-blue-100 text-blue-800 uppercase">Thành viên</span>
                        </div>
                        <span class="font-bold text-gray-900 text-sm">Khách Hàng</span>
                        <p class="text-xs text-gray-500 mt-1 leading-relaxed">Tài khoản thành viên xem phim thông thường, không truy cập bảng quản trị.</p>
                        <div class="mt-3 flex items-center text-xs font-semibold" :class="selectedRole === 'customer' ? 'text-blue-700' : 'text-gray-400'">
                            <span class="inline-block w-3.5 h-3.5 rounded-full border-2 mr-1.5 flex items-center justify-center" :class="selectedRole === 'customer' ? 'border-blue-600 bg-blue-600' : 'border-gray-300'"></span>
                            <span x-text="selectedRole === 'customer' ? 'Đã chọn' : 'Chọn vai trò'"></span>
                        </div>
                    </label>
                </div>
                @error('role')
                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Section 3: Thiết lập mật khẩu -->
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-bold">3</span>
                        Thiết Lập Mật Khẩu
                    </h2>
                    <button type="button" @click="generateRandomPassword()" class="px-3 py-1.5 text-xs font-semibold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 rounded-lg flex items-center gap-1.5 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Tạo mật khẩu ngẫu nhiên
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                    <!-- Password -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                            Mật Khẩu Khởi Tạo <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'" 
                                   name="password" 
                                   x-model="password"
                                   required 
                                   minlength="6"
                                   placeholder="Tối thiểu 6 ký tự..."
                                   class="w-full pl-4 pr-10 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-mono transition">
                            <button type="button" @click="showPassword = !showPassword" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                                <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                            Xác Nhận Mật Khẩu <span class="text-red-500">*</span>
                        </label>
                        <input :type="showPassword ? 'text' : 'password'" 
                               name="password_confirmation" 
                               x-model="confirmPassword"
                               required 
                               minlength="6"
                               placeholder="Nhập lại mật khẩu..."
                               class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-mono transition">
                    </div>
                </div>

                <div x-show="copyNotice" x-cloak class="mt-3 p-3 bg-emerald-50 text-emerald-800 rounded-xl text-xs flex items-center justify-between border border-emerald-200">
                    <span class="flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Mật khẩu ngẫu nhiên đã được tự động điền và sao chép vào bộ nhớ tạm!
                    </span>
                    <button type="button" @click="copyNotice = false" class="text-emerald-700 font-bold ml-2">✕</button>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="pt-6 border-t border-gray-100 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 text-center text-sm font-semibold text-gray-700 hover:bg-gray-100 rounded-xl transition">
                    Hủy bỏ
                </a>
                <button type="submit" class="px-6 py-2.5 text-center text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm hover:shadow transition-all duration-200 flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Tạo Tài Khoản
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function createAccountForm(initialRole) {
        return {
            selectedRole: initialRole || 'staff',
            password: '',
            confirmPassword: '',
            showPassword: true,
            copyNotice: false,

            generateRandomPassword() {
                const chars = "abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789!@#$%";
                let pass = "";
                for (let i = 0; i < 10; i++) {
                    pass += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                this.password = pass;
                this.confirmPassword = pass;
                this.showPassword = true;

                if (navigator.clipboard) {
                    navigator.clipboard.writeText(pass).then(() => {
                        this.copyNotice = true;
                        setTimeout(() => { this.copyNotice = false; }, 4000);
                    }).catch(() => {});
                }
            }
        };
    }
</script>
@endpush
@endsection
