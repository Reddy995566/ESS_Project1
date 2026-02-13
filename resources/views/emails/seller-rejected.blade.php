<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Account Rejected</title>
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
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
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
        .warning-box {
            background: #fee2e2;
            border: 2px solid #ef4444;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            text-align: center;
        }
        .info-box {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #ef4444;
            border-radius: 5px;
        }
        .reason-box {
            background: #fffbeb;
            border: 2px solid #f59e0b;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
        }
        .button {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin: 20px 0;
            font-weight: bold;
            font-size: 16px;
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
        <h1 style="margin: 0; font-size: 32px;">❌ Application Rejected</h1>
        <p style="margin: 10px 0 0 0; font-size: 18px;">Your seller account application has been rejected</p>
    </div>
    
    <div class="content">
        <p>Dear {{ $seller->user->name }},</p>
        
        <div class="warning-box">
            <h2 style="margin: 0; color: #dc2626;">Application Not Approved</h2>
            <p style="margin: 10px 0 0 0; color: #991b1b;">Unfortunately, we are unable to approve your seller account application at this time.</p>
        </div>
        
        @if($seller->rejection_reason)
        <div class="reason-box">
            <h3 style="margin-top: 0; color: #d97706;">Rejection Reason</h3>
            <p style="margin-bottom: 0; color: #92400e; font-size: 15px;">{{ $seller->rejection_reason }}</p>
        </div>
        @endif
        
        <div class="info-box">
            <h3 style="margin-top: 0; color: #ef4444;">Application Details</h3>
            <p><strong>Business Name:</strong> {{ $seller->business_name }}</p>
            <p><strong>Email:</strong> {{ $seller->user->email }}</p>
            <p><strong>Application Date:</strong> {{ $seller->created_at->format('M d, Y') }}</p>
            <p style="margin-bottom: 0;"><strong>Status:</strong> <span style="color: #ef4444; font-weight: bold;">Rejected</span></p>
        </div>
        
        <div class="info-box" style="border-left-color: #3b82f6;">
            <h3 style="margin-top: 0; color: #3b82f6;">What Can You Do?</h3>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li>Review the rejection reason carefully</li>
                <li>Address the issues mentioned</li>
                <li>Update your business information if needed</li>
                <li>Submit a new application after making corrections</li>
                <li>Contact our support team for clarification</li>
            </ul>
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('seller.register') }}" class="button">
                Submit New Application →
            </a>
        </div>
        
        <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; color: #6b7280; font-size: 14px;">
            <strong>Need Help?</strong> If you have questions about this rejection or need assistance with your application, please contact our support team. We're here to help you succeed!
        </p>
    </div>
    
    <div class="footer">
        <p>This is an automated notification from {{ config('app.name') }}</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>
