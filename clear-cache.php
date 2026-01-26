<?php
/**
 * Cache Clear Script for Hostinger
 * 
 * SECURITY WARNING: Delete this file after use or add password protection!
 * 
 * Usage: 
 * 1. Upload this file to your domain root
 * 2. Visit: https://yourdomain.com/clear-cache.php
 * 3. Delete this file after clearing cache
 */

// Simple password protection (CHANGE THIS!)
$password = 'your-secret-password-123';

// Check password
if (!isset($_GET['password']) || $_GET['password'] !== $password) {
    die('❌ Access Denied! Add ?password=your-secret-password-123 to URL');
}

echo '<pre>';
echo "🧹 Starting Cache Clear Process...\n\n";

// Change to Laravel root directory
chdir(__DIR__);

// Array of commands to run
$commands = [
    'php artisan cache:clear' => 'Application Cache',
    'php artisan config:clear' => 'Config Cache',
    'php artisan route:clear' => 'Route Cache',
    'php artisan view:clear' => 'View Cache',
    'php artisan clear-compiled' => 'Compiled Classes',
    'php artisan config:cache' => 'Config Cache (Recreate)',
    'php artisan route:cache' => 'Route Cache (Recreate)',
    'php artisan view:cache' => 'View Cache (Recreate)',
    'php artisan optimize' => 'Optimization',
];

foreach ($commands as $command => $description) {
    echo "🔄 Clearing {$description}...\n";
    
    $output = [];
    $return_var = 0;
    exec($command . ' 2>&1', $output, $return_var);
    
    if ($return_var === 0) {
        echo "✅ {$description} cleared successfully\n";
    } else {
        echo "⚠️ {$description} - " . implode("\n", $output) . "\n";
    }
    echo "\n";
}

echo "\n";
echo "✅ All caches cleared and recreated!\n";
echo "🎉 Your website should now show the latest changes\n";
echo "\n";
echo "⚠️ IMPORTANT: Delete this file (clear-cache.php) for security!\n";
echo '</pre>';
?>
