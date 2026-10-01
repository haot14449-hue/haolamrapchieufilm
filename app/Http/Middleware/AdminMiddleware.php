<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     * Only Admin accounts can access the Admin management area.
     * Staff accounts CANNOT access admin (they are redirected to the POS counter).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Admin has full access to Admin area
        if (method_exists($user, 'isAdmin') ? $user->isAdmin() : ($user->role === 'admin')) {
            return $next($request);
        }

        // Staff accounts cannot access admin -> redirect to their dedicated POS counter
        if (method_exists($user, 'isStaff') ? $user->isStaff() : ($user->role === 'staff')) {
            return redirect()->route('pos.index')->with('error', 'Tài khoản nhân viên chỉ có quyền truy cập Quầy bán vé tại quầy (POS), không thể vào trang Quản trị Admin.');
        }

        // Regular customers or guests
        return redirect('/');
    }
}
