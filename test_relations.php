<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$product = App\Models\Product::find(4);
echo "Product ID: " . $product->id . "\n";
echo "Collections Count: " . $product->collections()->count() . "\n";
echo "Collection IDs: " . json_encode($product->collections->pluck('id')) . "\n";
echo "Tags Count: " . $product->tags()->count() . "\n";
echo "Tag IDs: " . json_encode($product->tags->pluck('id')) . "\n";
