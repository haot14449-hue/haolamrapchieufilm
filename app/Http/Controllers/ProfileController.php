<?php

namespace App\Http\Controllers;

use App\Mail\PasswordChangeOtpMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile view with Dark Cinematic Theme.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        
        // Count user tickets and bookings for profile statistics
        $totalBookings = \App\Models\Booking::where('user_id', $user->id)->count();
        $totalTickets = \App\Models\Ticket::whereHas('booking', function ($q) use ($user) {
            $q->where('user_id', $user->id)->where('status', 'paid');
        })->count();

        return view('profile.edit', compact('user', 'totalBookings', 'totalTickets'));
    }

    /**
     * Update the user's profile information (Name, Phone, Avatar).
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'regex:/^[0-9]{9,12}$/'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:3072'],
        ];

        if ($request->has('email')) {
            $rules['email'] = [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                \Illuminate\Validation\Rule::unique(\App\Models\User::class)->ignore($user->id),
            ];
        }

        $request->validate($rules, [
            'name.required' => 'Vui lòng nhập họ và tên.',
            'phone.regex' => 'Số điện thoại không hợp lệ (phải từ 9 đến 12 chữ số).',
            'avatar.image' => 'File tải lên phải là hình ảnh hợp lệ.',
            'avatar.mimes' => 'Ảnh đại diện hỗ trợ các định dạng: JPEG, PNG, JPG, WEBP, GIF.',
            'avatar.max' => 'Dung lượng ảnh tối đa là 3MB.',
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
            'email.unique' => 'Địa chỉ email này đã được sử dụng.',
        ]);

        $user->name = $request->input('name');
        
        if ($request->has('phone')) {
            $user->phone = $request->input('phone');
        }

        if ($request->has('email') && $request->input('email') !== $user->email) {
            $user->email = $request->input('email');
            $user->email_verified_at = null;
        }

        // Handle Avatar File Upload
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $uploadDir = public_path('uploads/avatars');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Remove previous avatar file if exists locally
            if (!empty($user->avatar_url) && str_starts_with($user->avatar_url, '/uploads/avatars/')) {
                $oldPath = public_path(ltrim($user->avatar_url, '/'));
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $filename = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $filename);
            $user->avatar_url = '/uploads/avatars/' . $filename;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('profile_success', 'Cập nhật thông tin cá nhân và ảnh đại diện thành công!');
    }

    /**
     * Send OTP code to registered email for password change verification.
     */
    public function sendPasswordOtp(Request $request): JsonResponse|RedirectResponse
    {
        $user = $request->user();

        // Check cooldown in session (prevent spam within 60s)
        $existing = $request->session()->get('password_change_otp');
        if ($existing && isset($existing['sent_at']) && (now()->timestamp - $existing['sent_at'] < 50)) {
            $remaining = 50 - (now()->timestamp - $existing['sent_at']);
            $msg = "Vui lòng đợi {$remaining} giây trước khi yêu cầu gửi lại mã mới.";
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 429);
            }
            return back()->with('otp_error', $msg);
        }

        // Generate 6-digit OTP code
        $otp = (string) random_int(100000, 999999);

        // Store in session valid for 10 minutes
        $request->session()->put('password_change_otp', [
            'code' => $otp,
            'email' => $user->email,
            'expires_at' => now()->addMinutes(10)->timestamp,
            'sent_at' => now()->timestamp,
        ]);

        try {
            Mail::to($user->email)->send(new PasswordChangeOtpMail($otp, $user->name, 10));
            Log::info("Password change OTP sent to {$user->email}");
        } catch (\Throwable $e) {
            Log::error('Error sending password change OTP: ' . $e->getMessage());
            $errorMsg = 'Không thể gửi email mã xác thực. Vui lòng kiểm tra kết nối mạng hoặc thử lại sau.';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $errorMsg], 500);
            }
            return back()->with('otp_error', $errorMsg);
        }

        $successMsg = "Mã xác thực OTP 6 số đã được gửi thành công về email: {$user->email}. Mã có hiệu lực trong 10 phút.";
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMsg,
                'email' => $user->email,
                'expires_in' => 600,
            ]);
        }

        return back()->with('otp_sent', $successMsg);
    }

    /**
     * Verify OTP and change password.
     */
    public function updatePasswordWithOtp(Request $request): RedirectResponse
    {
        $user = $request->user();

        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'otp.required' => 'Vui lòng nhập mã xác thực OTP 6 chữ số.',
            'otp.size' => 'Mã xác thực OTP phải gồm đúng 6 chữ số.',
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.confirmed' => 'Xác nhận mật khẩu mới không trùng khớp.',
            'password.min' => 'Mật khẩu mới phải có tối thiểu 8 ký tự.',
        ]);

        $storedOtp = $request->session()->get('password_change_otp');

        if (!$storedOtp || !isset($storedOtp['code'])) {
            return back()->withErrors(['otp' => 'Bạn chưa yêu cầu gửi mã xác nhận. Vui lòng bấm "Gửi mã xác nhận về Email" trước.'])
                         ->withInput();
        }

        if (now()->timestamp > $storedOtp['expires_at']) {
            return back()->withErrors(['otp' => 'Mã OTP đã hết hiệu lực. Vui lòng bấm "Gửi lại mã" để nhận mã mới.'])
                         ->withInput();
        }

        if (trim($request->input('otp')) !== $storedOtp['code']) {
            return back()->withErrors(['otp' => 'Mã xác thực OTP không chính xác. Vui lòng kiểm tra lại email.'])
                         ->withInput();
        }

        // Update password
        $user->password = Hash::make($request->input('password'));
        $user->save();

        // Clear OTP from session
        $request->session()->forget('password_change_otp');

        return Redirect::route('profile.edit')->with('password_success', 'Đổi mật khẩu tài khoản thành công! Mật khẩu mới của bạn đã có hiệu lực.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
