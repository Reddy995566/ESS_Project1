@echo off
echo ========================================
echo   Laravel Hostinger Deployment Prep
echo ========================================
echo.

echo [1/6] Installing Composer dependencies...
call composer install --optimize-autoloader --no-dev
if %errorlevel% neq 0 (
    echo ERROR: Composer install failed!
    pause
    exit /b 1
)

echo.
echo [2/6] Installing NPM dependencies...
call npm install
if %errorlevel% neq 0 (
    echo ERROR: NPM install failed!
    pause
    exit /b 1
)

echo.
echo [3/6] Building assets...
call npm run build
if %errorlevel% neq 0 (
    echo ERROR: Build failed!
    pause
    exit /b 1
)

echo.
echo [4/6] Clearing caches...
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

echo.
echo [5/6] Optimizing for production...
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

echo.
echo [6/6] Creating deployment package...
powershell Compress-Archive -Path * -DestinationPath deployment.zip -Force -CompressionLevel Optimal

echo.
echo ========================================
echo   Deployment package ready!
echo ========================================
echo.
echo File: deployment.zip
echo.
echo Next steps:
echo 1. Upload deployment.zip to Hostinger
echo 2. Extract in your domain root
echo 3. Configure .env file
echo 4. Run: php artisan key:generate
echo 5. Run: php artisan migrate --force
echo 6. Run: php artisan storage:link
echo 7. Set permissions: chmod -R 775 storage bootstrap/cache
echo.
echo See QUICK_DEPLOY.md for detailed instructions
echo.
pause
