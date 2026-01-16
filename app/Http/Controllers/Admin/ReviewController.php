<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    /**
     * Display a listing of reviews
     */
    public function index(Request $request)
    {
        $query = Review::with(['product', 'user', 'images']);

        // Filter by status
        if ($request->has('status')) {
            if ($request->status === 'pending') {
                $query->where('is_approved', false);
            } elseif ($request->status === 'approved') {
                $query->where('is_approved', true);
            }
        }

        // Filter by rating
        if ($request->has('rating') && $request->rating != '') {
            $query->where('rating', $request->rating);
        }

        // Search by product name or reviewer name
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('comment', 'like', "%{$search}%")
                    ->orWhereHas('product', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $reviews = $query->paginate(20);

        // Get statistics
        $stats = [
            'total' => Review::count(),
            'pending' => Review::where('is_approved', false)->count(),
            'approved' => Review::where('is_approved', true)->count(),
            'average_rating' => round(Review::where('is_approved', true)->avg('rating'), 1),
        ];

        return view('admin.reviews.index', compact('reviews', 'stats'));
    }

    /**
     * Display the specified review
     */
    public function show($id)
    {
        $review = Review::with(['product', 'user', 'images'])->findOrFail($id);

        return view('admin.reviews.show', compact('review'));
    }

    /**
     * Approve a review
     */
    public function approve($id)
    {
        try {
            $review = Review::findOrFail($id);
            $review->is_approved = true;
            $review->save();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Review approved successfully'
                ]);
            }

            return redirect()->back()->with('success', 'Review approved successfully');

        } catch (\Exception $e) {
            Log::error('Review approval error: ' . $e->getMessage());

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to approve review'
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to approve review');
        }
    }

    /**
     * Reject/Delete a review
     */
    public function reject($id)
    {
        try {
            $review = Review::findOrFail($id);

            // Delete associated images from ImageKit if needed
            // (Optional: implement ImageKit deletion)

            $review->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Review deleted successfully'
                ]);
            }

            return redirect()->route('admin.reviews.index')->with('success', 'Review deleted successfully');

        } catch (\Exception $e) {
            Log::error('Review deletion error: ' . $e->getMessage());

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete review'
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to delete review');
        }
    }

    /**
     * Toggle approval status
     */
    public function toggleApproval($id)
    {
        try {
            $review = Review::findOrFail($id);
            $review->is_approved = !$review->is_approved;
            $review->save();

            return response()->json([
                'success' => true,
                'is_approved' => $review->is_approved,
                'message' => $review->is_approved ? 'Review approved' : 'Review unapproved'
            ]);

        } catch (\Exception $e) {
            Log::error('Review toggle error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update review'
            ], 500);
        }
    }

    /**
     * Bulk approve reviews
     */
    public function bulkApprove(Request $request)
    {
        try {
            $ids = $request->input('ids', []);

            Review::whereIn('id', $ids)->update(['is_approved' => true]);

            return response()->json([
                'success' => true,
                'message' => count($ids) . ' reviews approved successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Bulk approve error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to approve reviews'
            ], 500);
        }
    }

    /**
     * Bulk delete reviews
     */
    public function bulkDelete(Request $request)
    {
        try {
            $ids = $request->input('ids', []);

            Review::whereIn('id', $ids)->delete();

            return response()->json([
                'success' => true,
                'message' => count($ids) . ' reviews deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Bulk delete error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete reviews'
            ], 500);
        }
    }
}
