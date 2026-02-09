<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class OrdersController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
                      ->with(['items.product']) // Eager load items and products
                      ->orderBy('created_at', 'desc')
                      ->get();

        return view('website.user.orders', compact('orders'));
    }
    public function show($id)
    {
        $order = Order::with(['items.product', 'items.returns'])->findOrFail($id);

        // Authorization Check
        if(Auth::id() != $order->user_id) {
            abort(403); 
        }

        return view('website.user.order_details', compact('order'));
    }
}
