<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;
use Illuminate\Support\Facades\DB;

echo "Fixing default variants...\n";

// Reset all is_default to 0 first
DB::table('product_variants')->update(['is_default' => 0]);
echo "Reset all variants.\n";

// Get all products with variants
$products = Product::with('variants')->get();

foreach ($products as $product) {
    if ($product->variants->count() > 0) {
        // Check if any variant is already default
        $hasDefault = $product->variants->where('is_default', 1)->first();
        
        if (!$hasDefault) {
            // Set first variant as default
            $firstVariant = $product->variants->first();
            $firstVariant->update(['is_default' => 1]);
            echo "Set default variant for product ID: {$product->id} - {$product->name}\n";
        }
    }
}

echo "\nDone! Default variants have been set.\n";
