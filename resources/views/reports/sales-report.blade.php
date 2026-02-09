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
            border-bottom: 2px solid #4F46E5;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #4F46E5;
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
            color: #4F46E5;
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
            background-color: #4F46E5;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .section-title {
            color: #4F46E5;
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
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p><strong>Period:</strong> {{ $period }}</p>
        @if($seller)
            <p><strong>Seller:</strong> {{ $seller->business_name ?? $seller->name }}</p>
        @endif
        <p><strong>Generated:</strong> {{ $generated_at }}</p>
    </div>

    <div class="summary">
        <div class="summary-card">
            <h3>₹{{ number_format($summary['total_revenue'], 0) }}</h3>
            <p>Total Revenue</p>
        </div>
        <div class="summary-card">
            <h3>{{ number_format($summary['total_orders']) }}</h3>
            <p>Total Orders</p>
        </div>
        <div class="summary-card">
            <h3>{{ number_format($summary['total_items']) }}</h3>
            <p>Items Sold</p>
        </div>
        <div class="summary-card">
            <h3>₹{{ number_format($summary['average_order_value'], 0) }}</h3>
            <p>Avg Order Value</p>
        </div>
    </div>

    <div class="section-title">Daily Sales Breakdown</div>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Revenue</th>
                <th>Orders</th>
                <th>Items</th>
                <th>Avg Order Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach($daily_breakdown as $day)
            <tr>
                <td>{{ $day['date'] }}</td>
                <td>₹{{ number_format($day['revenue'], 0) }}</td>
                <td>{{ number_format($day['orders']) }}</td>
                <td>{{ number_format($day['items']) }}</td>
                <td>₹{{ $day['orders'] > 0 ? number_format($day['revenue'] / $day['orders'], 0) : 0 }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Top Performing Products</div>
    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Quantity Sold</th>
                <th>Revenue</th>
                <th>Orders</th>
                <th>Avg Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($top_products as $product)
            <tr>
                <td>{{ $product['name'] }}</td>
                <td>{{ number_format($product['quantity']) }}</td>
                <td>₹{{ number_format($product['revenue'], 0) }}</td>
                <td>{{ number_format($product['orders']) }}</td>
                <td>₹{{ $product['quantity'] > 0 ? number_format($product['revenue'] / $product['quantity'], 0) : 0 }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>This report was generated automatically by the system on {{ $generated_at }}</p>
        <p>© {{ date('Y') }} {{ config('app.name') }} - All rights reserved</p>
    </div>
</body>
</html>