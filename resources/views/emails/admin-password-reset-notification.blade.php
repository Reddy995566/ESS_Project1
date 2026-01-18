<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Alert - Password Reset</title>
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
            background: linear-gradient(135deg, #DC2626 0%, #991B1B 100%);
            padding: 40px 30px;
            text-align: center;
        }
        .site-name {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }
        .header-subtitle {
            color: #FEE2E2;
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .alert-icon {
            width: 80px;
            height: 80px;
            background-color: #DC2626;
            border-radius: 50%;
            margin: -40px auto 0;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(220, 38, 38, 0.4);
            border: 4px solid #ffffff;
        }
        .alert-icon svg {
            width: 40px;
            height: 40px;
            fill: #ffffff;
        }
        .content {
            padding: 50px 30px 30px;
        }
        .greeting {
            font-size: 24px;
            font-weight: 700;
            color: #DC2626;
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
        .alert-box {
            background: linear-gradient(135deg, #FEE2E2 0%, #FECACA 100%);
            border: 2px solid #DC2626;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
        }
        .alert-title {
            font-size: 18px;
            font-weight: 700;
            color: #991B1B;
            margin-bottom: 15px;
            font-family: 'Playfair Display', serif;
            text-align: center;
        }
        .user-info-box {
            background: linear-gradient(135deg, #FAF5ED 0%, #EDE5DA 100%);
            border: 2px solid #E6B873;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 16px;
            font-weight: 700;
            color: #5C1F33;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #E6B873;
            font-family: 'Playfair Display', serif;
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
            word-break: break-all;
        }
        .action-box {
            background: linear-gradient(135deg, #E0F2FE 0%, #BAE6FD 100%);
            border-left: 4px solid #0284C7;
            padding: 20px;
            margin: 25px 0;
            border-radius: 8px;
        }
        .action-box h3 {
            margin: 0 0 15px 0;
            font-size: 16px;
            color: #0C4A6E;
            font-family: 'Playfair Display', serif;
            font-weight: 700;
        }
        .action-box ul {
            margin: 0;
            padding-left: 20px;
            font-size: 13px;
            color: #075985;
        }
        .action-box li {
            margin: 8px 0;
            line-height: 1.6;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #DC2626 0%, #991B1B 100%);
            color: #ffffff;
            text-decoration: none;
            padding: 15px 40px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            text-align: center;
            margin: 20px 0;
            box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);
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
            <div class="site-name">🔐 ADMIN ALERT</div>
            <div class="header-subtitle">Password Reset Notification</div>
        </div>

        <!-- Alert Icon -->
        <div class="alert-icon">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
            </svg>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">Password Reset Alert</div>
            
            <p class="message">
                A user has successfully reset their password on {{ config('app.name') }}.
            </p>

            <!-- Alert Box -->
            <div class="alert-box">
                <div class="alert-title">⚠️ Security Event Detected</div>
                <p style="text-align: center; color: #991B1B; font-size: 14px; margin: 0;">
                    This is an automated notification for password reset activity.
                </p>
            </div>

            <!-- User Information -->
            <div class="user-info-box">
                <div class="section-title">👤 User Details</div>
                <div class="info-row">
                    <div class="info-label">User Name:</div>
                    <div class="info-value">{{ $user->name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Email Address:</div>
                    <div class="info-value">{{ $user->email }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">User ID:</div>
                    <div class="info-value">#{{ $user->id }}</div>
                </div>
            </div>

            <!-- Reset Details -->
            <div class="user-info-box">
                <div class="section-title">🕐 Reset Activity Details</div>
                <div class="info-row">
                    <div class="info-label">Reset Time:</div>
                    <div class="info-value">{{ $resetTime }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">IP Address:</div>
                    <div class="info-value">{{ $ipAddress }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">User Agent:</div>
                    <div class="info-value" style="font-size: 12px;">{{ $userAgent }}</div>
                </div>
            </div>

            <div class="divider"></div>

            <!-- Action Items -->
            <div class="action-box">
                <h3>📋 Recommended Actions</h3>
                <ul>
                    <li>Verify this activity with the user if suspicious</li>
                    <li>Check for any unusual account activity</li>
                    <li>Monitor login attempts from this IP address</li>
                    <li>Review user account security settings</li>
                    <li>Contact user if this seems unauthorized</li>
                </ul>
            </div>

            <!-- CTA Button -->
            <center>
                <a href="{{ route('admin.users.show', $user->id) }}" class="button">View User Account</a>
            </center>

            <p class="message" style="margin-top: 30px; font-size: 14px; color: #6B6B6B;">
                This is an automated security notification. No action is required unless the activity seems suspicious.
            </p>

            <p class="message" style="text-align: center; font-size: 14px; color: #6B6B6B;">
                <strong style="color: #5C1F33;">{{ config('app.name') }} Security Team</strong>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-text">
                <strong>{{ config('app.name') }} - Admin Notification System</strong>
            </div>
            
            <div class="contact-info">
                <div class="contact-item">📧 {{ config('mail.from.address') }}</div>
                <div class="contact-item">🌐 {{ config('app.url') }}</div>
            </div>

            <div class="divider"></div>

            <div class="footer-text" style="font-size: 11px; color: #999;">
                This is an automated security alert. Please do not reply to this message.
            </div>
            
            <div class="footer-text" style="font-size: 12px; color: #6B6B6B; margin-top: 10px;">
                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>
