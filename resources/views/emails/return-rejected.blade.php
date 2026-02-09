<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Request Update</title>
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
        .header-subtitle {
            color: #FAF5ED;
            font-size: 16px;
            font-weight: 500;
        }
        .info-icon {
            width: 80px;
            height: 80px;
            background-color: #E74C3C;
            border-radius: 50%;
            margin: -40px auto 0;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
        }
        .info-symbol {
            color: #ffffff;
            font-size: 40px;
            font-weight: bold;
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
            margin-bottom: 30px;
            line-height: 1.8;
        }
        .return-box {
            background: #FDEDEC;
            border: 2px solid #E74C3C;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
        }
        .return-number {
            font-size: 18px;
            font-weight: 700;
            color: #E74C3C;
            margin-bottom: 15px;
            text-align: center;
            font-family: 'Playfair Display', serif;
        }
        .return-details {
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
        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #5C1F33;
            margin: 30px 0 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #E74C3C;
            font-family: 'Playfair Display', serif;
        }
        .product-item {
            display: flex;
            padding: 20px;
            background-color: #FAF5ED;
            border-radius: 8px;
            margin-bottom: 15px;
            border: 1px solid #EDE5DA;
        }
        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 6px;
            margin-right: 15px;
            border: 1px solid #D6B27A;
        }
        .product-details {
            flex: 1;
        }
        .product-name {
            font-size: 15px;
            font-weight: 600;
            color: #2B2B2B;
            margin-bottom: 5px;
        }
        .product-meta {
            font-size: 13px;
            color: #6B6B6B;
            margin-bottom: 8px;
        }
        .product-price {
            font-size: 16px;
            font-weight: 700;
            color: #E74C3C;
        }
        .reason-box {
            background-color: #FDEDEC;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 4px solid #E74C3C;
        }
        .reason-title {
            font-size: 14px;
            font-weight: 700;
            color: #E74C3C;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .reason-text {
            color: #4B4B4B;
            font-size: 14px;
            line-height: 1.8;
        }
        .button {
            display: inline-block;
            background: #5C1F33;
            color: #ffffff;
            text-decoration: none;
            padding: 15px 40px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            text-align: center;
            margin: 20px 0;
            box-shadow: 0 4px 15px rgba(92, 31, 51, 0.3);
        }
        .footer {
            background-color: #FAF5ED;
            padding: 30px;
            text-align: center;
            border-top: 2px solid #E74C3C;
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
        .divider {
            height: 2px;
            background: #E74C3C;
            margin: 30px 0;
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
            .product-item {
                flex-direction: column;
            }
            .product-image {
                margin-bottom: 15px;
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
        </div>

        <!-- Info Icon -->
        <div class="info-icon">
            <div class="info-symbol">!</div>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">Return Request Update</div>
            <p class="message">
                Hi {{ $return->user->name }}, we've reviewed your return request and unfortunately, we're unable to approve it at this time.
            </p>

            <!-- Return Summary Box -->
            <div class="return-box">
                <div class="return-number">Return #{{ $return->return_number }}</div>
                <div class="return-details">
                    <div class="detail-row">
                        <div class="detail-label">Reviewed Date:</div>
                        <div class="detail-value">{{ $return->rejected_at->format('d M, Y') }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Order Number:</div>
                        <div class="detail-value">{{ $return->order->order_number }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Requested Amount:</div>
                        <div class="detail-value">₹{{ number_format($return->amount, 2) }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Status:</div>
                        <div class="detail-value" style="color: #E74C3C;">Rejected</div>
                    </div>
                </div>
            </div>

            <!-- Product Details -->
            <div class="section-title">Product Details</div>
            <div class="product-item">
                @php
                    $productImage = $return->orderItem->getImageUrl();
                @endphp
                @if($productImage)
                <img src="{{ $productImage }}" alt="{{ $return->orderItem->product_name }}" class="product-image">
                @endif
                <div class="product-details">
                    <div class="product-name">{{ $return->orderItem->product_name }}</div>
                    <div class="product-meta">
                        @if($return->orderItem->variant_name)
                            {{ $return->orderItem->variant_name }}<br>
                        @endif
                        Requested Quantity: {{ $return->quantity }}
                    </div>
                    <div class="product-price">₹{{ number_format($return->amount, 2) }}</div>
                </div>
            </div>

            <!-- Rejection Reason -->
            <div class="section-title">Reason for Rejection</div>
            <div class="reason-box">
                <div class="reason-title">Why was my return rejected?</div>
                <div class="reason-text">{{ $return->rejection_reason }}</div>
            </div>

            @if($return->admin_notes)
            <div class="reason-box">
                <div class="reason-title">Additional Notes</div>
                <div class="reason-text">{{ $return->admin_notes }}</div>
            </div>
            @endif

            <div class="divider"></div>

            <!-- CTA Button -->
            <center>
                <a href="{{ route('contact') }}" class="button">Contact Support</a>
            </center>

            <p class="message" style="margin-top: 30px; text-align: center; font-size: 14px;">
                If you have questions about this decision, please don't hesitate to contact our support team.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-text">
                Thank you for shopping with {{ $siteName }}!
            </div>
            
            @if($siteEmail || $sitePhone || $siteAddress)
            <div class="contact-info">
                @if($siteEmail)
                <div class="contact-item">📧 {{ $siteEmail }}</div>
                @endif
                @if($sitePhone)
                <div class="contact-item">📞 {{ $sitePhone }}</div>
                @endif
                @if($siteAddress)
                <div class="contact-item">📍 {{ $siteAddress }}</div>
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