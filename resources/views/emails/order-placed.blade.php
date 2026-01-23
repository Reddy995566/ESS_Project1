<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
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
        .logo {
            max-width: 180px;
            height: auto;
            margin-bottom: 20px;
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
        }
        .message {
            color: #4B4B4B;
            font-size: 15px;
            margin-bottom: 30px;
            line-height: 1.8;
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
        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #5C1F33;
            margin: 30px 0 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #E6B873;
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
            color: #5C1F33;
        }
        .address-box {
            background-color: #FAF5ED;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 4px solid #E6B873;
        }
        .address-title {
            font-size: 14px;
            font-weight: 700;
            color: #5C1F33;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .address-text {
            color: #4B4B4B;
            font-size: 14px;
            line-height: 1.8;
        }
        .total-box {
            background: #5C1F33;
            border-radius: 10px;
            padding: 25px;
            margin: 30px 0;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            color: #FAF5ED;
            font-size: 15px;
        }
        .total-row.grand {
            border-top: 2px solid #E6B873;
            margin-top: 10px;
            padding-top: 15px;
            font-size: 20px;
            font-weight: 700;
            color: #ffffff;
        }
        .button {
            display: inline-block;
            background: #495530;
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
        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(73, 85, 48, 0.4);
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
        .divider {
            height: 2px;
            background: #E6B873;
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
            <div class="header-subtitle">The Trusted Store</div>
        </div>

        <!-- Success Icon -->
        <div class="success-icon">
            <div class="checkmark"></div>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">Thank You, {{ $order->first_name }}!</div>
            <p class="message">
                Your order has been successfully placed and is being processed. We're excited to get your items to you soon!
            </p>

            <!-- Order Summary Box -->
            <div class="order-box">
                <div class="order-number">Order #{{ $order->order_number }}</div>
                <div class="order-details">
                    <div class="detail-row">
                        <div class="detail-label">Order Date:</div>
                        <div class="detail-value">{{ $order->created_at->format('d M, Y') }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Payment Method:</div>
                        <div class="detail-value">{{ ucfirst($order->payment_method) }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Order Status:</div>
                        <div class="detail-value" style="color: #495530;">{{ ucfirst($order->status) }}</div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="section-title">Order Items</div>
            @foreach($order->items as $item)
            <div class="product-item">
                @php
                    $productImage = null;
                    if($item->product) {
                        $displayImages = $item->product->display_images;
                        $productImage = !empty($displayImages) ? $displayImages[0] : null;
                    }
                @endphp
                @if($productImage)
                <img src="{{ $productImage }}" alt="{{ $item->product_name }}" class="product-image">
                @endif
                <div class="product-details">
                    <div class="product-name">{{ $item->product_name }}</div>
                    <div class="product-meta">
                        @if($item->variant)
                            Color: {{ $item->variant->color->name ?? 'N/A' }} | 
                            Size: {{ $item->variant->size->name ?? 'N/A' }}
                        @elseif($item->variant_name)
                            {{ $item->variant_name }}
                        @endif
                        <br>Quantity: {{ $item->quantity }}
                    </div>
                    <div class="product-price">₹{{ number_format($item->price * $item->quantity, 2) }}</div>
                </div>
            </div>
            @endforeach

            <!-- Order Total -->
            <div class="total-box">
                <div class="total-row">
                    <span>Subtotal:</span>
                    <span>₹{{ number_format($order->subtotal, 2) }}</span>
                </div>
                @if(isset($order->discount) && $order->discount > 0)
                <div class="total-row">
                    <span>Discount:</span>
                    <span>-₹{{ number_format($order->discount, 2) }}</span>
                </div>
                @endif
                @if(isset($order->shipping) && $order->shipping > 0)
                <div class="total-row">
                    <span>Shipping:</span>
                    <span>₹{{ number_format($order->shipping, 2) }}</span>
                </div>
                @else
                <div class="total-row">
                    <span>Shipping:</span>
                    <span>FREE</span>
                </div>
                @endif
                <div class="total-row grand">
                    <span>Total:</span>
                    <span>₹{{ number_format($order->total, 2) }}</span>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="section-title">Shipping Address</div>
            <div class="address-box">
                <div class="address-title">Deliver To:</div>
                <div class="address-text">
                    <strong>{{ $order->first_name }}{{ $order->last_name ? ' ' . $order->last_name : '' }}</strong><br>
                    {{ $order->address }}<br>
                    @if($order->address_line_2)
                        {{ $order->address_line_2 }}<br>
                    @endif
                    {{ $order->city }}, {{ $order->state }} - {{ $order->zipcode }}<br>
                    {{ $order->country }}<br>
                    Phone: {{ $order->phone }}
                </div>
            </div>

            <div class="divider"></div>

            <!-- CTA Button -->
            <center>
                <a href="{{ route('user.orders') }}" class="button">View Order Details</a>
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
