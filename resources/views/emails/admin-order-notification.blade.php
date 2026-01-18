<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order Notification</title>
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
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
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
            color: #D1FAE5;
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .alert-icon {
            width: 80px;
            height: 80px;
            background-color: #059669;
            border-radius: 50%;
            margin: -40px auto 0;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(5, 150, 105, 0.4);
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
            color: #059669;
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
        .order-box {
            background: linear-gradient(135deg, #FAF5ED 0%, #EDE5DA 100%);
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
        .customer-box {
            background-color: #FAF5ED;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 4px solid #059669;
        }
        .customer-title {
            font-size: 14px;
            font-weight: 700;
            color: #5C1F33;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .customer-text {
            color: #4B4B4B;
            font-size: 14px;
            line-height: 1.8;
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
        .total-box {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            border-radius: 10px;
            padding: 25px;
            margin: 30px 0;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            color: #D1FAE5;
            font-size: 15px;
        }
        .total-row.grand {
            border-top: 2px solid #D1FAE5;
            margin-top: 10px;
            padding-top: 15px;
            font-size: 20px;
            font-weight: 700;
            color: #ffffff;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: #ffffff;
            text-decoration: none;
            padding: 15px 40px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            text-align: center;
            margin: 20px 0;
            box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3);
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
            @endif
            <div class="site-name">🛍️ NEW ORDER</div>
            <div class="header-subtitle">Admin Notification</div>
        </div>

        <!-- Alert Icon -->
        <div class="alert-icon">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/>
            </svg>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">New Order Received!</div>
            
            <p class="message">
                A new order has been placed on {{ $siteName }}. Please review and process it.
            </p>

            <!-- Order Summary Box -->
            <div class="order-box">
                <div class="order-number">Order #{{ $order->order_number }}</div>
                <div class="order-details">
                    <div class="detail-row">
                        <div class="detail-label">Order Date:</div>
                        <div class="detail-value">{{ $order->created_at->format('d M, Y h:i A') }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Payment Method:</div>
                        <div class="detail-value">{{ ucfirst($order->payment_method) }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Payment Status:</div>
                        <div class="detail-value" style="color: {{ $order->payment_status == 'paid' ? '#059669' : '#DC2626' }};">
                            {{ ucfirst($order->payment_status) }}
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Order Status:</div>
                        <div class="detail-value" style="color: #059669;">{{ ucfirst($order->status) }}</div>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="section-title">👤 Customer Information</div>
            <div class="customer-box">
                <div class="customer-title">Customer Details:</div>
                <div class="customer-text">
                    <strong>{{ $order->first_name }} {{ $order->last_name }}</strong><br>
                    Email: {{ $order->email }}<br>
                    Phone: {{ $order->phone }}
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="customer-box">
                <div class="customer-title">Shipping Address:</div>
                <div class="customer-text">
                    {{ $order->address }}<br>
                    @if($order->address_line_2)
                        {{ $order->address_line_2 }}<br>
                    @endif
                    {{ $order->city }}, {{ $order->state }} - {{ $order->zipcode }}<br>
                    {{ $order->country }}
                </div>
            </div>

            <!-- Order Items -->
            <div class="section-title">📦 Order Items</div>
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

            <div class="divider"></div>

            <!-- CTA Button -->
            <center>
                <a href="{{ route('admin.orders.show', $order->id) }}" class="button">View Order Details</a>
            </center>

            <p class="message" style="margin-top: 30px; font-size: 14px; color: #6B6B6B;">
                Please process this order as soon as possible.
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
