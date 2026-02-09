<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class CollectionController extends Controller
{
    use LogsActivity;
    public function index(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        $search = $request->get('search', '');
        $status = $request->get('status');
        $homepage = $request->get('homepage');
        $sortField = $request->get('sort_field', 'id');
        $sortDirection = $request->get('sort_direction', 'desc');
        $perPage = $request->get('per_page', 10);

        $query = Collection::query()->where('seller_id', $seller->id);

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

        if ($homepage !== null && $homepage !== '') {
            $query->where('show_in_homepage', $homepage == '1');
        }

        $query->orderBy($sortField, $sortDirection);
        $collections = $query->paginate($perPage);

        $totalCollections = Collection::where('seller_id', $seller->id)->count();
        $activeCollections = Collection::where('seller_id', $seller->id)->where('is_active', true)->count();
        $homepageCollections = Collection::where('seller_id', $seller->id)->where('show_in_homepage', true)->count();

        return view('seller.collections.index', [
            'collections' => $collections,
            'totalCollections' => $totalCollections,
            'activeCollections' => $activeCollections,
            'homepageCollections' => $homepageCollections,
            'search' => $search,
            'status' => $status,
            'homepage' => $homepage,
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
            'perPage' => $perPage,
        ]);
    }

    public function store(Request $request)
    {
        $seller = Auth::guard('seller')->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('collections', 'name')->whereNull('deleted_at')],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('collections', 'slug')->whereNull('deleted_at')],
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
            'show_in_homepage' => 'nullable|boolean',
        ]);

        $validated['seller_id'] = $seller->id;

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['show_in_homepage'] = $request->has('show_in_homepage');

        try {
            $collection = Collection::create($validated);

            self::logActivity('created', "Created new collection: {$collection->name}", $collection);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Collection created successfully!',
                    'collection' => [
                        'id' => $collection->id,
                        'name' => $collection->name,
                        'slug' => $collection->slug,
                        'description' => $collection->description,
                        'image' => $collection->image,
                        'sort_order' => $collection->sort_order,
                        'is_active' => (bool) $collection->is_active,
                        'show_in_homepage' => (bool) $collection->show_in_homepage,
                        'created_at' => $collection->created_at->format('M d, Y'),
                    ]
                ]);
            }

            return redirect()->route('seller.collections.index')->with('success', 'Collection created successfully!');
        } catch (\Exception $e) {
            \Log::error('Collection creation failed', ['error' => $e->getMessage()]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create collection.'
                ], 500);
            }

            return back()->withInput()->withErrors(['error' => 'Failed to create collection.']);
        }
    }

    public function update(Request $request, Collection $collection)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('collections', 'name')->ignore($collection->id)->whereNull('deleted_at')],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('collections', 'slug')->ignore($collection->id)->whereNull('deleted_at')],
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
            'show_in_homepage' => 'nullable|boolean',
        ]);

        if (empty($validated['slug']) && $validated['name'] !== $collection->name) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['show_in_homepage'] = $request->has('show_in_homepage');

        try {
            $oldData = $collection->only(['name', 'slug', 'is_active']);
            $collection->update($validated);

            self::logActivity('updated', "Updated collection: {$collection->name}", $collection, $oldData, $validated);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Collection updated successfully!',
                    'collection' => [
                        'id' => $collection->id,
                        'name' => $collection->name,
                        'slug' => $collection->slug,
                        'description' => $collection->description,
                        'image' => $collection->image,
                        'sort_order' => $collection->sort_order,
                        'is_active' => (bool) $collection->is_active,
                        'show_in_homepage' => (bool) $collection->show_in_homepage,
                        'created_at' => $collection->created_at->format('M d, Y'),
                    ]
                ]);
            }

            return redirect()->route('seller.collections.index')->with('success', 'Collection updated successfully!');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to update collection.'], 500);
            }
            return back()->withInput()->withErrors(['error' => 'Failed to update collection.']);
        }
    }

    public function destroy(Collection $collection)
    {
        try {
            $name = $collection->name;
            $collection->delete();

            self::logActivity('deleted', "Deleted collection: {$name}");
            if (request()->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Collection deleted successfully!']);
            }
            return redirect()->route('seller.collections.index')->with('success', 'Collection deleted successfully!');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to delete collection.'], 500);
            }
            return redirect()->route('seller.collections.index')->with('error', 'Failed to delete collection.');
        }
    }

    public function toggle(Request $request, Collection $collection)
    {
        try {
            $field = $request->input('field');
            $value = $request->input('value');

            if (!in_array($field, ['is_active', 'show_in_homepage'])) {
                return response()->json(['success' => false, 'message' => 'Invalid field: ' . $field], 400);
            }

            $boolValue = filter_var($value, FILTER_VALIDATE_BOOLEAN);
            $collection->update([$field => $boolValue]);

            return response()->json(['success' => true, 'message' => 'Collection status updated successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update collection'], 500);
        }
    }

    public function bulkAction(Request $request)
    {
        try {
            $action = $request->input('action');
            $ids = $request->input('ids', []);

            if (empty($ids)) {
                return response()->json(['success' => false, 'message' => 'No collections selected'], 400);
            }

            $count = 0;
            switch ($action) {
                case 'activate':
                    $count = Collection::whereIn('id', $ids)->update(['is_active' => true]);
                    $message = "$count collection(s) activated!";
                    break;
                case 'deactivate':
                    $count = Collection::whereIn('id', $ids)->update(['is_active' => false]);
                    $message = "$count collection(s) deactivated!";
                    break;
                case 'delete':
                    $count = Collection::whereIn('id', $ids)->delete();
                    $message = "$count collection(s) deleted!";
                    break;
                default:
                    return response()->json(['success' => false, 'message' => 'Invalid action'], 400);
            }

            return response()->json(['success' => true, 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to perform bulk action'], 500);
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

            $folder = $request->input('folder', 'collections');
            $imageKitService = new \App\Services\ImageKitService();
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

    public function export()
    {
        $collections = Collection::orderBy('sort_order')->get();
        $filename = 'collections_' . date('Y-m-d_His') . '.csv';
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=\"$filename\""];

        $callback = function() use ($collections) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Name', 'Slug', 'Description', 'Sort Order', 'Status', 'Created At']);
            foreach ($collections as $collection) {
                fputcsv($file, [
                    $collection->id, $collection->name, $collection->slug, $collection->description,
                    $collection->sort_order, $collection->is_active ? 'Active' : 'Inactive',
                    $collection->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
}
