<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payout Rejected</title>
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
        .amount-box {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            padding: 30px;
            margin: 20px 0;
            border-radius: 10px;
            text-align: center;
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
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        table td:first-child {
            font-weight: bold;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0; font-size: 32px;">❌ Payout Rejected</h1>
        <p style="margin: 10px 0 0 0; font-size: 18px;">Your payout request has been rejected</p>
    </div>
    
    <div class="content">
        <p>Dear {{ $payout->seller->user->name }},</p>
        
        <div class="warning-box">
            <h2 style="margin: 0; color: #dc2626;">Payout Request Rejected</h2>
            <p style="margin: 10px 0 0 0; color: #991b1b;">Unfortunately, your payout request could not be processed at this time.</p>
        </div>
        
        <div class="amount-box">
            <p style="margin: 0; font-size: 16px; opacity: 0.9;">Requested Amount</p>
            <h1 style="margin: 10px 0 0 0; font-size: 48px;">₹{{ number_format($payout->amount, 2) }}</h1>
        </div>
        
        <div class="info-box">
            <h3 style="margin-top: 0; color: #ef4444;">Payout Details</h3>
            <table>
                <tr>
                    <td>Payout Number:</td>
                    <td>{{ $payout->payout_number }}</td>
                </tr>
                <tr>
                    <td>Amount:</td>
                    <td>₹{{ number_format($payout->amount, 2) }}</td>
                </tr>
                <tr>
                    <td>Status:</td>
                    <td><span style="color: #ef4444; font-weight: bold;">Rejected</span></td>
                </tr>
                <tr>
                    <td>Requested Date:</td>
                    <td>{{ $payout->created_at->format('M d, Y h:i A') }}</td>
                </tr>
                <tr>
                    <td>Rejected Date:</td>
                    <td>{{ now()->format('M d, Y h:i A') }}</td>
                </tr>
            </table>
        </div>
        
        @if($rejectionReason)
        <div class="info-box" style="border-left-color: #f59e0b; background: #fffbeb;">
            <h3 style="margin-top: 0; color: #d97706;">Rejection Reason</h3>
            <p style="margin-bottom: 0; color: #92400e;">{{ $rejectionReason }}</p>
        </div>
        @endif
        
        <div class="info-box" style="border-left-color: #3b82f6;">
            <h3 style="margin-top: 0; color: #3b82f6;">What Happens Next?</h3>
            <p>The requested amount has been refunded back to your wallet balance. You can:</p>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li>Review the rejection reason above</li>
                <li>Update your bank account details if needed</li>
                <li>Submit a new payout request</li>
                <li>Contact support for assistance</li>
            </ul>
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('seller.wallet.index') }}" class="button">
                View Wallet Balance →
            </a>
        </div>
        
        <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; color: #6b7280; font-size: 14px;">
            <strong>Need Help?</strong> If you have questions about this rejection, please contact our support team for clarification.
        </p>
    </div>
    
    <div class="footer">
        <p>This is an automated notification from {{ config('app.name') }}</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>
