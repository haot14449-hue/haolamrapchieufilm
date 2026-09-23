<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mã xác thực OTP - HCTV Cinema</title>
</head>
<body style="margin: 0; padding: 0; background-color: #0b0d14; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #ffffff;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #0b0d14; padding: 40px 15px;">
        <tr>
            <td align="center">
                <!-- Main Container -->
                <table role="presentation" width="100%" max-width="560" style="max-width: 560px; background-color: #121520; border-radius: 20px; border: 1px solid rgba(255, 255, 255, 0.1); overflow: hidden; box-shadow: 0 20px 50px rgba(0, 0, 0, 0.7);">
                    
                    <!-- Header with Red Cinema Gradient -->
                    <tr>
                        <td align="center" style="padding: 35px 30px 25px 30px; background: linear-gradient(180deg, rgba(229, 9, 20, 0.25) 0%, rgba(18, 21, 32, 0) 100%);">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td align="center">
                                        <div style="font-size: 32px; font-weight: 900; letter-spacing: 2px; color: #ffffff; text-transform: uppercase;">
                                            HC<span style="color: #e50914;">TV</span>
                                        </div>
                                        <div style="font-size: 11px; letter-spacing: 4px; color: #9ca3af; text-transform: uppercase; margin-top: 2px;">
                                            Cinemas & Entertainment
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 10px 35px 30px 35px;">
                            <h2 style="margin: 0 0 12px 0; font-size: 22px; font-weight: 700; color: #ffffff; text-align: center;">
                                Xác thực đăng ký tài khoản
                            </h2>
                            <p style="margin: 0 0 25px 0; font-size: 14px; line-height: 1.6; color: #cbd5e1; text-align: center;">
                                Xin chào <strong style="color: #ffffff;">{{ $name }}</strong>, cảm ơn bạn đã đăng ký tham gia thành viên <strong style="color: #e50914;">HCTV Cinema</strong>. Vui lòng nhập mã OTP dưới đây để hoàn tất kích hoạt tài khoản:
                            </p>

                            <!-- OTP Box -->
                            <div style="margin: 0 auto 25px auto; padding: 22px 20px; background: #1a1e2e; border: 1px dashed rgba(229, 9, 20, 0.6); border-radius: 16px; text-align: center; max-width: 380px;">
                                <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 2px; color: #9ca3af; margin-bottom: 8px;">
                                    Mã xác thực của bạn
                                </div>
                                <div style="font-size: 38px; font-weight: 800; letter-spacing: 12px; color: #ffffff; text-shadow: 0 0 15px rgba(229, 9, 20, 0.6); font-family: 'Courier New', Courier, monospace; padding-left: 12px;">
                                    {{ $otp }}
                                </div>
                                <div style="font-size: 12px; color: #f59e0b; margin-top: 10px;">
                                    ⏱️ Mã có hiệu lực trong vòng <strong>{{ $expiresInMinutes }} phút</strong>
                                </div>
                            </div>

                            <!-- Security Warning -->
                            <div style="padding: 14px 18px; background: rgba(255, 255, 255, 0.03); border-radius: 12px; border-left: 3px solid #e50914; margin-bottom: 25px;">
                                <p style="margin: 0; font-size: 12px; line-height: 1.5; color: #94a3b8;">
                                    🔒 <strong>Lưu ý an toàn:</strong> Không chia sẻ mã này cho bất kỳ ai, kể cả nhân viên rạp HCTV. Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua email.
                                </p>
                            </div>

                            <p style="margin: 0; font-size: 13px; line-height: 1.5; color: #64748b; text-align: center;">
                                Chúc bạn có những phút giây trải nghiệm điện ảnh tuyệt vời tại HCTV Cinema!
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 25px 30px; background-color: #0e111a; border-top: 1px solid rgba(255, 255, 255, 0.05); text-align: center;">
                            <p style="margin: 0 0 8px 0; font-size: 11px; color: #64748b;">
                                © {{ date('Y') }} HCTV Cinema. Bản quyền thuộc về HCTV.
                            </p>
                            <p style="margin: 0; font-size: 11px; color: #475569;">
                                Email này được gửi tự động từ hệ thống xác thực tài khoản HCTV.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
