<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Seller Registration</title>
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
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9fafb;
            padding: 30px;
            border: 1px solid #e5e7eb;
        }
        .info-box {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #6366f1;
            border-radius: 5px;
        }
        .info-row {
            margin: 10px 0;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: bold;
            color: #6366f1;
            display: inline-block;
            width: 180px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin: 20px 0;
            font-weight: bold;
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
        <h1 style="margin: 0;">🏪 New Seller Registration</h1>
        <p style="margin: 10px 0 0 0;">Action Required - Review Application</p>
    </div>
    
    <div class="content">
        <p>Hello Admin,</p>
        
        <p>A new seller has registered on the platform and is awaiting your approval.</p>
        
        <div class="info-box">
            <h3 style="margin-top: 0; color: #6366f1;">Business Information</h3>
            <div class="info-row">
                <span class="label">Business Name:</span>
                <span>{{ $seller->business_name }}</span>
            </div>
            <div class="info-row">
                <span class="label">Business Type:</span>
                <span>{{ ucfirst($seller->business_type) }}</span>
            </div>
            <div class="info-row">
                <span class="label">PAN Number:</span>
                <span>{{ $seller->pan_number }}</span>
            </div>
            @if($seller->gst_number)
            <div class="info-row">
                <span class="label">GST Number:</span>
                <span>{{ $seller->gst_number }}</span>
            </div>
            @endif
        </div>
        
        <div class="info-box">
            <h3 style="margin-top: 0; color: #6366f1;">Contact Information</h3>
            <div class="info-row">
                <span class="label">Contact Person:</span>
                <span>{{ $seller->user->name }}</span>
            </div>
            <div class="info-row">
                <span class="label">Email:</span>
                <span>{{ $seller->user->email }}</span>
            </div>
            <div class="info-row">
                <span class="label">Phone:</span>
                <span>{{ $seller->user->mobile }}</span>
            </div>
            <div class="info-row">
                <span class="label">Address:</span>
                <span>{{ $seller->business_address }}</span>
            </div>
        </div>
        
        <div class="info-box">
            <h3 style="margin-top: 0; color: #6366f1;">Bank Details</h3>
            <div class="info-row">
                <span class="label">Account Holder:</span>
                <span>{{ $seller->bankDetails->account_holder_name }}</span>
            </div>
            <div class="info-row">
                <span class="label">Bank Name:</span>
                <span>{{ $seller->bankDetails->bank_name }}</span>
            </div>
            <div class="info-row">
                <span class="label">Account Number:</span>
                <span>{{ $seller->bankDetails->account_number }}</span>
            </div>
            <div class="info-row">
                <span class="label">IFSC Code:</span>
                <span>{{ $seller->bankDetails->ifsc_code }}</span>
            </div>
        </div>
        
        <div style="text-align: center;">
            <a href="{{ route('admin.sellers.show', $seller->id) }}" class="button">
                Review Application →
            </a>
        </div>
        
        <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; color: #6b7280; font-size: 14px;">
            <strong>Note:</strong> Please review the seller's information and approve or reject their application from the admin panel.
        </p>
    </div>
    
    <div class="footer">
        <p>This is an automated notification from {{ config('app.name') }}</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>
