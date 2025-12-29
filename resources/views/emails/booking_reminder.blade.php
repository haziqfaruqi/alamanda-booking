<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Reminder</title>
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
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            padding: 40px 30px;
            text-align: center;
        }
        .content {
            padding: 40px 30px;
        }
        .countdown-box {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-radius: 16px;
            padding: 24px;
            text-align: center;
            margin: 24px 0;
        }
        .countdown-number {
            font-size: 48px;
            font-weight: 700;
            color: #92400e;
        }
        .countdown-text {
            color: #92400e;
            font-size: 14px;
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
        .reminder-list {
            background: #eff6ff;
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
        }
        .reminder-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 12px;
        }
        .reminder-item:last-child {
            margin-bottom: 0;
        }
        .check-icon {
            width: 20px;
            height: 20px;
            background: #3b82f6;
            border-radius: 50%;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .footer {
            background-color: #f9fafb;
            padding: 24px 30px;
            text-align: center;
            color: #9ca3af;
            font-size: 14px;
        }
        .contact-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #4b5563;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" style="margin-bottom: 16px;">
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                <polyline points="12 17v.01"></polyline>
            </svg>
            <h1 style="color: #ffffff; margin: 0 0 8px 0; font-size: 28px; font-weight: 700;">Your Trip is Coming Up!</h1>
            <p style="color: #fef3c7; margin: 0;">Get ready for your Alamanda Houseboat adventure</p>
        </div>

        <!-- Content -->
        <div class="content">
            <p>Hi {{ $booking->contact_name }},</p>
            <p style="color: #6b7280;">This is a friendly reminder that your booking with Alamanda Houseboat is coming up soon.</p>

            <!-- Countdown -->
            <div class="countdown-box">
                <div class="countdown-number">{{ $daysUntil }}</div>
                <div class="countdown-text">
                    @if($daysUntil == 1)
                        Day
                    @else
                        Days
                    @endif
                    Until Your Check-In
                </div>
            </div>

            <!-- Booking Details -->
            <div class="section-title">Booking Details</div>
            <div class="detail-row">
                <span class="detail-label">Booking Reference</span>
                <span class="detail-value">#ALM-{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</span>
            </div>
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
                <span class="detail-value">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('l, d M Y') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Check-in Time</span>
                <span class="detail-value">2:00 PM</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Guests</span>
                <span class="detail-value">{{ $booking->total_guests }} pax</span>
            </div>

            <!-- Reminder Checklist -->
            <div class="reminder-list">
                <p style="margin: 0 0 16px 0; font-weight: 600; color: #1e40af;">Before You Go:</p>
                <div class="reminder-item">
                    <div class="check-icon">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </div>
                    <span style="font-size: 14px; color: #1e40af;">Bring your IC/Passport for check-in</span>
                </div>
                <div class="reminder-item">
                    <div class="check-icon">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </div>
                    <span style="font-size: 14px; color: #1e40af;">Arrive by 2:00 PM for check-in</span>
                </div>
                <div class="reminder-item">
                    <div class="check-icon">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </div>
                    <span style="font-size: 14px; color: #1e40af;">Pack comfortable clothing and swimwear</span>
                </div>
                <div class="reminder-item">
                    <div class="check-icon">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </div>
                    <span style="font-size: 14px; color: #1e40af;">Don't forget sunscreen and insect repellent</span>
                </div>
            </div>

            <!-- Contact -->
            <div style="text-align: center; margin-top: 24px;">
                <p style="color: #6b7280; font-size: 14px; margin: 0 0 8px 0;">Questions? Contact us anytime</p>
                <div class="contact-item" style="justify-content: center; margin: 4px 0;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                    </svg>
                    +60 12-345 6789
                </div>
                <div class="contact-item" style="justify-content: center; margin: 4px 0;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                    alamandahousebot@gmail.com
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>See you soon at Kenyir Lake!</p>
            <p style="margin: 8px 0 0 0; font-size: 12px;">&copy; {{ date('Y') }} Alamanda Houseboat</p>
        </div>
    </div>
</body>
</html>
