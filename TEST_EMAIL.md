# Quick Email Test Guide

## Test Email Configuration

Before testing order emails, verify your email configuration works:

### Method 1: Using Tinker (Quick Test)
```bash
php artisan tinker
```

Then run:
```php
Mail::raw('This is a test email from your Laravel app', function($message) {
    $message->to('your-email@example.com')
            ->subject('Test Email');
});
```

If successful, you'll see `null` returned. Check your inbox!

### Method 2: Test Order Email
1. Configure your .env file with Hostinger SMTP settings
2. Place a test order on your website
3. Check the customer's email inbox
4. Verify the email looks correct

## Common Issues

### "Connection refused" Error
- Check MAIL_HOST is correct: `smtp.hostinger.com`
- Verify MAIL_PORT is `465` for SSL or `587` for TLS
- Ensure MAIL_ENCRYPTION is `ssl` or `tls`

### "Authentication failed" Error
- Double-check MAIL_USERNAME (full email address)
- Verify MAIL_PASSWORD is correct
- Make sure the email account exists in Hostinger

### Email Not Received
- Check spam/junk folder
- Verify recipient email is correct
- Check Laravel logs: `storage/logs/laravel.log`
- Try sending to a different email address

### "Address in mailbox given" Error
- Ensure MAIL_FROM_ADDRESS matches MAIL_USERNAME
- Both should be the same email address

## Example .env Configuration

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_USERNAME=orders@yourdomain.com
MAIL_PASSWORD=YourSecurePassword123
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=orders@yourdomain.com
MAIL_FROM_NAME="Fashion Store"
```

## After Configuration

1. Clear config cache: `php artisan config:clear`
2. Clear cache: `php artisan cache:clear`
3. Test with tinker command above
4. Place a test order

## Email Preview

The order confirmation email includes:
- ✅ Beautiful header with your logo
- ✅ Green success checkmark
- ✅ Order number and details
- ✅ Product images and information
- ✅ Shipping address
- ✅ Order total breakdown
- ✅ Contact information from database
- ✅ Mobile-responsive design
- ✅ Theme-matching colors

## Need Help?

Check these files if you need to customize:
- Email template: `resources/views/emails/order-placed.blade.php`
- Mailable class: `app/Mail/OrderPlaced.php`
- Controller: `app/Http/Controllers/Website/CheckoutController.php`
