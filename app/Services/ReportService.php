<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Seller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportService
{
    protected $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    /**
     * Generate sales report
     */
    public function generateSalesReport($sellerId = null, $startDate = null, $endDate = null, $format = 'pdf')
    {
        $startDate = $startDate ? Carbon::parse($startDate) : Carbon::now()->subDays(30);
        $endDate = $endDate ? Carbon::parse($endDate) : Carbon::now();

        $data = [
            'title' => 'Sales Report',
            'period' => $startDate->format('M d, Y') . ' - ' . $endDate->format('M d, Y'),
            'generated_at' => Carbon::now()->format('M d, Y H:i A'),
            'seller' => $sellerId ? Seller::find($sellerId) : null,
        ];

        // Get sales data
        $query = OrderItem::with(['product', 'order.user'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($sellerId) {
            $query->where('seller_id', $sellerId);
        }

        $salesData = $query->get();

        // Calculate summary metrics
        $data['summary'] = [
            'total_revenue' => $salesData->sum('seller_amount'),
            'total_orders' => $salesData->unique('order_id')->count(),
            'total_items' => $salesData->sum('quantity'),
            'average_order_value' => $salesData->unique('order_id')->count() > 0 
                ? $salesData->sum('seller_amount') / $salesData->unique('order_id')->count() 
                : 0,
        ];

        // Group by date for daily breakdown
        $data['daily_breakdown'] = $salesData->groupBy(function($item) {
            return $item->created_at->format('Y-m-d');
        })->map(function($dayItems) {
            return [
                'date' => $dayItems->first()->created_at->format('M d, Y'),
                'revenue' => $dayItems->sum('seller_amount'),
                'orders' => $dayItems->unique('order_id')->count(),
                'items' => $dayItems->sum('quantity'),
            ];
        })->values();

        // Top products
        $data['top_products'] = $salesData->groupBy('product_id')
            ->map(function($productItems) {
                $product = $productItems->first()->product;
                return [
                    'name' => $product->name,
                    'quantity' => $productItems->sum('quantity'),
                    'revenue' => $productItems->sum('seller_amount'),
                    'orders' => $productItems->unique('order_id')->count(),
                ];
            })
            ->sortByDesc('revenue')
            ->take(10)
            ->values();

        $data['sales_data'] = $salesData;

        return $this->generateReportFile($data, 'sales-report', $format);
    }

    /**
     * Generate product performance report
     */
    public function generateProductReport($sellerId = null, $startDate = null, $endDate = null, $format = 'pdf')
    {
        $startDate = $startDate ? Carbon::parse($startDate) : Carbon::now()->subDays(30);
        $endDate = $endDate ? Carbon::parse($endDate) : Carbon::now();

        $data = [
            'title' => 'Product Performance Report',
            'period' => $startDate->format('M d, Y') . ' - ' . $endDate->format('M d, Y'),
            'generated_at' => Carbon::now()->format('M d, Y H:i A'),
            'seller' => $sellerId ? Seller::find($sellerId) : null,
        ];

        // Get product performance data
        $query = Product::with(['category', 'brand'])
            ->leftJoin('order_items', function($join) use ($startDate, $endDate) {
                $join->on('products.id', '=', 'order_items.product_id')
                     ->whereBetween('order_items.created_at', [$startDate, $endDate]);
            })
            ->select(
                'products.*',
                DB::raw('COALESCE(SUM(order_items.quantity), 0) as total_sold'),
                DB::raw('COALESCE(SUM(order_items.seller_amount), 0) as total_revenue'),
                DB::raw('COUNT(DISTINCT order_items.order_id) as total_orders')
            );

        if ($sellerId) {
            $query->where('products.seller_id', $sellerId);
        }

        $products = $query->groupBy('products.id')->get();

        // Calculate performance metrics
        $data['products'] = $products->map(function($product) {
            $viewsCount = 0; // You can implement view tracking
            $conversionRate = $viewsCount > 0 ? ($product->total_orders / $viewsCount) * 100 : 0;
            
            return [
                'name' => $product->name,
                'category' => $product->category->name ?? 'N/A',
                'brand' => $product->brand->name ?? 'N/A',
                'price' => $product->price,
                'stock' => $product->stock_quantity,
                'sold' => $product->total_sold,
                'revenue' => $product->total_revenue,
                'orders' => $product->total_orders,
                'conversion_rate' => $conversionRate,
                'performance_score' => $this->calculateProductPerformanceScore($product),
            ];
        })->sortByDesc('revenue');

        // Summary metrics
        $data['summary'] = [
            'total_products' => $products->count(),
            'products_sold' => $products->where('total_sold', '>', 0)->count(),
            'total_revenue' => $products->sum('total_revenue'),
            'average_revenue_per_product' => $products->count() > 0 
                ? $products->sum('total_revenue') / $products->count() 
                : 0,
        ];

        return $this->generateReportFile($data, 'product-report', $format);
    }

    /**
     * Generate customer report
     */
    public function generateCustomerReport($sellerId = null, $startDate = null, $endDate = null, $format = 'pdf')
    {
        $startDate = $startDate ? Carbon::parse($startDate) : Carbon::now()->subDays(30);
        $endDate = $endDate ? Carbon::parse($endDate) : Carbon::now();

        $data = [
            'title' => 'Customer Analysis Report',
            'period' => $startDate->format('M d, Y') . ' - ' . $endDate->format('M d, Y'),
            'generated_at' => Carbon::now()->format('M d, Y H:i A'),
            'seller' => $sellerId ? Seller::find($sellerId) : null,
        ];

        // Get customer data
        $query = User::leftJoin('orders', 'users.id', '=', 'orders.user_id')
            ->leftJoin('order_items', function($join) use ($startDate, $endDate, $sellerId) {
                $join->on('orders.id', '=', 'order_items.order_id')
                     ->whereBetween('order_items.created_at', [$startDate, $endDate]);
                if ($sellerId) {
                    $join->where('order_items.seller_id', $sellerId);
                }
            })
            ->select(
                'users.*',
                DB::raw('COUNT(DISTINCT orders.id) as total_orders'),
                DB::raw('COALESCE(SUM(order_items.seller_amount), 0) as total_spent'),
                DB::raw('COALESCE(SUM(order_items.quantity), 0) as total_items'),
                DB::raw('MAX(order_items.created_at) as last_order_date'),
                DB::raw('MIN(order_items.created_at) as first_order_date')
            )
            ->groupBy('users.id')
            ->having('total_orders', '>', 0);

        $customers = $query->get();

        // Customer segmentation
        $data['customers'] = $customers->map(function($customer) {
            $daysSinceLastOrder = $customer->last_order_date 
                ? Carbon::parse($customer->last_order_date)->diffInDays(Carbon::now())
                : null;
            
            $segment = $this->getCustomerSegment($customer->total_spent, $customer->total_orders);
            
            return [
                'name' => $customer->name,
                'email' => $customer->email,
                'total_orders' => $customer->total_orders,
                'total_spent' => $customer->total_spent,
                'total_items' => $customer->total_items,
                'average_order_value' => $customer->total_orders > 0 
                    ? $customer->total_spent / $customer->total_orders 
                    : 0,
                'days_since_last_order' => $daysSinceLastOrder,
                'segment' => $segment,
                'first_order' => $customer->first_order_date 
                    ? Carbon::parse($customer->first_order_date)->format('M d, Y')
                    : null,
                'last_order' => $customer->last_order_date 
                    ? Carbon::parse($customer->last_order_date)->format('M d, Y')
                    : null,
            ];
        })->sortByDesc('total_spent');

        // Summary metrics
        $data['summary'] = [
            'total_customers' => $customers->count(),
            'total_revenue' => $customers->sum('total_spent'),
            'average_customer_value' => $customers->count() > 0 
                ? $customers->sum('total_spent') / $customers->count() 
                : 0,
            'repeat_customers' => $customers->where('total_orders', '>', 1)->count(),
        ];

        // Customer segments
        $data['segments'] = $customers->groupBy(function($customer) {
            return $this->getCustomerSegment($customer->total_spent, $customer->total_orders);
        })->map(function($segment) {
            return [
                'count' => $segment->count(),
                'revenue' => $segment->sum('total_spent'),
                'percentage' => 0, // Will be calculated in view
            ];
        });

        return $this->generateReportFile($data, 'customer-report', $format);
    }

    /**
     * Generate inventory report
     */
    public function generateInventoryReport($sellerId = null, $format = 'pdf')
    {
        $data = [
            'title' => 'Inventory Report',
            'generated_at' => Carbon::now()->format('M d, Y H:i A'),
            'seller' => $sellerId ? Seller::find($sellerId) : null,
        ];

        // Get inventory data
        $query = Product::with(['category', 'brand'])
            ->select(
                'products.*',
                DB::raw('COALESCE(products.stock_quantity, 0) as current_stock')
            );

        if ($sellerId) {
            $query->where('seller_id', $sellerId);
        }

        $products = $query->get();

        // Categorize inventory
        $data['inventory'] = $products->map(function($product) {
            $status = $this->getInventoryStatus($product->current_stock, $product->low_stock_threshold ?? 10);
            
            return [
                'name' => $product->name,
                'sku' => $product->sku,
                'category' => $product->category->name ?? 'N/A',
                'brand' => $product->brand->name ?? 'N/A',
                'current_stock' => $product->current_stock,
                'low_stock_threshold' => $product->low_stock_threshold ?? 10,
                'status' => $status,
                'value' => $product->current_stock * $product->price,
                'price' => $product->price,
            ];
        });

        // Summary metrics
        $data['summary'] = [
            'total_products' => $products->count(),
            'total_inventory_value' => $products->sum(function($p) { 
                return $p->current_stock * $p->price; 
            }),
            'low_stock_items' => $products->filter(function($p) {
                return $p->current_stock <= ($p->low_stock_threshold ?? 10);
            })->count(),
            'out_of_stock_items' => $products->where('current_stock', 0)->count(),
        ];

        return $this->generateReportFile($data, 'inventory-report', $format);
    }

    /**
     * Generate report file in specified format
     */
    private function generateReportFile($data, $reportType, $format)
    {
        $filename = $reportType . '-' . Carbon::now()->format('Y-m-d-H-i-s');

        switch ($format) {
            case 'pdf':
                return $this->generatePDF($data, $reportType, $filename);
            case 'excel':
                return $this->generateExcel($data, $reportType, $filename);
            case 'csv':
                return $this->generateCSV($data, $reportType, $filename);
            default:
                return $data;
        }
    }

    /**
     * Generate PDF report
     */
    private function generatePDF($data, $reportType, $filename)
    {
        $pdf = Pdf::loadView("reports.{$reportType}", $data);
        $pdf->setPaper('A4', 'portrait');
        
        $path = "reports/{$filename}.pdf";
        Storage::disk('public')->put($path, $pdf->output());
        
        return [
            'success' => true,
            'filename' => $filename . '.pdf',
            'path' => $path,
            'url' => Storage::disk('public')->url($path),
            'size' => Storage::disk('public')->size($path),
        ];
    }

    /**
     * Generate Excel report
     */
    private function generateExcel($data, $reportType, $filename)
    {
        // Implementation for Excel generation using PhpSpreadsheet
        // This would require installing PhpSpreadsheet package
        
        return [
            'success' => true,
            'filename' => $filename . '.xlsx',
            'message' => 'Excel generation not implemented yet',
        ];
    }

    /**
     * Generate CSV report
     */
    private function generateCSV($data, $reportType, $filename)
    {
        $csvData = $this->convertArrayToCSV($data);
        
        $path = "reports/{$filename}.csv";
        Storage::disk('public')->put($path, $csvData);
        
        return [
            'success' => true,
            'filename' => $filename . '.csv',
            'path' => $path,
            'url' => Storage::disk('public')->url($path),
            'size' => Storage::disk('public')->size($path),
        ];
    }

    /**
     * Helper methods
     */
    private function calculateProductPerformanceScore($product)
    {
        // Simple performance score based on sales and revenue
        $salesScore = min(($product->total_sold / 100) * 40, 40); // Max 40 points
        $revenueScore = min(($product->total_revenue / 10000) * 40, 40); // Max 40 points
        $orderScore = min(($product->total_orders / 50) * 20, 20); // Max 20 points
        
        return round($salesScore + $revenueScore + $orderScore, 2);
    }

    private function getCustomerSegment($totalSpent, $totalOrders)
    {
        if ($totalSpent >= 10000 || $totalOrders >= 10) {
            return 'VIP';
        } elseif ($totalSpent >= 5000 || $totalOrders >= 5) {
            return 'High Value';
        } elseif ($totalSpent >= 1000 || $totalOrders >= 2) {
            return 'Regular';
        } else {
            return 'New';
        }
    }

    private function getInventoryStatus($currentStock, $threshold)
    {
        if ($currentStock == 0) {
            return 'Out of Stock';
        } elseif ($currentStock <= $threshold) {
            return 'Low Stock';
        } else {
            return 'In Stock';
        }
    }

    private function convertArrayToCSV($data)
    {
        // Simple CSV conversion - can be enhanced based on data structure
        $output = '';
        
        if (isset($data['summary'])) {
            $output .= "Summary\n";
            foreach ($data['summary'] as $key => $value) {
                $output .= ucwords(str_replace('_', ' ', $key)) . "," . $value . "\n";
            }
            $output .= "\n";
        }
        
        return $output;
    }
}