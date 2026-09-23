<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpVerificationMail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     * Generates a 6-digit OTP and sends it to the user's email.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'name.required' => 'Vui lòng nhập họ và tên.',
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
            'email.unique' => 'Địa chỉ email này đã được sử dụng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.confirmed' => 'Xác nhận mật khẩu không trùng khớp.',
            'password.min' => 'Mật khẩu phải có tối thiểu 8 ký tự.',
        ]);

        // Tạo mã OTP ngẫu nhiên gồm 6 chữ số
        $otp = (string) random_int(100000, 999999);

        // Lưu thông tin đăng ký và OTP vào session tạm (hiệu lực 10 phút)
        $request->session()->put('register_pending', [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10)->timestamp,
        ]);

        // Gửi email OTP tới người dùng
        try {
            Mail::to($request->email)->send(new OtpVerificationMail($otp, $request->name, 10));
        } catch (\Throwable $e) {
            Log::error('Lỗi gửi email mã OTP HCTV: ' . $e->getMessage());
            return back()->withInput()->withErrors([
                'email' => 'Không thể gửi email mã OTP. Vui lòng kiểm tra lại địa chỉ email hoặc thử lại sau.'
            ]);
        }

        return redirect()->route('register.otp.notice');
    }

    /**
     * Display the OTP verification view.
     */
    public function showOtpForm(Request $request): View|RedirectResponse
    {
        $pending = $request->session()->get('register_pending');

        if (!$pending) {
            return redirect()->route('register')->withErrors([
                'email' => 'Phiên đăng ký đã hết hạn hoặc chưa khởi tạo. Vui lòng nhập lại thông tin.'
            ]);
        }

        return view('auth.verify-otp', [
            'email' => $pending['email'],
            'name' => $pending['name'],
            'expiresAt' => $pending['otp_expires_at'],
        ]);
    }

    /**
     * Handle OTP verification.
     */
    public function verifyOtp(Request $request): RedirectResponse
    {
        $pending = $request->session()->get('register_pending');

        if (!$pending) {
            return redirect()->route('register')->withErrors([
                'email' => 'Phiên đăng ký đã hết hạn. Vui lòng thực hiện lại.'
            ]);
        }

        // Lấy mã OTP (hỗ trợ cả trường 'otp' đơn hoặc 6 ô digit_1 -> digit_6)
        $inputOtp = $request->input('otp');
        if (!$inputOtp && $request->has('digit_1')) {
            $inputOtp = $request->input('digit_1') .
                        $request->input('digit_2') .
                        $request->input('digit_3') .
                        $request->input('digit_4') .
                        $request->input('digit_5') .
                        $request->input('digit_6');
        }
        $inputOtp = trim((string) $inputOtp);

        if (empty($inputOtp) || strlen($inputOtp) !== 6) {
            return back()->withErrors(['otp' => 'Vui lòng nhập đầy đủ mã xác thực gồm 6 chữ số.']);
        }

        // Kiểm tra thời hạn OTP
        if (now()->timestamp > $pending['otp_expires_at']) {
            return back()->withErrors(['otp' => 'Mã OTP đã hết hiệu lực. Vui lòng bấm "Gửi lại mã" để nhận mã mới.']);
        }

        // Kiểm tra tính chính xác của OTP
        if ($inputOtp !== $pending['otp']) {
            return back()->withErrors(['otp' => 'Mã OTP không chính xác. Vui lòng kiểm tra lại hộp thư email của bạn.']);
        }

        // Kiểm tra nếu email đã đăng ký trong lúc chờ OTP
        if (User::where('email', $pending['email'])->exists()) {
            $request->session()->forget('register_pending');
            return redirect()->route('login')->with('status', 'Email này đã được kích hoạt tài khoản. Vui lòng đăng nhập.');
        }

        // Tạo tài khoản người dùng chính thức
        $user = User::create([
            'name' => $pending['name'],
            'email' => $pending['email'],
            'password' => $pending['password'],
            'role' => 'user',
        ]);

        // Đánh dấu đã xác thực email
        $user->email_verified_at = now();
        $user->save();

        // Xóa dữ liệu tạm trong session
        $request->session()->forget('register_pending');

        event(new Registered($user));

        Auth::login($user);

        return redirect()->intended(route('home'))->with('success', 'Chào mừng thành viên mới! Bạn đã đăng ký tài khoản HCTV thành công.');
    }

    /**
     * Resend a new OTP code to the pending user's email.
     */
    public function resendOtp(Request $request): RedirectResponse
    {
        $pending = $request->session()->get('register_pending');

        if (!$pending) {
            return redirect()->route('register')->withErrors([
                'email' => 'Phiên đăng ký đã hết hạn. Vui lòng đăng ký lại.'
            ]);
        }

        // Sinh mã OTP mới
        $otp = (string) random_int(100000, 999999);
        $pending['otp'] = $otp;
        $pending['otp_expires_at'] = now()->addMinutes(10)->timestamp;

        $request->session()->put('register_pending', $pending);

        try {
            Mail::to($pending['email'])->send(new OtpVerificationMail($otp, $pending['name'], 10));
        } catch (\Throwable $e) {
            Log::error('Lỗi gửi lại mã OTP HCTV: ' . $e->getMessage());
            return back()->withErrors(['otp' => 'Không thể gửi lại mã OTP. Vui lòng thử lại sau giây lát.']);
        }

        return back()->with('status', 'Mã OTP mới gồm 6 chữ số đã được gửi tới email ' . $pending['email']);
    }

    /**
     * Cancel pending registration and return to register form.
     */
    public function cancelOtp(Request $request): RedirectResponse
    {
        $request->session()->forget('register_pending');
        return redirect()->route('register');
    }
}
