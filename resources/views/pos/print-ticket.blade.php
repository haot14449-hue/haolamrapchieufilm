<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>In Vé - {{ $ticketCode }} - HCTV Cinema</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Courier New', Courier, monospace, 'Segoe UI', Tahoma, sans-serif;
        }
        body {
            background-color: #f1f5f9;
            display: flex;
            justify-content: center;
            padding: 20px;
        }
        .ticket-wrapper {
            background: #fff;
            width: 80mm;
            min-height: 140mm;
            padding: 14px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            color: #000;
            font-size: 13px;
            line-height: 1.35;
        }
        .header {
            text-align: center;
            border-bottom: 1.5px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .logo {
            font-size: 20px;
            font-weight: 900;
            letter-spacing: 2px;
        }
        .sublogo {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #444;
        }
        .ticket-title {
            font-size: 14px;
            font-weight: 800;
            margin-top: 6px;
            text-transform: uppercase;
        }
        .dashed-line {
            border-top: 1.5px dashed #000;
            margin: 10px 0;
        }
        .movie-title {
            font-size: 15px;
            font-weight: 900;
            text-transform: uppercase;
            text-align: center;
            margin: 6px 0;
            line-height: 1.25;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }
        .info-label {
            color: #444;
        }
        .info-value {
            font-weight: bold;
            text-align: right;
        }
        .seats-highlight {
            background: #000;
            color: #fff;
            padding: 6px 8px;
            text-align: center;
            font-size: 16px;
            font-weight: 900;
            letter-spacing: 1px;
            margin: 8px 0;
            border-radius: 4px;
        }
        .qr-section {
            text-align: center;
            margin: 12px 0;
        }
        .qr-section img {
            width: 120px;
            height: 120px;
            margin: 0 auto;
            display: block;
        }
        .ticket-code {
            font-size: 16px;
            font-weight: 900;
            letter-spacing: 2px;
            text-align: center;
            margin-top: 4px;
        }
        .footer-note {
            font-size: 10px;
            text-align: center;
            color: #555;
            margin-top: 10px;
            line-height: 1.3;
        }
        .no-print-bar {
            position: fixed;
            top: 15px;
            right: 15px;
            display: flex;
            gap: 10px;
            z-index: 100;
        }
        .btn-action {
            background: #1e293b;
            color: #fff;
            padding: 8px 16px;
            border-radius: 8px;
            border: none;
            font-size: 13px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }
        .btn-print {
            background: #10b981;
        }
        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .ticket-wrapper {
                box-shadow: none;
                width: 100%;
                padding: 0;
            }
            .no-print-bar {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="no-print-bar">
        <button class="btn-action btn-print" onclick="window.print()">🖨️ In Vé Ngay</button>
        <button class="btn-action" onclick="window.close()">✕ Đóng</button>
    </div>

    <div class="ticket-wrapper">
        <!-- Header -->
        <div class="header">
            <div class="logo">HCTV CINEMA</div>
            <div class="sublogo">Vé xem phim & Hóa đơn quầy</div>
            <div class="ticket-title">PHIẾU VÀO PHÒNG CHIẾU</div>
        </div>

        <!-- Cinema & Room -->
        <div class="info-row">
            <span class="info-label">Cụm rạp:</span>
            <span class="info-value">{{ $booking->showtime->room->cinema->name ?? 'HCTV Cinema' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Phòng chiếu:</span>
            <span class="info-value">{{ $booking->showtime->room->name }} ({{ $booking->showtime->format ?? '2D' }})</span>
        </div>

        <div class="dashed-line"></div>

        <!-- Movie Title -->
        <div class="movie-title">{{ $booking->showtime->movie->title }}</div>

        <div class="info-row">
            <span class="info-label">Suất chiếu:</span>
            <span class="info-value">{{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('H:i') }} | {{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('d/m/Y') }}</span>
        </div>

        <!-- Seats Highlight -->
        <div class="seats-highlight">
            GHẾ: {{ $booking->tickets->map(fn($t) => $t->seat->row . $t->seat->number)->join(', ') }}
        </div>

        <!-- Foods if any -->
        @if($bookingFoods->isNotEmpty())
        <div class="dashed-line"></div>
        <div style="font-weight: bold; margin-bottom: 4px; text-transform: uppercase; font-size: 11px;">Bắp nước & Combo:</div>
        @foreach($bookingFoods as $f)
            <div class="info-row">
                <span>{{ $f->name }} x{{ $f->quantity }}</span>
                <span class="info-value">{{ number_format($f->price * $f->quantity, 0, ',', '.') }} đ</span>
            </div>
        @endforeach
        @endif

        <div class="dashed-line"></div>

        <!-- Payment & Total -->
        <div class="info-row" style="font-size: 15px; font-weight: bold;">
            <span>TỔNG TIỀN:</span>
            <span>{{ number_format($booking->total_price, 0, ',', '.') }} VNĐ</span>
        </div>
        <div class="info-row">
            <span class="info-label">Thanh toán:</span>
            <span class="info-value">{{ $booking->payment_method ?? 'Tiền mặt' }}</span>
        </div>

        @if($booking->cash_given)
        <div class="info-row">
            <span class="info-label">Tiền khách đưa:</span>
            <span class="info-value">{{ number_format($booking->cash_given, 0, ',', '.') }} đ</span>
        </div>
        <div class="info-row">
            <span class="info-label">Tiền thừa trả khách:</span>
            <span class="info-value">{{ number_format($booking->cash_change ?? 0, 0, ',', '.') }} đ</span>
        </div>
        @endif

        <div class="dashed-line"></div>

        <!-- Customer & Cashier Info -->
        <div class="info-row">
            <span class="info-label">Khách hàng:</span>
            <span class="info-value">{{ $booking->user->name ?? 'Khách lẻ' }}</span>
        </div>
        @if(!empty($booking->user->phone))
        <div class="info-row">
            <span class="info-label">Số ĐT:</span>
            <span class="info-value">{{ $booking->user->phone }}</span>
        </div>
        @endif
        @if(!empty($booking->user->birthday))
        <div class="info-row">
            <span class="info-label">Sinh nhật:</span>
            <span class="info-value">{{ \Carbon\Carbon::parse($booking->user->birthday)->format('d/m/Y') }}</span>
        </div>
        @endif
        <div class="info-row">
            <span class="info-label">Thu ngân (POS):</span>
            <span class="info-value">{{ $booking->cashier->name ?? 'Nhân viên quầy' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Thời gian xuất:</span>
            <span class="info-value">{{ now()->format('H:i - d/m/Y') }}</span>
        </div>

        <!-- QR Code Section -->
        <div class="qr-section">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode($ticketCode . '|' . ($booking->showtime->movie->title ?? '') . '|' . $booking->id) }}" alt="QR Code">
            <div class="ticket-code">{{ $ticketCode }}</div>
        </div>

        <!-- Footer -->
        <div class="footer-note">
            Vui lòng xuất trình mã QR này tại cửa soát vé.<br>
            Vé đã mua không được đổi hoặc trả lại.<br>
            Chúc quý khách xem phim vui vẻ!
        </div>
    </div>

    <script>
        // Auto print on load
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
