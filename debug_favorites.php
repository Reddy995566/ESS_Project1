<?php
use App\Models\Product;
use App\Models\Category;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "DEBUGGING SELLER PRODUCTS LOCATION\n";
echo "==================================\n";

// 1. Find ANY active approved seller products
$sellerProducts = Product::whereNotNull('seller_id')
    ->where('status', 'active')
    ->where('approval_status', 'approved')
    ->limit(5)
    ->get();

if ($sellerProducts->count() == 0) {
    echo "WARNING: No Active & Approved Seller Products found at all!\n";

    $anySellerProduct = Product::whereNotNull('seller_id')->first();
    if ($anySellerProduct) {
        echo "Found at least one seller product (ID: {$anySellerProduct->id}) but status is '{$anySellerProduct->status}' and approval is '{$anySellerProduct->approval_status}'\n";
    } else {
        echo "No seller products exist in the database.\n";
    }
} else {
    echo "Found " . $sellerProducts->count() . " sample Active & Approved Seller Products:\n";
    foreach ($sellerProducts as $p) {
        $cat = $p->category;
        $parent = $cat ? $cat->parent : null;
        $grandparent = $parent ? $parent->parent : null; // Assuming 3 levels max for now

        echo "- Product ID: {$p->id} | Name: {$p->name}\n";
        echo "  Category ID: {$p->category_id} | Name: " . ($cat ? $cat->name : 'N/A') . "\n";

        // Trace back to root category
        $root = $cat;
        $path = [$cat->name];
        while ($root && $root->parent) {
            $root = $root->parent;
            array_unshift($path, $root->name);
        }
        echo "  Full Category Path: " . implode(' > ', $path) . "\n";
        echo "  Root Category ID: " . ($root ? $root->id : 'N/A') . "\n";
        echo "  Show in Homepage (Root): " . ($root ? ($root->show_in_homepage ? 'YES' : 'NO') : 'N/A') . "\n";
        echo "----------------------------------------\n";
    }
}

// 2. Check the specific All-Time Favorites Categories again to be sure
$allTimeFavoritesCats = Category::whereNull('parent_id')
    ->where('is_active', true)
    ->where('show_in_homepage', true)
    ->orderBy('sort_order')
    ->orderBy('name')
    ->take(2)
    ->pluck('id');

echo "\nCategories used for 'All-Time Favorites' (IDs): " . implode(', ', $allTimeFavoritesCats->toArray()) . "\n";
