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
            border-bottom: 2px solid #8B5CF6;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #8B5CF6;
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
            color: #8B5CF6;
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
            background-color: #8B5CF6;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .section-title {
            color: #8B5CF6;
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
        .performance-score {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }
        .score-high { background: #10B981; color: white; }
        .score-medium { background: #F59E0B; color: white; }
        .score-low { background: #EF4444; color: white; }
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
            <h3>{{ number_format($summary['total_products']) }}</h3>
            <p>Total Products</p>
        </div>
        <div class="summary-card">
            <h3>{{ number_format($summary['products_sold']) }}</h3>
            <p>Products with Sales</p>
        </div>
        <div class="summary-card">
            <h3>₹{{ number_format($summary['total_revenue'], 0) }}</h3>
            <p>Total Revenue</p>
        </div>
        <div class="summary-card">
            <h3>₹{{ number_format($summary['average_revenue_per_product'], 0) }}</h3>
            <p>Avg Revenue/Product</p>
        </div>
    </div>

    <div class="section-title">Product Performance Analysis</div>
    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Category</th>
                <th>Brand</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Sold</th>
                <th>Revenue</th>
                <th>Orders</th>
                <th>Performance</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ Str::limit($product['name'], 30) }}</td>
                <td>{{ $product['category'] }}</td>
                <td>{{ $product['brand'] }}</td>
                <td>₹{{ number_format($product['price'], 0) }}</td>
                <td>{{ number_format($product['stock']) }}</td>
                <td>{{ number_format($product['sold']) }}</td>
                <td>₹{{ number_format($product['revenue'], 0) }}</td>
                <td>{{ number_format($product['orders']) }}</td>
                <td>
                    @php
                        $score = $product['performance_score'];
                        $class = $score >= 70 ? 'score-high' : ($score >= 40 ? 'score-medium' : 'score-low');
                    @endphp
                    <span class="performance-score {{ $class }}">{{ number_format($score, 1) }}%</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>This report was generated automatically by the system on {{ $generated_at }}</p>
        <p>Performance Score: Based on sales volume, revenue, and order frequency</p>
        <p>© {{ date('Y') }} {{ config('app.name') }} - All rights reserved</p>
    </div>
</body>
</html>