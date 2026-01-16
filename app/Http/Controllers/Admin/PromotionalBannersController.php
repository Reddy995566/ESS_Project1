<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromotionalBanner;
use App\Services\ImageKitService;
use Illuminate\Http\Request;

class PromotionalBannersController extends Controller
{
    protected $imageKitService;

    public function __construct(ImageKitService $imageKitService)
    {
        $this->imageKitService = $imageKitService;
    }

    public function index()
    {
        $banners = PromotionalBanner::orderBy('position')
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalBanners = PromotionalBanner::count();
        $activeBanners = PromotionalBanner::where('is_active', true)->count();
        $scheduledBanners = PromotionalBanner::where('is_active', true)
            ->where('start_date', '>', now())
            ->count();

        $positions = PromotionalBanner::POSITIONS;

        return view('admin.promotional-banners.index', compact(
            'banners',
            'totalBanners',
            'activeBanners',
            'scheduledBanners',
            'positions'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:500',
            'desktop_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'mobile_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'position' => 'required|string|in:' . implode(',', array_keys(PromotionalBanner::POSITIONS)),
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $banner = new PromotionalBanner();
        $banner->title = $validated['title'] ?? null;
        $banner->link = $validated['link'] ?? null;
        $banner->position = $validated['position'];
        $banner->sort_order = $validated['sort_order'] ?? 0;
        $banner->is_active = $request->has('is_active');
        $banner->start_date = $validated['start_date'] ?? null;
        $banner->end_date = $validated['end_date'] ?? null;

        // Upload desktop image to ImageKit
        if ($request->hasFile('desktop_image')) {
            $result = $this->imageKitService->uploadCategoryImage(
                $request->file('desktop_image'),
                'banners/desktop'
            );

            if ($result) {
                $banner->desktop_image = $result['name'] ?? null;
                $banner->desktop_imagekit_file_id = $result['file_id'] ?? null;
                $banner->desktop_imagekit_url = $result['url'] ?? null;
                $banner->desktop_imagekit_file_path = $result['filePath'] ?? null;
            }
        }

        // Upload mobile image to ImageKit
        if ($request->hasFile('mobile_image')) {
            $result = $this->imageKitService->uploadCategoryImage(
                $request->file('mobile_image'),
                'banners/mobile'
            );

            if ($result) {
                $banner->mobile_image = $result['name'] ?? null;
                $banner->mobile_imagekit_file_id = $result['file_id'] ?? null;
                $banner->mobile_imagekit_url = $result['url'] ?? null;
                $banner->mobile_imagekit_file_path = $result['filePath'] ?? null;
            }
        }

        $banner->save();

        return redirect()->route('admin.promotional-banners.index')
            ->with('success', 'Promotional banner created successfully!');
    }

    public function update(Request $request, PromotionalBanner $promotionalBanner)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:500',
            'desktop_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'mobile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'position' => 'required|string|in:' . implode(',', array_keys(PromotionalBanner::POSITIONS)),
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $promotionalBanner->title = $validated['title'] ?? null;
        $promotionalBanner->link = $validated['link'] ?? null;
        $promotionalBanner->position = $validated['position'];
        $promotionalBanner->sort_order = $validated['sort_order'] ?? 0;
        $promotionalBanner->is_active = $request->has('is_active');
        $promotionalBanner->start_date = $validated['start_date'] ?? null;
        $promotionalBanner->end_date = $validated['end_date'] ?? null;

        // Upload new desktop image if provided
        if ($request->hasFile('desktop_image')) {
            // Delete old image from ImageKit
            if ($promotionalBanner->desktop_imagekit_file_id) {
                $this->imageKitService->deleteImage($promotionalBanner->desktop_imagekit_file_id);
            }

            $result = $this->imageKitService->uploadCategoryImage(
                $request->file('desktop_image'),
                'banners/desktop'
            );

            if ($result) {
                $promotionalBanner->desktop_image = $result['name'] ?? null;
                $promotionalBanner->desktop_imagekit_file_id = $result['file_id'] ?? null;
                $promotionalBanner->desktop_imagekit_url = $result['url'] ?? null;
                $promotionalBanner->desktop_imagekit_file_path = $result['filePath'] ?? null;
            }
        }

        // Upload new mobile image if provided
        if ($request->hasFile('mobile_image')) {
            // Delete old image from ImageKit
            if ($promotionalBanner->mobile_imagekit_file_id) {
                $this->imageKitService->deleteImage($promotionalBanner->mobile_imagekit_file_id);
            }

            $result = $this->imageKitService->uploadCategoryImage(
                $request->file('mobile_image'),
                'banners/mobile'
            );

            if ($result) {
                $promotionalBanner->mobile_image = $result['name'] ?? null;
                $promotionalBanner->mobile_imagekit_file_id = $result['file_id'] ?? null;
                $promotionalBanner->mobile_imagekit_url = $result['url'] ?? null;
                $promotionalBanner->mobile_imagekit_file_path = $result['filePath'] ?? null;
            }
        }

        $promotionalBanner->save();

        return redirect()->route('admin.promotional-banners.index')
            ->with('success', 'Promotional banner updated successfully!');
    }

    public function destroy(PromotionalBanner $promotionalBanner)
    {
        // Delete images from ImageKit
        if ($promotionalBanner->desktop_imagekit_file_id) {
            $this->imageKitService->deleteImage($promotionalBanner->desktop_imagekit_file_id);
        }
        if ($promotionalBanner->mobile_imagekit_file_id) {
            $this->imageKitService->deleteImage($promotionalBanner->mobile_imagekit_file_id);
        }

        $promotionalBanner->delete();

        return redirect()->route('admin.promotional-banners.index')
            ->with('success', 'Promotional banner deleted successfully!');
    }

    public function toggleStatus(PromotionalBanner $promotionalBanner)
    {
        $promotionalBanner->is_active = !$promotionalBanner->is_active;
        $promotionalBanner->save();

        return response()->json([
            'success' => true,
            'is_active' => $promotionalBanner->is_active,
            'message' => $promotionalBanner->is_active ? 'Banner activated!' : 'Banner deactivated!'
        ]);
    }
}
