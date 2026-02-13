<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use App\Models\ReviewImage;
use App\Services\ImageKitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    protected $imageKitService;

    public function __construct(ImageKitService $imageKitService)
    {
        $this->imageKitService = $imageKitService;
    }

    /**
     * Store a new review
     */
    public function store(Request $request)
    {
        try {
            // Check if user is logged in
            if (!auth()->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be logged in to write a review.'
                ], 401);
            }

            // Check if user has purchased this product
            $hasPurchased = $this->checkVerifiedPurchase($request->product_id, auth()->user()->email);
            
            if (!$hasPurchased) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only verified buyers can write reviews. You must purchase and receive this product first.'
                ], 403);
            }

            // Validation
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|exists:products,id',
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'rating' => 'required|integer|min:1|max:5',
                'title' => 'nullable|string|max:255',
                'comment' => 'required|string',
                'images' => 'nullable|array|max:5',
                'images.*' => 'nullable|string', // ImageKit URLs
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            // Create review
            $review = Review::create([
                'product_id' => $request->product_id,
                'user_id' => auth()->id(),
                'name' => $request->name,
                'email' => $request->email,
                'rating' => $request->rating,
                'title' => $request->title,
                'comment' => $request->comment,
                'is_verified_purchase' => true, // Already verified above
                'is_approved' => false, // Requires admin approval
            ]);

            // Store review images if provided
            if ($request->has('images') && is_array($request->images)) {
                foreach ($request->images as $imageData) {
                    if (!empty($imageData)) {
                        $imageInfo = json_decode($imageData, true);

                        ReviewImage::create([
                            'review_id' => $review->id,
                            'image_url' => $imageInfo['url'] ?? $imageData,
                            'image_path' => $imageInfo['file_path'] ?? null,
                            'file_id' => $imageInfo['file_id'] ?? null,
                            'thumbnail_url' => $imageInfo['thumbnail_url'] ?? null,
                            'width' => $imageInfo['width'] ?? null,
                            'height' => $imageInfo['height'] ?? null,
                            'size' => $imageInfo['size'] ?? null,
                        ]);
                    }
                }
            }

            // Send notification to seller if product has a seller
            $product = Product::find($request->product_id);
            if ($product && $product->seller) {
                $product->seller->sendNotification(
                    'new_review',
                    'New Product Review',
                    'You received a new ' . $request->rating . '-star review for "' . $product->product_name . '" from ' . $request->name,
                    json_encode([
                        'product_id' => $product->id,
                        'review_id' => $review->id,
                        'rating' => $request->rating,
                        'reviewer_name' => $request->name
                    ])
                );
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Thank you for your review! It will be published after approval.',
                'review' => $review->load('images')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Review submission error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to submit review. Please try again.'
            ], 500);
        }
    }

    /**
     * Get approved reviews for a product
     */
    public function getProductReviews(Request $request, $productId)
    {
        try {
            $perPage = $request->get('per_page', 10);
            $sortBy = $request->get('sort_by', 'recent'); // recent, helpful, rating_high, rating_low

            $query = Review::where('product_id', $productId)
                ->where('is_approved', true)
                ->with(['images', 'user']);

            // Apply sorting
            switch ($sortBy) {
                case 'helpful':
                    $query->orderBy('helpful_count', 'desc');
                    break;
                case 'rating_high':
                    $query->orderBy('rating', 'desc');
                    break;
                case 'rating_low':
                    $query->orderBy('rating', 'asc');
                    break;
                case 'recent':
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }

            $reviews = $query->paginate($perPage);

            // Calculate rating statistics
            $product = Product::findOrFail($productId);
            $stats = $this->getReviewStats($productId);

            return response()->json([
                'success' => true,
                'reviews' => $reviews,
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Get reviews error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to load reviews'
            ], 500);
        }
    }

    /**
     * Upload review image to ImageKit
     */
    public function uploadImage(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'image' => 'required|image|mimes:jpeg,jpg,png,webp|max:5120', // 5MB max
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $file = $request->file('image');
            $folder = 'reviews/' . date('Y/m');

            // Upload to ImageKit
            $result = $this->imageKitService->uploadProductImage($file, $folder);

            if ($result && $result['success']) {
                return response()->json([
                    'success' => true,
                    'image' => $result
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to upload image'
            ], 500);

        } catch (\Exception $e) {
            Log::error('Review image upload error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to upload image'
            ], 500);
        }
    }

    /**
     * Check if purchase is verified
     */
    private function checkVerifiedPurchase($productId, $email)
    {
        // Check if user with this email has purchased and received this product
        return DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.email', $email)
            ->where('order_items.product_id', $productId)
            ->where('orders.status', 'delivered') // Must be delivered
            ->exists();
    }

    /**
     * Get review statistics for a product
     */
    private function getReviewStats($productId)
    {
        $reviews = Review::where('product_id', $productId)
            ->where('is_approved', true)
            ->get();

        $totalReviews = $reviews->count();
        $averageRating = $totalReviews > 0 ? round($reviews->avg('rating'), 1) : 0;

        // Rating breakdown
        $ratingBreakdown = [];
        for ($i = 5; $i >= 1; $i--) {
            $count = $reviews->where('rating', $i)->count();
            $percentage = $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0;

            $ratingBreakdown[$i] = [
                'count' => $count,
                'percentage' => $percentage
            ];
        }

        return [
            'total_reviews' => $totalReviews,
            'average_rating' => $averageRating,
            'rating_breakdown' => $ratingBreakdown,
            'verified_purchases' => $reviews->where('is_verified_purchase', true)->count()
        ];
    }
}
