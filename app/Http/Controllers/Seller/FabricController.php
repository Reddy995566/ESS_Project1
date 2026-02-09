<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Fabric;
use App\Traits\LogsActivity;
use App\Services\ImageKitService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class FabricController extends Controller
{
    use LogsActivity;
    
    public function index(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        $search = $request->get('search', '');
        $status = $request->get('status');
        $sortField = $request->get('sort_field', 'id');
        $sortDirection = $request->get('sort_direction', 'desc');
        $perPage = $request->get('per_page', 10);

        $query = Fabric::query()->where('seller_id', $seller->id);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('slug', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($status !== null && $status !== '') {
            $query->where('is_active', $status == '1');
        }

        $query->orderBy($sortField, $sortDirection);
        $fabrics = $query->paginate($perPage);

        $totalFabrics = Fabric::where('seller_id', $seller->id)->count();
        $activeFabrics = Fabric::where('seller_id', $seller->id)->where('is_active', true)->count();
        $featuredFabrics = Fabric::where('seller_id', $seller->id)->where('is_featured', true)->count();

        return view('seller.fabrics.index', [
            'fabrics' => $fabrics,
            'totalFabrics' => $totalFabrics,
            'activeFabrics' => $activeFabrics,
            'featuredFabrics' => $featuredFabrics,
            'search' => $search,
            'status' => $status,
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
            'perPage' => $perPage,
        ]);
    }

    public function store(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:fabrics,name,NULL,id,deleted_at,NULL',
            'slug' => 'nullable|string|max:255|unique:fabrics,slug,NULL,id,deleted_at,NULL',
            'description' => 'nullable|string',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'show_in_navbar' => 'nullable|boolean',
            'show_in_homepage' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255'
        ]);

        $validated['seller_id'] = $seller->id;

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        } else {
            $validated['slug'] = Str::slug($validated['slug']);
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['show_in_navbar'] = $request->has('show_in_navbar');
        $validated['show_in_homepage'] = $request->has('show_in_homepage');

        if ($request->hasFile('image')) {
            try {
                set_time_limit(120);
                
                $imageKitService = new ImageKitService();
                $uploadResult = $imageKitService->uploadCategoryImage($request->file('image'));

                if ($uploadResult && $uploadResult['success']) {
                    $validated['imagekit_file_id'] = $uploadResult['file_id'];
                    $validated['imagekit_url'] = $uploadResult['url'];
                    $validated['imagekit_thumbnail_url'] = $uploadResult['thumbnail_url'];
                    $validated['imagekit_file_path'] = $uploadResult['file_path'];
                    $validated['image_size'] = $uploadResult['size'];
                    $validated['original_image_size'] = $uploadResult['original_size'];
                    $validated['image_width'] = $uploadResult['width'];
                    $validated['image_height'] = $uploadResult['height'];
                    $validated['image'] = null;
                } else {
                    if (request()->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Failed to upload image to ImageKit. Please try again.',
                            'errors' => ['image' => ['Failed to upload image to ImageKit. Please try again.']]
                        ], 422);
                    }
                    return back()->withInput()->withErrors(['image' => 'Failed to upload image to ImageKit. Please try again.']);
                }
            } catch (\Exception $e) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Image upload failed: ' . $e->getMessage(),
                        'errors' => ['image' => ['Image upload failed: ' . $e->getMessage()]]
                    ], 422);
                }
                return back()->withInput()->withErrors(['image' => 'Image upload failed: ' . $e->getMessage()]);
            }
        }

        try {
            $fabric = Fabric::create($validated);

            self::logActivity('created', "Created new fabric: {$fabric->name}", $fabric);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Fabric created successfully!',
                    'fabric' => [
                        'id' => $fabric->id,
                        'name' => $fabric->name,
                        'slug' => $fabric->slug,
                        'description' => $fabric->description,
                        'sort_order' => $fabric->sort_order,
                        'is_active' => $fabric->is_active,
                        'is_featured' => $fabric->is_featured,
                        'show_in_navbar' => $fabric->show_in_navbar,
                        'show_in_homepage' => $fabric->show_in_homepage,
                        'imagekit_url' => $fabric->imagekit_url,
                        'imagekit_thumbnail_url' => $fabric->imagekit_thumbnail_url,
                        'created_at' => $fabric->created_at->format('M d, Y'),
                    ]
                ]);
            }

            return redirect()->route('seller.fabrics.index')->with('success', 'Fabric created successfully!');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create fabric. Error: ' . $e->getMessage()
                ], 500);
            }
            return back()->withInput()->withErrors(['error' => 'Failed to create fabric. Please try again.']);
        }
    }

    public function update(Request $request, Fabric $fabric)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:fabrics,name,' . $fabric->id . ',id,deleted_at,NULL',
            'slug' => 'nullable|string|max:255|unique:fabrics,slug,' . $fabric->id . ',id,deleted_at,NULL',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'show_in_navbar' => 'nullable|boolean',
            'show_in_homepage' => 'nullable|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255'
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        } else {
            $validated['slug'] = Str::slug($validated['slug']);
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['show_in_navbar'] = $request->has('show_in_navbar');
        $validated['show_in_homepage'] = $request->has('show_in_homepage');

        if ($request->hasFile('image')) {
            try {
                set_time_limit(120);

                if ($fabric->imagekit_file_id) {
                    $imageKitService = new ImageKitService();
                    $imageKitService->deleteImage($fabric->imagekit_file_id);
                }

                $imageKitService = new ImageKitService();
                $uploadResult = $imageKitService->uploadCategoryImage($request->file('image'));

                if ($uploadResult && $uploadResult['success']) {
                    $validated['imagekit_file_id'] = $uploadResult['file_id'];
                    $validated['imagekit_url'] = $uploadResult['url'];
                    $validated['imagekit_thumbnail_url'] = $uploadResult['thumbnail_url'];
                    $validated['imagekit_file_path'] = $uploadResult['file_path'];
                    $validated['image_size'] = $uploadResult['size'];
                    $validated['original_image_size'] = $uploadResult['original_size'];
                    $validated['image_width'] = $uploadResult['width'];
                    $validated['image_height'] = $uploadResult['height'];
                    $validated['image'] = null;
                } else {
                    if (request()->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Failed to upload image to ImageKit. Please try again.',
                            'errors' => ['image' => ['Failed to upload image to ImageKit. Please try again.']]
                        ], 422);
                    }
                    return back()->withInput()->withErrors(['image' => 'Failed to upload image to ImageKit. Please try again.']);
                }
            } catch (\Exception $e) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Image upload failed: ' . $e->getMessage(),
                        'errors' => ['image' => ['Image upload failed: ' . $e->getMessage()]]
                    ], 422);
                }
                return back()->withInput()->withErrors(['image' => 'Image upload failed: ' . $e->getMessage()]);
            }
        }

        try {
            $fabric->update($validated);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Fabric updated successfully!',
                    'fabric' => [
                        'id' => $fabric->id,
                        'name' => $fabric->name,
                        'slug' => $fabric->slug,
                        'description' => $fabric->description,
                        'sort_order' => $fabric->sort_order,
                        'is_active' => $fabric->is_active,
                        'is_featured' => $fabric->is_featured,
                        'show_in_navbar' => $fabric->show_in_navbar,
                        'show_in_homepage' => $fabric->show_in_homepage,
                        'imagekit_url' => $fabric->imagekit_url,
                        'imagekit_thumbnail_url' => $fabric->imagekit_thumbnail_url,
                        'created_at' => $fabric->created_at->format('M d, Y'),
                    ]
                ]);
            }

            return redirect()->route('seller.fabrics.index')->with('success', 'Fabric updated successfully!');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update fabric. Error: ' . $e->getMessage()
                ], 500);
            }
            return back()->withInput()->withErrors(['error' => 'Failed to update fabric. Please try again.']);
        }
    }

    public function destroy(Fabric $fabric)
    {
        try {
            if ($fabric->imagekit_file_id) {
                $imageKitService = new ImageKitService();
                $imageKitService->deleteImage($fabric->imagekit_file_id);
            }

            $fabric->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Fabric deleted successfully!'
                ]);
            }

            return redirect()->route('seller.fabrics.index')
                ->with('success', 'Fabric deleted successfully!');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete fabric. Please try again.'
                ], 500);
            }

            return redirect()->route('seller.fabrics.index')
                ->with('error', 'Failed to delete fabric. Please try again.');
        }
    }

    public function toggle(Request $request, Fabric $fabric)
    {
        try {
            $field = $request->input('field');
            $value = $request->input('value');

            if (!in_array($field, ['is_active', 'is_featured', 'show_in_navbar', 'show_in_homepage'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid field: ' . $field
                ], 400);
            }

            $boolValue = filter_var($value, FILTER_VALIDATE_BOOLEAN);
            $fabric->update([$field => $boolValue]);

            $fieldNames = [
                'is_active' => 'active status',
                'is_featured' => 'featured status',
                'show_in_navbar' => 'navbar status',
                'show_in_homepage' => 'homepage status',
            ];

            $message = 'Fabric ' . ($fieldNames[$field] ?? $field) . ' updated successfully!';

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update fabric'
            ], 500);
        }
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,activate,deactivate,feature,unfeature,show_in_navbar,hide_in_navbar,show_in_homepage,hide_in_homepage',
            'ids' => 'required|array',
            'ids.*' => 'exists:fabrics,id'
        ]);

        $action = $request->input('action');
        $ids = $request->input('ids');
        $fabrics = Fabric::whereIn('id', $ids);

        try {
            switch ($action) {
                case 'delete':
                    $count = $fabrics->count();
                    $fabrics->delete();
                    $message = "Successfully deleted {$count} fabrics";
                    break;

                case 'activate':
                    $count = $fabrics->update(['is_active' => true]);
                    $message = "Successfully activated {$count} fabrics";
                    break;

                case 'deactivate':
                    $count = $fabrics->update(['is_active' => false]);
                    $message = "Successfully deactivated {$count} fabrics";
                    break;

                case 'feature':
                    $count = $fabrics->update(['is_featured' => true]);
                    $message = "Successfully marked {$count} fabrics as featured";
                    break;

                case 'unfeature':
                    $count = $fabrics->update(['is_featured' => false]);
                    $message = "Successfully removed {$count} fabrics from featured";
                    break;

                case 'show_in_navbar':
                    $count = $fabrics->update(['show_in_navbar' => true]);
                    $message = "Successfully marked {$count} fabrics to show in navbar";
                    break;

                case 'hide_in_navbar':
                    $count = $fabrics->update(['show_in_navbar' => false]);
                    $message = "Successfully hidden {$count} fabrics from navbar";
                    break;

                case 'show_in_homepage':
                    $count = $fabrics->update(['show_in_homepage' => true]);
                    $message = "Successfully marked {$count} fabrics to show in homepage";
                    break;

                case 'hide_in_homepage':
                    $count = $fabrics->update(['show_in_homepage' => false]);
                    $message = "Successfully hidden {$count} fabrics from homepage";
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bulk action failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function export(Request $request)
    {
        $query = Fabric::query();

        if ($request->filled('status')) {
            $query->where('is_active', $request->status == '1');
        }

        if ($request->filled('featured')) {
            $query->where('is_featured', $request->featured == '1');
        }

        $fabrics = $query->orderBy('sort_order')->get();

        $filename = 'fabrics_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($fabrics) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'ID', 'Name', 'Slug', 'Description', 
                'Sort Order', 'Active', 'Featured', 
                'Navbar', 'Homepage',
                'Created At', 'Updated At'
            ]);

            foreach ($fabrics as $fabric) {
                fputcsv($file, [
                    $fabric->id,
                    $fabric->name,
                    $fabric->slug,
                    $fabric->description,
                    $fabric->sort_order,
                    $fabric->is_active ? 'Yes' : 'No',
                    $fabric->is_featured ? 'Yes' : 'No',
                    $fabric->show_in_navbar ? 'Yes' : 'No',
                    $fabric->show_in_homepage ? 'Yes' : 'No',
                    $fabric->created_at->format('Y-m-d H:i:s'),
                    $fabric->updated_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
