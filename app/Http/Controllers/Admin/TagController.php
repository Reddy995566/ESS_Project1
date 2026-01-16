<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TagController extends Controller
{
    use LogsActivity;

    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $statusFilter = $request->get('status_filter', 'all');
        $sortField = $request->get('sort_field', 'name');
        $sortDirection = $request->get('sort_direction', 'asc');
        $perPage = $request->get('per_page', 25);

        $query = Tag::query()->withCount('products');

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if ($statusFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($statusFilter === 'inactive') {
            $query->where('is_active', false);
        }

        $query->orderBy($sortField, $sortDirection);
        $tags = $query->paginate($perPage);

        $totalTags = Tag::count();
        $activeTags = Tag::where('is_active', true)->count();
        $inactiveTags = Tag::where('is_active', false)->count();

        $usedTags = Tag::withCount('products')->having('products_count', '>', 0)->count();
        $unusedTags = $totalTags - $usedTags;
        $totalUsageCount = \DB::table('product_tag')->count();

        return view('admin.tags.index', [
            'tags' => $tags,
            'totalTags' => $totalTags,
            'activeTags' => $activeTags,
            'inactiveTags' => $inactiveTags,
            'usedTags' => $usedTags,
            'unusedTags' => $unusedTags,
            'totalUsageCount' => $totalUsageCount,
            'search' => $search,
            'statusFilter' => $statusFilter,
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
            'perPage' => $perPage,
        ]);
    }

    public function show(Tag $tag)
    {
        $tag->loadCount('products');

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'tag' => [
                    'id' => $tag->id,
                    'name' => $tag->name,
                    'is_active' => (bool) $tag->is_active,
                    'usage_count' => $tag->products_count ?? 0,
                ]
            ]);
        }

        return view('admin.tags.show', compact('tag'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('tags', 'name')->whereNull('deleted_at')],
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        try {
            $tag = Tag::create($validated);
            $tag->loadCount('products');

            self::logActivity('created', "Created new tag: {$tag->name}", $tag);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tag created successfully!',
                    'tag' => [
                        'id' => $tag->id,
                        'name' => $tag->name,
                        'is_active' => (bool) $tag->is_active,
                        'usage_count' => $tag->products_count ?? 0,
                        'created_at' => $tag->created_at->format('M d, Y'),
                    ]
                ]);
            }

            return redirect()->route('admin.tags.index')->with('success', 'Tag created successfully!');
        } catch (\Exception $e) {
            \Log::error('Tag creation failed', ['error' => $e->getMessage()]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create tag.'
                ], 500);
            }

            return back()->withInput()->withErrors(['error' => 'Failed to create tag.']);
        }
    }

    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('tags', 'name')->ignore($tag->id)->whereNull('deleted_at')],
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        try {
            $oldData = $tag->only(['name', 'is_active']);
            $tag->update($validated);
            $tag->loadCount('products');

            self::logActivity('updated', "Updated tag: {$tag->name}", $tag, $oldData, $validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tag updated successfully!',
                    'tag' => [
                        'id' => $tag->id,
                        'name' => $tag->name,
                        'is_active' => (bool) $tag->is_active,
                        'usage_count' => $tag->products_count ?? 0,
                        'created_at' => $tag->created_at->format('M d, Y'),
                    ]
                ]);
            }

            return redirect()->route('admin.tags.index')->with('success', 'Tag updated successfully!');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to update tag.'], 500);
            }
            return back()->withInput()->withErrors(['error' => 'Failed to update tag.']);
        }
    }

    public function destroy(Tag $tag)
    {
        try {
            $name = $tag->name;
            $tag->delete();

            self::logActivity('deleted', "Deleted tag: {$name}");
            if (request()->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Tag deleted successfully!']);
            }
            return redirect()->route('admin.tags.index')->with('success', 'Tag deleted successfully!');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to delete tag.'], 500);
            }
            return redirect()->route('admin.tags.index')->with('error', 'Failed to delete tag.');
        }
    }

    public function toggle(Request $request, Tag $tag)
    {
        try {
            $field = $request->input('field');
            $value = $request->input('value');

            if (!in_array($field, ['is_active'])) {
                return response()->json(['success' => false, 'message' => 'Invalid field: ' . $field], 400);
            }

            $boolValue = filter_var($value, FILTER_VALIDATE_BOOLEAN);
            $tag->update([$field => $boolValue]);

            return response()->json(['success' => true, 'message' => 'Tag status updated successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update tag'], 500);
        }
    }

    public function bulkAction(Request $request)
    {
        try {
            $action = $request->input('action');
            $ids = $request->input('ids', []);

            if (empty($ids)) {
                return response()->json(['success' => false, 'message' => 'No tags selected'], 400);
            }

            $count = 0;
            switch ($action) {
                case 'activate':
                    $count = Tag::whereIn('id', $ids)->update(['is_active' => true]);
                    $message = "$count tag(s) activated!";
                    break;
                case 'deactivate':
                    $count = Tag::whereIn('id', $ids)->update(['is_active' => false]);
                    $message = "$count tag(s) deactivated!";
                    break;
                case 'delete':
                    $count = Tag::whereIn('id', $ids)->delete();
                    $message = "$count tag(s) deleted!";
                    break;
                default:
                    return response()->json(['success' => false, 'message' => 'Invalid action'], 400);
            }

            return response()->json(['success' => true, 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to perform bulk action'], 500);
        }
    }

    public function export()
    {
        $tags = Tag::withCount('products')->orderBy('products_count', 'desc')->get();
        $filename = 'tags_' . date('Y-m-d_His') . '.csv';
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=\"$filename\""];

        $callback = function() use ($tags) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Name', 'Usage Count', 'Status', 'Created At']);
            foreach ($tags as $tag) {
                fputcsv($file, [
                    $tag->id, $tag->name, $tag->products_count ?? 0, 
                    $tag->is_active ? 'Active' : 'Inactive',
                    $tag->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
}