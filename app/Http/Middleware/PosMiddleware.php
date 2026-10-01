<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PosMiddleware
{
    /**
     * Handle an incoming request.
     * Only Staff and Admin can access the POS ticket sales counter.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if (method_exists($user, 'canAccessPos') && $user->canAccessPos()) {
            return $next($request);
        }

        if (in_array($user->role, ['admin', 'staff'])) {
            return $next($request);
        }

        return redirect('/');
    }
}
