#!/bin/bash

# Cache Clear Script for Hostinger
# Run this script on server after deployment

echo "🧹 Clearing all Laravel caches..."

# Clear application cache
php artisan cache:clear
echo "✅ Application cache cleared"

# Clear route cache
php artisan route:clear
echo "✅ Route cache cleared"

# Clear config cache
php artisan config:clear
echo "✅ Config cache cleared"

# Clear view cache
php artisan view:clear
echo "✅ View cache cleared"

# Clear compiled classes
php artisan clear-compiled
echo "✅ Compiled classes cleared"

# Optimize autoloader
composer dump-autoload --optimize
echo "✅ Autoloader optimized"

# Recreate caches for production
echo ""
echo "⚡ Recreating optimized caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

echo ""
echo "✅ All caches cleared and recreated!"
echo "🎉 Your website should now show the latest changes"
