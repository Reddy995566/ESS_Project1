<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Seller;

class CheckLowStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:check-low {--threshold=5 : Stock threshold for low stock alert}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for low stock products and send notifications to sellers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $threshold = $this->option('threshold');
        
        $this->info("Checking for products with stock <= {$threshold}...");
        
        // Get products with low stock that have sellers
        $lowStockProducts = Product::whereNotNull('seller_id')
            ->where('stock', '>', 0)
            ->where('stock', '<=', $threshold)
            ->where('status', 'active')
            ->with(['seller'])
            ->get();
        
        if ($lowStockProducts->isEmpty()) {
            $this->info('No low stock products found.');
            return;
        }
        
        $notificationsSent = 0;
        
        foreach ($lowStockProducts as $product) {
            if ($product->seller) {
                // Check if we already sent a notification for this product recently (within 24 hours)
                $recentNotification = $product->seller->notifications()
                    ->where('type', 'low_stock')
                    ->where('data', 'like', '%"product_id":' . $product->id . '%')
                    ->where('created_at', '>=', now()->subHours(24))
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
                            'threshold' => $threshold
                        ])
                    );
                    
                    $notificationsSent++;
                    $this->line("✓ Notification sent for: {$product->name} (Stock: {$product->stock})");
                } else {
                    $this->line("- Skipped: {$product->name} (Recent notification already sent)");
                }
            }
        }
        
        $this->info("Low stock check completed. {$notificationsSent} notifications sent.");
    }
}
