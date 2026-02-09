<?php

namespace App\Observers;

use App\Models\Product;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        // Check if stock was updated and is now low
        if ($product->isDirty('stock') && $product->seller) {
            $oldStock = $product->getOriginal('stock');
            $newStock = $product->stock;
            
            // If stock decreased and is now low (but not out of stock)
            if ($newStock < $oldStock && $product->isLowStock() && !$product->isOutOfStock()) {
                // Check if we haven't sent a notification recently (within 6 hours)
                $recentNotification = $product->seller->notifications()
                    ->where('type', 'low_stock')
                    ->where('data', 'like', '%"product_id":' . $product->id . '%')
                    ->where('created_at', '>=', now()->subHours(6))
                    ->exists();
                
                if (!$recentNotification) {
                    $product->seller->sendNotification(
                        'low_stock',
                        'Low Stock Alert',
                        'Your product "' . $product->name . '" is running low on stock. Only ' . $product->stock . ' units remaining.',
                        json_encode([
                            'product_id' => $product->id,
                            'product_name' => $product->name,
                            'current_stock' => $product->stock,
                            'previous_stock' => $oldStock
                        ])
                    );
                }
            }
        }
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }
}
