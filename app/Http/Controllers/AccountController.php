<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Promotion;
use App\Models\UserVoucher;
use Carbon\Carbon;

class AccountController extends Controller
{
    /**
     * Display the authenticated user's ticket booking history.
     */
    public function tickets()
    {
        Booking::cleanupExpired();

        $bookings = Booking::where('user_id', auth()->id())
            ->with(['showtime.movie', 'showtime.room.cinema', 'tickets.seat'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('account.tickets', compact('bookings'));
    }

    /**
     * Display the user's Voucher Wallet (Ví Voucher).
     */
    public function vouchers(Request $request)
    {
        $user = auth()->user();

        // 1. User's saved vouchers
        $savedVouchers = UserVoucher::where('user_id', $user->id)
            ->with('promotion')
            ->orderBy('is_used', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Active and available vouchers
        $availableVouchers = $savedVouchers->filter(function ($uv) {
            return !$uv->is_used && $uv->promotion && (!$uv->promotion->end_date || Carbon::parse($uv->promotion->end_date)->endOfDay()->isFuture());
        });

        // Used or expired vouchers
        $usedOrExpiredVouchers = $savedVouchers->filter(function ($uv) {
            return $uv->is_used || ($uv->promotion && $uv->promotion->end_date && Carbon::parse($uv->promotion->end_date)->endOfDay()->isPast());
        });

        // 2. All available website promotions (Kho ưu đãi của Web)
        $allPromotions = Promotion::active()
            ->orderBy('end_date', 'asc')
            ->get();

        // Saved promotion IDs array for quick lookup
        $savedPromotionIds = $savedVouchers->pluck('promotion_id')->toArray();

        return view('account.vouchers', compact(
            'user',
            'savedVouchers',
            'availableVouchers',
            'usedOrExpiredVouchers',
            'allPromotions',
            'savedPromotionIds'
        ));
    }

    /**
     * Save a promotion / voucher into user's wallet.
     */
    public function saveVoucher(Request $request)
    {
        $user = auth()->user();
        $promo = null;

        // Find promo by ID or by Promo Code
        if ($request->filled('promotion_id')) {
            $promo = Promotion::find($request->promotion_id);
        } elseif ($request->filled('code')) {
            $code = strtoupper(trim($request->code));
            $promo = Promotion::where('code', $code)->first();
        }

        if (!$promo) {
            $msg = 'Mã ưu đãi không tồn tại hoặc đã hết hiệu lực.';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 404);
            }
            return back()->with('error', $msg);
        }

        // Check if expired or not yet started
        if ($promo->isExpired()) {
            $msg = 'Ưu đãi này đã hết hạn sử dụng.';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 400);
            }
            return back()->with('error', $msg);
        }

        if ($promo->isUpcoming()) {
            $msg = 'Ưu đãi này chưa đến ngày bắt đầu áp dụng.';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 400);
            }
            return back()->with('error', $msg);
        }

        // Check if already in user wallet
        $existing = UserVoucher::where('user_id', $user->id)
            ->where('promotion_id', $promo->id)
            ->first();

        if ($existing) {
            if ($existing->is_used) {
                $msg = 'Bạn đã từng sử dụng mã voucher này rồi.';
            } else {
                $msg = 'Voucher này đã có sẵn trong Ví của bạn!';
            }
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'already_saved' => true, 'message' => $msg], 200);
            }
            return back()->with('info', $msg);
        }

        // Check points requirement if applicable
        if ($promo->points_required > 0) {
            if ($user->points < $promo->points_required) {
                $msg = "Bạn cần tích lũy tối thiểu {$promo->points_required} điểm để đổi voucher này (Hiện có: {$user->points} điểm).";
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => $msg], 400);
                }
                return back()->with('error', $msg);
            }

            // Deduct points
            $user->decrement('points', $promo->points_required);
        }

        // Create wallet record
        $userVoucher = UserVoucher::create([
            'user_id' => $user->id,
            'promotion_id' => $promo->id,
            'is_used' => false,
            'saved_at' => now(),
        ]);

        $successMsg = "Đã lưu thành công voucher '{$promo->title}' vào Ví voucher của bạn!";

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMsg,
                'voucher' => $promo,
                'user_voucher_id' => $userVoucher->id,
                'active_count' => $user->fresh()->activeVouchersCount(),
                'user_points' => $user->fresh()->points,
            ]);
        }

        return back()->with('success', $successMsg);
    }

    /**
     * Remove a saved voucher from wallet.
     */
    public function removeVoucher(Request $request, $id)
    {
        $user = auth()->user();
        $userVoucher = UserVoucher::where('user_id', $user->id)->findOrFail($id);

        if ($userVoucher->is_used) {
            $msg = 'Voucher đã qua sử dụng không thể xóa khỏi lịch sử.';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 400);
            }
            return back()->with('error', $msg);
        }

        $title = $userVoucher->promotion ? $userVoucher->promotion->title : 'Voucher';
        $userVoucher->delete();

        $successMsg = "Đã xóa voucher '{$title}' khỏi ví của bạn.";

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMsg,
                'active_count' => $user->fresh()->activeVouchersCount(),
            ]);
        }

        return back()->with('success', $successMsg);
    }
}
