<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
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
        .header img {
            width: 60px;
            height: 60px;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 24px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 16px;
        }
        .message {
            color: #6b7280;
            font-size: 16px;
            margin-bottom: 24px;
        }
        .button-container {
            text-align: center;
            margin: 32px 0;
        }
        .button {
            display: inline-block;
            padding: 14px 32px;
            background-color: #0f172a;
            color: #ffffff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
        }
        .button:hover {
            background-color: #1e293b;
        }
        .footer {
            background-color: #f9fafb;
            padding: 24px 30px;
            text-align: center;
            color: #9ca3af;
            font-size: 14px;
        }
        .warning {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 16px;
            margin: 24px 0;
            color: #92400e;
            font-size: 14px;
        }
        .copy-link {
            color: #6b7280;
            font-size: 12px;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: 700;">ALAMANDA</h1>
            <p style="color: #94a3b8; margin: 8px 0 0 0; font-size: 14px;">Houseboat Kenyir</p>
        </div>

        <!-- Content -->
        <div class="content">
            @if($userName)
            <p class="greeting">Hi {{ $userName }},</p>
            @else
            <p class="greeting">Hello,</p>
            @endif

            <p class="message">
                We received a request to reset your password for your Alamanda Houseboat account.
                Click the button below to create a new password:
            </p>

            <div class="button-container">
                <a href="{{ $resetUrl }}" class="button">Reset Password</a>
            </div>

            <p class="copy-link">
                Or copy and paste this link into your browser:<br>
                <a href="{{ $resetUrl }}" style="color: #0f172a;">{{ $resetUrl }}</a>
            </p>

            <div class="warning">
                <strong>This link will expire in 60 minutes.</strong><br>
                If you didn't request a password reset, please ignore this email or contact support if you have concerns.
            </div>
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
