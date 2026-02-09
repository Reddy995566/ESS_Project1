<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function sales(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        
        // Date range
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : now()->subDays(30);
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : now();
        
        // Key metrics
        $metrics = [
            'total_revenue' => $seller->orderItems()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('seller_amount') ?? 0,
            'total_orders' => $seller->orderItems()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->distinct('order_id')
                ->count('order_id') ?? 0,
            'average_order_value' => $seller->orderItems()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->avg('seller_amount') ?? 0,
            'total_items_sold' => $seller->orderItems()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('quantity') ?? 0,
        ];
        
        // Sales trend
        $salesTrend = $seller->orderItems()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, SUM(seller_amount) as total, COUNT(DISTINCT order_id) as orders')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Revenue by category
        $revenueByCategory = $seller->orderItems()
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereBetween('order_items.created_at', [$startDate, $endDate])
            ->selectRaw('categories.name, SUM(order_items.seller_amount) as revenue')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('revenue')
            ->get();
        
        // Top products
        $topProducts = $seller->products()
            ->withCount(['orderItems as units_sold' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                      ->select(DB::raw('SUM(quantity)'));
            }])
            ->withSum(['orderItems as revenue' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }], 'seller_amount')
            ->with('category')
            ->having('units_sold', '>', 0)
            ->orderByDesc('units_sold')
            ->limit(10)
            ->get();
        
        return view('seller.analytics.sales', compact(
            'metrics',
            'salesTrend',
            'revenueByCategory',
            'topProducts',
            'startDate',
            'endDate'
        ));
    }
    
    public function products(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        
        // Product performance
        $products = $seller->products()
            ->withCount(['orderItems as units_sold' => function ($query) {
                $query->select(DB::raw('SUM(quantity)'));
            }])
            ->withSum('orderItems as revenue', 'seller_amount')
            ->withAvg('reviews as average_rating', 'rating')
            ->with('category')
            ->orderByDesc('units_sold')
            ->paginate(20);
        
        // Low stock products
        $lowStockProducts = $seller->products()
            ->where('stock', '>', 0)
            ->where('stock', '<=', 10)
            ->where('status', 'active')
            ->orderBy('stock')
            ->limit(10)
            ->get();
        
        // Out of stock products
        $outOfStockProducts = $seller->products()
            ->where('stock', 0)
            ->where('status', 'active')
            ->limit(10)
            ->get();
        
        return view('seller.analytics.products', compact(
            'products',
            'lowStockProducts',
            'outOfStockProducts'
        ));
    }
    
    public function customers(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        
        // Customer stats
        $stats = [
            'total_customers' => $seller->orderItems()
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->distinct('orders.user_id')
                ->count('orders.user_id'),
            'new_customers' => $seller->orderItems()
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->whereMonth('orders.created_at', now()->month)
                ->distinct('orders.user_id')
                ->count('orders.user_id'),
            'repeat_customers' => $seller->orderItems()
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->select('orders.user_id')
                ->groupBy('orders.user_id')
                ->havingRaw('COUNT(DISTINCT orders.id) > 1')
                ->get()
                ->count(),
        ];
        
        // Top customers
        $topCustomers = $seller->orderItems()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->selectRaw('users.id, users.name, users.email, COUNT(DISTINCT orders.id) as total_orders, SUM(order_items.seller_amount) as total_spent, MAX(orders.created_at) as last_order_date')
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('total_spent')
            ->limit(20)
            ->get();
        
        return view('seller.analytics.customers', compact('stats', 'topCustomers'));
    }
    
    public function export(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        $type = $request->get('type', 'sales');
        
        try {
            switch ($type) {
                case 'sales':
                    return $this->exportSalesData($seller, $request);
                case 'products':
                    return $this->exportProductsData($seller, $request);
                case 'customers':
                    return $this->exportCustomersData($seller, $request);
                default:
                    return back()->with('error', 'Invalid export type');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Export failed: ' . $e->getMessage());
        }
    }
    
    private function exportSalesData($seller, $request)
    {
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : now()->subDays(30);
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : now();
        
        $salesData = $seller->orderItems()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereBetween('order_items.created_at', [$startDate, $endDate])
            ->select([
                'orders.order_number',
                'products.name as product_name',
                'order_items.quantity',
                'order_items.price',
                'order_items.seller_amount',
                'order_items.created_at'
            ])
            ->orderBy('order_items.created_at', 'desc')
            ->get();
        
        $filename = 'sales_data_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($salesData) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Order Number', 'Product Name', 'Quantity', 'Price', 'Seller Amount', 'Date']);
            
            foreach ($salesData as $row) {
                fputcsv($file, [
                    $row->order_number,
                    $row->product_name,
                    $row->quantity,
                    $row->price,
                    $row->seller_amount,
                    $row->created_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    private function exportProductsData($seller, $request)
    {
        $products = $seller->products()
            ->withCount(['orderItems as units_sold' => function ($query) {
                $query->select(DB::raw('SUM(quantity)'));
            }])
            ->withSum('orderItems as revenue', 'seller_amount')
            ->withAvg('reviews as average_rating', 'rating')
            ->with('category')
            ->get();
        
        $filename = 'products_data_' . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($products) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Product Name', 'SKU', 'Category', 'Stock', 'Price', 'Units Sold', 'Revenue', 'Average Rating', 'Status']);
            
            foreach ($products as $product) {
                fputcsv($file, [
                    $product->name,
                    $product->sku ?? 'N/A',
                    $product->category->name ?? 'Uncategorized',
                    $product->stock,
                    $product->price,
                    $product->units_sold ?? 0,
                    $product->revenue ?? 0,
                    $product->average_rating ? number_format($product->average_rating, 1) : 'No reviews',
                    ucfirst($product->status)
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    private function exportCustomersData($seller, $request)
    {
        $customers = $seller->orderItems()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->selectRaw('users.id, users.name, users.email, COUNT(DISTINCT orders.id) as total_orders, SUM(order_items.seller_amount) as total_spent, MAX(orders.created_at) as last_order_date, MIN(orders.created_at) as first_order_date')
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('total_spent')
            ->get();
        
        $filename = 'customers_data_' . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($customers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Customer Name', 'Email', 'Total Orders', 'Total Spent', 'First Order', 'Last Order', 'Customer Type']);
            
            foreach ($customers as $customer) {
                $customerType = $customer->total_orders > 5 ? 'VIP Customer' : ($customer->total_orders > 1 ? 'Repeat Customer' : 'New Customer');
                
                fputcsv($file, [
                    $customer->name,
                    $customer->email,
                    $customer->total_orders,
                    $customer->total_spent,
                    Carbon::parse($customer->first_order_date)->format('Y-m-d'),
                    Carbon::parse($customer->last_order_date)->format('Y-m-d'),
                    $customerType
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
