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
        $order->status = $request->status;
        
        // Update timestamps based on status
        if ($request->status === 'shipped' && !$order->shipped_at) {
            $order->shipped_at = now();
        }
        if ($request->status === 'delivered' && !$order->delivered_at) {
            $order->delivered_at = now();
        }
        
        $order->save();

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
}
