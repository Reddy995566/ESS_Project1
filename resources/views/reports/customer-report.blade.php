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
            border-bottom: 2px solid #10B981;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #10B981;
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
            color: #10B981;
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
            background-color: #10B981;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .section-title {
            color: #10B981;
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
        .customer-segment {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }
        .segment-vip { background: #8B5CF6; color: white; }
        .segment-high { background: #10B981; color: white; }
        .segment-regular { background: #F59E0B; color: white; }
        .segment-new { background: #6B7280; color: white; }
        .segments-overview {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .segment-card {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            width: 23%;
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
            <h3>{{ number_format($summary['total_customers']) }}</h3>
            <p>Total Customers</p>
        </div>
        <div class="summary-card">
            <h3>₹{{ number_format($summary['total_revenue'], 0) }}</h3>
            <p>Total Revenue</p>
        </div>
        <div class="summary-card">
            <h3>₹{{ number_format($summary['average_customer_value'], 0) }}</h3>
            <p>Avg Customer Value</p>
        </div>
        <div class="summary-card">
            <h3>{{ number_format($summary['repeat_customers']) }}</h3>
            <p>Repeat Customers</p>
        </div>
    </div>

    <div class="section-title">Customer Segments Overview</div>
    <div class="segments-overview">
        @foreach($segments as $segmentName => $segment)
        <div class="segment-card">
            <h3>{{ number_format($segment['count']) }}</h3>
            <p>{{ ucfirst($segmentName) }} Customers</p>
            <small>₹{{ number_format($segment['revenue'], 0) }} revenue</small>
        </div>
        @endforeach
    </div>

    <div class="section-title">Customer Analysis</div>
    <table>
        <thead>
            <tr>
                <th>Customer Name</th>
                <th>Email</th>
                <th>Orders</th>
                <th>Total Spent</th>
                <th>Avg Order Value</th>
                <th>Last Order</th>
                <th>Days Since</th>
                <th>Segment</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $customer)
            <tr>
                <td>{{ $customer['name'] }}</td>
                <td>{{ $customer['email'] }}</td>
                <td>{{ number_format($customer['total_orders']) }}</td>
                <td>₹{{ number_format($customer['total_spent'], 0) }}</td>
                <td>₹{{ number_format($customer['average_order_value'], 0) }}</td>
                <td>{{ $customer['last_order'] }}</td>
                <td>{{ $customer['days_since_last_order'] ?? 'N/A' }}</td>
                <td>
                    @php
                        $segment = strtolower($customer['segment']);
                        $class = 'segment-' . str_replace(' ', '-', $segment);
                    @endphp
                    <span class="customer-segment {{ $class }}">{{ $customer['segment'] }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>This report was generated automatically by the system on {{ $generated_at }}</p>
        <p>Customer Segments: VIP (₹10k+ or 10+ orders), High Value (₹5k+ or 5+ orders), Regular (₹1k+ or 2+ orders), New (others)</p>
        <p>© {{ date('Y') }} {{ config('app.name') }} - All rights reserved</p>
    </div>
</body>
</html>