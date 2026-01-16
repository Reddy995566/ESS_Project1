<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Stats Cards
        // Match revenue calculation with OrderController: sum of total for orders that are processing, shipped, or delivered
        $totalRevenue = Order::whereIn('status', ['delivered', 'shipped', 'processing'])->sum('total');
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalCustomers = User::count(); // All users in users table are customers

        // Calculate percentage changes (mock logic for now or simple comparison if data exists)
        // For simplicity, we'll keep the percentage static or calculate if previous month data exists
        $previousMonthRevenue = Order::whereIn('status', ['delivered', 'shipped', 'processing'])
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->sum('total');
        
        $revenueGrowth = $previousMonthRevenue > 0 
            ? (($totalRevenue - $previousMonthRevenue) / $previousMonthRevenue) * 100 
            : 0;

        // 2. Recent Orders
        $recentOrders = Order::with('user')
            ->latest()
            ->take(10)
            ->get();

        // 3. Top Products (by revenue)
        $topProducts = OrderItem::select('product_name', DB::raw('SUM(quantity) as total_sales'), DB::raw('SUM(total) as total_revenue'))
            ->groupBy('product_name')
            ->orderByDesc('total_revenue')
            ->take(5)
            ->get();

        // 4. Sales Chart (Monthly data for current year)
        $salesData = Order::select(
            DB::raw('SUM(total) as total'),
            DB::raw("DATE_FORMAT(created_at, '%m') as month_key")
        )
        ->whereYear('created_at', date('Y'))
        ->whereIn('status', ['delivered', 'shipped', 'processing'])
        ->groupBy('month_key')
        ->orderBy('month_key')
        ->pluck('total', 'month_key')
        ->toArray();

        $chartData = [];
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        
        foreach (range(1, 12) as $month) {
            $key = sprintf('%02d', $month);
            $chartData[] = $salesData[$key] ?? 0;
        }

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'totalProducts',
            'totalCustomers',
            'revenueGrowth',
            'recentOrders',
            'topProducts',
            'chartData',
            'months'
        ));
    }
}
