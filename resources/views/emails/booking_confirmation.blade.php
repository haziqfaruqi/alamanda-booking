<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Booking Confirmed</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style type="text/css">
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        table { border-collapse: collapse !important; }
        body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f7;">
    <!-- Preview Text -->
    <div style="display: none; max-height: 0; overflow: hidden;">
        Your Alamanda Houseboat booking has been confirmed!
    </div>

    <!-- Email Container -->
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f4f4f7; padding: 20px 0;">
        <tr>
            <td align="center">
                <!-- Main Content Table -->
                <table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);">

                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); padding: 40px 30px; text-align: center;">
                            <!-- Success Icon -->
                            <table border="0" cellpadding="0" cellspacing="0" width="60" style="margin: 0 auto 16px;">
                                <tr>
                                    <td align="center" style="background-color: #10b981; width: 60px; height: 60px; border-radius: 50%; line-height: 60px;">
                                        <span style="color: #ffffff; font-size: 24px;">&checkmark;</span>
                                    </td>
                                </tr>
                            </table>
                            <h1 style="color: #ffffff; margin: 0 0 8px 0; font-size: 28px; font-weight: 700;">Booking Confirmed!</h1>
                            <p style="color: #94a3b8; margin: 0; font-size: 14px;">Your Alamanda Houseboat adventure awaits</p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="margin: 0 0 10px 0; color: #1f2937;">Hi {{ $userName ?? 'there' }},</p>
                            <p style="margin: 0 0 24px 0; color: #6b7280; font-size: 14px;">Great news! Your booking has been confirmed and payment received. Here are your booking details:</p>

                            <!-- Booking Reference -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 24px;">
                                <tr>
                                    <td align="center" style="padding: 20px 0; border-bottom: 1px solid #f3f4f6;">
                                        <p style="color: #6b7280; font-size: 14px; margin: 0 0 8px 0;">Booking Reference</p>
                                        <p style="font-size: 24px; font-weight: 700; color: #1f2937; margin: 0 0 12px 0;">#ALM-{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</p>
                                        <span style="display: inline-block; padding: 6px 16px; background-color: #d1fae5; color: #065f46; border-radius: 20px; font-size: 13px; font-weight: 600;">{{ strtoupper($booking->status) }}</span>
                                    </td>
                                </tr>
                            </table>

                            <!-- Package Details -->
                            <p style="margin: 0 0 12px 0; font-size: 18px; font-weight: 600; color: #1f2937; border-bottom: 2px solid #f59e0b; padding-bottom: 8px; display: inline-block;">Package Details</p>

                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 20px;">
                                <tr>
                                    <td style="padding: 12px 0; border-bottom: 1px solid #f3f4f6;">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td style="color: #6b7280; font-size: 14px;">Package</td>
                                                <td align="right" style="font-weight: 600; color: #1f2937; font-size: 14px;">{{ $booking->package->name ?? 'N/A' }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0; border-bottom: 1px solid #f3f4f6;">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td style="color: #6b7280; font-size: 14px;">Duration</td>
                                                <td align="right" style="font-weight: 600; color: #1f2937; font-size: 14px;">{{ $booking->duration }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0; border-bottom: 1px solid #f3f4f6;">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td style="color: #6b7280; font-size: 14px;">Check-in Date</td>
                                                <td align="right" style="font-weight: 600; color: #1f2937; font-size: 14px;">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M Y (l') }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0; border-bottom: 1px solid #f3f4f6;">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td style="color: #6b7280; font-size: 14px;">Check-out Date</td>
                                                <td align="right" style="font-weight: 600; color: #1f2937; font-size: 14px;">{{ \Carbon\Carbon::parse($booking->check_out_date)->format('d M Y (l') }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0;">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td style="color: #6b7280; font-size: 14px;">Guests</td>
                                                <td align="right" style="font-weight: 600; color: #1f2937; font-size: 14px;">{{ $booking->total_guests }} pax</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            @if($booking->guests && $booking->guests->count() > 0)
                            <!-- Guest Information -->
                            <p style="margin: 24px 0 12px 0; font-size: 18px; font-weight: 600; color: #1f2937; border-bottom: 2px solid #f59e0b; padding-bottom: 8px; display: inline-block;">Guest Information</p>

                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 20px; background-color: #f9fafb; border-radius: 12px;">
                                @foreach($booking->guests as $guest)
                                <tr>
                                    <td style="padding: 10px 16px; border-bottom: 1px solid #e5e7eb; {{ $loop->last ? 'border-bottom: none;' : '' }}">
                                        <span style="font-weight: 600; color: #1f2937; font-size: 14px;">{{ $guest->guest_name }}</span>
                                        @if($guest->id_number)
                                        <span style="color: #6b7280; font-size: 13px;"> - {{ $guest->id_type === 'passport' ? 'Passport' : 'IC' }}: {{ $guest->id_number }}</span>
                                        @endif
                                        @if($guest->age)
                                        <span style="color: #6b7280; font-size: 13px;"> - {{ $guest->age }} years old</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                            @endif

                            <!-- Payment Summary -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #fef3c7; border-radius: 12px; padding: 20px; margin-top: 20px;">
                                <tr>
                                    <td>
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td style="color: #92400e; font-weight: 600; font-size: 14px;">Total Amount Paid</td>
                                                <td align="right" style="font-size: 24px; font-weight: 700; color: #1f2937;">RM {{ number_format($booking->total_price, 0, ',', ',') }}</td>
                                            </tr>
                                        </table>
                                        @if($booking->discount_amount > 0)
                                        <p style="font-size: 13px; color: #92400e; margin: 8px 0 0 0;">
                                            (Discount applied: RM {{ number_format($booking->discount_amount, 0, ',', ',') }})
                                        </p>
                                        @endif
                                    </td>
                                </tr>
                            </table>

                            <!-- Contact Information -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #eff6ff; border-radius: 12px; padding: 16px; margin: 20px 0;">
                                <tr>
                                    <td>
                                        <p style="margin: 0 0 12px 0; font-weight: 600; color: #1e40af; font-size: 14px;">Need to contact us?</p>
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td style="padding: 4px 0; color: #1e40af; font-size: 14px;">&#9742; +60 12-345 6789</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 4px 0; color: #1e40af; font-size: 14px;">&#9993; alamandahousebot@gmail.com</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <p style="color: #6b7280; font-size: 14px; margin: 24px 0 0 0; text-align: center;">
                                We look forward to welcoming you aboard Alamanda Houseboat!
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 24px 30px; text-align: center;">
                            <p style="color: #9ca3af; font-size: 14px; margin: 0;">&copy; {{ date('Y') }} Alamanda Houseboat. All rights reserved.</p>
                            <p style="margin: 8px 0 0 0;">
                                <a href="mailto:alamandahousebot@gmail.com" style="color: #9ca3af; text-decoration: none; font-size: 14px;">alamandahousebot@gmail.com</a>
                            </p>
                        </td>
                    </tr>

                </table>
                <!-- End Main Content Table -->
            </td>
        </tr>
    </table>
    <!-- End Email Container -->
</body>
</html>
