<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refund Processed</title>
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
        .success-icon {
            width: 80px;
            height: 80px;
            background-color: #16A085;
            border-radius: 50%;
            margin: -40px auto 0;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(22, 160, 133, 0.3);
        }
        .money-symbol {
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
            background: #E8F8F5;
            border: 2px solid #16A085;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
        }
        .return-number {
            font-size: 18px;
            font-weight: 700;
            color: #16A085;
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
            border-bottom: 2px solid #16A085;
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
            color: #16A085;
        }
        .refund-box {
            background: #16A085;
            border-radius: 10px;
            padding: 25px;
            margin: 30px 0;
            text-align: center;
        }
        .refund-amount {
            font-size: 32px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 10px;
            font-family: 'Playfair Display', serif;
        }
        .refund-text {
            color: #E8F8F5;
            font-size: 16px;
            font-weight: 500;
        }
        .info-box {
            background-color: #E8F8F5;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 4px solid #16A085;
        }
        .info-title {
            font-size: 14px;
            font-weight: 700;
            color: #16A085;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .info-text {
            color: #4B4B4B;
            font-size: 14px;
            line-height: 1.8;
        }
        .button {
            display: inline-block;
            background: #16A085;
            color: #ffffff;
            text-decoration: none;
            padding: 15px 40px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            text-align: center;
            margin: 20px 0;
            box-shadow: 0 4px 15px rgba(22, 160, 133, 0.3);
        }
        .footer {
            background-color: #FAF5ED;
            padding: 30px;
            text-align: center;
            border-top: 2px solid #16A085;
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
            background: #16A085;
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

        <!-- Success Icon -->
        <div class="success-icon">
            <div class="money-symbol">₹</div>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">Refund Processed Successfully!</div>
            <p class="message">
                Hi {{ $return->user->name }}, great news! Your refund has been processed and the amount will be credited to your account soon.
            </p>

            <!-- Refund Amount Box -->
            <div class="refund-box">
                <div class="refund-amount">₹{{ number_format($return->refund_amount, 2) }}</div>
                <div class="refund-text">Refund Amount</div>
            </div>

            <!-- Return Summary Box -->
            <div class="return-box">
                <div class="return-number">Return #{{ $return->return_number }}</div>
                <div class="return-details">
                    <div class="detail-row">
                        <div class="detail-label">Refund Date:</div>
                        <div class="detail-value">{{ $return->refunded_at->format('d M, Y') }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Order Number:</div>
                        <div class="detail-value">{{ $return->order->order_number }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Refund Method:</div>
                        <div class="detail-value">{{ ucfirst(str_replace('_', ' ', $return->refund_method)) }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Status:</div>
                        <div class="detail-value" style="color: #16A085;">Refunded</div>
                    </div>
                </div>
            </div>

            <!-- Product Details -->
            <div class="section-title">Returned Product</div>
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
                        Returned Quantity: {{ $return->quantity }}
                    </div>
                    <div class="product-price">₹{{ number_format($return->refund_amount, 2) }}</div>
                </div>
            </div>

            <!-- Refund Timeline -->
            <div class="section-title">Refund Timeline</div>
            <div class="info-box">
                <div class="info-title">When will I receive my refund?</div>
                <div class="info-text">
                    • <strong>Credit/Debit Card:</strong> 5-7 business days<br>
                    • <strong>Net Banking:</strong> 3-5 business days<br>
                    • <strong>UPI/Wallet:</strong> 1-3 business days<br><br>
                    The exact timeline may vary depending on your bank's processing time.
                </div>
            </div>

            @if($return->admin_notes)
            <div class="info-box">
                <div class="info-title">Admin Notes</div>
                <div class="info-text">{{ $return->admin_notes }}</div>
            </div>
            @endif

            <div class="divider"></div>

            <!-- CTA Button -->
            <center>
                <a href="{{ route('user.returns.show', $return->id) }}" class="button">View Return Details</a>
            </center>

            <p class="message" style="margin-top: 30px; text-align: center; font-size: 14px;">
                Thank you for your patience throughout the return process!
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