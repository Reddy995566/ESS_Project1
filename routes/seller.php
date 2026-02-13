<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Seller\AuthController;
use App\Http\Controllers\Seller\DashboardController;
use App\Http\Controllers\Seller\ProductController;
use App\Http\Controllers\Seller\OrderController;
use App\Http\Controllers\Seller\PayoutController;
use App\Http\Controllers\Seller\AnalyticsController;
use App\Http\Controllers\Seller\NotificationController;
use App\Http\Controllers\Seller\SettingController;
use App\Http\Controllers\Seller\ProfileController;

// Guest Routes (Login, Register) - Allow both guest users and regular logged-in users
Route::middleware('guest:seller')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('seller.login');
    Route::post('/login', [AuthController::class, 'login'])->name('seller.login.post');
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('seller.forgot-password');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('seller.forgot-password.post');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('seller.reset-password');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('seller.reset-password.post');
});

// Seller Registration - Allow both guest and logged-in users
Route::get('/register', [AuthController::class, 'showRegister'])->name('seller.register');
Route::post('/register', [AuthController::class, 'register'])->name('seller.register.post');

// Authenticated Routes
Route::middleware(['seller.auth'])->group(function () {
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('seller.logout');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('seller.dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'stats'])->name('seller.dashboard.stats');
    Route::get('/dashboard/sales-chart', [DashboardController::class, 'salesChart'])->name('seller.dashboard.sales-chart');
    
    // Products
    Route::prefix('products')->name('seller.products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/pending', [ProductController::class, 'pending'])->name('pending');
        Route::get('/rejected', [ProductController::class, 'rejected'])->name('rejected');
        
        // Multi-step product creation
        Route::get('/create/step-1', [ProductController::class, 'createStep1'])->name('create.step1');
        Route::post('/create/step-1', [ProductController::class, 'processStep1'])->name('create.step1.process');
        
        // Step 2 is now Variants
        Route::get('/create/step-2', [ProductController::class, 'createStep2'])->name('create.step2');
        Route::post('/create/step-2', [ProductController::class, 'processStep2'])->name('create.step2.process');
        
        Route::get('/create/step-3', [ProductController::class, 'createStep3'])->name('create.step3');
        Route::post('/create/step-3', [ProductController::class, 'processStep3'])->name('create.step3.process');
        
        Route::get('/create/step-4', [ProductController::class, 'createStep4'])->name('create.step4');
        Route::post('/create/step-4', [ProductController::class, 'processStep4'])->name('create.step4.process');
        
        // Step 5 is now SEO
        Route::get('/create/step-5', [ProductController::class, 'createStep5'])->name('create.step5');
        Route::post('/create/step-5', [ProductController::class, 'processStep5'])->name('create.step5.process');
        
        // Step 6 is now Settings
        Route::get('/create/step-6', [ProductController::class, 'createStep6'])->name('create.step6');
        Route::post('/create/step-6', [ProductController::class, 'processStep6'])->name('create.step6.process');
        
        // Old step 6 and 7 routes redirect to new routes
        Route::get('/create/step-7', function() {
            return redirect()->route('seller.products.create.step6');
        })->name('create.step7');
        Route::post('/create/step-7', function() {
            return redirect()->route('seller.products.create.step6');
        })->name('create.step7.process');
        
        Route::post('/create/clear-session', [ProductController::class, 'clearSession'])->name('create.clear-session');
        
        Route::get('/create', function () {
            return redirect()->route('seller.products.create.step1');
        })->name('create');
        
        // Image upload
        Route::post('/upload-image', [ProductController::class, 'uploadImage'])->name('upload-image');
        
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::get('/{id}', [ProductController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ProductController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/toggle', [ProductController::class, 'toggle'])->name('toggle');
        Route::post('/{id}/copy', [ProductController::class, 'copy'])->name('copy');
        Route::post('/bulk-action', [ProductController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/export', [ProductController::class, 'export'])->name('export');
    });
    
    // Image upload route (alias for backward compatibility)
    Route::post('/upload-image', [ProductController::class, 'uploadImage'])->name('seller.upload.image');
    
    // Orders
    Route::prefix('orders')->name('seller.orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/cancelled', [OrderController::class, 'cancelled'])->name('cancelled');
        Route::get('/{id}', [OrderController::class, 'show'])->name('show');
        Route::post('/{id}/cancel', [OrderController::class, 'cancel'])->name('cancel');
        Route::post('/{id}/shiprocket', [OrderController::class, 'shipToShiprocket'])->name('shiprocket');
        Route::get('/{id}/shiprocket/couriers', [OrderController::class, 'getCouriers'])->name('shiprocket.couriers');
        Route::post('/{id}/shiprocket/awb', [OrderController::class, 'generateAwb'])->name('shiprocket.awb');
        Route::get('/{id}/invoice', [App\Http\Controllers\InvoiceController::class, 'sellerGenerate'])->name('invoice');
        Route::put('/{id}/status', [OrderController::class, 'updateStatus'])->name('update-status');
        Route::post('/{id}/notes', [OrderController::class, 'addNote'])->name('add-note');
        Route::post('/{id}/shipping', [OrderController::class, 'updateShipping'])->name('update-shipping');
        Route::get('/export', [OrderController::class, 'export'])->name('export');
    });
    
    // Payouts
    Route::prefix('payouts')->name('seller.payouts.')->group(function () {
        Route::get('/', [PayoutController::class, 'index'])->name('index');
        Route::post('/request', [PayoutController::class, 'requestPayout'])->name('request');
        Route::get('/{id}', [PayoutController::class, 'show'])->name('show');
        Route::get('/{id}/invoice', [PayoutController::class, 'downloadInvoice'])->name('invoice');
        Route::post('/{id}/dispute', [PayoutController::class, 'raiseDispute'])->name('dispute');
        Route::get('/commissions', [PayoutController::class, 'commissions'])->name('commissions');
    });
    
    // Wallet
    Route::prefix('wallet')->name('seller.wallet.')->group(function () {
        Route::get('/', [App\Http\Controllers\Seller\WalletController::class, 'index'])->name('index');
        Route::post('/withdraw', [App\Http\Controllers\Seller\WalletController::class, 'withdraw'])->name('withdraw');
        Route::get('/transactions', [App\Http\Controllers\Seller\WalletController::class, 'transactions'])->name('transactions');
    });

    // Returns
    Route::prefix('returns')->name('seller.returns.')->group(function () {
        Route::get('/', [App\Http\Controllers\Seller\ReturnController::class, 'index'])->name('index');
        Route::get('/{return}', [App\Http\Controllers\Seller\ReturnController::class, 'show'])->name('show');
        Route::post('/{return}/acknowledge', [App\Http\Controllers\Seller\ReturnController::class, 'acknowledge'])->name('acknowledge');
        Route::post('/{return}/notes', [App\Http\Controllers\Seller\ReturnController::class, 'addNotes'])->name('add-notes');
    });
    
    // Analytics
    Route::prefix('analytics')->name('seller.analytics.')->group(function () {
        Route::get('/sales', [AnalyticsController::class, 'sales'])->name('sales');
        Route::get('/products', [AnalyticsController::class, 'products'])->name('products');
        Route::get('/customers', [AnalyticsController::class, 'customers'])->name('customers');
        Route::get('/data', [AnalyticsController::class, 'getAnalyticsData'])->name('getAnalyticsData');
        Route::post('/reports/generate', [AnalyticsController::class, 'generateReport'])->name('generateReport');
        Route::get('/export', [AnalyticsController::class, 'export'])->name('export');
    });
    
    // Analytics main page (redirect to sales)
    Route::get('/analytics', function() {
        return redirect()->route('seller.analytics.sales');
    })->name('seller.analytics');
    
    // Notifications
    Route::prefix('notifications')->name('seller.notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
    });
    
    // Settings
    Route::prefix('settings')->name('seller.settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::put('/profile', [SettingController::class, 'updateProfile'])->name('update-profile');
        Route::put('/business', [SettingController::class, 'updateBusiness'])->name('update-business');
        Route::put('/bank', [SettingController::class, 'updateBank'])->name('update-bank');
        Route::put('/password', [SettingController::class, 'updatePassword'])->name('update-password');
        Route::put('/notifications', [SettingController::class, 'updateNotifications'])->name('update-notifications');
        Route::post('/shiprocket', [SettingController::class, 'updateShiprocket'])->name('shiprocket');
        Route::post('/shiprocket/test', [SettingController::class, 'testShiprocket'])->name('shiprocket.test');
        Route::post('/2fa/enable', [SettingController::class, 'enable2FA'])->name('enable-2fa');
        Route::post('/2fa/confirm', [SettingController::class, 'confirm2FA'])->name('confirm-2fa');
        Route::post('/2fa/disable', [SettingController::class, 'disable2FA'])->name('disable-2fa');
    });
    
    // Profile
    Route::prefix('profile')->name('seller.profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('update-password');
        Route::post('/avatar', [ProfileController::class, 'uploadAvatar'])->name('upload-avatar');
    });
    
    // Categories
    Route::prefix('categories')->name('seller.categories.')->group(function () {
        Route::get('/', [App\Http\Controllers\Seller\CategoryController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Seller\CategoryController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Seller\CategoryController::class, 'store'])->name('store');
        Route::get('/{category}/edit', [App\Http\Controllers\Seller\CategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [App\Http\Controllers\Seller\CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [App\Http\Controllers\Seller\CategoryController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-delete', [App\Http\Controllers\Seller\CategoryController::class, 'bulkDelete'])->name('bulk-delete');
        Route::post('/{category}/toggle', [App\Http\Controllers\Seller\CategoryController::class, 'toggle'])->name('toggle');
        Route::post('/bulk-action', [App\Http\Controllers\Seller\CategoryController::class, 'bulkAction'])->name('bulk-action');
        Route::post('/upload-image', [App\Http\Controllers\Seller\CategoryController::class, 'uploadImage'])->name('upload-image');
        Route::get('/export', [App\Http\Controllers\Seller\CategoryController::class, 'export'])->name('export');
    });
    
    // Collections
    Route::prefix('collections')->name('seller.collections.')->group(function () {
        Route::get('/', [App\Http\Controllers\Seller\CollectionController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Seller\CollectionController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Seller\CollectionController::class, 'store'])->name('store');
        Route::get('/{collection}/edit', [App\Http\Controllers\Seller\CollectionController::class, 'edit'])->name('edit');
        Route::put('/{collection}', [App\Http\Controllers\Seller\CollectionController::class, 'update'])->name('update');
        Route::delete('/{collection}', [App\Http\Controllers\Seller\CollectionController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-delete', [App\Http\Controllers\Seller\CollectionController::class, 'bulkDelete'])->name('bulk-delete');
        Route::post('/{collection}/toggle', [App\Http\Controllers\Seller\CollectionController::class, 'toggle'])->name('toggle');
        Route::post('/bulk-action', [App\Http\Controllers\Seller\CollectionController::class, 'bulkAction'])->name('bulk-action');
        Route::post('/upload-image', [App\Http\Controllers\Seller\CollectionController::class, 'uploadImage'])->name('upload-image');
        Route::get('/export', [App\Http\Controllers\Seller\CollectionController::class, 'export'])->name('export');
    });
    
    // Tags
    Route::prefix('tags')->name('seller.tags.')->group(function () {
        Route::get('/', [App\Http\Controllers\Seller\TagController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Seller\TagController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Seller\TagController::class, 'store'])->name('store');
        Route::get('/{tag}/edit', [App\Http\Controllers\Seller\TagController::class, 'edit'])->name('edit');
        Route::put('/{tag}', [App\Http\Controllers\Seller\TagController::class, 'update'])->name('update');
        Route::delete('/{tag}', [App\Http\Controllers\Seller\TagController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-delete', [App\Http\Controllers\Seller\TagController::class, 'bulkDelete'])->name('bulk-delete');
        Route::post('/{tag}/toggle', [App\Http\Controllers\Seller\TagController::class, 'toggle'])->name('toggle');
        Route::post('/bulk-action', [App\Http\Controllers\Seller\TagController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/export', [App\Http\Controllers\Seller\TagController::class, 'export'])->name('export');
    });
    
    // Brands
    Route::prefix('brands')->name('seller.brands.')->group(function () {
        Route::get('/', [App\Http\Controllers\Seller\BrandController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Seller\BrandController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Seller\BrandController::class, 'store'])->name('store');
        Route::get('/{brand}/edit', [App\Http\Controllers\Seller\BrandController::class, 'edit'])->name('edit');
        Route::put('/{brand}', [App\Http\Controllers\Seller\BrandController::class, 'update'])->name('update');
        Route::delete('/{brand}', [App\Http\Controllers\Seller\BrandController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-delete', [App\Http\Controllers\Seller\BrandController::class, 'bulkDelete'])->name('bulk-delete');
        Route::post('/{brand}/toggle', [App\Http\Controllers\Seller\BrandController::class, 'toggle'])->name('toggle');
        Route::post('/bulk-action', [App\Http\Controllers\Seller\BrandController::class, 'bulkAction'])->name('bulk-action');
        Route::post('/upload-image', [App\Http\Controllers\Seller\BrandController::class, 'uploadImage'])->name('upload-image');
        Route::get('/export', [App\Http\Controllers\Seller\BrandController::class, 'export'])->name('export');
    });
    
    // Fabrics
    Route::prefix('fabrics')->name('seller.fabrics.')->group(function () {
        Route::get('/', [App\Http\Controllers\Seller\FabricController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Seller\FabricController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Seller\FabricController::class, 'store'])->name('store');
        Route::get('/{fabric}/edit', [App\Http\Controllers\Seller\FabricController::class, 'edit'])->name('edit');
        Route::put('/{fabric}', [App\Http\Controllers\Seller\FabricController::class, 'update'])->name('update');
        Route::delete('/{fabric}', [App\Http\Controllers\Seller\FabricController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-delete', [App\Http\Controllers\Seller\FabricController::class, 'bulkDelete'])->name('bulk-delete');
        Route::post('/{fabric}/toggle', [App\Http\Controllers\Seller\FabricController::class, 'toggle'])->name('toggle');
        Route::post('/bulk-action', [App\Http\Controllers\Seller\FabricController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/export', [App\Http\Controllers\Seller\FabricController::class, 'export'])->name('export');
    });
    
    // Colors
    Route::prefix('colors')->name('seller.colors.')->group(function () {
        Route::get('/', [App\Http\Controllers\Seller\ColorController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Seller\ColorController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Seller\ColorController::class, 'store'])->name('store');
        Route::get('/{color}/edit', [App\Http\Controllers\Seller\ColorController::class, 'edit'])->name('edit');
        Route::put('/{color}', [App\Http\Controllers\Seller\ColorController::class, 'update'])->name('update');
        Route::delete('/{color}', [App\Http\Controllers\Seller\ColorController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-delete', [App\Http\Controllers\Seller\ColorController::class, 'bulkDelete'])->name('bulk-delete');
        Route::post('/{color}/toggle', [App\Http\Controllers\Seller\ColorController::class, 'toggle'])->name('toggle');
        Route::post('/bulk-action', [App\Http\Controllers\Seller\ColorController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/export', [App\Http\Controllers\Seller\ColorController::class, 'export'])->name('export');
    });
    
    // Sizes
    Route::prefix('sizes')->name('seller.sizes.')->group(function () {
        Route::get('/', [App\Http\Controllers\Seller\SizeController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Seller\SizeController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Seller\SizeController::class, 'store'])->name('store');
        Route::get('/{size}/edit', [App\Http\Controllers\Seller\SizeController::class, 'edit'])->name('edit');
        Route::put('/{size}', [App\Http\Controllers\Seller\SizeController::class, 'update'])->name('update');
        Route::delete('/{size}', [App\Http\Controllers\Seller\SizeController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-delete', [App\Http\Controllers\Seller\SizeController::class, 'bulkDelete'])->name('bulk-delete');
        Route::post('/{size}/toggle', [App\Http\Controllers\Seller\SizeController::class, 'toggle'])->name('toggle');
        Route::post('/bulk-action', [App\Http\Controllers\Seller\SizeController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/export', [App\Http\Controllers\Seller\SizeController::class, 'export'])->name('export');
    });
    
    // Documents & Verification
    Route::prefix('documents')->name('seller.documents.')->group(function () {
        Route::get('/', [App\Http\Controllers\Seller\DocumentController::class, 'index'])->name('index');
        Route::post('/upload', [App\Http\Controllers\Seller\DocumentController::class, 'upload'])->name('upload');
        Route::get('/{document}', [App\Http\Controllers\Seller\DocumentController::class, 'show'])->name('show');
        Route::delete('/{document}', [App\Http\Controllers\Seller\DocumentController::class, 'delete'])->name('delete');
        Route::post('/{document}/reupload', [App\Http\Controllers\Seller\DocumentController::class, 'reupload'])->name('reupload');
    });
    
    // Profile main page (alias - redirect to settings)
    Route::get('/profile', function() {
        return redirect()->route('seller.settings.index');
    })->name('seller.profile');
});
