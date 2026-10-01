<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Authorize that only users with role 'admin' can manage accounts.
     */
    protected function authorizeAdmin(): void
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Bạn không có quyền truy cập chức năng Quản lý Tài khoản. Chỉ Quản trị viên (Admin) mới có quyền này.');
        }
    }

    /**
     * Display a listing of the accounts.
     */
    public function index(Request $request)
    {
        $this->authorizeAdmin();

        $query = User::query();

        // Search by keyword (name, email, phone)
        if ($search = trim($request->input('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by role
        $roleFilter = $request->input('role', '');
        if ($roleFilter === 'staff') {
            $query->where('role', 'staff');
        } elseif ($roleFilter === 'admin') {
            $query->where('role', 'admin');
        } elseif ($roleFilter === 'customer') {
            $query->whereIn('role', ['customer', 'user']);
        }

        // Stats summary
        $totalCount = User::count();
        $staffCount = User::where('role', 'staff')->count();
        $adminCount = User::where('role', 'admin')->count();
        $customerCount = User::whereIn('role', ['customer', 'user'])->count();

        $users = $query->orderBy('id', 'desc')->paginate(12)->withQueryString();

        return view('admin.users.index', compact(
            'users',
            'totalCount',
            'staffCount',
            'adminCount',
            'customerCount',
            'roleFilter',
            'search'
        ));
    }

    /**
     * Show the form for creating a new account (staff/admin/customer).
     */
    public function create(Request $request)
    {
        $this->authorizeAdmin();

        $defaultRole = $request->input('role', 'staff');
        return view('admin.users.create', compact('defaultRole'));
    }

    /**
     * Store a newly created account in storage.
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', Rule::in(['staff', 'admin', 'customer'])],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required' => 'Vui lòng nhập tên tài khoản / họ tên nhân viên.',
            'email.required' => 'Vui lòng nhập địa chỉ email đăng nhập.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'email.unique' => 'Email này đã tồn tại trong hệ thống. Vui lòng chọn email khác.',
            'role.required' => 'Vui lòng chọn vai trò cho tài khoản.',
            'role.in' => 'Vai trò không hợp lệ.',
            'password.required' => 'Vui lòng nhập mật khẩu khởi tạo.',
            'password.min' => 'Mật khẩu phải chứa ít nhất 6 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        $user = User::create([
            'name' => trim($validated['name']),
            'email' => strtolower(trim($validated['email'])),
            'phone' => !empty($validated['phone']) ? trim($validated['phone']) : null,
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(), // Admin created, mark verified
        ]);

        $roleText = match($user->role) {
            'staff' => 'Nhân viên',
            'admin' => 'Quản trị viên',
            default => 'Khách hàng',
        };

        return redirect()->route('admin.users.index')
            ->with('success', "Đã tạo tài khoản {$roleText} \"{$user->name}\" ({$user->email}) thành công!");
    }

    /**
     * Show the form for editing the specified account.
     */
    public function edit(User $user)
    {
        $this->authorizeAdmin();

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified account in storage.
     */
    public function update(Request $request, User $user)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', Rule::in(['staff', 'admin', 'customer'])],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required' => 'Vui lòng nhập tên tài khoản / họ tên.',
            'email.required' => 'Vui lòng nhập email đăng nhập.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'email.unique' => 'Email này đã được sử dụng bởi tài khoản khác.',
            'role.required' => 'Vui lòng chọn vai trò.',
            'password.min' => 'Mật khẩu mới phải chứa ít nhất 6 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu mới không khớp.',
        ]);

        // Safety: Do not let logged-in admin change their own role to non-admin
        if ($user->id === auth()->id() && $validated['role'] !== 'admin') {
            return back()->withInput()->with('error', 'Bạn không thể tự hạ cấp vai trò Quản trị viên của chính mình.');
        }

        $user->name = trim($validated['name']);
        $user->email = strtolower(trim($validated['email']));
        $user->phone = !empty($validated['phone']) ? trim($validated['phone']) : null;
        $user->role = $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', "Đã cập nhật thông tin tài khoản \"{$user->name}\" thành công!");
    }

    /**
     * Quick password update for an account.
     */
    public function updatePassword(Request $request, User $user)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.min' => 'Mật khẩu mới phải có tối thiểu 6 ký tự.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
        ]);

        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()->back()
            ->with('success', "Đã thay đổi mật khẩu cho tài khoản \"{$user->name}\" ({$user->email}) thành công!");
    }

    /**
     * Remove the specified account from storage.
     */
    public function destroy(User $user)
    {
        $this->authorizeAdmin();

        // Safety: Admin cannot delete themselves
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Không thể xóa tài khoản bạn đang đăng nhập!');
        }

        // Safety: Check if this is the last admin
        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return redirect()->back()->with('error', 'Không thể xóa Quản trị viên duy nhất còn lại của hệ thống!');
        }

        $userName = $user->name;
        $userEmail = $user->email;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Đã xóa tài khoản \"{$userName}\" ({$userEmail}) khỏi hệ thống!");
    }
}
