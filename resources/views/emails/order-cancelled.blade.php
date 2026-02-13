<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Cancelled</title>
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
            box-shadow: 0 4px 20px rgba(220, 38, 38, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #DC2626 0%, #991B1B 100%);
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
        .header-subtitle {
            color: #FEE2E2;
            font-size: 16px;
            font-weight: 500;
        }
        .cancel-icon {
            width: 80px;
            height: 80px;
            background-color: #DC2626;
            border-radius: 50%;
            margin: -40px auto 0;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);
        }
        .cross-mark {
            width: 40px;
            height: 40px;
            position: relative;
        }
        .cross-mark:before,
        .cross-mark:after {
            content: '';
            position: absolute;
            width: 3px;
            height: 40px;
            background-color: #ffffff;
            left: 50%;
            top: 0;
        }
        .cross-mark:before {
            transform: translateX(-50%) rotate(45deg);
        }
        .cross-mark:after {
            transform: translateX(-50%) rotate(-45deg);
        }
        .content {
            padding: 50px 30px 30px;
        }
        .greeting {
            font-size: 24px;
            font-weight: 600;
            color: #DC2626;
            margin-bottom: 15px;
            font-family: 'Playfair Display', serif;
        }
        .message {
            color: #4B4B4B;
            font-size: 15px;
            margin-bottom: 30px;
            line-height: 1.8;
        }
        .alert-box {
            background: #FEE2E2;
            border-left: 4px solid #DC2626;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
        }
        .alert-title {
            font-size: 16px;
            font-weight: 700;
            color: #991B1B;
            margin-bottom: 10px;
        }
        .alert-text {
            color: #7F1D1D;
            font-size: 14px;
            line-height: 1.6;
        }
        .order-box {
            background: #FAF5ED;
            border: 2px solid #FCA5A5;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
        }
        .order-number {
            font-size: 18px;
            font-weight: 700;
            color: #DC2626;
            margin-bottom: 15px;
            text-align: center;
            font-family: 'Playfair Display', serif;
        }
        .order-details {
            display: table;
            width: 100%;
            margin-top: 15px;
        }
        .detail-row {
            display: table-row;
        }
        .detail-label {
            display: table-cell;
            padding: 8px 0;
            color: #6B6B6B;
            font-size: 14px;
            font-weight: 500;
        }
        .detail-value {
            display: table-cell;
            padding: 8px 0;
            color: #2B2B2B;
            font-size: 14px;
            font-weight: 600;
            text-align: right;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            background: #DC2626;
            color: #ffffff;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .refund-box {
            background: #DBEAFE;
            border: 2px solid #3B82F6;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }
        .refund-title {
            font-size: 16px;
            font-weight: 700;
            color: #1E40AF;
            margin-bottom: 10px;
        }
        .refund-text {
            color: #1E3A8A;
            font-size: 14px;
            line-height: 1.6;
        }
        .button {
            display: inline-block;
            background: #DC2626;
            color: #ffffff;
            text-decoration: none;
            padding: 15px 40px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            text-align: center;
            margin: 20px 0;
            box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);
        }
        .footer {
            background-color: #FAF5ED;
            padding: 30px;
            text-align: center;
            border-top: 2px solid #FCA5A5;
        }
        .footer-text {
            color: #6B6B6B;
            font-size: 13px;
            margin-bottom: 15px;
        }
        .divider {
            height: 2px;
            background: #FCA5A5;
            margin: 30px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            @if($siteLogo)
                <img src="{{ $siteLogo }}" alt="{{ $siteName }}" style="max-width: 180px; height: auto; margin-bottom: 20px;">
            @else
                <div class="site-name">{{ strtoupper($siteName) }}</div>
            @endif
            <div class="header-subtitle">Order Cancellation Notice</div>
        </div>

        <!-- Cancel Icon -->
        <div class="cancel-icon">
            <div class="cross-mark"></div>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">Order Cancelled</div>
            <p class="message">
                Dear {{ $order->first_name }},<br><br>
                We're writing to inform you that your order has been cancelled.
            </p>

            <!-- Alert Box -->
            <div class="alert-box">
                <div class="alert-title">Cancellation Details</div>
                <div class="alert-text">
                    <strong>Cancelled By:</strong> {{ ucfirst($order->cancelled_by ?? 'System') }}<br>
                    <strong>Cancelled On:</strong> {{ $order->cancelled_at ? $order->cancelled_at->format('d M, Y h:i A') : 'N/A' }}<br>
                    @if($order->cancellation_reason)
                    <strong>Reason:</strong> {{ $order->cancellation_reason }}
                    @endif
                </div>
            </div>

            <!-- Order Summary Box -->
            <div class="order-box">
                <div class="order-number">Order #{{ $order->order_number }}</div>
                <div style="text-align: center; margin-bottom: 15px;">
                    <span class="status-badge">CANCELLED</span>
                </div>
                <div class="order-details">
                    <div class="detail-row">
                        <div class="detail-label">Order Date:</div>
                        <div class="detail-value">{{ $order->created_at->format('d M, Y') }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Order Total:</div>
                        <div class="detail-value">₹{{ number_format($order->total, 2) }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Payment Method:</div>
                        <div class="detail-value">{{ ucfirst($order->payment_method) }}</div>
                    </div>
                </div>
            </div>

            <!-- Refund Information -->
            @if($order->payment_method !== 'cod' && $order->payment_status === 'paid')
            <div class="refund-box">
                <div class="refund-title">💰 Refund Information</div>
                <div class="refund-text">
                    Your refund of <strong>₹{{ number_format($order->total, 2) }}</strong> will be processed within 5-7 business days. 
                    The amount will be credited back to your original payment method.
                </div>
            </div>
            @endif

            <div class="divider"></div>

            <p class="message" style="text-align: center;">
                If you have any questions about this cancellation, please don't hesitate to contact our support team.
            </p>

            <!-- CTA Button -->
            <center>
                <a href="{{ route('user.orders') }}" class="button">View My Orders</a>
            </center>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-text">
                Thank you for shopping with {{ $siteName }}!
            </div>
            
            @if($siteEmail || $sitePhone)
            <div style="margin: 20px 0;">
                @if($siteEmail)
                <div style="color: #4B4B4B; font-size: 13px; margin: 5px 0;">📧 {{ $siteEmail }}</div>
                @endif
                @if($sitePhone)
                <div style="color: #4B4B4B; font-size: 13px; margin: 5px 0;">📞 {{ $sitePhone }}</div>
                @endif
            </div>
            @endif

            <div class="divider"></div>

            <div class="footer-text" style="font-size: 12px; color: #6B6B6B;">
                © {{ date('Y') }} {{ $siteName }}. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>
