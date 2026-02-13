<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Seller;
use App\Models\SellerCommission;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    /**
     * Get comprehensive dashboard analytics
     */
    public function getDashboardAnalytics($sellerId = null, $dateRange = 30)
    {
        $startDate = Carbon::now()->subDays($dateRange);
        $endDate = Carbon::now();

        return [
            'overview' => $this->getOverviewMetrics($sellerId, $startDate, $endDate),
            'sales_chart' => $this->getSalesChartData($sellerId, $startDate, $endDate),
            'top_products' => $this->getTopProducts($sellerId, $startDate, $endDate),
            'customer_insights' => $this->getCustomerInsights($sellerId, $startDate, $endDate),
            'revenue_breakdown' => $this->getRevenueBreakdown($sellerId, $startDate, $endDate),
            'performance_metrics' => $this->getPerformanceMetrics($sellerId, $startDate, $endDate),
        ];
    }

    /**
     * Get overview metrics
     */
    public function getOverviewMetrics($sellerId = null, $startDate = null, $endDate = null)
    {
        $startDate = $startDate ?: Carbon::now()->subDays(30);
        $endDate = $endDate ?: Carbon::now();
        $previousStart = $startDate->copy()->subDays($startDate->diffInDays($endDate));
        $previousEnd = $startDate->copy();

        $query = OrderItem::whereBetween('order_items.created_at', [$startDate, $endDate]);
        $previousQuery = OrderItem::whereBetween('order_items.created_at', [$previousStart, $previousEnd]);

        if ($sellerId) {
            $query->where('order_items.seller_id', $sellerId);
            $previousQuery->where('order_items.seller_id', $sellerId);
        }

        // Current period metrics
        $currentRevenue = $query->sum('seller_amount');
        $currentOrders = $query->distinct('order_id')->count('order_id');
        $currentItems = $query->sum('quantity');
        $currentCustomers = $query->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->distinct('orders.user_id')->count('orders.user_id');

        // Previous period metrics
        $previousRevenue = $previousQuery->sum('seller_amount');
        $previousOrders = $previousQuery->distinct('order_id')->count('order_id');
        $previousItems = $previousQuery->sum('quantity');
        $previousCustomers = $previousQuery->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->distinct('orders.user_id')->count('orders.user_id');

        return [
            'revenue' => [
                'current' => $currentRevenue,
                'previous' => $previousRevenue,
                'change' => $this->calculatePercentageChange($currentRevenue, $previousRevenue),
            ],
            'orders' => [
                'current' => $currentOrders,
                'previous' => $previousOrders,
                'change' => $this->calculatePercentageChange($currentOrders, $previousOrders),
            ],
            'items_sold' => [
                'current' => $currentItems,
                'previous' => $previousItems,
                'change' => $this->calculatePercentageChange($currentItems, $previousItems),
            ],
            'customers' => [
                'current' => $currentCustomers,
                'previous' => $previousCustomers,
                'change' => $this->calculatePercentageChange($currentCustomers, $previousCustomers),
            ],
            'average_order_value' => [
                'current' => $currentOrders > 0 ? $currentRevenue / $currentOrders : 0,
                'previous' => $previousOrders > 0 ? $previousRevenue / $previousOrders : 0,
            ],
        ];
    }

    /**
     * Get sales chart data
     */
    public function getSalesChartData($sellerId = null, $startDate = null, $endDate = null, $groupBy = 'day')
    {
        $startDate = $startDate ?: Carbon::now()->subDays(30);
        $endDate = $endDate ?: Carbon::now();

        $query = OrderItem::select(
            DB::raw("DATE({$this->getDateFormat($groupBy)}) as date"),
            DB::raw('SUM(seller_amount) as revenue'),
            DB::raw('COUNT(DISTINCT order_id) as orders'),
            DB::raw('SUM(quantity) as items')
        )
        ->whereBetween('order_items.created_at', [$startDate, $endDate]);

        if ($sellerId) {
            $query->where('order_items.seller_id', $sellerId);
        }

        $data = $query->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'labels' => $data->pluck('date')->map(function($date) use ($groupBy) {
                return $this->formatDateLabel($date, $groupBy);
            }),
            'datasets' => [
                [
                    'label' => 'Revenue',
                    'data' => $data->pluck('revenue'),
                    'backgroundColor' => 'rgba(79, 70, 229, 0.1)',
                    'borderColor' => 'rgba(79, 70, 229, 1)',
                    'borderWidth' => 2,
                    'fill' => true,
                ],
                [
                    'label' => 'Orders',
                    'data' => $data->pluck('orders'),
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'borderColor' => 'rgba(16, 185, 129, 1)',
                    'borderWidth' => 2,
                    'fill' => true,
                ],
            ],
        ];
    }

    /**
     * Get top performing products
     */
    public function getTopProducts($sellerId = null, $startDate = null, $endDate = null, $limit = 10)
    {
        $startDate = $startDate ?: Carbon::now()->subDays(30);
        $endDate = $endDate ?: Carbon::now();

        $query = OrderItem::select(
            'products.id',
            'products.name',
            'products.image',
            'products.price',
            DB::raw('SUM(order_items.quantity) as total_sold'),
            DB::raw('SUM(order_items.seller_amount) as total_revenue'),
            DB::raw('COUNT(DISTINCT order_items.order_id) as total_orders'),
            DB::raw('AVG(order_items.seller_amount / order_items.quantity) as avg_price')
        )
        ->join('products', 'order_items.product_id', '=', 'products.id')
        ->whereBetween('order_items.created_at', [$startDate, $endDate]);

        if ($sellerId) {
            $query->where('order_items.seller_id', $sellerId);
        }

        return $query->groupBy('products.id', 'products.name', 'products.image', 'products.price')
            ->orderBy('total_revenue', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get customer insights
     */
    public function getCustomerInsights($sellerId = null, $startDate = null, $endDate = null)
    {
        $startDate = $startDate ?: Carbon::now()->subDays(30);
        $endDate = $endDate ?: Carbon::now();

        $query = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->whereBetween('order_items.created_at', [$startDate, $endDate]);

        if ($sellerId) {
            $query->where('order_items.seller_id', $sellerId);
        }

        // Top customers by revenue
        $topCustomers = $query->select(
            'users.id',
            'users.name',
            'users.email',
            DB::raw('SUM(order_items.seller_amount) as total_spent'),
            DB::raw('COUNT(DISTINCT order_items.order_id) as total_orders'),
            DB::raw('SUM(order_items.quantity) as total_items')
        )
        ->groupBy('users.id', 'users.name', 'users.email')
        ->orderBy('total_spent', 'desc')
        ->limit(10)
        ->get();

        // Customer segments
        $customerSegments = $this->getCustomerSegments($sellerId, $startDate, $endDate);

        // New vs returning customers
        $newVsReturning = $this->getNewVsReturningCustomers($sellerId, $startDate, $endDate);

        return [
            'top_customers' => $topCustomers,
            'segments' => $customerSegments,
            'new_vs_returning' => $newVsReturning,
        ];
    }

    /**
     * Get revenue breakdown
     */
    public function getRevenueBreakdown($sellerId = null, $startDate = null, $endDate = null)
    {
        $startDate = $startDate ?: Carbon::now()->subDays(30);
        $endDate = $endDate ?: Carbon::now();

        $query = OrderItem::whereBetween('order_items.created_at', [$startDate, $endDate]);

        if ($sellerId) {
            $query->where('order_items.seller_id', $sellerId);
        }

        // Revenue by category
        $categoryRevenue = $query->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'categories.name as category',
                DB::raw('SUM(order_items.seller_amount) as revenue'),
                DB::raw('SUM(order_items.quantity) as quantity')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('revenue', 'desc')
            ->get();

        // Revenue by payment method
        $paymentMethodRevenue = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('order_items.created_at', [$startDate, $endDate])
            ->when($sellerId, function($q) use ($sellerId) {
                return $q->where('order_items.seller_id', $sellerId);
            })
            ->select(
                'orders.payment_method',
                DB::raw('SUM(order_items.seller_amount) as revenue'),
                DB::raw('COUNT(DISTINCT order_items.order_id) as orders')
            )
            ->groupBy('orders.payment_method')
            ->get();

        return [
            'by_category' => $categoryRevenue,
            'by_payment_method' => $paymentMethodRevenue,
        ];
    }

    /**
     * Get performance metrics
     */
    public function getPerformanceMetrics($sellerId = null, $startDate = null, $endDate = null)
    {
        $startDate = $startDate ?: Carbon::now()->subDays(30);
        $endDate = $endDate ?: Carbon::now();

        $query = OrderItem::whereBetween('order_items.created_at', [$startDate, $endDate]);

        if ($sellerId) {
            $query->where('order_items.seller_id', $sellerId);
        }

        $totalRevenue = $query->sum('seller_amount');
        $totalOrders = $query->distinct('order_id')->count('order_id');
        $totalItems = $query->sum('quantity');

        // Conversion metrics
        $conversionRate = $this->getConversionRate($sellerId, $startDate, $endDate);
        
        // Return rate
        $returnRate = $this->getReturnRate($sellerId, $startDate, $endDate);

        // Inventory turnover
        $inventoryTurnover = $this->getInventoryTurnover($sellerId, $startDate, $endDate);

        return [
            'conversion_rate' => $conversionRate,
            'return_rate' => $returnRate,
            'inventory_turnover' => $inventoryTurnover,
            'average_order_value' => $totalOrders > 0 ? $totalRevenue / $totalOrders : 0,
            'average_items_per_order' => $totalOrders > 0 ? $totalItems / $totalOrders : 0,
        ];
    }

    /**
     * Generate comprehensive report
     */
    public function generateReport($type, $sellerId = null, $startDate = null, $endDate = null, $format = 'array')
    {
        $startDate = $startDate ? Carbon::parse($startDate) : Carbon::now()->subDays(30);
        $endDate = $endDate ? Carbon::parse($endDate) : Carbon::now();

        $data = [
            'report_info' => [
                'type' => $type,
                'seller_id' => $sellerId,
                'date_range' => [
                    'start' => $startDate->format('Y-m-d'),
                    'end' => $endDate->format('Y-m-d'),
                ],
                'generated_at' => Carbon::now()->toISOString(),
            ],
            'analytics' => $this->getDashboardAnalytics($sellerId, $startDate->diffInDays($endDate)),
        ];

        switch ($format) {
            case 'json':
                return json_encode($data, JSON_PRETTY_PRINT);
            case 'csv':
                return $this->convertToCSV($data);
            default:
                return $data;
        }
    }

    /**
     * Helper methods
     */
    private function calculatePercentageChange($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        
        return round((($current - $previous) / $previous) * 100, 2);
    }

    private function getDateFormat($groupBy)
    {
        switch ($groupBy) {
            case 'hour':
                return 'created_at';
            case 'day':
                return 'created_at';
            case 'week':
                return 'created_at';
            case 'month':
                return 'created_at';
            case 'year':
                return 'created_at';
            default:
                return 'created_at';
        }
    }

    private function formatDateLabel($date, $groupBy)
    {
        $carbonDate = Carbon::parse($date);
        
        switch ($groupBy) {
            case 'hour':
                return $carbonDate->format('H:i');
            case 'day':
                return $carbonDate->format('M d');
            case 'week':
                return 'Week ' . $carbonDate->weekOfYear;
            case 'month':
                return $carbonDate->format('M Y');
            case 'year':
                return $carbonDate->format('Y');
            default:
                return $carbonDate->format('M d');
        }
    }

    private function getCustomerSegments($sellerId, $startDate, $endDate)
    {
        // Implementation for customer segmentation
        return [
            'high_value' => 0,
            'medium_value' => 0,
            'low_value' => 0,
            'new_customers' => 0,
        ];
    }

    private function getNewVsReturningCustomers($sellerId, $startDate, $endDate)
    {
        // Implementation for new vs returning customer analysis
        return [
            'new_customers' => 0,
            'returning_customers' => 0,
        ];
    }

    private function getConversionRate($sellerId, $startDate, $endDate)
    {
        // Implementation for conversion rate calculation
        return 0;
    }

    private function getReturnRate($sellerId, $startDate, $endDate)
    {
        // Implementation for return rate calculation
        return 0;
    }

    private function getInventoryTurnover($sellerId, $startDate, $endDate)
    {
        // Implementation for inventory turnover calculation
        return 0;
    }

    private function convertToCSV($data)
    {
        // Implementation for CSV conversion
        return '';
    }
}