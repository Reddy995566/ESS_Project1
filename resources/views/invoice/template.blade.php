<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $order->order_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.3;
            color: #333;
            background: #f5f5f5;
            font-size: 14px;
        }

        .invoice-container {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 0;
            min-height: auto;
        }

        .invoice-header {
            padding: 40px 40px 20px 40px;
            border-bottom: 1px solid #eee;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .company-info h1 {
            font-size: 32px;
            font-weight: 300;
            color: #333;
            margin-bottom: 5px;
        }

        .company-info p {
            font-size: 14px;
            color: #666;
            margin: 2px 0;
        }

        .invoice-title {
            text-align: right;
        }

        .invoice-title h2 {
            font-size: 48px;
            font-weight: 300;
            color: #333;
            margin-bottom: 5px;
        }

        .invoice-number {
            font-size: 18px;
            color: #666;
            font-weight: 600;
        }

        .invoice-date {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }

        .invoice-body {
            padding: 25px 40px;
        }

        .address-section {
            display: flex;
            justify-content: space-between;
            gap: 50px;
            margin-bottom: 30px;
        }

        .address-block {
            flex: 1;
            min-width: 0;
        }

        .address-block h3 {
            font-size: 14px;
            font-weight: 600;
            color: #4a90e2;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #4a90e2;
        }

        .address-block p {
            font-size: 14px;
            color: #333;
            margin: 3px 0;
            line-height: 1.4;
        }

        .address-block .name {
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 8px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .items-table thead {
            background: #4a90e2;
        }

        .items-table th {
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: white;
        }

        .items-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
            vertical-align: top;
        }

        .items-table tbody tr:last-child td {
            border-bottom: 2px solid #ddd;
        }

        .product-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 4px;
        }

        .product-variant {
            font-size: 12px;
            color: #666;
            margin: 2px 0;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .totals-section {
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
        }

        .totals-table {
            width: 300px;
        }

        .totals-table tr td {
            padding: 8px 15px;
            font-size: 14px;
            border: none;
        }

        .totals-table tr td:first-child {
            text-align: right;
            color: #666;
            font-weight: 500;
        }

        .totals-table tr td:last-child {
            text-align: right;
            font-weight: 600;
            color: #333;
        }

        .total-row {
            border-top: 2px solid #333 !important;
            font-weight: 700 !important;
            font-size: 16px !important;
        }

        .total-row td {
            padding-top: 15px !important;
            color: #333 !important;
        }

        .invoice-footer {
            background: #f8f9fa;
            padding: 20px 40px;
            border-top: 1px solid #eee;
            text-align: center;
        }

        .footer-note {
            color: #666;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .footer-contact {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .footer-contact span {
            color: #4a90e2;
            font-size: 12px;
            font-weight: 500;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-processing {
            background: #cce5ff;
            color: #004085;
        }

        .status-shipped {
            background: #d4edda;
            color: #155724;
        }

        .status-delivered {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        @media print {
            body {
                background: white !important;
                font-size: 12px;
                line-height: 1.3;
            }
            
            .invoice-container {
                box-shadow: none !important;
                margin: 0 !important;
                padding: 0 !important;
                max-width: none !important;
                width: 100% !important;
            }
            
            .invoice-header {
                padding: 20px 30px 15px 30px !important;
                page-break-inside: avoid;
            }
            
            .invoice-body {
                padding: 20px 30px !important;
            }
            
            /* Force side-by-side layout in print */
            div[style*="display: flex"] {
                display: flex !important;
                justify-content: space-between !important;
                gap: 30px !important;
                margin-bottom: 25px !important;
                page-break-inside: avoid !important;
            }
            
            div[style*="flex: 1"] {
                flex: 1 !important;
                min-width: 0 !important;
            }
            
            .items-table {
                margin: 20px 0 !important;
                page-break-inside: avoid;
            }
            
            .items-table th {
                padding: 8px 10px !important;
                font-size: 11px !important;
            }
            
            .items-table td {
                padding: 8px 10px !important;
                font-size: 12px !important;
            }
            
            .totals-section {
                margin-top: 20px !important;
                page-break-inside: avoid;
            }
            
            .invoice-footer {
                padding: 15px 30px !important;
                page-break-inside: avoid;
            }
            
            .no-print {
                display: none !important;
            }
            
            .company-info h1 {
                font-size: 24px !important;
            }
            
            .invoice-title h2 {
                font-size: 36px !important;
            }
            
            .address-block h3 {
                font-size: 12px !important;
            }
            
            .address-block p {
                font-size: 12px !important;
            }
            
            .product-name {
                font-size: 13px !important;
            }
            
            .product-variant {
                font-size: 11px !important;
            }
            
            /* Ensure single page layout */
            @page {
                margin: 0.5in;
                size: A4;
            }
            
            /* Prevent page breaks in critical sections */
            .invoice-header,
            .address-section,
            .items-table,
            .totals-section {
                page-break-inside: avoid;
            }
        }

        @media (max-width: 768px) {
            .invoice-container {
                margin: 10px;
            }
            
            .header-content {
                flex-direction: column;
                gap: 20px;
            }
            
            .invoice-title {
                text-align: left;
            }
            
            .address-section {
                flex-direction: column !important;
                gap: 30px !important;
            }
            
            .items-table {
                font-size: 12px;
            }
            
            .items-table th,
            .items-table td {
                padding: 8px 10px;
            }
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #4a90e2;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            box-shadow: 0 2px 10px rgba(74, 144, 226, 0.3);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .print-button:hover {
            background: #357abd;
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(74, 144, 226, 0.4);
        }
    </style>
</head>
<body>
    <button class="print-button no-print" onclick="window.print()">
        🖨️ Print Invoice
    </button>

    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="header-content">
                <div class="company-info">
                    <h1>{{ $siteSettings['name'] ?? 'Switch2kart' }}</h1>
                    @if(!empty($siteSettings['address']))
                        <p>{{ $siteSettings['address'] }}</p>
                    @endif
                    @if(!empty($siteSettings['email']))
                        <p>Email: {{ $siteSettings['email'] }}</p>
                    @endif
                    @if(!empty($siteSettings['phone']))
                        <p>Phone: {{ $siteSettings['phone'] }}</p>
                    @endif
                    @if(!empty($siteSettings['gst']))
                        <p>GST: {{ $siteSettings['gst'] }}</p>
                    @endif
                </div>
                <div class="invoice-title">
                    <h2>Invoice</h2>
                    <p class="invoice-number">#{{ $order->order_number }}</p>
                    <p class="invoice-date">{{ $order->created_at->format('d M, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Body -->
        <div class="invoice-body">
            <!-- Address Section -->
            <div style="display: flex; justify-content: space-between; gap: 50px; margin-bottom: 30px;">
                <div style="flex: 1; min-width: 0;">
                    <h3 style="font-size: 13px; font-weight: 600; color: #4a90e2; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px; padding-bottom: 6px; border-bottom: 2px solid #4a90e2;">Billing Address</h3>
                    <p style="font-size: 13px; color: #333; margin: 2px 0; line-height: 1.3; font-weight: 600;">{{ $order->first_name }} {{ $order->last_name }}</p>
                    <p style="font-size: 13px; color: #333; margin: 2px 0; line-height: 1.3;">{{ $order->address }}</p>
                    @if($order->address_line_2)
                        <p style="font-size: 13px; color: #333; margin: 2px 0; line-height: 1.3;">{{ $order->address_line_2 }}</p>
                    @endif
                    <p style="font-size: 13px; color: #333; margin: 2px 0; line-height: 1.3;">{{ $order->city }}, {{ $order->state }} {{ $order->zipcode }}</p>
                    <p style="font-size: 13px; color: #333; margin: 2px 0; line-height: 1.3;">{{ $order->country }}</p>
                    <p style="font-size: 13px; color: #333; margin: 2px 0; line-height: 1.3;">{{ $order->email }}</p>
                    <p style="font-size: 13px; color: #333; margin: 2px 0; line-height: 1.3;">{{ $order->phone }}</p>
                </div>
                <div style="flex: 1; min-width: 0;">
                    <h3 style="font-size: 13px; font-weight: 600; color: #4a90e2; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px; padding-bottom: 6px; border-bottom: 2px solid #4a90e2;">Invoice Details</h3>
                    <p style="font-size: 13px; color: #333; margin: 2px 0; line-height: 1.3;"><strong>Invoice Number:</strong> {{ $order->order_number }}</p>
                    <p style="font-size: 13px; color: #333; margin: 2px 0; line-height: 1.3;"><strong>Order Date:</strong> {{ $order->created_at->format('d M, Y') }}</p>
                    <p style="font-size: 13px; color: #333; margin: 2px 0; line-height: 1.3;"><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
                    <p style="font-size: 13px; color: #333; margin: 2px 0; line-height: 1.3;"><strong>Payment Status:</strong> {{ ucfirst($order->payment_status) }}</p>
                    <p style="font-size: 13px; color: #333; margin: 2px 0; line-height: 1.3;"><strong>Order Status:</strong> <span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span></p>
                    @if($order->transaction_id)
                        <p style="font-size: 13px; color: #333; margin: 2px 0; line-height: 1.3;"><strong>Transaction ID:</strong> {{ $order->transaction_id }}</p>
                    @endif
                </div>
            </div>

            <!-- Items Table -->
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 50%">Product Description</th>
                        <th class="text-center" style="width: 12%">Quantity</th>
                        <th class="text-right" style="width: 15%">Unit Price</th>
                        <th class="text-right" style="width: 10%">Discount</th>
                        <th class="text-right" style="width: 13%">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>
                            <div class="product-name">{{ $item->product_name }}</div>
                            @if($item->variant_name)
                                <div class="product-variant">{{ $item->variant_name }}</div>
                            @endif
                            @if($item->seller_id && $item->seller)
                                <div class="product-variant">Seller: {{ $item->seller->business_name ?? 'N/A' }}</div>
                            @endif
                        </td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">₹{{ number_format($item->price, 2) }}</td>
                        <td class="text-right">
                            @php
                                $itemDiscount = ($item->price * $item->quantity) - $item->total;
                            @endphp
                            @if($itemDiscount > 0)
                                ₹{{ number_format($itemDiscount, 2) }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-right">₹{{ number_format($item->total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Totals -->
            <div class="totals-section">
                <table class="totals-table">
                    <tr>
                        <td>Subtotal</td>
                        <td>₹{{ number_format($order->subtotal, 2) }}</td>
                    </tr>
                    @if($order->discount > 0)
                    <tr>
                        <td>Discount</td>
                        <td>₹{{ number_format($order->discount, 2) }}</td>
                    </tr>
                    @endif
                    @if($order->shipping > 0)
                    <tr>
                        <td>Shipping</td>
                        <td>₹{{ number_format($order->shipping, 2) }}</td>
                    </tr>
                    @endif
                    @if($order->tax > 0)
                    <tr>
                        <td>Tax</td>
                        <td>₹{{ number_format($order->tax, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="total-row">
                        <td>Total Amount Paid</td>
                        <td>₹{{ number_format($order->total, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Total Due</td>
                        <td>₹0</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Footer -->
        <div class="invoice-footer">
            <p class="footer-note">
                Thank you for your business! If you have any questions about this invoice, please contact us.
            </p>
            <div class="footer-contact">
                @if(!empty($siteSettings['email']))
                    <span>📧 {{ $siteSettings['email'] }}</span>
                @endif
                @if(!empty($siteSettings['phone']))
                    <span>📞 {{ $siteSettings['phone'] }}</span>
                @endif
                @if(!empty($siteSettings['website']))
                    <span>🌐 {{ $siteSettings['website'] }}</span>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Auto-focus for better printing experience
        window.addEventListener('load', function() {
            // Add keyboard shortcut for printing
            document.addEventListener('keydown', function(e) {
                if (e.ctrlKey && e.key === 'p') {
                    e.preventDefault();
                    window.print();
                }
            });
        });
    </script>
</body>
</html>