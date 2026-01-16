<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\LogsActivity;
use App\Services\ImageKitService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    use LogsActivity;
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $status = $request->get('status');
        $featured = $request->get('featured');
        $parent = $request->get('parent');
        $sortField = $request->get('sort_field', 'id');
        $sortDirection = $request->get('sort_direction', 'desc');
        $perPage = $request->get('per_page', 10);

        $query = Category::query()
            ->with(['parent', 'children'])
            ->withCount('products');

        // Search Filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('slug', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Status Filter
        if ($status !== null && $status !== '') {
            $query->where('is_active', $status == '1');
        }

        // Featured Filter
        if ($featured !== null && $featured !== '') {
            $query->where('is_featured', $featured == '1');
        }

        // Parent Filter
        if ($parent !== null && $parent !== '') {
            if ($parent === 'null') {
                $query->whereNull('parent_id');
            } else {
                $query->where('parent_id', $parent);
            }
        }

        // Navbar Filter
        if ($request->has('navbar') && $request->navbar !== null && $request->navbar !== '') {
             $query->where('show_in_navbar', $request->navbar == '1');
        }

        // Homepage Filter
        if ($request->has('homepage') && $request->homepage !== null && $request->homepage !== '') {
             $query->where('show_in_homepage', $request->homepage == '1');
        }

        $query->orderBy($sortField, $sortDirection);
        $categories = $query->paginate($perPage);

        $parentCategories = Category::whereNull('parent_id')->orderBy('name')->get();

        $totalCategories = Category::count();
        $activeCategories = Category::where('is_active', true)->count();
        $featuredCategories = Category::where('is_featured', true)->count();
        $parentCategoriesCount = Category::whereNull('parent_id')->count();

        return view('admin.categories.index', [
            'categories' => $categories,
            'parentCategories' => $parentCategories,
            'totalCategories' => $totalCategories,
            'activeCategories' => $activeCategories,
            'featuredCategories' => $featuredCategories,
            'parentCategoriesCount' => $parentCategoriesCount,
            'search' => $search,
            'status' => $status,
            'featured' => $featured,
            'parent' => $parent,
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
            'perPage' => $perPage,
        ]);
    }

    public function create()
    {
        // Get only root categories with their children eager loaded
        $parentCategories = Category::whereNull('parent_id')
            ->with(['children' => function($query) {
                $query->with(['children' => function($q) {
                    $q->orderBy('sort_order')->orderBy('name');
                }])
                ->orderBy('sort_order')
                ->orderBy('name');
            }])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        
        return view('admin.categories.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,NULL,id,deleted_at,NULL',
            'slug' => 'nullable|string|max:255|unique:categories,slug,NULL,id,deleted_at,NULL',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
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

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        } else {
            $validated['slug'] = Str::slug($validated['slug']);
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['show_in_navbar'] = $request->has('show_in_navbar');
        $validated['show_in_homepage'] = $request->has('show_in_homepage');
        $validated['show_in_circle'] = $request->has('show_in_circle');
        
        // Circle settings
        if ($request->has('show_in_circle')) {
            $validated['circle_type'] = $request->input('circle_type', 'image');
            $validated['circle_text'] = $request->input('circle_text');
            $validated['circle_link'] = $request->input('circle_link');
            $validated['circle_bg_color'] = $request->input('circle_bg_color', '#4A0F23');
            $validated['circle_text_color'] = $request->input('circle_text_color', '#D6B27A');
            $validated['circle_order'] = $request->input('circle_order', 0);
        }

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
                    \Log::error('ImageKit upload failed', ['result' => $uploadResult]);

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
                \Log::error('ImageKit upload exception', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

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
            $category = Category::create($validated);

            self::logActivity('created', "Created new category: {$category->name}", $category);

            if (request()->expectsJson()) {

                $category->load('parent');

                return response()->json([
                    'success' => true,
                    'message' => 'Category created successfully!',
                    'category' => [
                        'id' => $category->id,
                        'name' => $category->name,
                        'slug' => $category->slug,
                        'description' => $category->description,
                        'parent_name' => $category->parent ? $category->parent->name : null,
                        'sort_order' => $category->sort_order,
                        'is_active' => $category->is_active,
                        'is_featured' => $category->is_featured,
                        'show_in_navbar' => $category->show_in_navbar,
                        'show_in_homepage' => $category->show_in_homepage,
                        'imagekit_url' => $category->imagekit_url,
                        'imagekit_thumbnail_url' => $category->imagekit_thumbnail_url,
                        'created_at' => $category->created_at->format('M d, Y'),
                    ]
                ]);
            }

            return redirect()->route('admin.categories.index')->with('success', 'Category created successfully!');
        } catch (\Exception $e) {
            \Log::error('Category creation failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create category. Error: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()->withErrors(['error' => 'Failed to create category. Please try again.']);
        }
    }

    public function show(Category $category)
    {
        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        // Get all categories except current one and its children (to prevent circular reference)
        $excludeIds = [$category->id];
        
        // Get all child IDs recursively
        $this->getChildIds($category, $excludeIds);
        
        // Get only root categories with their children eager loaded (excluding current category and its descendants)
        $parentCategories = Category::whereNull('parent_id')
            ->whereNotIn('id', $excludeIds)
            ->with(['children' => function($query) use ($excludeIds) {
                $query->whereNotIn('id', $excludeIds)
                    ->with(['children' => function($q) use ($excludeIds) {
                        $q->whereNotIn('id', $excludeIds)
                          ->orderBy('sort_order')
                          ->orderBy('name');
                    }])
                    ->orderBy('sort_order')
                    ->orderBy('name');
            }])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }
    
    // Helper method to get all child category IDs recursively
    private function getChildIds($category, &$excludeIds)
    {
        $children = Category::where('parent_id', $category->id)->get();
        foreach ($children as $child) {
            $excludeIds[] = $child->id;
            $this->getChildIds($child, $excludeIds);
        }
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id . ',id,deleted_at,NULL',
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id . ',id,deleted_at,NULL',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'parent_id' => 'nullable|exists:categories,id',
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
        $validated['show_in_circle'] = $request->has('show_in_circle');
        
        // Circle settings
        $validated['circle_type'] = $request->input('circle_type', 'image');
        $validated['circle_text'] = $request->input('circle_text');
        $validated['circle_link'] = $request->input('circle_link');
        $validated['circle_bg_color'] = $request->input('circle_bg_color', '#4A0F23');
        $validated['circle_text_color'] = $request->input('circle_text_color', '#D6B27A');
        $validated['circle_order'] = $request->input('circle_order', 0);

        if ($request->hasFile('image')) {
            try {
                set_time_limit(120);

                if ($category->imagekit_file_id) {
                    $imageKitService = new ImageKitService();
                    $imageKitService->deleteImage($category->imagekit_file_id);
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
                    \Log::error('ImageKit upload failed', ['result' => $uploadResult]);

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
                \Log::error('ImageKit upload exception', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

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
            $category->update($validated);

            if (request()->expectsJson()) {

                $category->load('parent');

                return response()->json([
                    'success' => true,
                    'message' => 'Category updated successfully!',
                    'category' => [
                        'id' => $category->id,
                        'name' => $category->name,
                        'slug' => $category->slug,
                        'description' => $category->description,
                        'parent_name' => $category->parent ? $category->parent->name : null,
                        'sort_order' => $category->sort_order,
                        'is_active' => $category->is_active,
                        'is_featured' => $category->is_featured,
                        'show_in_navbar' => $category->show_in_navbar,
                        'show_in_homepage' => $category->show_in_homepage,
                        'imagekit_url' => $category->imagekit_url,
                        'imagekit_thumbnail_url' => $category->imagekit_thumbnail_url,
                        'created_at' => $category->created_at->format('M d, Y'),
                    ]
                ]);
            }

            return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully!');
        } catch (\Exception $e) {

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update category. Error: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()->withErrors(['error' => 'Failed to update category. Please try again.']);
        }
    }

    public function destroy(Category $category)
    {
        try {

            if ($category->imagekit_file_id) {
                $imageKitService = new ImageKitService();
                $imageKitService->deleteImage($category->imagekit_file_id);
            }

            $category->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Category deleted successfully!'
                ]);
            }

            return redirect()->route('admin.categories.index')
                ->with('success', 'Category deleted successfully!');
        } catch (\Exception $e) {

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete category. Please try again.'
                ], 500);
            }

            return redirect()->route('admin.categories.index')
                ->with('error', 'Failed to delete category. Please try again.');
        }
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id'
        ]);

        try {
            $categories = Category::whereIn('id', $request->categories)->get();

            foreach ($categories as $category) {

                if ($category->imagekit_file_id) {
                    $imageKitService = new ImageKitService();
                    $imageKitService->deleteImage($category->imagekit_file_id);
                }
                $category->delete();
            }

            return redirect()->route('admin.categories.index')->with('success', 'Selected categories deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.categories.index')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Category $category)
    {
        $category->update(['is_active' => !$category->is_active]);
        return back()->with('success', 'Category status updated!');
    }

    public function toggleFeatured(Category $category)
    {
        $category->update(['is_featured' => !$category->is_featured]);
        return back()->with('success', 'Featured status updated!');
    }

    public function toggle(Request $request, Category $category)
    {

        try {
            $field = $request->input('field');
            $value = $request->input('value');

            if (!in_array($field, ['is_active', 'is_featured', 'show_in_navbar', 'show_in_homepage', 'show_in_circle'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid field: ' . $field
                ], 400);
            }

            $boolValue = filter_var($value, FILTER_VALIDATE_BOOLEAN);

            $category->update([$field => $boolValue]);

            $fieldNames = [
                'is_active' => 'active status',
                'is_featured' => 'featured status', 
                'show_in_navbar' => 'navbar visibility',
                'show_in_homepage' => 'homepage visibility',
                'show_in_circle' => 'circle visibility'
            ];

            $message = 'Category ' . ($fieldNames[$field] ?? $field) . ' updated successfully!';

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update category'
            ], 500);
        }
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,activate,deactivate,feature,unfeature',
            'ids' => 'required|array',
            'ids.*' => 'exists:categories,id'
        ]);

        $action = $request->input('action');
        $ids = $request->input('ids');
        $categories = Category::whereIn('id', $ids);

        try {
            switch ($action) {
                case 'delete':
                    $count = $categories->count();
                    $categories->delete();
                    $message = "Successfully deleted {$count} categories";
                    break;

                case 'activate':
                    $count = $categories->update(['is_active' => true]);
                    $message = "Successfully activated {$count} categories";
                    break;

                case 'deactivate':
                    $count = $categories->update(['is_active' => false]);
                    $message = "Successfully deactivated {$count} categories";
                    break;

                case 'feature':
                    $count = $categories->update(['is_featured' => true]);
                    $message = "Successfully marked {$count} categories as featured";
                    break;

                case 'unfeature':
                    $count = $categories->update(['is_featured' => false]);
                    $message = "Successfully removed {$count} categories from featured";
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

    public function uploadImage(Request $request)
    {
        try {
            set_time_limit(120);
            
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:10240',
                'folder' => 'nullable|string'
            ]);

            $folder = $request->input('folder', 'categories');
            $imageKitService = new ImageKitService();
            $uploadResult = $imageKitService->uploadCategoryImage($request->file('image'), $folder);

            if ($uploadResult && $uploadResult['success']) {
                return response()->json($uploadResult);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to upload image to ImageKit'
                ], 422);
            }
        } catch (\Exception $e) {
            \Log::error('Instant image upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function export(Request $request)
    {
        $query = Category::query()->with(['parent', 'children']);

        if ($request->filled('status')) {
            $query->where('is_active', $request->status == '1');
        }

        if ($request->filled('featured')) {
            $query->where('is_featured', $request->featured == '1');
        }

        if ($request->filled('parent')) {
            if ($request->parent == 'null') {
                $query->whereNull('parent_id');
            } else {
                $query->where('parent_id', $request->parent);
            }
        }

        if ($request->filled('navbar')) {
            $query->where('show_in_navbar', $request->navbar == '1');
        }

        $categories = $query->orderBy('sort_order')->get();

        $filename = 'categories_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($categories) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'ID', 'Name', 'Slug', 'Description', 'Parent Category', 
                'Sort Order', 'Active', 'Featured', 'Show in Navbar', 
                'Show in Homepage', 'Products Count', 'Created At', 'Updated At'
            ]);

            foreach ($categories as $category) {
                fputcsv($file, [
                    $category->id,
                    $category->name,
                    $category->slug,
                    $category->description,
                    $category->parent ? $category->parent->name : '',
                    $category->sort_order,
                    $category->is_active ? 'Yes' : 'No',
                    $category->is_featured ? 'Yes' : 'No',
                    $category->show_in_navbar ? 'Yes' : 'No',
                    $category->show_in_homepage ? 'Yes' : 'No',
                    $category->products()->count(),
                    $category->created_at->format('Y-m-d H:i:s'),
                    $category->updated_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}