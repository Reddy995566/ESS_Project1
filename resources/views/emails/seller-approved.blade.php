<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Account Approved</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 40px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9fafb;
            padding: 30px;
            border: 1px solid #e5e7eb;
        }
        .success-box {
            background: #d1fae5;
            border: 2px solid #10b981;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            text-align: center;
        }
        .info-box {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #10b981;
            border-radius: 5px;
        }
        .button {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin: 20px 0;
            font-weight: bold;
            font-size: 16px;
        }
        .feature-list {
            list-style: none;
            padding: 0;
        }
        .feature-list li {
            padding: 10px 0;
            padding-left: 30px;
            position: relative;
        }
        .feature-list li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #10b981;
            font-weight: bold;
            font-size: 18px;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #6b7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0; font-size: 32px;">🎉 Congratulations!</h1>
        <p style="margin: 10px 0 0 0; font-size: 18px;">Your Seller Account Has Been Approved</p>
    </div>
    
    <div class="content">
        <p>Dear {{ $seller->user->name }},</p>
        
        <div class="success-box">
            <h2 style="margin: 0; color: #059669;">Welcome to {{ config('app.name') }}!</h2>
            <p style="margin: 10px 0 0 0; color: #065f46;">Your seller account has been successfully approved by our admin team.</p>
        </div>
        
        <p>We're excited to have <strong>{{ $seller->business_name }}</strong> as part of our marketplace!</p>
        
        <div class="info-box">
            <h3 style="margin-top: 0; color: #059669;">What's Next?</h3>
            <ul class="feature-list">
                <li>Login to your seller dashboard</li>
                <li>Add your products to the marketplace</li>
                <li>Manage your inventory and orders</li>
                <li>Track your sales and earnings</li>
                <li>Receive payments directly to your bank account</li>
            </ul>
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('seller.login') }}" class="button">
                Login to Seller Dashboard →
            </a>
        </div>
        
        <div class="info-box">
            <h3 style="margin-top: 0; color: #059669;">Your Account Details</h3>
            <p><strong>Business Name:</strong> {{ $seller->business_name }}</p>
            <p><strong>Email:</strong> {{ $seller->user->email }}</p>
            <p><strong>Login URL:</strong> <a href="{{ route('seller.login') }}">{{ route('seller.login') }}</a></p>
        </div>
        
        <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; color: #6b7280; font-size: 14px;">
            <strong>Need Help?</strong> If you have any questions or need assistance, please don't hesitate to contact our support team.
        </p>
    </div>
    
    <div class="footer">
        <p>This is an automated notification from {{ config('app.name') }}</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>
