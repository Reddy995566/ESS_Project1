<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Successful</title>
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
            background: linear-gradient(135deg, #495530 0%, #384225 100%);
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
        .success-icon {
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
        .checkmark {
            width: 40px;
            height: 40px;
            border: 3px solid #ffffff;
            border-radius: 50%;
            position: relative;
        }
        .checkmark:after {
            content: '';
            position: absolute;
            left: 10px;
            top: 5px;
            width: 10px;
            height: 18px;
            border: solid #ffffff;
            border-width: 0 3px 3px 0;
            transform: rotate(45deg);
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
            text-align: center;
        }
        .message {
            color: #4B4B4B;
            font-size: 15px;
            margin-bottom: 25px;
            line-height: 1.8;
            text-align: center;
        }
        .success-box {
            background: linear-gradient(135deg, #E8F5E9 0%, #C8E6C9 100%);
            border: 2px solid #4CAF50;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
            text-align: center;
        }
        .success-title {
            font-size: 18px;
            font-weight: 700;
            color: #2E7D32;
            margin-bottom: 10px;
            font-family: 'Playfair Display', serif;
        }
        .success-text {
            color: #1B5E20;
            font-size: 14px;
            line-height: 1.6;
        }
        .info-box {
            background: linear-gradient(135deg, #FAF5ED 0%, #EDE5DA 100%);
            border: 2px solid #E6B873;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #E6B873;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            color: #6B6B6B;
            font-size: 14px;
            font-weight: 600;
        }
        .info-value {
            color: #2B2B2B;
            font-size: 14px;
            font-weight: 600;
            text-align: right;
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
            background: linear-gradient(135deg, #E7F3FF 0%, #D4E9FF 100%);
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
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #495530 0%, #384225 100%);
            color: #ffffff;
            text-decoration: none;
            padding: 15px 40px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            text-align: center;
            margin: 20px 0;
            box-shadow: 0 4px 15px rgba(73, 85, 48, 0.3);
            transition: all 0.3s ease;
        }
        .divider {
            height: 2px;
            background: linear-gradient(90deg, transparent, #E6B873, transparent);
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
            .info-row {
                flex-direction: column;
            }
            .info-value {
                text-align: left;
                margin-top: 5px;
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

        <!-- Success Icon -->
        <div class="success-icon">
            <div class="checkmark"></div>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">Password Reset Successful!</div>
            
            <p class="message">
                Hi <strong>{{ $user->name }}</strong>, your password has been successfully reset.
            </p>

            <!-- Success Box -->
            <div class="success-box">
                <div class="success-title">✓ Password Changed Successfully</div>
                <div class="success-text">
                    You can now login to your account using your new password.
                </div>
            </div>

            <!-- Reset Details -->
            <div class="info-box">
                <div class="info-row">
                    <div class="info-label">Account Email:</div>
                    <div class="info-value">{{ $user->email }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Reset Time:</div>
                    <div class="info-value">{{ $resetTime }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">IP Address:</div>
                    <div class="info-value">{{ $ipAddress }}</div>
                </div>
            </div>

            <!-- Warning Box -->
            <div class="warning-box">
                <p>
                    <span class="warning-icon">⚠️</span>
                    <strong>Didn't make this change?</strong> If you didn't reset your password, please contact our support team immediately to secure your account.
                </p>
            </div>

            <div class="divider"></div>

            <!-- Security Tips -->
            <div class="security-tips">
                <h3>🛡️ Keep Your Account Secure</h3>
                <ul>
                    <li>Never share your password with anyone</li>
                    <li>Use a unique password for this account</li>
                    <li>Enable two-factor authentication if available</li>
                    <li>Regularly update your password</li>
                    <li>Be cautious of phishing emails</li>
                </ul>
            </div>

            <!-- CTA Button -->
            <center>
                <a href="{{ route('login') }}" class="button">Login to Your Account</a>
            </center>

            <p class="message" style="margin-top: 30px; font-size: 14px; color: #6B6B6B;">
                If you have any questions or concerns, please contact our support team.
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
