<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VideoReel;
use App\Models\Product;
use App\Services\ImageKitService;
use Illuminate\Http\Request;

class VideoReelsController extends Controller
{
    public function index(Request $request)
    {
        $query = VideoReel::with('product');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('product', function($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        // Sorting
        $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');

        // Pagination
        $perPage = $request->get('per_page', 25);
        if ($perPage == -1) {
            $videoReels = $query->get();
            $videoReels = new \Illuminate\Pagination\LengthAwarePaginator(
                $videoReels, $videoReels->count(), $videoReels->count(), 1
            );
        } else {
            $videoReels = $query->paginate($perPage);
        }

        // Stats
        $totalReels = VideoReel::count();
        $activeReels = VideoReel::where('is_active', true)->count();
        $totalViews = VideoReel::sum('views_count');

        // Products for dropdown
        $products = Product::where('status', 'active')->orderBy('name')->get();

        return view('admin.video-reels.index', compact(
            'videoReels',
            'totalReels',
            'activeReels',
            'totalViews',
            'products'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'video_url' => 'required|string',
            'title' => 'nullable|string|max:255',
            'badge' => 'nullable|string|max:50',
            'badge_color' => 'nullable|string|max:20',
            'views_count' => 'nullable|integer|min:0',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        VideoReel::create([
            'product_id' => $request->product_id,
            'video_url' => $request->video_url,
            'video_file_id' => $request->video_file_id,
            'title' => $request->title,
            'badge' => $request->badge,
            'badge_color' => $request->badge_color ?? '#3D5A3C',
            'views_count' => $request->views_count ?? 0,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->boolean('is_active', true),
            'autoplay' => $request->boolean('autoplay', false),
        ]);

        return redirect()->route('admin.video-reels.index')->with('success', 'Video Reel created successfully!');
    }

    public function update(Request $request, VideoReel $videoReel)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'video_url' => 'required|string',
            'title' => 'nullable|string|max:255',
            'badge' => 'nullable|string|max:50',
            'badge_color' => 'nullable|string|max:20',
            'views_count' => 'nullable|integer|min:0',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $videoReel->update([
            'product_id' => $request->product_id,
            'video_url' => $request->video_url,
            'video_file_id' => $request->video_file_id ?? $videoReel->video_file_id,
            'title' => $request->title,
            'badge' => $request->badge,
            'badge_color' => $request->badge_color ?? '#3D5A3C',
            'views_count' => $request->views_count ?? 0,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->boolean('is_active', true),
            'autoplay' => $request->boolean('autoplay', false),
        ]);

        return redirect()->route('admin.video-reels.index')->with('success', 'Video Reel updated successfully!');
    }

    public function destroy(VideoReel $videoReel)
    {
        $videoReel->delete();
        return response()->json(['success' => true, 'message' => 'Video Reel deleted successfully!']);
    }

    public function toggle(Request $request, VideoReel $videoReel)
    {
        $field = $request->field;
        $value = $request->value;

        if (in_array($field, ['is_active', 'autoplay'])) {
            $videoReel->update([$field => $value]);
            return response()->json(['success' => true, 'message' => 'Updated successfully!']);
        }

        return response()->json(['success' => false, 'message' => 'Invalid field']);
    }

    public function bulkAction(Request $request)
    {
        $action = $request->action;
        $ids = $request->ids;

        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected']);
        }

        switch ($action) {
            case 'activate':
                VideoReel::whereIn('id', $ids)->update(['is_active' => true]);
                return response()->json(['success' => true, 'message' => count($ids) . ' reels activated']);
            
            case 'deactivate':
                VideoReel::whereIn('id', $ids)->update(['is_active' => false]);
                return response()->json(['success' => true, 'message' => count($ids) . ' reels deactivated']);
            
            case 'delete':
                VideoReel::whereIn('id', $ids)->delete();
                return response()->json(['success' => true, 'message' => count($ids) . ' reels deleted']);
            
            default:
                return response()->json(['success' => false, 'message' => 'Invalid action']);
        }
    }

    public function uploadVideo(Request $request, ImageKitService $imageKit)
    {
        try {
            $request->validate([
                'video' => 'required|mimes:mp4,webm,mov,avi|max:51200', // 50MB max
            ]);

            $result = $imageKit->uploadVideo($request->file('video'), 'video-reels');

            if ($result && $result['success']) {
                return response()->json([
                    'success' => true,
                    'url' => $result['url'],
                    'file_id' => $result['file_id'],
                    'size' => $result['size'],
                ]);
            }

            return response()->json(['success' => false, 'message' => 'Failed to upload video'], 500);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
