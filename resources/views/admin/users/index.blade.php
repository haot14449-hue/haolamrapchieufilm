@extends('admin.layouts.app')

@section('title', 'Quản lý Tài Khoản - Admin HCTV')

@section('content')
<div x-data="accountManagement()">
    <!-- Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Quản Lý Tài Khoản
            </h1>
            <p class="text-sm text-gray-500 mt-1">Quản lý danh sách nhân viên rạp, phân quyền quản trị và tài khoản khách hàng</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.create', ['role' => 'staff']) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                Thêm Nhân Viên Mới
            </a>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Total Users -->
        <a href="{{ route('admin.users.index') }}" class="p-4 bg-white rounded-xl border {{ empty($roleFilter) ? 'border-indigo-400 ring-2 ring-indigo-50 shadow-sm' : 'border-gray-200 hover:border-gray-300' }} transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng tài khoản</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalCount) }}</p>
                </div>
                <div class="w-11 h-11 bg-gray-100 rounded-xl flex items-center justify-center text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <span class="text-xs text-indigo-600 font-medium mt-2 inline-block">Xem tất cả &rarr;</span>
        </a>

        <!-- Staff -->
        <a href="{{ route('admin.users.index', ['role' => 'staff']) }}" class="p-4 bg-white rounded-xl border {{ $roleFilter === 'staff' ? 'border-emerald-500 ring-2 ring-emerald-50 shadow-sm' : 'border-gray-200 hover:border-gray-300' }} transition">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-1.5">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Nhân viên rạp</p>
                        <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                    </div>
                    <p class="text-2xl font-bold text-emerald-600 mt-1">{{ number_format($staffCount) }}</p>
                </div>
                <div class="w-11 h-11 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <span class="text-xs text-emerald-600 font-medium mt-2 inline-block">Lọc danh sách &rarr;</span>
        </a>

        <!-- Admins -->
        <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" class="p-4 bg-white rounded-xl border {{ $roleFilter === 'admin' ? 'border-indigo-500 ring-2 ring-indigo-50 shadow-sm' : 'border-gray-200 hover:border-gray-300' }} transition">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-1.5">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Quản trị viên</p>
                        <span class="inline-block w-2 h-2 rounded-full bg-indigo-500"></span>
                    </div>
                    <p class="text-2xl font-bold text-indigo-600 mt-1">{{ number_format($adminCount) }}</p>
                </div>
                <div class="w-11 h-11 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
            </div>
            <span class="text-xs text-indigo-600 font-medium mt-2 inline-block">Lọc danh sách &rarr;</span>
        </a>

        <!-- Customers -->
        <a href="{{ route('admin.users.index', ['role' => 'customer']) }}" class="p-4 bg-white rounded-xl border {{ $roleFilter === 'customer' ? 'border-blue-500 ring-2 ring-blue-50 shadow-sm' : 'border-gray-200 hover:border-gray-300' }} transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Khách hàng / TV</p>
                    <p class="text-2xl font-bold text-blue-600 mt-1">{{ number_format($customerCount) }}</p>
                </div>
                <div class="w-11 h-11 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>
            <span class="text-xs text-blue-600 font-medium mt-2 inline-block">Lọc danh sách &rarr;</span>
        </a>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm mb-6 flex flex-col md:flex-row gap-4 justify-between items-center">
        <!-- Filter Tabs -->
        <div class="flex flex-wrap items-center gap-1.5 w-full md:w-auto">
            <a href="{{ route('admin.users.index', ['search' => $search]) }}" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ empty($roleFilter) ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Tất cả ({{ $totalCount }})
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'staff', 'search' => $search]) }}" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors flex items-center gap-1.5 {{ $roleFilter === 'staff' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                <span class="w-2 h-2 rounded-full {{ $roleFilter === 'staff' ? 'bg-white' : 'bg-emerald-500' }}"></span>
                Nhân viên ({{ $staffCount }})
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'admin', 'search' => $search]) }}" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors flex items-center gap-1.5 {{ $roleFilter === 'admin' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                <span class="w-2 h-2 rounded-full {{ $roleFilter === 'admin' ? 'bg-white' : 'bg-indigo-500' }}"></span>
                Quản trị viên ({{ $adminCount }})
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'customer', 'search' => $search]) }}" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors flex items-center gap-1.5 {{ $roleFilter === 'customer' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                <span class="w-2 h-2 rounded-full {{ $roleFilter === 'customer' ? 'bg-white' : 'bg-blue-500' }}"></span>
                Khách hàng ({{ $customerCount }})
            </a>
        </div>

        <!-- Search Form -->
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex items-center gap-2 w-full md:w-80">
            @if(!empty($roleFilter))
                <input type="hidden" name="role" value="{{ $roleFilter }}">
            @endif
            <div class="relative flex-1">
                <input type="text" name="search" value="{{ $search }}" placeholder="Tìm theo tên, email, SĐT..." class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 absolute left-3 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <button type="submit" class="px-3.5 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                Tìm
            </button>
            @if(!empty($search))
                <a href="{{ route('admin.users.index', ['role' => $roleFilter]) }}" class="px-2.5 py-2 text-gray-500 hover:text-gray-700 text-sm" title="Xóa tìm kiếm">✕</a>
            @endif
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50/80 text-gray-500 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3.5 font-semibold text-xs uppercase tracking-wider">Tài khoản / Họ Tên</th>
                        <th class="px-6 py-3.5 font-semibold text-xs uppercase tracking-wider">Thông Tin Liên Hệ</th>
                        <th class="px-6 py-3.5 font-semibold text-xs uppercase tracking-wider">Vai Trò</th>
                        <th class="px-6 py-3.5 font-semibold text-xs uppercase tracking-wider">Ngày Khởi Tạo</th>
                        <th class="px-6 py-3.5 font-semibold text-xs uppercase tracking-wider text-right">Thao Tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50/80 transition-colors {{ $user->id === auth()->id() ? 'bg-indigo-50/30' : '' }}">
                        <!-- Name & Avatar -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3.5">
                                <div class="relative w-10 h-10 rounded-full shrink-0 flex items-center justify-center font-bold text-sm shadow-sm border border-gray-200 {{ $user->role === 'admin' ? 'bg-gradient-to-tr from-indigo-600 to-purple-600 text-white' : ($user->role === 'staff' ? 'bg-gradient-to-tr from-emerald-600 to-teal-500 text-white' : 'bg-gray-200 text-gray-700') }}">
                                    @if(!empty($user->avatar_url))
                                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-full h-full rounded-full object-cover">
                                    @else
                                        {{ mb_substr($user->name, 0, 1) }}
                                    @endif
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-gray-900">{{ $user->name }}</span>
                                        @if($user->id === auth()->id())
                                            <span class="px-1.5 py-0.5 text-[10px] font-bold bg-indigo-100 text-indigo-700 rounded-md">Bạn</span>
                                        @endif
                                    </div>
                                    <span class="text-xs text-gray-400">ID: #{{ $user->id }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- Contact -->
                        <td class="px-6 py-4">
                            <div class="space-y-0.5">
                                <div class="flex items-center gap-1.5 text-gray-800 font-medium">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <span>{{ $user->email }}</span>
                                </div>
                                @if(!empty($user->phone))
                                <div class="flex items-center gap-1.5 text-xs text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <span>{{ $user->phone }}</span>
                                </div>
                                @endif
                            </div>
                        </td>

                        <!-- Role -->
                        <td class="px-6 py-4">
                            @if($user->role === 'admin')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-600"></span>
                                    Quản trị viên
                                </span>
                            @elseif($user->role === 'staff')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                    Nhân viên rạp
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                    Khách hàng
                                </span>
                            @endif
                        </td>

                        <!-- Date -->
                        <td class="px-6 py-4 text-gray-500 text-xs">
                            <span class="block text-gray-700 font-medium">{{ $user->created_at ? $user->created_at->format('d/m/Y') : 'N/A' }}</span>
                            <span class="text-gray-400">{{ $user->created_at ? $user->created_at->format('H:i') : '' }}</span>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <!-- Đổi mật khẩu nhanh (Key Icon) -->
                                <button type="button" 
                                        @click="openPasswordModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}')"
                                        class="p-2 text-amber-600 hover:text-amber-700 hover:bg-amber-50 rounded-lg transition-colors border border-transparent hover:border-amber-200" 
                                        title="Đổi mật khẩu nhanh">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                    </svg>
                                </button>

                                <!-- Chỉnh sửa thông tin (Edit) -->
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="p-2 text-indigo-600 hover:text-indigo-700 hover:bg-indigo-50 rounded-lg transition-colors border border-transparent hover:border-indigo-200" title="Chỉnh sửa thông tin">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                <!-- Xóa tài khoản (Delete) -->
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tài khoản {{ addslashes($user->name) }} ({{ addslashes($user->email) }})? Hành động này không thể hoàn tác.');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors border border-transparent hover:border-red-200" title="Xóa tài khoản">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                @else
                                    <span class="p-2 text-gray-300 cursor-not-allowed" title="Không thể xóa chính tài khoản đang đăng nhập">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                        </svg>
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <div class="max-w-sm mx-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <p class="text-gray-600 font-medium">Không tìm thấy tài khoản nào phù hợp.</p>
                                <p class="text-xs text-gray-400 mt-1">Thử thay đổi từ khóa tìm kiếm hoặc bỏ lọc vai trò.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="p-4 border-t border-gray-100 bg-gray-50/50">
            {{ $users->links() }}
        </div>
        @endif
    </div>

    <!-- Quick Password Reset Modal (Alpine.js) -->
    <div x-show="showPasswordModal" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">
        
        <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">
            <!-- Backdrop -->
            <div x-show="showPasswordModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="showPasswordModal = false"
                 class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal Content -->
            <div x-show="showPasswordModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                
                <form :action="passwordUpdateUrl" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="bg-white px-6 pt-6 pb-4">
                        <div class="flex items-start justify-between pb-3 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 border border-amber-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900" id="modal-title">Đổi Mật Khẩu Nhanh</h3>
                                    <p class="text-xs text-gray-500 mt-0.5">Đặt lại mật khẩu bảo mật cho tài khoản</p>
                                </div>
                            </div>
                            <button type="button" @click="showPasswordModal = false" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <!-- Target User Info -->
                        <div class="my-4 p-3.5 bg-gray-50 rounded-xl border border-gray-200 text-xs space-y-1">
                            <p class="text-gray-500">Tài khoản nhân viên/người dùng:</p>
                            <p class="font-bold text-sm text-gray-900" x-text="targetUserName"></p>
                            <p class="text-gray-600 font-mono" x-text="targetUserEmail"></p>
                        </div>

                        <!-- Password Inputs -->
                        <div class="space-y-4">
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label class="block text-xs font-semibold text-gray-700">Mật khẩu mới <span class="text-red-500">*</span></label>
                                    <button type="button" @click="generateRandomPassword()" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        Tạo ngẫu nhiên
                                    </button>
                                </div>
                                <div class="relative">
                                    <input :type="showPassword ? 'text' : 'password'" 
                                           name="password" 
                                           x-model="newPassword"
                                           required 
                                           minlength="6"
                                           placeholder="Tối thiểu 6 ký tự..."
                                           class="w-full px-3.5 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-mono">
                                    <button type="button" @click="showPassword = !showPassword" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                                        <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <svg x-show="showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Xác nhận mật khẩu mới <span class="text-red-500">*</span></label>
                                <input :type="showPassword ? 'text' : 'password'" 
                                       name="password_confirmation" 
                                       x-model="confirmPassword"
                                       required 
                                       minlength="6"
                                       placeholder="Nhập lại mật khẩu..."
                                       class="w-full px-3.5 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-mono">
                            </div>

                            <div x-show="generatedNotice" x-cloak class="p-2.5 bg-emerald-50 text-emerald-800 rounded-lg text-xs flex items-center justify-between border border-emerald-200">
                                <span>✓ Đã tạo và sao chép mật khẩu vào bộ nhớ đệm!</span>
                                <button type="button" @click="generatedNotice = false" class="text-emerald-700 font-bold ml-2">✕</button>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-end gap-2 border-t border-gray-100">
                        <button type="button" @click="showPasswordModal = false" class="px-4 py-2.5 text-xs font-semibold text-gray-700 hover:bg-gray-200 rounded-xl transition">
                            Hủy bỏ
                        </button>
                        <button type="submit" class="px-5 py-2.5 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow transition">
                            Lưu Mật Khẩu Mới
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function accountManagement() {
        return {
            showPasswordModal: false,
            targetUserId: null,
            targetUserName: '',
            targetUserEmail: '',
            passwordUpdateUrl: '',
            newPassword: '',
            confirmPassword: '',
            showPassword: true,
            generatedNotice: false,

            openPasswordModal(id, name, email) {
                this.targetUserId = id;
                this.targetUserName = name;
                this.targetUserEmail = email;
                this.passwordUpdateUrl = `{{ url('admin/users') }}/${id}/password`;
                this.newPassword = '';
                this.confirmPassword = '';
                this.generatedNotice = false;
                this.showPassword = true;
                this.showPasswordModal = true;
            },

            generateRandomPassword() {
                const chars = "abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789!@#$%";
                let pass = "";
                for (let i = 0; i < 10; i++) {
                    pass += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                this.newPassword = pass;
                this.confirmPassword = pass;
                this.showPassword = true;

                // Copy to clipboard
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(pass).then(() => {
                        this.generatedNotice = true;
                        setTimeout(() => { this.generatedNotice = false; }, 4000);
                    }).catch(() => {});
                }
            }
        };
    }
</script>
@endpush
@endsection
