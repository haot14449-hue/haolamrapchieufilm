<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vé xem phim điện tử - HCTV Cinema</title>
</head>
<body style="margin: 0; padding: 0; background-color: #0b0d14; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #ffffff;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #0b0d14; padding: 30px 15px;">
        <tr>
            <td align="center">
                <!-- Main Container -->
                <table role="presentation" width="100%" style="max-width: 600px; background-color: #121520; border-radius: 20px; border: 1px solid rgba(255, 255, 255, 0.1); overflow: hidden; box-shadow: 0 20px 50px rgba(0, 0, 0, 0.7);">
                    
                    <!-- Header with Red Cinema Gradient -->
                    <tr>
                        <td align="center" style="padding: 30px 30px 20px 30px; background: linear-gradient(180deg, rgba(229, 9, 20, 0.3) 0%, rgba(18, 21, 32, 0) 100%);">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td align="center">
                                        <div style="font-size: 34px; font-weight: 900; letter-spacing: 2px; color: #ffffff; text-transform: uppercase;">
                                            HC<span style="color: #e50914;">TV</span>
                                        </div>
                                        <div style="font-size: 11px; letter-spacing: 4px; color: #9ca3af; text-transform: uppercase; margin-top: 3px;">
                                            Cinemas & Entertainment
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Success Badge -->
                    <tr>
                        <td align="center" style="padding: 0 35px 20px 35px;">
                            <div style="display: inline-block; padding: 8px 18px; background-color: rgba(34, 197, 94, 0.15); border: 1px solid #22c55e; border-radius: 30px; color: #4ade80; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">
                                ✓ Thanh toán thành công
                            </div>
                            <h2 style="margin: 15px 0 6px 0; font-size: 24px; font-weight: 800; color: #ffffff; text-align: center;">
                                Vé Xem Phim Điện Tử
                            </h2>
                            <p style="margin: 0; font-size: 14px; color: #94a3b8; text-align: center;">
                                Cảm ơn quý khách <strong>{{ $booking->user->name ?? 'Bạn' }}</strong> đã đặt vé tại HCTV Cinema.
                            </p>
                        </td>
                    </tr>

                    <!-- Ticket Card -->
                    <tr>
                        <td style="padding: 0 25px 25px 25px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #1a1e2e; border-radius: 16px; border: 1px solid rgba(255, 255, 255, 0.12); overflow: hidden;">
                                
                                <!-- Ticket Code Bar -->
                                <tr>
                                    <td style="padding: 16px 22px; background-color: #222738; border-bottom: 2px dashed rgba(255, 255, 255, 0.15); text-align: center;">
                                        <span style="font-size: 11px; text-transform: uppercase; letter-spacing: 2px; color: #94a3b8; display: block; margin-bottom: 4px;">Mã đặt vé của bạn</span>
                                        <span style="font-size: 28px; font-weight: 900; letter-spacing: 4px; color: #e50914; font-family: 'Courier New', Courier, monospace;">
                                            {{ $ticketCode }}
                                        </span>
                                    </td>
                                </tr>

                                <!-- Movie Title & Poster banner -->
                                <tr>
                                    <td style="padding: 22px 24px 10px 24px;">
                                        <h3 style="margin: 0 0 8px 0; font-size: 20px; font-weight: 800; color: #f8fafc; line-height: 1.3;">
                                            {{ $booking->showtime->movie->title }}
                                        </h3>
                                        <div style="font-size: 13px; color: #f59e0b; font-weight: 600;">
                                            ★ Phim: {{ $booking->showtime->movie->duration ?? 120 }} phút | {{ $booking->showtime->movie->rating ?? 'P' }}
                                        </div>
                                    </td>
                                </tr>

                                <!-- Details Grid -->
                                <tr>
                                    <td style="padding: 10px 24px 20px 24px;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td width="50%" style="padding: 8px 0; vertical-align: top;">
                                                    <div style="font-size: 11px; text-transform: uppercase; color: #64748b; font-weight: 600;">Rạp chiếu</div>
                                                    <div style="font-size: 14px; font-weight: 700; color: #ffffff; margin-top: 3px;">
                                                        {{ $booking->showtime->room->cinema->name ?? 'HCTV' }}
                                                    </div>
                                                </td>
                                                <td width="50%" style="padding: 8px 0; vertical-align: top;">
                                                    <div style="font-size: 11px; text-transform: uppercase; color: #64748b; font-weight: 600;">Phòng chiếu</div>
                                                    <div style="font-size: 14px; font-weight: 700; color: #ffffff; margin-top: 3px;">
                                                        {{ $booking->showtime->room->name ?? 'Phòng' }}
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="50%" style="padding: 8px 0; vertical-align: top;">
                                                    <div style="font-size: 11px; text-transform: uppercase; color: #64748b; font-weight: 600;">Ngày chiếu</div>
                                                    <div style="font-size: 14px; font-weight: 700; color: #ffffff; margin-top: 3px;">
                                                        {{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('d/m/Y') }}
                                                    </div>
                                                </td>
                                                <td width="50%" style="padding: 8px 0; vertical-align: top;">
                                                    <div style="font-size: 11px; text-transform: uppercase; color: #64748b; font-weight: 600;">Giờ chiếu</div>
                                                    <div style="font-size: 14px; font-weight: 700; color: #e50914; margin-top: 3px;">
                                                        {{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('H:i') }}
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <!-- Seats Box -->
                                <tr>
                                    <td style="padding: 0 24px 18px 24px;">
                                        <div style="background-color: #121520; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 10px; padding: 14px 18px;">
                                            <div style="font-size: 11px; text-transform: uppercase; color: #94a3b8; font-weight: 700; margin-bottom: 6px;">
                                                Ghế đã chọn ({{ $booking->tickets->count() }} vé):
                                            </div>
                                            <div style="font-size: 18px; font-weight: 800; color: #fbbf24; letter-spacing: 1px;">
                                                {{ $booking->tickets->map(function($t) { return $t->seat ? ($t->seat->row . $t->seat->number) : ''; })->filter()->join(', ') }}
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Food & Drinks (if any) -->
                                @if(isset($bookingFoods) && count($bookingFoods) > 0)
                                <tr>
                                    <td style="padding: 0 24px 18px 24px;">
                                        <div style="background-color: #121520; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 10px; padding: 14px 18px;">
                                            <div style="font-size: 11px; text-transform: uppercase; color: #94a3b8; font-weight: 700; margin-bottom: 8px;">
                                                Bắp nước & Combo:
                                            </div>
                                            @foreach($bookingFoods as $f)
                                            <div style="display: flex; justify-content: space-between; font-size: 13px; color: #cbd5e1; margin-bottom: 4px;">
                                                <span>{{ $f->quantity }}x {{ $f->name }}</span>
                                                <span style="font-weight: 600; color: #ffffff;">{{ number_format($f->price * $f->quantity, 0, ',', '.') }} đ</span>
                                            </div>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                                @endif

                                <!-- Total Paid & Payment Method -->
                                <tr>
                                    <td style="padding: 16px 24px; background-color: #151926; border-top: 1px solid rgba(255, 255, 255, 0.08);">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td>
                                                    <span style="font-size: 12px; color: #94a3b8; display: block;">Phương thức:</span>
                                                    <span style="font-size: 14px; font-weight: 700; color: #38bdf8;">{{ $booking->payment_method ?? 'VNPAY' }}</span>
                                                </td>
                                                <td align="right">
                                                    <span style="font-size: 12px; color: #94a3b8; display: block;">Tổng tiền thanh toán:</span>
                                                    <span style="font-size: 22px; font-weight: 900; color: #e50914;">
                                                        {{ number_format($booking->total_price, 0, ',', '.') }} VNĐ
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <!-- QR Code Box -->
                                <tr>
                                    <td align="center" style="padding: 24px 24px; background-color: #ffffff; text-align: center;">
                                        <p style="margin: 0 0 10px 0; font-size: 12px; font-weight: 800; color: #000000; text-transform: uppercase; letter-spacing: 1px;">
                                            Mã QR soát vé tại quầy / cổng rạp
                                        </p>
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data={{ urlencode($ticketCode) }}" alt="QR Code" width="150" height="150" style="display: block; margin: 0 auto; border: 2px solid #e50914; border-radius: 8px; padding: 5px;">
                                        <p style="margin: 10px 0 0 0; font-size: 11px; color: #64748b;">
                                            Quý khách vui lòng xuất trình mã QR này cho nhân viên soát vé khi vào rạp
                                        </p>
                                    </td>
                                </tr>

                            </table>
                        </td>
                    </tr>

                    <!-- Instructions -->
                    <tr>
                        <td style="padding: 0 35px 25px 35px;">
                            <div style="background-color: rgba(255, 255, 255, 0.03); border-left: 3px solid #e50914; border-radius: 8px; padding: 14px 18px;">
                                <p style="margin: 0 0 6px 0; font-size: 12px; font-weight: 700; color: #f8fafc;">
                                    📌 Hướng dẫn khi đến rạp:
                                </p>
                                <p style="margin: 0; font-size: 12px; line-height: 1.5; color: #94a3b8;">
                                    - Vui lòng có mặt tại rạp trước giờ chiếu ít nhất 15 phút.<br>
                                    - Xuất trình email này hoặc mã đặt vé <strong>{{ $ticketCode }}</strong> trên điện thoại.<br>
                                    - Đối với suất chiếu 18+, vui lòng mang theo CCCD/giấy tờ tùy thân để kiểm tra độ tuổi.
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 20px 30px; background-color: #0e111a; border-top: 1px solid rgba(255, 255, 255, 0.05); text-align: center;">
                            <p style="margin: 0 0 6px 0; font-size: 11px; color: #64748b;">
                                © {{ date('Y') }} HCTV Cinema. Bản quyền thuộc về HCTV.
                            </p>
                            <p style="margin: 0; font-size: 11px; color: #475569;">
                                Email này được gửi tự động tới tài khoản đăng ký {{ $booking->user->email ?? '' }}. Chúc bạn xem phim vui vẻ!
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
