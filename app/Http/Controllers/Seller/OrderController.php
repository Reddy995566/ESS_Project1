<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        
        // Get orders that have items from this seller
        $query = Order::whereHas('items', function($q) use ($seller) {
            $q->where('seller_id', $seller->id);
        })->with(['user', 'items' => function($q) use ($seller) {
            $q->where('seller_id', $seller->id);
        }]);
        
        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Search by order number or customer
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', '%' . $search . '%')
                  ->orWhere('first_name', 'like', '%' . $search . '%')
                  ->orWhere('last_name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', '%' . $search . '%')
                               ->orWhere('email', 'like', '%' . $search . '%');
                  });
            });
        }
        
        $orders = $query->latest()->paginate(25);
        
        // Calculate statistics for all seller orders (not just current page)
        $allSellerOrders = Order::whereHas('items', function($q) use ($seller) {
            $q->where('seller_id', $seller->id);
        })->with(['items' => function($q) use ($seller) {
            $q->where('seller_id', $seller->id);
        }])->get();
        
        $totalOrders = $allSellerOrders->count();
        $pendingOrders = $allSellerOrders->where('status', 'pending')->count();
        $completedOrders = $allSellerOrders->where('status', 'delivered')->count();
        $totalEarnings = $allSellerOrders->sum(function($order) {
            return $order->items->sum('seller_amount');
        });
        
        return view('seller.orders.index', compact(
            'orders', 
            'totalOrders', 
            'pendingOrders', 
            'completedOrders', 
            'totalEarnings'
        ));
    }

    public function show($id)
    {
        $seller = Auth::guard('seller')->user();
        
        $order = Order::whereHas('items', function($q) use ($seller) {
            $q->where('seller_id', $seller->id);
        })->with(['user', 'items' => function($q) use ($seller) {
            $q->where('seller_id', $seller->id)->with('product');
        }])->findOrFail($id);
        
        return view('seller.orders.show', compact('order'));
    }
}
