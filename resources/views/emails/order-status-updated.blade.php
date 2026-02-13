<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Update</title>
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
            background: linear-gradient(135deg, 
                @if($status === 'processing') #F59E0B 0%, #D97706 100%
                @elseif($status === 'shipped') #3B82F6 0%, #2563EB 100%
                @elseif($status === 'delivered') #10B981 0%, #059669 100%
                @else #5C1F33 0%, #4A1828 100%
                @endif
            );
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
            color: rgba(255, 255, 255, 0.9);
            font-size: 16px;
            font-weight: 500;
        }
        .status-icon {
            width: 80px;
            height: 80px;
            background-color: 
                @if($status === 'processing') #F59E0B
                @elseif($status === 'shipped') #3B82F6
                @elseif($status === 'delivered') #10B981
                @else #5C1F33
                @endif;
            border-radius: 50%;
            margin: -40px auto 0;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        .status-icon svg {
            width: 40px;
            height: 40px;
            color: #ffffff;
        }
        .content {
            padding: 50px 30px 30px;
        }
        .greeting {
            font-size: 24px;
            font-weight: 600;
            color: 
                @if($status === 'processing') #F59E0B
                @elseif($status === 'shipped') #3B82F6
                @elseif($status === 'delivered') #10B981
                @else #5C1F33
                @endif;
            margin-bottom: 15px;
            font-family: 'Playfair Display', serif;
        }
        .message {
            color: #4B4B4B;
            font-size: 15px;
            margin-bottom: 30px;
            line-height: 1.8;
        }
        .status-box {
            background: 
                @if($status === 'processing') #FEF3C7
                @elseif($status === 'shipped') #DBEAFE
                @elseif($status === 'delivered') #D1FAE5
                @else #FAF5ED
                @endif;
            border: 2px solid 
                @if($status === 'processing') #FCD34D
                @elseif($status === 'shipped') #93C5FD
                @elseif($status === 'delivered') #6EE7B7
                @else #E6B873
                @endif;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
            text-align: center;
        }
        .status-badge {
            display: inline-block;
            padding: 12px 30px;
            background: 
                @if($status === 'processing') #F59E0B
                @elseif($status === 'shipped') #3B82F6
                @elseif($status === 'delivered') #10B981
                @else #5C1F33
                @endif;
            color: #ffffff;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 15px;
        }
        .order-box {
            background: #FAF5ED;
            border: 2px solid #E6B873;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
        }
        .order-number {
            font-size: 18px;
            font-weight: 700;
            color: #5C1F33;
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
        .timeline {
            margin: 30px 0;
            padding: 20px;
            background: #F9FAFB;
            border-radius: 10px;
        }
        .timeline-item {
            display: flex;
            align-items: center;
            padding: 12px 0;
            position: relative;
        }
        .timeline-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 15px;
            flex-shrink: 0;
        }
        .timeline-dot.active {
            background: 
                @if($status === 'processing') #F59E0B
                @elseif($status === 'shipped') #3B82F6
                @elseif($status === 'delivered') #10B981
                @else #5C1F33
                @endif;
        }
        .timeline-dot.inactive {
            background: #D1D5DB;
        }
        .timeline-text {
            font-size: 14px;
            color: #4B5563;
        }
        .timeline-text.active {
            font-weight: 600;
            color: #1F2937;
        }
        .button {
            display: inline-block;
            background: 
                @if($status === 'processing') #F59E0B
                @elseif($status === 'shipped') #3B82F6
                @elseif($status === 'delivered') #10B981
                @else #5C1F33
                @endif;
            color: #ffffff;
            text-decoration: none;
            padding: 15px 40px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            text-align: center;
            margin: 20px 0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
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
        .divider {
            height: 2px;
            background: #E6B873;
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
            <div class="header-subtitle">Order Status Update</div>
        </div>

        <!-- Status Icon -->
        <div class="status-icon">
            @if($status === 'processing')
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            @elseif($status === 'shipped')
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                </svg>
            @elseif($status === 'delivered')
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            @else
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
            @endif
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                @if($status === 'processing')
                    Order is Being Processed!
                @elseif($status === 'shipped')
                    Your Order Has Been Shipped!
                @elseif($status === 'delivered')
                    Order Delivered Successfully!
                @else
                    Order Status Updated
                @endif
            </div>
            
            <p class="message">
                Dear {{ $order->first_name }},<br><br>
                @if($status === 'processing')
                    Great news! Your order is now being processed. Our team is carefully preparing your items for shipment.
                @elseif($status === 'shipped')
                    Exciting news! Your order has been shipped and is on its way to you. You can track your package using the details below.
                @elseif($status === 'delivered')
                    Wonderful! Your order has been successfully delivered. We hope you love your purchase!
                @else
                    Your order status has been updated. Please check the details below.
                @endif
            </p>

            <!-- Status Box -->
            <div class="status-box">
                <div class="status-badge">{{ strtoupper($status) }}</div>
                <p style="color: #6B6B6B; font-size: 14px; margin-top: 10px;">
                    Updated on {{ now()->format('d M, Y h:i A') }}
                </p>
            </div>

            <!-- Order Summary Box -->
            <div class="order-box">
                <div class="order-number">Order #{{ $order->order_number }}</div>
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
                    @if($status === 'shipped' && $order->tracking_number)
                    <div class="detail-row">
                        <div class="detail-label">Tracking Number:</div>
                        <div class="detail-value">{{ $order->tracking_number }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-dot active"></div>
                    <div class="timeline-text active">Order Placed</div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-dot {{ in_array($status, ['processing', 'shipped', 'delivered']) ? 'active' : 'inactive' }}"></div>
                    <div class="timeline-text {{ in_array($status, ['processing', 'shipped', 'delivered']) ? 'active' : '' }}">Processing</div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-dot {{ in_array($status, ['shipped', 'delivered']) ? 'active' : 'inactive' }}"></div>
                    <div class="timeline-text {{ in_array($status, ['shipped', 'delivered']) ? 'active' : '' }}">Shipped</div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-dot {{ $status === 'delivered' ? 'active' : 'inactive' }}"></div>
                    <div class="timeline-text {{ $status === 'delivered' ? 'active' : '' }}">Delivered</div>
                </div>
            </div>

            @if($status === 'delivered')
            <div style="background: #FEF3C7; border-left: 4px solid #F59E0B; padding: 15px; border-radius: 8px; margin: 20px 0;">
                <p style="color: #92400E; font-size: 14px; line-height: 1.6;">
                    <strong>💝 Loved your purchase?</strong><br>
                    We'd love to hear your feedback! Please take a moment to review your order.
                </p>
            </div>
            @endif

            <div class="divider"></div>

            <!-- CTA Button -->
            <center>
                <a href="{{ route('user.orders.show', $order->id) }}" class="button">View Order Details</a>
            </center>

            <p class="message" style="margin-top: 30px; text-align: center; font-size: 14px;">
                Need help? Contact our support team anytime.
            </p>
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
