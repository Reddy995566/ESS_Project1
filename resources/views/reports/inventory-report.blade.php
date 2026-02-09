<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #F59E0B;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #F59E0B;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .summary {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .summary-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            width: 23%;
        }
        .summary-card h3 {
            margin: 0 0 10px 0;
            color: #F59E0B;
            font-size: 18px;
        }
        .summary-card p {
            margin: 0;
            font-size: 11px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #F59E0B;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .section-title {
            color: #F59E0B;
            font-size: 16px;
            font-weight: bold;
            margin: 30px 0 15px 0;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        .stock-status {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-in-stock { background: #10B981; color: white; }
        .status-low-stock { background: #F59E0B; color: white; }
        .status-out-of-stock { background: #EF4444; color: white; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        @if($seller)
            <p><strong>Seller:</strong> {{ $seller->business_name ?? $seller->name }}</p>
        @endif
        <p><strong>Generated:</strong> {{ $generated_at }}</p>
    </div>

    <div class="summary">
        <div class="summary-card">
            <h3>{{ number_format($summary['total_products']) }}</h3>
            <p>Total Products</p>
        </div>
        <div class="summary-card">
            <h3>₹{{ number_format($summary['total_inventory_value'], 0) }}</h3>
            <p>Inventory Value</p>
        </div>
        <div class="summary-card">
            <h3>{{ number_format($summary['low_stock_items']) }}</h3>
            <p>Low Stock Items</p>
        </div>
        <div class="summary-card">
            <h3>{{ number_format($summary['out_of_stock_items']) }}</h3>
            <p>Out of Stock</p>
        </div>
    </div>

    <div class="section-title">Inventory Status</div>
    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>SKU</th>
                <th>Category</th>
                <th>Brand</th>
                <th>Current Stock</th>
                <th>Low Stock Threshold</th>
                <th>Unit Price</th>
                <th>Stock Value</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inventory as $item)
            <tr>
                <td>{{ Str::limit($item['name'], 30) }}</td>
                <td>{{ $item['sku'] }}</td>
                <td>{{ $item['category'] }}</td>
                <td>{{ $item['brand'] }}</td>
                <td>{{ number_format($item['current_stock']) }}</td>
                <td>{{ number_format($item['low_stock_threshold']) }}</td>
                <td>₹{{ number_format($item['price'], 0) }}</td>
                <td>₹{{ number_format($item['value'], 0) }}</td>
                <td>
                    @php
                        $status = strtolower(str_replace(' ', '-', $item['status']));
                        $class = 'status-' . $status;
                    @endphp
                    <span class="stock-status {{ $class }}">{{ $item['status'] }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>This report was generated automatically by the system on {{ $generated_at }}</p>
        <p>Stock Status: In Stock (above threshold), Low Stock (at or below threshold), Out of Stock (zero quantity)</p>
        <p>© {{ date('Y') }} {{ config('app.name') }} - All rights reserved</p>
    </div>
</body>
</html>