<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Services\ImageKitService;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    use LogsActivity;

    protected $imageKitService;

    public function __construct(ImageKitService $imageKitService)
    {
        $this->imageKitService = $imageKitService;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $banners = Banner::orderBy('order', 'asc')->get();

        $totalBanners = Banner::count();
        $activeBanners = Banner::where('is_active', true)->count();

        return view('admin.banners.index', [
            'banners' => $banners,
            'totalBanners' => $totalBanners,
            'activeBanners' => $activeBanners,
            'activeMenu' => 'banners',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'desktop_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:10240',
            'mobile_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:10240',
            'link' => 'nullable|url',
        ]);

        try {
            $banner = new Banner();
            $banner->order = Banner::max('order') + 1;
            $banner->link = $validated['link'] ?? null;
            $banner->is_active = $request->has('is_active');

            // Upload desktop image to ImageKit
            if ($request->hasFile('desktop_image')) {
                $result = $this->imageKitService->uploadCategoryImage(
                    $request->file('desktop_image'),
                    'banners/desktop'
                );

                if ($result && $result['success']) {
                    $banner->desktop_image = $result['name'] ?? null;
                    $banner->desktop_imagekit_file_id = $result['file_id'] ?? null;
                    $banner->desktop_imagekit_url = $result['url'] ?? null;
                }
            }

            // Upload mobile image to ImageKit
            if ($request->hasFile('mobile_image')) {
                $result = $this->imageKitService->uploadCategoryImage(
                    $request->file('mobile_image'),
                    'banners/mobile'
                );

                if ($result && $result['success']) {
                    $banner->mobile_image = $result['name'] ?? null;
                    $banner->mobile_imagekit_file_id = $result['file_id'] ?? null;
                    $banner->mobile_imagekit_url = $result['url'] ?? null;
                }
            }

            $banner->save();
            self::logActivity('created', "Created new hero banner", $banner);

            return response()->json([
                'success' => true,
                'message' => 'Banner created successfully!',
                'banner' => $banner
            ]);
        } catch (\Exception $e) {
            \Log::error('Banner creation failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create banner: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Banner $banner)
    {
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'banner' => $banner
            ]);
        }
        
        return view('admin.banners.show', compact('banner'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $banner = Banner::find($id);
        
        if (!$banner) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Banner not found'
                ], 404);
            }
            
            return redirect()->route('admin.banners.index')
                ->with('error', 'Banner not found');
        }

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'banner' => $banner
            ]);
        }
        
        return view('admin.banners.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'desktop_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'mobile_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'link' => 'nullable|url',
        ]);

        try {
            $banner->link = $validated['link'] ?? null;
            $banner->is_active = $request->has('is_active');

            // Upload desktop image to ImageKit if provided
            if ($request->hasFile('desktop_image')) {
                // Delete old image from ImageKit
                if ($banner->desktop_imagekit_file_id) {
                    $this->imageKitService->deleteFile($banner->desktop_imagekit_file_id);
                }

                $result = $this->imageKitService->uploadCategoryImage(
                    $request->file('desktop_image'),
                    'banners/desktop'
                );

                if ($result && $result['success']) {
                    $banner->desktop_image = $result['name'] ?? null;
                    $banner->desktop_imagekit_file_id = $result['file_id'] ?? null;
                    $banner->desktop_imagekit_url = $result['url'] ?? null;
                }
            }

            // Upload mobile image to ImageKit if provided
            if ($request->hasFile('mobile_image')) {
                // Delete old image from ImageKit
                if ($banner->mobile_imagekit_file_id) {
                    $this->imageKitService->deleteFile($banner->mobile_imagekit_file_id);
                }

                $result = $this->imageKitService->uploadCategoryImage(
                    $request->file('mobile_image'),
                    'banners/mobile'
                );

                if ($result && $result['success']) {
                    $banner->mobile_image = $result['name'] ?? null;
                    $banner->mobile_imagekit_file_id = $result['file_id'] ?? null;
                    $banner->mobile_imagekit_url = $result['url'] ?? null;
                }
            }

            $banner->save();
            self::logActivity('updated', "Updated hero banner", $banner);

            return response()->json([
                'success' => true,
                'message' => 'Banner updated successfully!',
                'banner' => $banner
            ]);
        } catch (\Exception $e) {
            \Log::error('Banner update failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update banner: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner)
    {
        try {
            // Delete images from ImageKit
            if ($banner->desktop_imagekit_file_id) {
                $this->imageKitService->deleteFile($banner->desktop_imagekit_file_id);
            }
            if ($banner->mobile_imagekit_file_id) {
                $this->imageKitService->deleteFile($banner->mobile_imagekit_file_id);
            }

            $banner->delete();
            self::logActivity('deleted', "Deleted hero banner");

            return response()->json([
                'success' => true,
                'message' => 'Banner deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete banner'
            ], 500);
        }
    }

    public function toggle(Request $request, Banner $banner)
    {
        try {
            $field = $request->input('field');
            $value = $request->input('value');

            if (!in_array($field, ['is_active'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid field'
                ], 400);
            }

            $boolValue = filter_var($value, FILTER_VALIDATE_BOOLEAN);
            $banner->update([$field => $boolValue]);

            $status = $boolValue ? 'activated' : 'deactivated';
            self::logActivity('updated', "Changed banner status to: {$status}", $banner);

            return response()->json([
                'success' => true,
                'message' => 'Banner status updated successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update banner status'
            ], 500);
        }
    }

    public function updateOrder(Request $request)
    {
        try {
            $items = $request->input('items', []);

            foreach ($items as $item) {
                Banner::where('id', $item['id'])->update(['order' => $item['order']]);
            }

            self::logActivity('updated', 'Updated banner order');

            return response()->json([
                'success' => true,
                'message' => 'Banner order updated successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update banner order'
            ], 500);
        }
    }
}
