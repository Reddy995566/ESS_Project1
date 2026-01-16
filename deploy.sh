#!/bin/bash

# Laravel Deployment Script for Hostinger
# Run this script before uploading to server

echo "🚀 Starting deployment preparation..."

# Step 1: Install dependencies
echo "📦 Installing Composer dependencies..."
composer install --optimize-autoloader --no-dev

# Step 2: Install NPM dependencies and build assets
echo "📦 Installing NPM dependencies..."
npm install

echo "🔨 Building assets..."
npm run build

# Step 3: Clear all caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Step 4: Optimize for production
echo "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Step 5: Create deployment package
echo "📦 Creating deployment package..."
timestamp=$(date +%Y%m%d_%H%M%S)
zip -r "deployment_${timestamp}.zip" . \
    -x "node_modules/*" \
    -x ".git/*" \
    -x "*.log" \
    -x "storage/logs/*" \
    -x "storage/framework/cache/*" \
    -x "storage/framework/sessions/*" \
    -x "storage/framework/views/*"

echo "✅ Deployment package created: deployment_${timestamp}.zip"
echo ""
echo "📋 Next steps:"
echo "1. Upload deployment_${timestamp}.zip to Hostinger"
echo "2. Extract in your domain root"
echo "3. Configure .env file"
echo "4. Run: php artisan key:generate"
echo "5. Run: php artisan migrate --force"
echo "6. Run: php artisan storage:link"
echo "7. Set permissions: chmod -R 775 storage bootstrap/cache"
echo ""
echo "📖 See HOSTINGER_DEPLOYMENT_GUIDE.md for detailed instructions"
