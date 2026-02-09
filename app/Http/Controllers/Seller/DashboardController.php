<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $seller = Auth::guard('seller')->user();

        // Stats
        $stats = [
            'total_sales' => $seller->orderItems()
                ->whereMonth('created_at', now()->month)
                ->sum('seller_amount'),
            'total_orders' => $seller->orderItems()
                ->whereMonth('created_at', now()->month)
                ->distinct('order_id')
                ->count('order_id'),
            'active_products' => $seller->products()
                ->where('status', 'active')
                ->where('approval_status', 'approved')
                ->count(),
            'pending_payouts' => $seller->payouts()
                ->where('status', 'pending')
                ->sum('net_amount'),
        ];

        // Recent orders
        $recentOrders = $seller->orderItems()
            ->with(['order', 'product'])
            ->latest()
            ->limit(10)
            ->get()
            ->groupBy('order_id')
            ->map(function ($items) {
                $order = $items->first()->order;
                return [
                    'order' => $order,
                    'items' => $items,
                    'total' => $items->sum('seller_amount'),
                ];
            })
            ->values();

        // Top products
        $topProducts = $seller->products()
            ->withCount(['orderItems as units_sold' => function ($query) {
                $query->select(\DB::raw('SUM(quantity)'));
            }])
            ->withSum('orderItems as revenue', 'seller_amount')
            ->orderBy('units_sold', 'desc')
            ->limit(5)
            ->get();

        return view('seller.dashboard', compact('seller', 'stats', 'recentOrders', 'topProducts'));
    }

    public function stats(Request $request)
    {
        $seller = Auth::guard('seller')->user();

        $stats = [
            'total_sales' => $seller->orderItems()
                ->whereMonth('created_at', now()->month)
                ->sum('seller_amount'),
            'total_orders' => $seller->orderItems()
                ->whereMonth('created_at', now()->month)
                ->distinct('order_id')
                ->count('order_id'),
            'active_products' => $seller->products()
                ->where('status', 'active')
                ->where('approval_status', 'approved')
                ->count(),
            'pending_payouts' => $seller->payouts()
                ->where('status', 'pending')
                ->sum('net_amount'),
        ];

        return response()->json($stats);
    }

    public function salesChart(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        $days = $request->get('days', 30);

        $sales = $seller->orderItems()
            ->where('created_at', '>=', now()->subDays($days))
            ->selectRaw('DATE(created_at) as date, SUM(seller_amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = [];
        $data = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('M d');
            
            $sale = $sales->firstWhere('date', $date);
            $data[] = $sale ? (float) $sale->total : 0;
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }
}
