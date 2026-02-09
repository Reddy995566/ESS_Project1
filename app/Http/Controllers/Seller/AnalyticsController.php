<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Services\AnalyticsService;
use App\Services\ReportService;

class AnalyticsController extends Controller
{
    protected $analyticsService;
    protected $reportService;

    public function __construct(AnalyticsService $analyticsService, ReportService $reportService)
    {
        $this->analyticsService = $analyticsService;
        $this->reportService = $reportService;
    }

    public function sales(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        $dateRange = $request->get('range', 30);
        
        // Get enhanced analytics data
        $analytics = $this->analyticsService->getDashboardAnalytics($seller->id, $dateRange);
        
        // Date range
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : now()->subDays($dateRange);
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : now();
        
        // Legacy metrics for backward compatibility
        $metrics = [
            'total_revenue' => $analytics['overview']['revenue']['current'],
            'total_orders' => $analytics['overview']['orders']['current'],
            'average_order_value' => $analytics['overview']['average_order_value']['current'],
            'total_items_sold' => $analytics['overview']['items_sold']['current'],
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
        
        // Top products from analytics service
        $topProducts = $analytics['top_products'];
        
        return view('seller.analytics.sales', compact(
            'analytics',
            'metrics',
            'salesTrend',
            'revenueByCategory',
            'topProducts',
            'startDate',
            'endDate',
            'dateRange'
        ));
    }
    
    public function products(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        $dateRange = $request->get('range', 30);
        
        $startDate = Carbon::now()->subDays($dateRange);
        $endDate = Carbon::now();
        
        // Get enhanced product analytics
        $topProducts = $this->analyticsService->getTopProducts($seller->id, $startDate, $endDate, 20);
        $performanceMetrics = $this->analyticsService->getPerformanceMetrics($seller->id, $startDate, $endDate);
        
        // Product performance
        $products = $seller->products()
            ->withCount(['orderItems as units_sold' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                      ->select(DB::raw('SUM(quantity)'));
            }])
            ->withSum(['orderItems as revenue' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }], 'seller_amount')
            ->withAvg('reviews as average_rating', 'rating')
            ->with('category')
            ->orderByDesc('units_sold')
            ->paginate(20);
        
        // Low stock products
        $lowStockProducts = $seller->products()
            ->where('stock_quantity', '>', 0)
            ->where('stock_quantity', '<=', 10)
            ->where('status', 'active')
            ->orderBy('stock_quantity')
            ->limit(10)
            ->get();
        
        // Out of stock products
        $outOfStockProducts = $seller->products()
            ->where('stock_quantity', 0)
            ->where('status', 'active')
            ->limit(10)
            ->get();
        
        return view('seller.analytics.products', compact(
            'topProducts',
            'performanceMetrics',
            'products',
            'lowStockProducts',
            'outOfStockProducts',
            'dateRange'
        ));
    }
    
    public function customers(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        $dateRange = $request->get('range', 30);
        
        $startDate = Carbon::now()->subDays($dateRange);
        $endDate = Carbon::now();
        
        // Get enhanced customer insights
        $customerInsights = $this->analyticsService->getCustomerInsights($seller->id, $startDate, $endDate);
        
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
        
        // Top customers from analytics service
        $topCustomers = $customerInsights['top_customers'];
        
        return view('seller.analytics.customers', compact(
            'customerInsights',
            'stats', 
            'topCustomers',
            'dateRange'
        ));
    }

    /**
     * Get analytics data via API
     */
    public function getAnalyticsData(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        $type = $request->get('type', 'overview');
        $dateRange = $request->get('range', 30);
        
        $startDate = Carbon::now()->subDays($dateRange);
        $endDate = Carbon::now();
        
        switch ($type) {
            case 'overview':
                $data = $this->analyticsService->getOverviewMetrics($seller->id, $startDate, $endDate);
                break;
            case 'sales_chart':
                $data = $this->analyticsService->getSalesChartData($seller->id, $startDate, $endDate);
                break;
            case 'top_products':
                $data = $this->analyticsService->getTopProducts($seller->id, $startDate, $endDate);
                break;
            case 'customer_insights':
                $data = $this->analyticsService->getCustomerInsights($seller->id, $startDate, $endDate);
                break;
            case 'revenue_breakdown':
                $data = $this->analyticsService->getRevenueBreakdown($seller->id, $startDate, $endDate);
                break;
            default:
                $data = $this->analyticsService->getDashboardAnalytics($seller->id, $dateRange);
        }
        
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Generate and download reports
     */
    public function generateReport(Request $request)
    {
        $request->validate([
            'type' => 'required|in:sales,products,customers,inventory',
            'format' => 'required|in:pdf,excel,csv',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $seller = Auth::guard('seller')->user();
        $type = $request->type;
        $format = $request->format;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        try {
            switch ($type) {
                case 'sales':
                    $result = $this->reportService->generateSalesReport($seller->id, $startDate, $endDate, $format);
                    break;
                case 'products':
                    $result = $this->reportService->generateProductReport($seller->id, $startDate, $endDate, $format);
                    break;
                case 'customers':
                    $result = $this->reportService->generateCustomerReport($seller->id, $startDate, $endDate, $format);
                    break;
                case 'inventory':
                    $result = $this->reportService->generateInventoryReport($seller->id, $format);
                    break;
                default:
                    throw new \Exception('Invalid report type');
            }

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Report generated successfully',
                    'download_url' => $result['url'],
                    'filename' => $result['filename'],
                ]);
            } else {
                throw new \Exception('Failed to generate report');
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating report: ' . $e->getMessage(),
            ], 500);
        }
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
                    $product->stock_quantity,
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
