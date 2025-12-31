<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f4f4f7;
            margin: 0;
            padding: 20px;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        .header {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            padding: 40px 30px;
            text-align: center;
        }
        .success-icon {
            width: 60px;
            height: 60px;
            background: #10b981;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-center;
            margin: 0 auto 16px;
        }
        .content {
            padding: 40px 30px;
        }
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 2px solid #f59e0b;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            color: #6b7280;
            font-size: 14px;
        }
        .detail-value {
            font-weight: 600;
            color: #1f2937;
            font-size: 14px;
            text-align: right;
        }
        .total-section {
            background: #fef3c7;
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .total-amount {
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            background: #d1fae5;
            color: #065f46;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }
        .footer {
            background-color: #f9fafb;
            padding: 24px 30px;
            text-align: center;
            color: #9ca3af;
            font-size: 14px;
        }
        .guest-list {
            margin-top: 8px;
        }
        .guest-item {
            padding: 8px 0;
            font-size: 14px;
            color: #4b5563;
        }
        .contact-info {
            background: #eff6ff;
            border-radius: 12px;
            padding: 16px;
            margin: 20px 0;
        }
        .contact-item {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
            font-size: 14px;
            color: #1e40af;
        }
        .contact-item:last-child {
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="success-icon" style="display: flex; align-items: center; justify-content: center;">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
            <h1 style="color: #ffffff; margin: 0 0 8px 0; font-size: 28px; font-weight: 700;">Booking Confirmed!</h1>
            <p style="color: #94a3b8; margin: 0;">Your Alamanda Houseboat adventure awaits</p>
        </div>

        <!-- Content -->
        <div class="content">
            <p>Hi {{ $userName ?? 'there' }},</p>
            <p style="color: #6b7280;">Great news! Your booking has been confirmed and payment received. Here are your booking details:</p>

            <!-- Booking Reference -->
            <div style="text-align: center; margin: 24px 0;">
                <p style="color: #6b7280; font-size: 14px; margin: 0 0 8px 0;">Booking Reference</p>
                <p style="font-size: 24px; font-weight: 700; color: #1f2937; margin: 0;">#ALM-{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</p>
                <span class="status-badge">{{ strtoupper($booking->status) }}</span>
            </div>

            <!-- Package Details -->
            <div class="section-title">Package Details</div>
            <div class="detail-row">
                <span class="detail-label">Package</span>
                <span class="detail-value">{{ $booking->package->name ?? 'N/A' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Duration</span>
                <span class="detail-value">{{ $booking->duration }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Check-in Date</span>
                <span class="detail-value">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M Y (l') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Check-out Date</span>
                <span class="detail-value">{{ \Carbon\Carbon::parse($booking->check_out_date)->format('d M Y (l') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Guests</span>
                <span class="detail-value">{{ $booking->total_guests }} pax</span>
            </div>

            @if($booking->guests && $booking->guests->count() > 0)
            <div class="section-title" style="margin-top: 24px;">Guest Information</div>
            <div class="guest-list">
                @foreach($booking->guests as $guest)
                <div class="guest-item">
                    <strong>{{ $guest->guest_name }}</strong>
                    @if($guest->id_number)
                    - {{ $guest->id_type === 'passport' ? 'Passport' : 'IC' }}: {{ $guest->id_number }}
                    @endif
                    @if($guest->age)
                    - {{ $guest->age }} years old
                    @endif
                </div>
                @endforeach
            </div>
            @endif

            <!-- Payment Summary -->
            <div class="total-section">
                <div class="total-row">
                    <span style="color: #92400e; font-weight: 600;">Total Amount Paid</span>
                    <span class="total-amount">RM {{ number_format($booking->total_price, 0, ',', ',') }}</span>
                </div>
                @if($booking->discount_amount > 0)
                <p style="font-size: 13px; color: #92400e; margin: 8px 0 0 0;">
                    (Discount applied: RM {{ number_format($booking->discount_amount, 0, ',', ',') }})
                </p>
                @endif
            </div>

            <!-- Contact Information -->
            <div class="contact-info">
                <p style="margin: 0 0 12px 0; font-weight: 600; color: #1e40af;">Need to contact us?</p>
                <div class="contact-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                    </svg>
                    +60 12-345 6789
                </div>
                <div class="contact-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                    alamandahousebot@gmail.com
                </div>
            </div>

            <p style="color: #6b7280; font-size: 14px; margin-top: 24px; text-align: center;">
                We look forward to welcoming you aboard Alamanda Houseboat!
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} Alamanda Houseboat. All rights reserved.</p>
            <p style="margin: 8px 0 0 0;">
                <a href="mailto:alamandahousebot@gmail.com" style="color: #9ca3af; text-decoration: none;">alamandahousebot@gmail.com</a>
            </p>
        </div>
    </div>
</body>
</html>
