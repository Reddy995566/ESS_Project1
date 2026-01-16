<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Services\ImageKitService;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::with('images')->orderBy('created_at', 'desc')->get();
        $totalBlogs = $blogs->count();
        $activeBlogs = $blogs->where('is_active', true)->count();

        return view('admin.blogs.index', compact('blogs', 'totalBlogs', 'activeBlogs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:blogs,slug',
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'featured_image' => 'nullable|string',
            'imagekit_file_id' => 'nullable|string',
            'imagekit_url' => 'nullable|string',
            'imagekit_thumbnail_url' => 'nullable|string',
            'imagekit_file_path' => 'nullable|string',
            'author' => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
            'is_featured' => 'boolean',
            'is_active' => 'boolean'
        ]);

        $validated['is_featured'] = $request->has('is_featured') ? 1 : 0;
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $blog = Blog::create($validated);

        if ($request->expectsJson()) {
            // Load the blog with images relationship
            $blog->load('images');
            
            return response()->json([
                'success' => true,
                'message' => 'Blog created successfully!',
                'blog' => [
                    'id' => $blog->id,
                    'title' => $blog->title,
                    'slug' => $blog->slug,
                    'excerpt' => $blog->excerpt,
                    'content' => $blog->content,
                    'featured_image' => $blog->featured_image,
                    'featured_image_url' => $blog->featured_image_url,
                    'imagekit_file_id' => $blog->imagekit_file_id,
                    'imagekit_url' => $blog->imagekit_url,
                    'imagekit_thumbnail_url' => $blog->imagekit_thumbnail_url,
                    'imagekit_file_path' => $blog->imagekit_file_path,
                    'author' => $blog->author,
                    'published_at' => $blog->published_at,
                    'views' => $blog->views ?? 0,
                    'is_featured' => $blog->is_featured,
                    'is_active' => $blog->is_active,
                    'images' => $blog->images,
                    'created_at' => $blog->created_at,
                    'updated_at' => $blog->updated_at
                ]
            ]);
        }

        return redirect()->route('admin.blogs.index')->with('success', 'Blog created successfully!');
    }

    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:blogs,slug,' . $blog->id,
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'featured_image' => 'nullable|string',
            'imagekit_file_id' => 'nullable|string',
            'imagekit_url' => 'nullable|string',
            'imagekit_thumbnail_url' => 'nullable|string',
            'imagekit_file_path' => 'nullable|string',
            'author' => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
            'is_featured' => 'boolean',
            'is_active' => 'boolean'
        ]);

        $validated['is_featured'] = $request->has('is_featured') ? 1 : 0;
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $blog->update($validated);
        $blog = $blog->fresh(['images']);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Blog updated successfully!',
                'blog' => [
                    'id' => $blog->id,
                    'title' => $blog->title,
                    'slug' => $blog->slug,
                    'excerpt' => $blog->excerpt,
                    'content' => $blog->content,
                    'featured_image' => $blog->featured_image,
                    'featured_image_url' => $blog->featured_image_url,
                    'imagekit_file_id' => $blog->imagekit_file_id,
                    'imagekit_url' => $blog->imagekit_url,
                    'imagekit_thumbnail_url' => $blog->imagekit_thumbnail_url,
                    'imagekit_file_path' => $blog->imagekit_file_path,
                    'author' => $blog->author,
                    'published_at' => $blog->published_at,
                    'views' => $blog->views ?? 0,
                    'is_featured' => $blog->is_featured,
                    'is_active' => $blog->is_active,
                    'images' => $blog->images,
                    'created_at' => $blog->created_at,
                    'updated_at' => $blog->updated_at
                ]
            ]);
        }

        return redirect()->route('admin.blogs.index')->with('success', 'Blog updated successfully!');
    }

    public function destroy(Blog $blog)
    {
        $blog->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Blog deleted successfully!'
            ]);
        }

        return redirect()->route('admin.blogs.index')->with('success', 'Blog deleted successfully!');
    }

    public function uploadImage(Request $request, ImageKitService $imageKit)
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            ]);

            $folder = 'blogs';
            $result = $imageKit->uploadCategoryImage($request->file('image'), $folder);

            if ($result && $result['success']) {
                return response()->json([
                    'success' => true,
                    'url' => $result['url'],
                    'file_id' => $result['file_id'],
                    'file_path' => $result['file_path'] ?? '',
                    'thumbnail_url' => $result['thumbnail_url'] ?? '',
                    'message' => 'Blog image uploaded successfully!'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to upload blog image'
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function toggle(Request $request, Blog $blog)
    {
        $field = $request->input('field');
        $value = $request->input('value');

        if (in_array($field, ['is_active', 'is_featured'])) {
            $blog->update([$field => $value]);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully!'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid field'
        ], 400);
    }

    public function saveGalleryImages(Request $request, Blog $blog)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*.url' => 'required|string',
            'images.*.file_id' => 'nullable|string',
            'images.*.file_path' => 'nullable|string',
            'images.*.thumbnail_url' => 'nullable|string',
        ]);

        $images = $request->input('images', []);
        
        // Delete old gallery images
        $blog->images()->delete();
        
        // Save new gallery images
        foreach ($images as $index => $imageData) {
            $blog->images()->create([
                'image_url' => $imageData['url'],
                'imagekit_file_id' => $imageData['file_id'] ?? null,
                'imagekit_url' => $imageData['url'],
                'imagekit_thumbnail_url' => $imageData['thumbnail_url'] ?? $imageData['url'],
                'imagekit_file_path' => $imageData['file_path'] ?? null,
                'sort_order' => $index
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Gallery images saved successfully!',
            'images' => $blog->fresh(['images'])->images
        ]);
    }

    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No items selected'
            ], 400);
        }

        switch ($action) {
            case 'activate':
                Blog::whereIn('id', $ids)->update(['is_active' => true]);
                return response()->json([
                    'success' => true,
                    'message' => count($ids) . ' blog(s) activated successfully!'
                ]);

            case 'deactivate':
                Blog::whereIn('id', $ids)->update(['is_active' => false]);
                return response()->json([
                    'success' => true,
                    'message' => count($ids) . ' blog(s) deactivated successfully!'
                ]);

            case 'delete':
                Blog::whereIn('id', $ids)->delete();
                return response()->json([
                    'success' => true,
                    'message' => count($ids) . ' blog(s) deleted successfully!'
                ]);

            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid action'
                ], 400);
        }
    }
}
