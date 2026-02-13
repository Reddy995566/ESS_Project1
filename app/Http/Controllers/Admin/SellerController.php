<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Seller;
use App\Models\Product;
use App\Mail\SellerApprovedNotification;
use Illuminate\Support\Facades\Mail;

class SellerController extends Controller
{
    public function index(Request $request)
    {
        $query = Seller::with('user')->withCount('products');
        
        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('business_name', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($q2) use ($request) {
                      $q2->where('email', 'like', '%' . $request->search . '%');
                  });
            });
        }
        
        $sellers = $query->latest()->paginate(20);
        
        return view('admin.sellers.index', compact('sellers'));
    }

    public function show($id)
    {
        $seller = Seller::with(['user', 'bankDetails', 'products', 'payouts'])->findOrFail($id);
        
        // Calculate stats
        $stats = [
            'total_products' => $seller->products()->count(),
            'approved_products' => $seller->products()->where('approval_status', 'approved')->count(),
            'pending_products' => $seller->products()->where('approval_status', 'pending')->count(),
            'total_sales' => $seller->getTotalSales(),
            'total_orders' => $seller->getTotalOrders(),
            'pending_payout' => $seller->getPendingPayouts(),
        ];
        
        return view('admin.sellers.show', compact('seller', 'stats'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,active,suspended,rejected',
            'rejection_reason' => 'required_if:status,rejected',
        ]);
        
        $seller = Seller::findOrFail($id);
        
        $seller->update([
            'status' => $request->status,
            'rejection_reason' => $request->rejection_reason,
        ]);
        
        // Send email notification when seller is approved
        if ($request->status === 'active') {
            try {
                Mail::to($seller->user->email)->send(new SellerApprovedNotification($seller));
            } catch (\Exception $e) {
                \Log::error('Failed to send seller approval email: ' . $e->getMessage());
            }
        }
        
        // Send email notification when seller is rejected
        if ($request->status === 'rejected') {
            try {
                Mail::to($seller->user->email)->send(new \App\Mail\SellerRejectedNotification($seller));
            } catch (\Exception $e) {
                \Log::error('Failed to send seller rejection email: ' . $e->getMessage());
            }
        }
        
        // Create notification for seller
        $seller->notifications()->create([
            'type' => 'status_updated',
            'title' => 'Account Status Updated',
            'message' => 'Your account status has been updated to: ' . ucfirst($request->status),
            'data' => json_encode(['status' => $request->status]),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Seller status updated successfully!' . 
                        ($request->status === 'active' ? ' Approval email sent to seller.' : '') .
                        ($request->status === 'rejected' ? ' Rejection email sent to seller.' : ''),
        ]);
    }

    public function approveProduct(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        
        $product->update([
            'approval_status' => 'approved',
            'approved_by' => auth()->guard('admin')->id(),
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);
        
        // Send notification using seller's notification system
        $product->seller->sendNotification(
            'product_approved',
            'Product Approved',
            'Your product "' . $product->product_name . '" has been approved and is now live!',
            json_encode(['product_id' => $product->id])
        );
        
        return response()->json([
            'success' => true,
            'message' => 'Product approved successfully!',
        ]);
    }

    public function rejectProduct(Request $request, $productId)
    {
        $request->validate([
            'rejection_reason' => 'required|string',
        ]);
        
        $product = Product::findOrFail($productId);
        
        $product->update([
            'approval_status' => 'rejected',
            'approved_by' => auth()->guard('admin')->id(),
            'approved_at' => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);
        
        // Send notification using seller's notification system
        $product->seller->sendNotification(
            'product_rejected',
            'Product Rejected',
            'Your product "' . $product->product_name . '" was rejected. Reason: ' . $request->rejection_reason,
            json_encode(['product_id' => $product->id, 'rejection_reason' => $request->rejection_reason])
        );
        
        return response()->json([
            'success' => true,
            'message' => 'Product rejected!',
        ]);
    }

    public function pendingProducts()
    {
        $products = Product::where('approval_status', 'pending')
            ->whereNotNull('seller_id')
            ->with(['seller.user', 'category', 'colors', 'sizes'])
            ->latest()
            ->paginate(20);
        
        return view('admin.sellers.pending-products', compact('products'));
    }

    public function rejectedProducts()
    {
        $products = Product::where('approval_status', 'rejected')
            ->whereNotNull('seller_id')
            ->with(['seller.user', 'category', 'colors', 'sizes'])
            ->latest()
            ->paginate(20);
        
        return view('admin.sellers.rejected-products', compact('products'));
    }

    public function approvedProducts()
    {
        $products = Product::where('approval_status', 'approved')
            ->whereNotNull('seller_id')
            ->with(['seller.user', 'category', 'colors', 'sizes'])
            ->latest()
            ->paginate(20);
        
        return view('admin.sellers.approved-products', compact('products'));
    }

    public function getSellerProducts($id)
    {
        try {
            $seller = Seller::findOrFail($id);
            
            $products = $seller->products()
                ->with(['category'])
                ->orderBy('created_at', 'desc')
                ->get();
            
            // Calculate stats
            $stats = [
                'total' => $products->count(),
                'approved' => $products->where('approval_status', 'approved')->count(),
                'pending' => $products->where('approval_status', 'pending')->count(),
                'rejected' => $products->where('approval_status', 'rejected')->count(),
            ];
            
            // Format products for frontend
            $formattedProducts = $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'price' => $product->price,
                    'stock' => $product->stock,
                    'status' => $product->status,
                    'approval_status' => $product->approval_status,
                    'image' => $product->image,
                    'category' => $product->category ? [
                        'id' => $product->category->id,
                        'name' => $product->category->name
                    ] : null,
                    'created_at' => $product->created_at->format('M d, Y'),
                ];
            });
            
            return response()->json([
                'success' => true,
                'products' => $formattedProducts,
                'stats' => $stats
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch seller products: ' . $e->getMessage()
            ], 500);
        }
    }
}
