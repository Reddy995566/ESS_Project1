<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Submission</title>
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
            background: #0284C7;
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
        .logo {
            max-width: 150px;
            height: auto;
            margin-bottom: 15px;
        }
        .header-subtitle {
            color: #E0F2FE;
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .content {
            padding: 30px 30px 30px;
        }
        .greeting {
            font-size: 24px;
            font-weight: 700;
            color: #0284C7;
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
        .contact-box {
            background: #FAF5ED;
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
        .message-box {
            background-color: #F8F9FA;
            border-left: 4px solid #0284C7;
            padding: 20px;
            margin: 25px 0;
            border-radius: 8px;
        }
        .message-box h3 {
            margin: 0 0 15px 0;
            font-size: 16px;
            color: #5C1F33;
            font-family: 'Playfair Display', serif;
            font-weight: 700;
        }
        .message-box p {
            margin: 0;
            font-size: 14px;
            color: #4B4B4B;
            line-height: 1.8;
            white-space: pre-wrap;
        }
        .button {
            display: inline-block;
            background: #0284C7;
            color: #ffffff;
            text-decoration: none;
            padding: 15px 40px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            text-align: center;
            margin: 20px 0;
            box-shadow: 0 4px 15px rgba(2, 132, 199, 0.3);
            transition: all 0.3s ease;
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
        @media only screen and (max-width: 600px) {
            .email-container {
                border-radius: 0;
            }
            .header {
                padding: 30px 20px;
            }
            .content {
                padding: 30px 20px 20px;
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
            @endif
            <div class="site-name">📧 NEW CONTACT</div>
            <div class="header-subtitle">Admin Notification</div>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">New Contact Form Submission!</div>
            
            <p class="message">
                Someone has submitted a contact form on {{ $siteName }}.
            </p>

            <!-- Contact Information -->
            <div class="contact-box">
                <div class="section-title">👤 Contact Details</div>
                <div class="info-row">
                    <div class="info-label">Name:</div>
                    <div class="info-value">{{ $contact->name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Email:</div>
                    <div class="info-value">{{ $contact->email }}</div>
                </div>
                @if($contact->phone)
                <div class="info-row">
                    <div class="info-label">Phone:</div>
                    <div class="info-value">{{ $contact->phone }}</div>
                </div>
                @endif
                <div class="info-row">
                    <div class="info-label">Submitted:</div>
                    <div class="info-value">{{ $contact->created_at->format('d M, Y h:i A') }}</div>
                </div>
            </div>

            <!-- Message -->
            <div class="message-box">
                <h3>💬 Message</h3>
                <p>{{ $contact->message }}</p>
            </div>

            <div class="divider"></div>

            <!-- CTA Button -->
            <center>
                <a href="{{ route('admin.contacts.index') }}" class="button">View All Contacts</a>
            </center>

            <p class="message" style="margin-top: 30px; font-size: 14px; color: #6B6B6B;">
                Please respond to this inquiry as soon as possible.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-text">
                <strong>{{ $siteName }} - Admin Notification System</strong>
            </div>
            
            <div class="divider"></div>

            <div class="footer-text" style="font-size: 11px; color: #999;">
                This is an automated notification. Please do not reply to this message.
            </div>
            
            <div class="footer-text" style="font-size: 12px; color: #6B6B6B; margin-top: 10px;">
                © {{ date('Y') }} {{ $siteName }}. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>
