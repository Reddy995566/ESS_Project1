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

    public function cancel(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        // Authorization Check
        if(Auth::id() != $order->user_id) {
            abort(403); 
        }

        // Check if order can be cancelled
        if (!in_array($order->status, ['pending', 'processing'])) {
            return response()->json([
                'success' => false,
                'message' => 'Order cannot be cancelled at this stage.'
            ], 400);
        }

        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        $order->status = 'cancelled';
        $order->cancellation_reason = $request->reason;
        $order->cancelled_at = now();
        $order->cancelled_by = 'customer';
        $order->save();

        // Restore product stock
        foreach ($order->items as $item) {
            if ($item->variant_id) {
                $variant = \App\Models\ProductVariant::find($item->variant_id);
                if ($variant) {
                    $variant->increment('stock', $item->quantity);
                }
            }
        }

        // Send cancellation email to customer
        try {
            \Mail::to($order->email)->send(new \App\Mail\OrderCancelledMail($order));
        } catch (\Exception $e) {
            \Log::error('Failed to send order cancellation email: ' . $e->getMessage());
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Order cancelled successfully.'
            ]);
        }

        return redirect()->route('user.orders.show', $id)->with('success', 'Order cancelled successfully.');
    }
}
