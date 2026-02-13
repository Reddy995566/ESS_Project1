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
        
        // Get seller settings for shiprocket
        $sellerSettings = $seller->settings()->pluck('value', 'key')->toArray();
        $shiprocketEnabled = ($sellerSettings['shiprocket_enabled'] ?? '0') === '1';
        $hasCredentials = !empty($sellerSettings['shiprocket_email']) && !empty($sellerSettings['shiprocket_password']);
        
        return view('seller.orders.show', compact('order', 'shiprocketEnabled', 'hasCredentials'));
    }
    
    public function shipToShiprocket(Request $request, $id, \App\Services\ShiprocketService $shiprocket)
    {
        try {
            $seller = Auth::guard('seller')->user();
            
            // Check if seller has shiprocket enabled
            $sellerSettings = $seller->settings()->pluck('value', 'key')->toArray();
            $shiprocketEnabled = ($sellerSettings['shiprocket_enabled'] ?? '0') === '1';
            
            if (!$shiprocketEnabled) {
                return response()->json([
                    'success' => false,
                    'message' => 'Shiprocket is not enabled. Please configure it in settings first.'
                ], 400);
            }
            
            // Check if seller has provided API credentials
            $requiredFields = ['shiprocket_email', 'shiprocket_password'];
            $missingFields = [];
            
            foreach ($requiredFields as $field) {
                if (empty($sellerSettings[$field])) {
                    $missingFields[] = $field;
                }
            }
            
            if (!empty($missingFields)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please configure your Shiprocket API credentials in Settings first.'
                ], 400);
            }
            
            $order = Order::whereHas('items', function($q) use ($seller) {
                $q->where('seller_id', $seller->id);
            })->with(['items' => function($q) use ($seller) {
                $q->where('seller_id', $seller->id)->with(['variant', 'product']);
            }])->findOrFail($id);
            
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
            
            $response = $shiprocket->createSellerOrder($order, $seller, $sellerSettings);

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
            $seller = Auth::guard('seller')->user();
            
            $order = Order::whereHas('items', function($q) use ($seller) {
                $q->where('seller_id', $seller->id);
            })->findOrFail($id);
            
            // Get seller's shiprocket credentials
            $sellerSettings = $seller->settings()->pluck('value', 'key')->toArray();
            
            $couriers = $shiprocket->getAvailableCouriers($order, $sellerSettings);
            
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

            $seller = Auth::guard('seller')->user();
            
            $order = Order::whereHas('items', function($q) use ($seller) {
                $q->where('seller_id', $seller->id);
            })->findOrFail($id);
            
            // Get seller's shiprocket credentials
            $sellerSettings = $seller->settings()->pluck('value', 'key')->toArray();
            
            $response = $shiprocket->generateAwb($request->shipment_id, $request->courier_id, $sellerSettings);

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
        $seller = Auth::guard('seller')->user();
        
        $order = Order::whereHas('items', function($q) use ($seller) {
            $q->where('seller_id', $seller->id);
        })->findOrFail($id);

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
        $order->cancelled_by = 'seller';
        $order->save();

        // Restore product stock for seller's items only
        foreach ($order->items()->where('seller_id', $seller->id)->get() as $item) {
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
        $seller = Auth::guard('seller')->user();
        
        // Get cancelled orders that have items from this seller
        $query = Order::whereHas('items', function($q) use ($seller) {
            $q->where('seller_id', $seller->id);
        })->with(['user', 'items' => function($q) use ($seller) {
            $q->where('seller_id', $seller->id);
        }])->where('status', 'cancelled');
        
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
        
        return view('seller.orders.cancelled', compact('orders'));
    }
}
