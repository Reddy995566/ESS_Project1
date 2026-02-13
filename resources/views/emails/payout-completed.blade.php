<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payout Completed</title>
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
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
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
            background: #ede9fe;
            border: 2px solid #8b5cf6;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            text-align: center;
        }
        .info-box {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #8b5cf6;
            border-radius: 5px;
        }
        .amount-box {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            color: white;
            padding: 30px;
            margin: 20px 0;
            border-radius: 10px;
            text-align: center;
        }
        .button {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
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
        <h1 style="margin: 0; font-size: 32px;">🎉 Payment Transferred!</h1>
        <p style="margin: 10px 0 0 0; font-size: 18px;">Your payout has been successfully completed</p>
    </div>
    
    <div class="content">
        <p>Dear {{ $payout->seller->user->name }},</p>
        
        <div class="success-box">
            <h2 style="margin: 0; color: #7c3aed;">Payment Successful!</h2>
            <p style="margin: 10px 0 0 0; color: #5b21b6;">The funds have been successfully transferred to your bank account.</p>
        </div>
        
        <div class="amount-box">
            <p style="margin: 0; font-size: 16px; opacity: 0.9;">Amount Transferred</p>
            <h1 style="margin: 10px 0 0 0; font-size: 48px;">₹{{ number_format($payout->amount, 2) }}</h1>
        </div>
        
        <div class="info-box">
            <h3 style="margin-top: 0; color: #8b5cf6;">Payout Details</h3>
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
                    <td><span style="color: #8b5cf6; font-weight: bold;">Completed</span></td>
                </tr>
                <tr>
                    <td>Transaction ID:</td>
                    <td><strong>{{ $payout->transaction_id }}</strong></td>
                </tr>
                <tr>
                    <td>Transaction Date:</td>
                    <td>{{ \Carbon\Carbon::parse($payout->transaction_date)->format('M d, Y') }}</td>
                </tr>
                <tr>
                    <td>Completed Date:</td>
                    <td>{{ $payout->processed_at->format('M d, Y h:i A') }}</td>
                </tr>
            </table>
        </div>
        
        <div class="info-box" style="border-left-color: #10b981; background: #d1fae5;">
            <h3 style="margin-top: 0; color: #059669;">✓ Payment Confirmation</h3>
            <p style="margin-bottom: 0; color: #065f46;">The payment has been credited to your registered bank account. Please allow 1-2 business days for the funds to reflect in your account statement.</p>
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('seller.payouts.index') }}" class="button">
                View Transaction History →
            </a>
        </div>
        
        <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; color: #6b7280; font-size: 14px;">
            <strong>Keep this email for your records.</strong> If you have any questions about this transaction, please contact our support team with the transaction ID mentioned above.
        </p>
    </div>
    
    <div class="footer">
        <p>This is an automated notification from {{ config('app.name') }}</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>
