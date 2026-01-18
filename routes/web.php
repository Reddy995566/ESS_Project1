<?php

use Illuminate\Support\Facades\Route;
use App\Services\ImageKitService;
use Illuminate\Http\Request;
use App\Http\Controllers\Website\CollectionController;

// Website Routes
// Website Routes
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Authentication Routes
Route::get('/login', [App\Http\Controllers\Website\AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Website\AuthController::class, 'login'])->name('login.post');
Route::get('/register', [App\Http\Controllers\Website\AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Website\AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [App\Http\Controllers\Website\AuthController::class, 'logout'])->name('logout');
Route::get('/auth/check', [App\Http\Controllers\Website\AuthController::class, 'checkAuth'])->name('auth.check');

// Password Reset Routes
Route::get('/forgot-password', [App\Http\Controllers\Website\ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [App\Http\Controllers\Website\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [App\Http\Controllers\Website\ResetPasswordController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [App\Http\Controllers\Website\ResetPasswordController::class, 'resetPassword'])->name('password.update');

// User Dashboard (Protected)
Route::middleware('auth')->group(function () {
    Route::get('/my-account', function () {
        return view('website.user.dashboard');
    })->name('user.dashboard');

    Route::get('/my-account/profile', function () {
        return view('website.user.profile');
    })->name('user.profile');

    Route::post('/my-account/profile/update', [App\Http\Controllers\Website\AuthController::class, 'updateProfile'])->name('user.profile.update');

    Route::get('/my-account/orders', [App\Http\Controllers\Website\OrdersController::class, 'index'])->name('user.orders');
    Route::get('/my-account/orders/{id}', [App\Http\Controllers\Website\OrdersController::class, 'show'])->name('user.orders.show');

    Route::get('/my-account/wishlist', function () {
        return view('website.user.wishlist');
    })->name('user.wishlist');

    Route::get('/my-account/addresses', function () {
        return view('website.user.addresses');
    })->name('user.addresses');

    // Address API Routes
    Route::get('/api/addresses', [App\Http\Controllers\Website\AddressController::class, 'index'])->name('api.addresses.index');
    Route::post('/api/addresses', [App\Http\Controllers\Website\AddressController::class, 'store'])->name('api.addresses.store');
    Route::put('/api/addresses/{id}', [App\Http\Controllers\Website\AddressController::class, 'update'])->name('api.addresses.update');
    Route::delete('/api/addresses/{id}', [App\Http\Controllers\Website\AddressController::class, 'destroy'])->name('api.addresses.destroy');
    Route::post('/api/addresses/{id}/set-default', [App\Http\Controllers\Website\AddressController::class, 'setDefault'])->name('api.addresses.setDefault');
});

Route::get('/collection', [CollectionController::class, 'index'])->name('shop');
Route::get('/collection/filter', [CollectionController::class, 'filterProducts'])->name('collection.filter');
Route::get('/categories', [App\Http\Controllers\Website\CategoriesController::class, 'index'])->name('categories');

Route::get('/bulk-orders', [App\Http\Controllers\Website\BulkOrderController::class, 'index'])->name('bulk-orders');
Route::post('/bulk-orders', [App\Http\Controllers\Website\BulkOrderController::class, 'store'])->name('bulk-orders.store');

Route::get('/about-us', function () {
    return view('website.about');
})->name('about');

Route::get('/faqs', function () {
    return view('website.faqs');
})->name('faqs');

Route::get('/privacy-policy', function () {
    return view('website.privacy-policy');
})->name('privacy-policy');

Route::get('/refund-policy', function () {
    return view('website.refund-policy');
})->name('refund-policy');

Route::get('/shipping-policy', function () {
    return view('website.shipping-policy');
})->name('shipping-policy');

Route::get('/terms-of-service', function () {
    return view('website.terms-of-service');
})->name('terms-of-service');

Route::get('/contact-information', function () {
    return view('website.contact-information');
})->name('contact-information');

Route::get('/contact-us', [App\Http\Controllers\Website\ContactController::class, 'index'])->name('contact');
Route::post('/contact-us', [App\Http\Controllers\Website\ContactController::class, 'store'])->name('contact.store');

// Search Route
Route::get('/search/ajax', [App\Http\Controllers\Website\SearchController::class, 'search'])->name('search.ajax');
Route::get('/search/quick-view/{id}', [App\Http\Controllers\Website\SearchController::class, 'quickView'])->name('search.quickview');

// Product Details
Route::get('/product/{slug}', [App\Http\Controllers\ProductController::class, 'show'])->name('product.show');
Route::post('/track-recently-viewed/{product}', [App\Http\Controllers\ProductController::class, 'trackRecentlyViewed']);

// Checkout Routes
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [App\Http\Controllers\Website\CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/place-order', [App\Http\Controllers\Website\CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/checkout/verify-payment', [App\Http\Controllers\Website\CheckoutController::class, 'verifyPayment'])->name('checkout.verify');
    Route::get('/order/success/{id}', [App\Http\Controllers\Website\CheckoutController::class, 'success'])->name('order.success');
});

// Cart Routes
Route::get('/cart/data', [App\Http\Controllers\Website\CartController::class, 'getCart'])->name('cart.data');
Route::post('/cart/add', [App\Http\Controllers\Website\CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/remove', [App\Http\Controllers\Website\CartController::class, 'removeFromCart'])->name('cart.remove');
Route::post('/cart/update', [App\Http\Controllers\Website\CartController::class, 'updateQuantity'])->name('cart.update');

// Wishlist Routes
Route::get('/wishlist/data', [App\Http\Controllers\Website\WishlistController::class, 'getWishlist'])->name('wishlist.data');
Route::post('/wishlist/add', [App\Http\Controllers\Website\WishlistController::class, 'addToWishlist'])->name('wishlist.add');
Route::post('/wishlist/remove', [App\Http\Controllers\Website\WishlistController::class, 'removeFromWishlist'])->name('wishlist.remove');
Route::post('/wishlist/check', [App\Http\Controllers\Website\WishlistController::class, 'checkWishlist'])->name('wishlist.check');

// Review Routes
Route::post('/reviews/submit', [App\Http\Controllers\Website\ReviewController::class, 'store'])->name('reviews.store');
Route::get('/reviews/product/{id}', [App\Http\Controllers\Website\ReviewController::class, 'getProductReviews'])->name('reviews.get');
Route::post('/reviews/upload-image', [App\Http\Controllers\Website\ReviewController::class, 'uploadImage'])->name('reviews.upload-image');

// Recently Viewed Routes
Route::post('/track-view', [App\Http\Controllers\ProductController::class, 'trackView'])->name('track.view');
Route::post('/recently-viewed', [App\Http\Controllers\ProductController::class, 'getRecentlyViewed'])->name('recently.viewed');

// TEST ROUTE - Product Details with Dummy Data
Route::get('/test-product', function () {
    $product = (object) [
        'id' => 1,
        'name' => 'Patola Linen Pink Saree',
        'slug' => 'patola-linen-pink-saree',
        'description' => '<p>Beautiful Patola Linen saree with elegant pink color and traditional design. Perfect for weddings and special occasions.</p><ul><li>Fabric: Pure Linen</li><li>Color: Pink with multicolor border</li><li>Length: 6.5 meters</li><li>Blouse: Unstitched</li></ul>',
        'price' => 1399.00,
        'sale_price' => null,
        'image' => 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?w=500',
        'images' => [
            'https://images.unsplash.com/photo-1610030469983-98e550d6193c?w=500',
            'https://images.unsplash.com/photo-1583391733956-6c78276477e5?w=500',
            'https://images.unsplash.com/photo-1617038260897-41a1f14a8ca0?w=500',
            'https://images.unsplash.com/photo-1632398363718-dfd7b5c0c0f3?w=500',
            'https://images.unsplash.com/photo-1611780416791-5ae9d6f487ca?w=500',
        ],
        'category_id' => 1,
        'status' => 'active',
    ];

    $similarProducts = collect([
        (object) [
            'id' => 2,
            'name' => 'Green Patola Saree',
            'slug' => 'green-patola-saree',
            'image' => 'https://images.unsplash.com/photo-1583391733956-6c78276477e5?w=200',
            'price' => 1299.00,
        ],
        (object) [
            'id' => 3,
            'name' => 'Pink Designer Saree',
            'slug' => 'pink-designer-saree',
            'image' => 'https://images.unsplash.com/photo-1617038260897-41a1f14a8ca0?w=200',
            'price' => 1499.00,
        ],
        (object) [
            'id' => 4,
            'name' => 'Yellow Traditional Saree',
            'slug' => 'yellow-traditional-saree',
            'image' => 'https://images.unsplash.com/photo-1632398363718-dfd7b5c0c0f3?w=200',
            'price' => 1199.00,
        ],
        (object) [
            'id' => 5,
            'name' => 'Red Festive Saree',
            'slug' => 'red-festive-saree',
            'image' => 'https://images.unsplash.com/photo-1611780416791-5ae9d6f487ca?w=200',
            'price' => 1599.00,
        ],
    ]);

    $recentlyViewed = collect([
        (object) [
            'id' => 6,
            'name' => 'Banarsi Linen Blue Saree',
            'slug' => 'banarsi-linen-blue-saree',
            'image' => 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?w=100',
            'price' => 1599.00,
        ],
        (object) [
            'id' => 7,
            'name' => 'Silk Cotton Pink Designer Saree',
            'slug' => 'silk-cotton-pink',
            'image' => 'https://images.unsplash.com/photo-1583391733956-6c78276477e5?w=100',
            'price' => 1899.00,
        ],
        (object) [
            'id' => 8,
            'name' => 'Patola Linen Cream Saree',
            'slug' => 'patola-cream',
            'image' => 'https://images.unsplash.com/photo-1617038260897-41a1f14a8ca0?w=100',
            'price' => 1299.00,
        ],
    ]);

    return view('website.product-details', compact('product', 'similarProducts', 'recentlyViewed'));
});


Route::prefix('admin')->group(function () {
    // Handle both /admin and /admin/ - both redirect to login page
    Route::get('/', [App\Http\Controllers\Admin\LoginController::class, 'showLogin'])->name('admin.login.page');
    Route::get('/login', [App\Http\Controllers\Admin\LoginController::class, 'showLogin'])->name('admin.login.form');
    Route::post('/login', [App\Http\Controllers\Admin\LoginController::class, 'login'])->name('admin.login');

    Route::middleware(['admin.auth'])->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');

        Route::get('/products', [App\Http\Controllers\Admin\ProductsController::class, 'index'])->name('admin.products');

        Route::get('/products/create/step-1', [App\Http\Controllers\Admin\ProductsController::class, 'createStep1'])->name('admin.products.create.step1');
        Route::post('/products/create/step-1', [App\Http\Controllers\Admin\ProductsController::class, 'processStep1'])->name('admin.products.create.step1.process');

        Route::get('/products/create/step-2', [App\Http\Controllers\Admin\ProductsController::class, 'createStep2'])->name('admin.products.create.step2');
        Route::post('/products/create/step-2', [App\Http\Controllers\Admin\ProductsController::class, 'processStep2'])->name('admin.products.create.step2.process');

        Route::get('/products/create/step-3', [App\Http\Controllers\Admin\ProductsController::class, 'createStep3'])->name('admin.products.create.step3');
        Route::post('/products/create/step-3', [App\Http\Controllers\Admin\ProductsController::class, 'processStep3'])->name('admin.products.create.step3.process');

        Route::get('/products/create/step-4', [App\Http\Controllers\Admin\ProductsController::class, 'createStep4'])->name('admin.products.create.step4');
        Route::post('/products/create/step-4', [App\Http\Controllers\Admin\ProductsController::class, 'processStep4'])->name('admin.products.create.step4.process');

        Route::get('/products/create/step-5', [App\Http\Controllers\Admin\ProductsController::class, 'createStep5'])->name('admin.products.create.step5');
        Route::post('/products/create/step-5', [App\Http\Controllers\Admin\ProductsController::class, 'processStep5'])->name('admin.products.create.step5.process');

        Route::get('/products/create/step-6', [App\Http\Controllers\Admin\ProductsController::class, 'createStep6'])->name('admin.products.create.step6');
        Route::post('/products/create/step-6', [App\Http\Controllers\Admin\ProductsController::class, 'processStep6'])->name('admin.products.create.step6.process');

        Route::get('/products/create/step-7', [App\Http\Controllers\Admin\ProductsController::class, 'createStep7'])->name('admin.products.create.step7');
        Route::post('/products/create/step-7', [App\Http\Controllers\Admin\ProductsController::class, 'processStep7'])->name('admin.products.create.step7.process');

        Route::post('/products/create/clear-session', [App\Http\Controllers\Admin\ProductsController::class, 'clearSession'])->name('admin.products.create.clear-session');

        Route::get('/products/{product}/edit/step1', [App\Http\Controllers\Admin\ProductsController::class, 'editStep1'])->name('admin.products.edit.step1');
        Route::post('/products/{product}/edit/step1', [App\Http\Controllers\Admin\ProductsController::class, 'processEditStep1'])->name('admin.products.edit.step1.process');

        Route::get('/products/{product}/edit/step2', [App\Http\Controllers\Admin\ProductsController::class, 'editStep2'])->name('admin.products.edit.step2');
        Route::post('/products/{product}/edit/step2', [App\Http\Controllers\Admin\ProductsController::class, 'processEditStep2'])->name('admin.products.edit.step2.process');

        Route::get('/products/{product}/edit/step3', [App\Http\Controllers\Admin\ProductsController::class, 'editStep3'])->name('admin.products.edit.step3');
        Route::post('/products/{product}/edit/step3', [App\Http\Controllers\Admin\ProductsController::class, 'processEditStep3'])->name('admin.products.edit.step3.process');

        Route::get('/products/{product}/edit/step4', [App\Http\Controllers\Admin\ProductsController::class, 'editStep4'])->name('admin.products.edit.step4');
        Route::post('/products/{product}/edit/step4', [App\Http\Controllers\Admin\ProductsController::class, 'processEditStep4'])->name('admin.products.edit.step4.process');

        Route::get('/products/{product}/edit/step5', [App\Http\Controllers\Admin\ProductsController::class, 'editStep5'])->name('admin.products.edit.step5');
        Route::post('/products/{product}/edit/step5', [App\Http\Controllers\Admin\ProductsController::class, 'processEditStep5'])->name('admin.products.edit.step5.process');

        Route::get('/products/{product}/edit/step6', [App\Http\Controllers\Admin\ProductsController::class, 'editStep6'])->name('admin.products.edit.step6');
        Route::post('/products/{product}/edit/step6', [App\Http\Controllers\Admin\ProductsController::class, 'processEditStep6'])->name('admin.products.edit.step6.process');

        Route::get('/products/{product}/edit/step7', [App\Http\Controllers\Admin\ProductsController::class, 'editStep7'])->name('admin.products.edit.step7');
        Route::post('/products/{product}/edit/step7', [App\Http\Controllers\Admin\ProductsController::class, 'processEditStep7'])->name('admin.products.edit.step7.process');

        Route::get('/products/create', function () {
            return redirect()->route('admin.products.create.step1');
        })->name('admin.products.create');
        Route::post('/products', [App\Http\Controllers\Admin\ProductsController::class, 'store'])->name('admin.products.store');

        Route::post('/upload-image', function (Request $request, ImageKitService $imageKit) {
            try {
                $request->validate([
                    'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
                    'folder' => 'nullable|string'
                ]);

                $folder = $request->input('folder', 'products');

                if ($folder === 'categories') {
                    $result = $imageKit->uploadCategoryImage($request->file('image'), $folder);
                } else {

                    $result = $imageKit->uploadProductImage($request->file('image'), $folder);
                }

                if ($result && $result['success']) {
                    return response()->json([
                        'success' => true,
                        'url' => $result['url'],
                        'file_id' => $result['file_id'],
                        'fileId' => $result['file_id'],
                        'width' => $result['width'],
                        'height' => $result['height'],
                        'size' => $result['size'],
                        'original_size' => $result['original_size'],
                        'originalSize' => $result['original_size'],
                        'name' => $result['name'] ?? 'image',
                    ]);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to upload image'
                ], 500);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
        })->name('admin.upload.image');

        Route::get('/products/{product}', [App\Http\Controllers\Admin\ProductsController::class, 'show'])->name('admin.products.show');
        Route::get('/products/{product}/edit', function ($product) {
            return redirect()->route('admin.products.edit.step1', $product);
        })->name('admin.products.edit');
        Route::put('/products/{product}', [App\Http\Controllers\Admin\ProductsController::class, 'update'])->name('admin.products.update');
        Route::delete('/products/{product}', [App\Http\Controllers\Admin\ProductsController::class, 'destroy'])->name('admin.products.destroy');
        Route::delete('/products/{product}/variant-color', [App\Http\Controllers\Admin\ProductsController::class, 'deleteVariantColor'])->name('admin.products.delete-variant-color');

        Route::post('/products/{product}/toggle', [App\Http\Controllers\Admin\ProductsController::class, 'toggle'])->name('admin.products.toggle');

        Route::post('/products/{product}/copy', [App\Http\Controllers\Admin\ProductsController::class, 'copyProduct'])->name('admin.products.copy');

        Route::post('/products/bulk-action', [App\Http\Controllers\Admin\ProductsController::class, 'bulkAction'])->name('admin.products.bulk-action');

        Route::get('/products/export', [App\Http\Controllers\Admin\ProductsController::class, 'export'])->name('admin.products.export');

        Route::post('/products/{product}/status', [App\Http\Controllers\Admin\ProductsController::class, 'updateStatus'])->name('admin.products.status');
        Route::post('/products/{product}/toggle-featured', [App\Http\Controllers\Admin\ProductsController::class, 'toggleFeatured'])->name('admin.products.toggle-featured');
        Route::post('/products/bulk-delete', [App\Http\Controllers\Admin\ProductsController::class, 'bulkDelete'])->name('admin.products.bulk-delete');

        Route::resource('/categories', App\Http\Controllers\Admin\CategoryController::class, [
            'as' => 'admin'
        ]);
        Route::post('/categories/{category}/toggle-status', [App\Http\Controllers\Admin\CategoryController::class, 'toggleStatus'])->name('admin.categories.toggle-status');
        Route::post('/categories/{category}/toggle-featured', [App\Http\Controllers\Admin\CategoryController::class, 'toggleFeatured'])->name('admin.categories.toggle-featured');
        Route::post('/categories/{category}/toggle', [App\Http\Controllers\Admin\CategoryController::class, 'toggle'])->name('admin.categories.toggle');
        Route::post('/categories/bulk-action', [App\Http\Controllers\Admin\CategoryController::class, 'bulkAction'])->name('admin.categories.bulk-action');
        Route::get('/categories/export', [App\Http\Controllers\Admin\CategoryController::class, 'export'])->name('admin.categories.export');
        Route::post('/categories/bulk-delete', [App\Http\Controllers\Admin\CategoryController::class, 'bulkDelete'])->name('admin.categories.bulk-delete');

        Route::resource('/colors', App\Http\Controllers\Admin\ColorController::class, [
            'as' => 'admin'
        ]);
        Route::post('/colors/{color}/toggle', [App\Http\Controllers\Admin\ColorController::class, 'toggle'])->name('admin.colors.toggle');
        Route::post('/colors/bulk-action', [App\Http\Controllers\Admin\ColorController::class, 'bulkAction'])->name('admin.colors.bulk-action');
        Route::get('/colors/export', [App\Http\Controllers\Admin\ColorController::class, 'export'])->name('admin.colors.export');

        Route::resource('/sizes', App\Http\Controllers\Admin\SizeController::class, [
            'as' => 'admin'
        ]);
        Route::post('/sizes/{size}/toggle', [App\Http\Controllers\Admin\SizeController::class, 'toggle'])->name('admin.sizes.toggle');
        Route::post('/sizes/bulk-action', [App\Http\Controllers\Admin\SizeController::class, 'bulkAction'])->name('admin.sizes.bulk-action');
        Route::get('/sizes/export', [App\Http\Controllers\Admin\SizeController::class, 'export'])->name('admin.sizes.export');

        // Fabric Routes
        Route::resource('/fabrics', App\Http\Controllers\Admin\FabricController::class, [
            'as' => 'admin'
        ]);
        Route::post('/fabrics/{fabric}/toggle', [App\Http\Controllers\Admin\FabricController::class, 'toggle'])->name('admin.fabrics.toggle');
        Route::post('/fabrics/bulk-action', [App\Http\Controllers\Admin\FabricController::class, 'bulkAction'])->name('admin.fabrics.bulk-action');
        Route::get('/fabrics/export', [App\Http\Controllers\Admin\FabricController::class, 'export'])->name('admin.fabrics.export');

        // Theme Settings Routes
        Route::get('/theme-settings', [App\Http\Controllers\Admin\ThemeSettingsController::class, 'index'])->name('admin.theme-settings.index');
        Route::post('/theme-settings', [App\Http\Controllers\Admin\ThemeSettingsController::class, 'update'])->name('admin.theme-settings.update');
        Route::post('/theme-settings/reset', [App\Http\Controllers\Admin\ThemeSettingsController::class, 'resetToDefault'])->name('admin.theme-settings.reset');

        // User Management Routes
        Route::resource('/users', App\Http\Controllers\Admin\UserController::class, [
            'as' => 'admin'
        ]);
        Route::post('/users/{user}/ban', [App\Http\Controllers\Admin\UserController::class, 'ban'])->name('admin.users.ban');
        Route::post('/users/{user}/unban', [App\Http\Controllers\Admin\UserController::class, 'unban'])->name('admin.users.unban');
        Route::post('/users/{user}/toggle', [App\Http\Controllers\Admin\UserController::class, 'toggle'])->name('admin.users.toggle');
        Route::post('/users/bulk-action', [App\Http\Controllers\Admin\UserController::class, 'bulkAction'])->name('admin.users.bulk-action');
        Route::get('/users/export', [App\Http\Controllers\Admin\UserController::class, 'export'])->name('admin.users.export');

        Route::post('/lookbooks/upload-image', [App\Http\Controllers\Admin\LookbookController::class, 'uploadImage'])->name('admin.lookbooks.uploadImage');

        Route::resource('/lookbooks', App\Http\Controllers\Admin\LookbookController::class, [
            'as' => 'admin'
        ]);
        Route::post('/lookbooks/{lookbook}/toggle', [App\Http\Controllers\Admin\LookbookController::class, 'toggle'])->name('admin.lookbooks.toggle');
        Route::post('/lookbooks/bulk-action', [App\Http\Controllers\Admin\LookbookController::class, 'bulkAction'])->name('admin.lookbooks.bulk-action');
        Route::get('/lookbooks/export', [App\Http\Controllers\Admin\LookbookController::class, 'export'])->name('admin.lookbooks.export');

        // Blog Routes
        Route::post('/blogs/upload-image', [App\Http\Controllers\Admin\BlogController::class, 'uploadImage'])->name('admin.blogs.upload-image');
        Route::post('/blogs/{blog}/save-gallery', [App\Http\Controllers\Admin\BlogController::class, 'saveGalleryImages'])->name('admin.blogs.save-gallery');
        Route::post('/blogs/bulk-action', [App\Http\Controllers\Admin\BlogController::class, 'bulkAction'])->name('admin.blogs.bulk-action');
        Route::post('/blogs/{blog}/toggle', [App\Http\Controllers\Admin\BlogController::class, 'toggle'])->name('admin.blogs.toggle');
        Route::resource('/blogs', App\Http\Controllers\Admin\BlogController::class, [
            'as' => 'admin'
        ]);

        Route::resource('/brands', App\Http\Controllers\Admin\BrandController::class, [
            'as' => 'admin'
        ]);
        Route::post('/brands/{brand}/toggle', [App\Http\Controllers\Admin\BrandController::class, 'toggle'])->name('admin.brands.toggle');
        Route::post('/brands/bulk-action', [App\Http\Controllers\Admin\BrandController::class, 'bulkAction'])->name('admin.brands.bulk-action');
        Route::get('/brands/export', [App\Http\Controllers\Admin\BrandController::class, 'export'])->name('admin.brands.export');

        Route::post('/collections/upload-image', [App\Http\Controllers\Admin\CollectionController::class, 'uploadImage'])->name('admin.collections.upload-image');

        Route::resource('/collections', App\Http\Controllers\Admin\CollectionController::class, [
            'as' => 'admin'
        ]);
        Route::post('/collections/{collection}/toggle', [App\Http\Controllers\Admin\CollectionController::class, 'toggle'])->name('admin.collections.toggle');
        Route::post('/collections/bulk-action', [App\Http\Controllers\Admin\CollectionController::class, 'bulkAction'])->name('admin.collections.bulk-action');
        Route::get('/collections/export', [App\Http\Controllers\Admin\CollectionController::class, 'export'])->name('admin.collections.export');

        Route::resource('/tags', App\Http\Controllers\Admin\TagController::class, [
            'as' => 'admin'
        ]);
        Route::post('/tags/{tag}/toggle', [App\Http\Controllers\Admin\TagController::class, 'toggle'])->name('admin.tags.toggle');
        Route::post('/tags/bulk-action', [App\Http\Controllers\Admin\TagController::class, 'bulkAction'])->name('admin.tags.bulk-action');
        Route::get('/tags/export', [App\Http\Controllers\Admin\TagController::class, 'export'])->name('admin.tags.export');

        Route::resource('/coupons', App\Http\Controllers\Admin\CouponController::class, [
            'as' => 'admin'
        ]);
        Route::post('/coupons/{coupon}/toggle', [App\Http\Controllers\Admin\CouponController::class, 'toggle'])->name('admin.coupons.toggle');
        Route::post('/coupons/bulk-action', [App\Http\Controllers\Admin\CouponController::class, 'bulkAction'])->name('admin.coupons.bulk-action');
        Route::get('/coupons/export', [App\Http\Controllers\Admin\CouponController::class, 'export'])->name('admin.coupons.export');

        // Orders Management
        Route::resource('/orders', App\Http\Controllers\Admin\OrderController::class, [
            'as' => 'admin'
        ]);
        Route::patch('/orders/{order}/update-status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('admin.orders.update-status');
        Route::post('/orders/{order}/payment-status', [App\Http\Controllers\Admin\OrderController::class, 'updatePaymentStatus'])->name('admin.orders.updatePaymentStatus');
        Route::post('/orders/bulk-action', [App\Http\Controllers\Admin\OrderController::class, 'bulkAction'])->name('admin.orders.bulk-action');
        Route::get('/orders/export', [App\Http\Controllers\Admin\OrderController::class, 'export'])->name('admin.orders.export');

        // Admin Users Management
        Route::resource('admin-users', App\Http\Controllers\Admin\AdminUserController::class);
        Route::post('/admin-users/{adminUser}/toggle-status', [App\Http\Controllers\Admin\AdminUserController::class, 'toggleStatus']);

        // Activity Logs & Login History
        Route::get('/activity-logs', [App\Http\Controllers\Admin\AdminUserController::class, 'activityLogs']);
        Route::get('/login-history', [App\Http\Controllers\Admin\AdminUserController::class, 'loginHistory']);

        Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('admin.settings');
        Route::post('/settings/imagekit', [App\Http\Controllers\Admin\SettingsController::class, 'updateImageKit'])->name('admin.settings.imagekit');
        Route::post('/settings/general', [App\Http\Controllers\Admin\SettingsController::class, 'updateGeneral'])->name('admin.settings.general');
        Route::post('/settings/payment', [App\Http\Controllers\Admin\SettingsController::class, 'updatePaymentGateway'])->name('admin.settings.payment');
        Route::post('/settings/upload-logo', [App\Http\Controllers\Admin\SettingsController::class, 'uploadLogo'])->name('admin.settings.uploadLogo');
        Route::post('/settings/social', [App\Http\Controllers\Admin\SettingsController::class, 'updateSocialMedia'])->name('admin.settings.social');
        Route::post('/settings/maintenance', [App\Http\Controllers\Admin\SettingsController::class, 'updateMaintenance'])->name('admin.settings.maintenance');
        Route::post('/settings/seo', [App\Http\Controllers\Admin\SettingsController::class, 'updateSEO'])->name('admin.settings.seo');
        Route::post('/settings/upload-og-image', [App\Http\Controllers\Admin\SettingsController::class, 'uploadOGImage'])->name('admin.settings.uploadOGImage');
        Route::post('/settings/generate-sitemap', [App\Http\Controllers\Admin\SettingsController::class, 'generateSitemap'])->name('admin.settings.generateSitemap');
        Route::post('/settings/shiprocket', [App\Http\Controllers\Admin\SettingsController::class, 'updateShiprocket'])->name('admin.settings.shiprocket');
        Route::post('/orders/{id}/shiprocket', [App\Http\Controllers\Admin\OrderController::class, 'shipToShiprocket'])->name('admin.orders.shiprocket');
        Route::get('/orders/{id}/shiprocket/couriers', [App\Http\Controllers\Admin\OrderController::class, 'getCouriers'])->name('admin.orders.shiprocket.couriers');
        Route::post('/orders/{id}/shiprocket/awb', [App\Http\Controllers\Admin\OrderController::class, 'generateAwb'])->name('admin.orders.shiprocket.awb');


        // Banner Management
        Route::resource('/banners', App\Http\Controllers\Admin\BannerController::class, [
            'as' => 'admin'
        ]);
        Route::post('/banners/upload-image', [App\Http\Controllers\Admin\BannerController::class, 'uploadImage'])->name('admin.banners.uploadImage');
        Route::post('/banners/{banner}/toggle', [App\Http\Controllers\Admin\BannerController::class, 'toggle'])->name('admin.banners.toggle');
        Route::post('/banners/update-order', [App\Http\Controllers\Admin\BannerController::class, 'updateOrder'])->name('admin.banners.updateOrder');
        Route::post('/banners/fill-demo', [App\Http\Controllers\Admin\BannerController::class, 'fillDemo'])->name('admin.banners.fillDemo');

        // Review Management
        Route::get('/reviews', [App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('admin.reviews.index');
        Route::get('/reviews/{id}', [App\Http\Controllers\Admin\ReviewController::class, 'show'])->name('admin.reviews.show');
        Route::post('/reviews/{id}/approve', [App\Http\Controllers\Admin\ReviewController::class, 'approve'])->name('admin.reviews.approve');
        Route::delete('/reviews/{id}', [App\Http\Controllers\Admin\ReviewController::class, 'reject'])->name('admin.reviews.reject');
        Route::post('/reviews/{id}/toggle-approval', [App\Http\Controllers\Admin\ReviewController::class, 'toggleApproval'])->name('admin.reviews.toggle-approval');
        Route::post('/reviews/bulk-approve', [App\Http\Controllers\Admin\ReviewController::class, 'bulkApprove'])->name('admin.reviews.bulk-approve');
        Route::post('/reviews/bulk-delete', [App\Http\Controllers\Admin\ReviewController::class, 'bulkDelete'])->name('admin.reviews.bulk-delete');

        // Bulk Orders
        Route::resource('/bulk-orders', App\Http\Controllers\Admin\BulkOrderController::class, [
            'as' => 'admin'
        ]);

        // Contacts
        Route::resource('/contacts', App\Http\Controllers\Admin\ContactController::class, [
            'as' => 'admin'
        ]);
        Route::post('/contacts/{contact}/toggle-status', [App\Http\Controllers\Admin\ContactController::class, 'toggleStatus'])->name('admin.contacts.toggle-status');

        // Testimonials
        Route::resource('/testimonials', App\Http\Controllers\Admin\TestimonialsController::class, [
            'as' => 'admin'
        ]);
        Route::post('/testimonials/{testimonial}/toggle', [App\Http\Controllers\Admin\TestimonialsController::class, 'toggle'])->name('admin.testimonials.toggle');
        Route::post('/testimonials/bulk-action', [App\Http\Controllers\Admin\TestimonialsController::class, 'bulkAction'])->name('admin.testimonials.bulk-action');

        // Video Reels
        Route::resource('/video-reels', App\Http\Controllers\Admin\VideoReelsController::class, [
            'as' => 'admin'
        ]);
        Route::post('/video-reels/{videoReel}/toggle', [App\Http\Controllers\Admin\VideoReelsController::class, 'toggle'])->name('admin.video-reels.toggle');
        Route::post('/video-reels/bulk-action', [App\Http\Controllers\Admin\VideoReelsController::class, 'bulkAction'])->name('admin.video-reels.bulk-action');
        Route::post('/video-reels/upload-video', [App\Http\Controllers\Admin\VideoReelsController::class, 'uploadVideo'])->name('admin.video-reels.upload-video');

        // Promotional Banners
        Route::resource('/promotional-banners', App\Http\Controllers\Admin\PromotionalBannersController::class, [
            'as' => 'admin'
        ]);
        Route::post('/promotional-banners/{promotionalBanner}/toggle-status', [App\Http\Controllers\Admin\PromotionalBannersController::class, 'toggleStatus'])->name('admin.promotional-banners.toggle-status');

        // Budget Cards (Shop By Budget)
        Route::get('/budget-cards', [App\Http\Controllers\Admin\BudgetCardsController::class, 'index'])->name('admin.budget-cards.index');
        Route::post('/budget-cards', [App\Http\Controllers\Admin\BudgetCardsController::class, 'store'])->name('admin.budget-cards.store');
        Route::put('/budget-cards/{budgetCard}', [App\Http\Controllers\Admin\BudgetCardsController::class, 'update'])->name('admin.budget-cards.update');
        Route::delete('/budget-cards/{budgetCard}', [App\Http\Controllers\Admin\BudgetCardsController::class, 'destroy'])->name('admin.budget-cards.destroy');
        Route::post('/budget-cards/{budgetCard}/toggle', [App\Http\Controllers\Admin\BudgetCardsController::class, 'toggleStatus'])->name('admin.budget-cards.toggle');
        Route::post('/budget-cards/update-order', [App\Http\Controllers\Admin\BudgetCardsController::class, 'updateOrder'])->name('admin.budget-cards.update-order');

        Route::get('/logout', [App\Http\Controllers\Admin\LoginController::class, 'logout'])->name('admin.logout');
    });
});
