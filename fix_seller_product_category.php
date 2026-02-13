<?php
use App\Models\Product;
use App\Models\Category;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "FIXING SELLER PRODUCT CATEGORY FOR TEST\n";
echo "=====================================\n";

$product = Product::find(4);
if (!$product) {
    echo "Product 4 not found.\n";
    exit;
}

echo "Current Category ID: " . $product->category_id . "\n";

// Change to 'New Arrivals' (ID 1)
$newArrivalsId = 1;
$product->category_id = $newArrivalsId;
$product->status = 'active'; // Ensure it's active
$product->approval_status = 'approved'; // Ensure it's approved
$product->save();

echo "Updated Category ID to: " . $product->category_id . "\n";
echo "Status: " . $product->status . "\n";
echo "Approval Status: " . $product->approval_status . "\n";
echo "Product ID 4 should now appear in 'All-Time Favorites' -> 'New Arrivals' tab.\n";
