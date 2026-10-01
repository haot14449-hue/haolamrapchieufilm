<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Booking;

class AutoCleanupExpiredBookings
{
    /**
     * Handle an incoming request.
     * Automatically cancel bookings exceeding 5 minutes without payment and release seats.
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // For admin, booking and account routes, execute directly for real-time accuracy
            if ($request->is('admin*') || $request->is('booking*') || $request->is('account*')) {
                Booking::cleanupExpired();
            } else {
                // For general web routes, throttle execution to at most once every 5 seconds
                if (cache()->add('auto_cleanup_expired_bookings_lock', true, now()->addSeconds(5))) {
                    Booking::cleanupExpired();
                }
            }
        } catch (\Throwable $e) {
            // Silently ignore to avoid interrupting the request if DB is temporarily unreachable
        }

        return $next($request);
    }
}
