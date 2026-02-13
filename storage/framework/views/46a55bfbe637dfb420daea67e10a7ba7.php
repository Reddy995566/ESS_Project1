<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Newsletter Subscription</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: #FAF5ED; padding: 20px; line-height: 1.6; }
        .email-container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(92, 31, 51, 0.1); }
        .header { background: #F59E0B; padding: 40px 30px; text-align: center; }
        .site-name { font-family: 'Playfair Display', serif; font-size: 28px; font-weight: 700; color: #ffffff; letter-spacing: 2px; margin-bottom: 10px; }
        .logo { max-width: 150px; height: auto; margin-bottom: 15px; }
        .header-subtitle { color: #FEF3C7; font-size: 16px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; }
        .content { padding: 30px; }
        .greeting { font-size: 24px; font-weight: 700; color: #F59E0B; margin-bottom: 15px; font-family: 'Playfair Display', serif; text-align: center; }
        .message { color: #4B4B4B; font-size: 15px; margin-bottom: 25px; line-height: 1.8; text-align: center; }
        .subscriber-box { background: #FAF5ED; border: 2px solid #E6B873; border-radius: 10px; padding: 25px; margin-bottom: 30px; text-align: center; }
        .email-display { font-size: 18px; font-weight: 700; color: #5C1F33; margin: 15px 0; }
        .date-display { font-size: 14px; color: #6B6B6B; }
        .button { display: inline-block; background: #F59E0B; color: #ffffff; text-decoration: none; padding: 15px 40px; border-radius: 8px; font-weight: 600; font-size: 15px; margin: 20px 0; box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3); }
        .divider { height: 2px; background: #E6B873; margin: 30px 0; }
        .footer { background-color: #FAF5ED; padding: 30px; text-align: center; border-top: 2px solid #E6B873; }
        .footer-text { color: #6B6B6B; font-size: 13px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <?php if($siteLogo): ?><img src="<?php echo e($siteLogo); ?>" alt="<?php echo e($siteName); ?>" class="logo"><?php endif; ?>
            <div class="site-name">📬 NEW SUBSCRIBER</div>
            <div class="header-subtitle">Admin Notification</div>
        </div>
        <div class="content">
            <div class="greeting">New Newsletter Subscription!</div>
            <p class="message">Someone has subscribed to your newsletter on <?php echo e($siteName); ?>.</p>
            <div class="subscriber-box">
                <div class="email-display"><?php echo e($newsletter->email); ?></div>
                <div class="date-display">Subscribed: <?php echo e($newsletter->created_at->format('d M, Y h:i A')); ?></div>
            </div>
            <div class="divider"></div>
            <center><a href="<?php echo e(route('admin.newsletters.index')); ?>" class="button">View All Subscribers</a></center>
            <p class="message" style="margin-top: 30px; font-size: 14px; color: #6B6B6B;">Your subscriber list is growing!</p>
        </div>
        <div class="footer">
            <div class="footer-text"><strong><?php echo e($siteName); ?> - Admin Notification System</strong></div>
            <div class="divider"></div>
            <div class="footer-text" style="font-size: 11px; color: #999;">This is an automated notification.</div>
            <div class="footer-text" style="font-size: 12px; color: #6B6B6B; margin-top: 10px;">© <?php echo e(date('Y')); ?> <?php echo e($siteName); ?>. All rights reserved.</div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\ESS_Project1\resources\views/emails/admin-newsletter-notification.blade.php ENDPATH**/ ?>