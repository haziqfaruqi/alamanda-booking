<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Payment Received</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: #f4f6fb;">

    <!-- Email Container -->
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 18px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08);">

        <!-- Header -->
        <div style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); padding: 40px 30px; text-align: center;">
            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 700; letter-spacing: 1px;">
                ALAMANDA HOUSEBOAT
            </h1>
            <p style="margin: 10px 0 0 0; color: rgba(255,255,255,0.9); font-size: 14px;">Lake Kenyir</p>
        </div>

        <!-- Content -->
        <div style="padding: 40px 30px;">

            <!-- Title -->
            <div style="text-align: center; margin-bottom: 30px;">
                <div style="display: inline-flex; align-items: center; justify-content: center; width: 60px; height: 60px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 50%; margin-bottom: 15px;">
                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                </div>
                <h2 style="margin: 0; color: #1f2937; font-size: 22px; font-weight: 700;">New Payment Received!</h2>
                <p style="margin: 8px 0 0 0; color: #6b7280; font-size: 14px;">A guest has completed their payment</p>
            </div>

            <!-- Booking Details Card -->
            <div style="background: #f9fafb; border-radius: 14px; padding: 25px; margin-bottom: 25px; border: 1px solid #e5e7eb;">
                <h3 style="margin: 0 0 20px 0; color: #1f2937; font-size: 16px; font-weight: 600; border-bottom: 2px solid #4f46e5; padding-bottom: 10px; display: inline-block;">
                    Booking Details
                </h3>

                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 10px 0; color: #6b7280; font-size: 14px; width: 140px;">Booking ID</td>
                        <td style="padding: 10px 0; color: #1f2937; font-size: 14px; font-weight: 600;">#{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0; color: #6b7280; font-size: 14px;">Contact Name</td>
                        <td style="padding: 10px 0; color: #1f2937; font-size: 14px; font-weight: 600;">{{ $booking->contact_name }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0; color: #6b7280; font-size: 14px;">Email</td>
                        <td style="padding: 10px 0; color: #1f2937; font-size: 14px;">{{ $booking->contact_email }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0; color: #6b7280; font-size: 14px;">Phone</td>
                        <td style="padding: 10px 0; color: #1f2937; font-size: 14px;">{{ $booking->contact_phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0; color: #6b7280; font-size: 14px;">Package</td>
                        <td style="padding: 10px 0; color: #1f2937; font-size: 14px; font-weight: 600;">{{ $booking->package->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0; color: #6b7280; font-size: 14px;">Check-in</td>
                        <td style="padding: 10px 0; color: #1f2937; font-size: 14px;">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0; color: #6b7280; font-size: 14px;">Check-out</td>
                        <td style="padding: 10px 0; color: #1f2937; font-size: 14px;">{{ \Carbon\Carbon::parse($booking->check_out_date)->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0; color: #6b7280; font-size: 14px;">Guests</td>
                        <td style="padding: 10px 0; color: #1f2937; font-size: 14px;">{{ $booking->total_guests }} pax</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0; color: #6b7280; font-size: 14px;">Payment Method</td>
                        <td style="padding: 10px 0; color: #1f2937; font-size: 14px; font-weight: 600;">
                            {{ ucfirst(str_replace('_', ' ', $booking->payment_method)) }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0; color: #6b7280; font-size: 14px;">Reference</td>
                        <td style="padding: 10px 0; color: #1f2937; font-size: 14px;">{{ $booking->payment_reference ?? '-' }}</td>
                    </tr>
                </table>
            </div>

            <!-- Amount Highlight -->
            <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 14px; padding: 25px; text-align: center; margin-bottom: 25px;">
                <p style="margin: 0 0 8px 0; color: rgba(255,255,255,0.9); font-size: 14px;">Amount Received</p>
                <p style="margin: 0; color: #ffffff; font-size: 32px; font-weight: 700;">RM {{ number_format($booking->total_price, 2) }}</p>
            </div>

            <!-- Payment Proof -->
            @if($booking->payment_proof_path)
            <div style="background: #fffbeb; border: 1px solid #fcd34d; border-radius: 14px; padding: 20px; margin-bottom: 25px;">
                <p style="margin: 0 0 10px 0; color: #92400e; font-size: 14px; font-weight: 600;">
                    Payment proof uploaded by guest
                </p>
                <p style="margin: 0; color: #78350f; font-size: 13px;">
                    File: {{ basename($booking->payment_proof_path) }}
                </p>
            </div>
            @endif

            <!-- CTA Buttons -->
            <div style="text-align: center;">
                <a href="{{ url('/admin/bookings/' . $booking->id) }}" style="display: inline-block; background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); color: #ffffff; text-decoration: none; padding: 14px 30px; border-radius: 10px; font-weight: 600; font-size: 14px;">
                    View Full Details
                </a>
                @if($receiptUrl)
                <a href="{{ $receiptUrl }}" style="display: inline-block; background: #ffffff; color: #4f46e5; text-decoration: none; padding: 14px 30px; border-radius: 10px; font-weight: 600; font-size: 14px; border: 2px solid #4f46e5; margin-left: 10px;">
                    View Receipt
                </a>
                @endif
            </div>

        </div>

        <!-- Footer -->
        <div style="background: #f9fafb; padding: 30px; text-align: center; border-top: 1px solid #e5e7eb;">
            <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 13px;">This is an automated notification from</p>
            <p style="margin: 0; color: #4f46e5; font-weight: 600; font-size: 14px;">Alamanda Houseboat Booking System</p>
            <p style="margin: 15px 0 0 0; color: #9ca3af; font-size: 12px;">{{ now()->format('d M Y, H:i') }}</p>
        </div>

    </div>

</body>
</html>
