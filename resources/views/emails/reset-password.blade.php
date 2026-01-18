<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #FAF5ED;
            padding: 20px;
            line-height: 1.6;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(92, 31, 51, 0.1);
        }
        .header {
            background: #5C1F33;
            padding: 40px 30px;
            text-align: center;
        }
        .site-name {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }
        .logo {
            max-width: 180px;
            height: auto;
            margin-bottom: 20px;
        }
        .header-subtitle {
            color: #FAF5ED;
            font-size: 16px;
            font-weight: 500;
        }
        .lock-icon {
            width: 80px;
            height: 80px;
            background-color: #495530;
            border-radius: 50%;
            margin: -40px auto 0;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(73, 85, 48, 0.3);
        }
        .lock-icon svg {
            width: 40px;
            height: 40px;
            fill: #ffffff;
        }
        .content {
            padding: 50px 30px 30px;
        }
        .greeting {
            font-size: 24px;
            font-weight: 600;
            color: #5C1F33;
            margin-bottom: 15px;
            font-family: 'Playfair Display', serif;
        }
        .message {
            color: #4B4B4B;
            font-size: 15px;
            margin-bottom: 25px;
            line-height: 1.8;
        }
        .info-box {
            background: #FAF5ED;
            border: 2px solid #E6B873;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
            text-align: center;
        }
        .info-title {
            font-size: 16px;
            font-weight: 700;
            color: #5C1F33;
            margin-bottom: 10px;
            font-family: 'Playfair Display', serif;
        }
        .info-text {
            color: #4B4B4B;
            font-size: 14px;
            line-height: 1.6;
        }
        .button-container {
            text-align: center;
            margin: 35px 0;
        }
        .reset-button {
            display: inline-block;
            background: #495530;
            color: #ffffff !important;
            text-decoration: none;
            padding: 16px 45px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            box-shadow: 0 4px 15px rgba(73, 85, 48, 0.3);
            transition: all 0.3s ease;
        }
        .reset-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(73, 85, 48, 0.4);
        }
        .alternative-link {
            background-color: #FAF5ED;
            border-radius: 8px;
            padding: 20px;
            margin: 30px 0;
            border-left: 4px solid #E6B873;
        }
        .alternative-link p {
            margin: 0 0 10px 0;
            font-size: 13px;
            color: #6B6B6B;
            font-weight: 600;
        }
        .alternative-link a {
            color: #5C1F33;
            word-break: break-all;
            font-size: 12px;
            text-decoration: none;
        }
        .warning-box {
            background-color: #FFF3CD;
            border: 2px solid #E6B873;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }
        .warning-box p {
            margin: 0;
            font-size: 14px;
            color: #856404;
            line-height: 1.6;
        }
        .warning-icon {
            display: inline-block;
            margin-right: 8px;
            font-size: 18px;
        }
        .security-tips {
            background: #E7F3FF;
            border-left: 4px solid #495530;
            padding: 20px;
            margin: 25px 0;
            border-radius: 8px;
        }
        .security-tips h3 {
            margin: 0 0 15px 0;
            font-size: 16px;
            color: #5C1F33;
            font-family: 'Playfair Display', serif;
            font-weight: 700;
        }
        .security-tips ul {
            margin: 0;
            padding-left: 20px;
            font-size: 13px;
            color: #4B4B4B;
        }
        .security-tips li {
            margin: 8px 0;
            line-height: 1.6;
        }
        .divider {
            height: 2px;
            background: #E6B873;
            margin: 30px 0;
        }
        .footer {
            background-color: #FAF5ED;
            padding: 30px;
            text-align: center;
            border-top: 2px solid #E6B873;
        }
        .footer-text {
            color: #6B6B6B;
            font-size: 13px;
            margin-bottom: 15px;
        }
        .contact-info {
            margin: 20px 0;
        }
        .contact-item {
            color: #4B4B4B;
            font-size: 13px;
            margin: 5px 0;
        }
        .social-links {
            margin-top: 20px;
        }
        .social-link {
            display: inline-block;
            margin: 0 8px;
            color: #5C1F33;
            text-decoration: none;
            font-size: 12px;
        }
        @media only screen and (max-width: 600px) {
            .email-container {
                border-radius: 0;
            }
            .header {
                padding: 30px 20px;
            }
            .content {
                padding: 40px 20px 20px;
            }
            .reset-button {
                padding: 14px 35px;
                font-size: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            @if($siteLogo)
                <img src="{{ $siteLogo }}" alt="{{ $siteName }}" class="logo">
            @else
                <div class="site-name">{{ strtoupper($siteName) }}</div>
            @endif
            <div class="header-subtitle">Premium Fashion Store</div>
        </div>

        <!-- Lock Icon -->
        <div class="lock-icon">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 1C8.676 1 6 3.676 6 7v3H5c-1.103 0-2 .897-2 2v9c0 1.103.897 2 2 2h14c1.103 0 2-.897 2-2v-9c0-1.103-.897-2-2-2h-1V7c0-3.324-2.676-6-6-6zm0 2c2.276 0 4 1.724 4 4v3H8V7c0-2.276 1.724-4 4-4zm0 10c1.103 0 2 .897 2 2s-.897 2-2 2-2-.897-2-2 .897-2 2-2z"/>
            </svg>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">Hello {{ $user->name }}!</div>
            
            <p class="message">
                We received a request to reset the password for your account associated with <strong>{{ $user->email }}</strong>.
            </p>

            <!-- Info Box -->
            <div class="info-box">
                <div class="info-title">🔐 Password Reset Request</div>
                <div class="info-text">
                    Click the button below to reset your password. This link will expire in <strong>60 minutes</strong> for security reasons.
                </div>
            </div>

            <!-- Reset Button -->
            <div class="button-container">
                <a href="{{ $resetUrl }}" class="reset-button">Reset My Password</a>
            </div>

            <!-- Alternative Link -->
            <div class="alternative-link">
                <p>Button not working? Copy and paste this link into your browser:</p>
                <a href="{{ $resetUrl }}">{{ $resetUrl }}</a>
            </div>

            <!-- Warning Box -->
            <div class="warning-box">
                <p>
                    <span class="warning-icon">⚠️</span>
                    <strong>Important:</strong> If you didn't request a password reset, please ignore this email or contact our support team immediately. Your password will remain unchanged.
                </p>
            </div>

            <div class="divider"></div>

            <!-- Security Tips -->
            <div class="security-tips">
                <h3>🛡️ Security Tips</h3>
                <ul>
                    <li>Never share your password with anyone</li>
                    <li>Use a strong password with a mix of letters, numbers, and symbols</li>
                    <li>Don't use the same password across multiple sites</li>
                    <li>Enable two-factor authentication when available</li>
                    <li>Regularly update your password for better security</li>
                </ul>
            </div>

            <p class="message" style="margin-top: 30px; text-align: center;">
                If you have any questions or need assistance, please don't hesitate to contact our support team.
            </p>

            <p class="message" style="text-align: center; font-size: 14px; color: #6B6B6B;">
                Best regards,<br>
                <strong style="color: #5C1F33;">{{ $siteName }} Team</strong>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-text">
                Thank you for being a valued member of {{ $siteName }}!
            </div>
            
            <div class="contact-info">
                <div class="contact-item">📧 {{ config('mail.from.address') }}</div>
                <div class="contact-item">🌐 {{ config('app.url') }}</div>
            </div>

            <div class="divider"></div>

            <div class="footer-text" style="font-size: 11px; color: #999;">
                This is an automated email. Please do not reply to this message.
            </div>
            
            <div class="footer-text" style="font-size: 12px; color: #6B6B6B; margin-top: 10px;">
                © {{ date('Y') }} {{ $siteName }}. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>
