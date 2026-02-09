<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductReturn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    public function index(Request $request)
    {
        $sellerId = Auth::guard('seller')->id();
        
        $query = ProductReturn::with(['user', 'order', 'orderItem.product'])
            ->where('seller_id', $sellerId)
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('return_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('order', function($orderQuery) use ($search) {
                      $orderQuery->where('order_number', 'like', "%{$search}%");
                  });
            });
        }

        $returns = $query->paginate(20);

        // Statistics
        $stats = [
            'total' => ProductReturn::where('seller_id', $sellerId)->count(),
            'pending' => ProductReturn::where('seller_id', $sellerId)->where('status', 'pending')->count(),
            'approved' => ProductReturn::where('seller_id', $sellerId)->where('status', 'approved')->count(),
            'refunded' => ProductReturn::where('seller_id', $sellerId)->where('status', 'refunded')->count(),
        ];

        return view('seller.returns.index', compact('returns', 'stats'));
    }

    public function show($id)
    {
        $sellerId = Auth::guard('seller')->id();
        
        $return = ProductReturn::with(['user', 'order', 'orderItem.product', 'processedByAdmin'])
            ->where('seller_id', $sellerId)
            ->findOrFail($id);

        return view('seller.returns.show', compact('return'));
    }

    public function addNotes(Request $request, $id)
    {
        $request->validate([
            'seller_notes' => 'required|string|max:1000'
        ]);

        $sellerId = Auth::guard('seller')->id();
        
        $return = ProductReturn::where('seller_id', $sellerId)->findOrFail($id);

        $return->update([
            'seller_notes' => $request->seller_notes,
            'processed_by_seller' => $sellerId
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notes added successfully.'
        ]);
    }

    public function acknowledge(Request $request, $id)
    {
        $sellerId = Auth::guard('seller')->id();
        
        $return = ProductReturn::where('seller_id', $sellerId)
            ->where('status', 'pending')
            ->findOrFail($id);

        DB::beginTransaction();
        try {
            $return->update([
                'processed_by_seller' => $sellerId,
                'seller_notes' => $request->seller_notes
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Return acknowledged successfully.'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to acknowledge return: ' . $e->getMessage()
            ], 500);
        }
    }
}