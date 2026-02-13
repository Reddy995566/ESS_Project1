<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items'])->latest();

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->has('payment_method') && $request->payment_method != '') {
            $query->where('payment_method', $request->payment_method);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(25);
        
        // Statistics
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'delivered')->count();
        $totalRevenue = Order::whereIn('status', ['delivered', 'shipped', 'processing'])->sum('total');
        
        return view('admin.orders.index', compact('orders', 'totalOrders', 'pendingOrders', 'completedOrders', 'totalRevenue'));
    }

    public function show($id)
    {
        $order = Order::with(['user', 'items.product', 'items.variant'])->findOrFail($id);
        
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $order = Order::findOrFail($id);
        $oldStatus = $order->status;
        $order->status = $request->status;
        
        // Update timestamps based on status
        if ($request->status === 'shipped' && !$order->shipped_at) {
            $order->shipped_at = now();
        }
        if ($request->status === 'delivered' && !$order->delivered_at) {
            $order->delivered_at = now();
            
            // Auto-mark COD orders as paid when delivered
            if ($order->payment_method === 'cod' && $order->payment_status === 'pending') {
                $order->payment_status = 'paid';
                $order->paid_at = now();
            }
        }
        
        // Restore stock if order is being cancelled
        if ($request->status === 'cancelled' && $oldStatus !== 'cancelled') {
            foreach ($order->items as $item) {
                if ($item->variant_id) {
                    $variant = \App\Models\ProductVariant::find($item->variant_id);
                    if ($variant) {
                        $variant->increment('stock', $item->quantity);
                    }
                }
            }
            $order->cancelled_at = now();
            $order->cancelled_by = 'admin';
        }
        
        $order->save();

        // Send email notification if status changed
        if ($oldStatus !== $request->status && $request->status !== 'cancelled') {
            try {
                \Mail::to($order->email)->send(new \App\Mail\OrderStatusUpdatedMail($order, $request->status));
            } catch (\Exception $e) {
                \Log::error('Failed to send order status update email: ' . $e->getMessage());
            }
        } elseif ($request->status === 'cancelled' && $oldStatus !== 'cancelled') {
            // Send cancellation email
            try {
                \Mail::to($order->email)->send(new \App\Mail\OrderCancelledMail($order));
            } catch (\Exception $e) {
                \Log::error('Failed to send order cancellation email: ' . $e->getMessage());
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully'
            ]);
        }

        return back()->with('success', 'Order status updated successfully');
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded'
        ]);

        $order = Order::findOrFail($id);
        $order->payment_status = $request->payment_status;
        
        if ($request->payment_status === 'paid' && !$order->paid_at) {
            $order->paid_at = now();
        }
        
        $order->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Payment status updated successfully'
            ]);
        }

        return back()->with('success', 'Payment status updated successfully');
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Order deleted successfully'
            ]);
        }

        return back()->with('success', 'Order deleted successfully');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:processing,shipped,delivered,cancelled',
            'ids' => 'required|array',
            'ids.*' => 'exists:orders,id'
        ]);

        $action = $request->action;
        $count = Order::whereIn('id', $request->ids)->update(['status' => $action]);
        
        $message = "Successfully updated {$count} order(s) to {$action} status";

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    public function export(Request $request)
    {
        $orders = Order::with(['user', 'items'])->get();
        
        $filename = 'orders_' . date('Y-m-d_His') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Headers
        fputcsv($output, [
            'Order Number', 'Customer Name', 'Email', 'Phone', 
            'Total', 'Payment Method', 'Payment Status', 
            'Order Status', 'Order Date'
        ]);
        
        // Data
        foreach ($orders as $order) {
            fputcsv($output, [
                $order->order_number,
                $order->first_name . ' ' . $order->last_name,
                $order->email,
                $order->phone,
                $order->total,
                $order->payment_method,
                $order->payment_status,
                $order->status,
                $order->created_at->format('Y-m-d H:i:s')
            ]);
        }
        
        fclose($output);
        exit;
    }
    public function shipToShiprocket(Request $request, $id, \App\Services\ShiprocketService $shiprocket)
    {
        try {
            $order = Order::with(['items', 'items.variant', 'items.product'])->findOrFail($id);
            
            if ($order->shiprocket_order_id) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Order is already pushed to Shiprocket',
                        'order_id' => $order->shiprocket_order_id
                    ]);
                }
                return back()->with('error', 'Order is already pushed to Shiprocket');
            }

            $response = $shiprocket->createOrder($order);

            // Update order with Shiprocket details
            $order->shiprocket_order_id = $response['order_id'];
            $order->shiprocket_shipment_id = $response['shipment_id'];
            $order->awb_code = $response['awb_code'] ?? null;
            $order->save();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order successfully pushed to Shiprocket!',
                    'order_id' => $response['order_id'],
                    'shipment_id' => $response['shipment_id']
                ]);
            }

            return back()->with('success', 'Order successfully pushed to Shiprocket! Order ID: ' . $response['order_id']);

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Shiprocket Error: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Shiprocket Error: ' . $e->getMessage());
        }
    }

    public function getCouriers($id, \App\Services\ShiprocketService $shiprocket)
    {
        try {
            $order = Order::findOrFail($id);
            $couriers = $shiprocket->getAvailableCouriers($order);
            
            return response()->json([
                'success' => true,
                'couriers' => $couriers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function generateAwb(Request $request, $id, \App\Services\ShiprocketService $shiprocket)
    {
        try {
            $request->validate([
                'courier_id' => 'required',
                'shipment_id' => 'required'
            ]);

            $order = Order::findOrFail($id);
            $response = $shiprocket->generateAwb($request->shipment_id, $request->courier_id);

            // Update with AWB
            $order->awb_code = $response['awb_code'] ?? $response['response']['data']['awb_code'] ?? null;
            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'AWB Generated Successfully!',
                'awb_code' => $order->awb_code
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function cancel(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        // Check if order can be cancelled
        if (!in_array($order->status, ['pending', 'processing', 'shipped'])) {
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
        $order->cancelled_by = 'admin';
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

        return back()->with('success', 'Order cancelled successfully.');
    }

    public function cancelled(Request $request)
    {
        $query = Order::with(['user', 'items'])
            ->where('status', 'cancelled')
            ->latest();

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(25);
        
        return view('admin.orders.cancelled', compact('orders'));
    }
}
