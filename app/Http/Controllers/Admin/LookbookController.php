<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lookbook;
use App\Services\ImageKitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LookbookController extends Controller
{
    public function index()
    {
        $lookbooks = Lookbook::orderBy('sort_order')->orderBy('created_at', 'desc')->get();
        $totalLookbooks = $lookbooks->count();
        $activeLookbooks = $lookbooks->where('is_active', true)->count();

        return view('admin.lookbooks.index', compact('lookbooks', 'totalLookbooks', 'activeLookbooks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'required|string',
            'link' => 'nullable|url|max:255',
            'position' => 'nullable|in:left,right',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        // Set defaults
        $validated['position'] = $validated['position'] ?? 'left';
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $lookbook = Lookbook::create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Lookbook created successfully!',
                'lookbook' => $lookbook
            ]);
        }

        return redirect()->route('admin.lookbooks.index')->with('success', 'Lookbook created successfully!');
    }

    public function update(Request $request, Lookbook $lookbook)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|string',
            'link' => 'nullable|url|max:255',
            'position' => 'nullable|in:left,right',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        // Set defaults
        $validated['position'] = $validated['position'] ?? 'left';
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $lookbook->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Lookbook updated successfully!',
                'lookbook' => $lookbook->fresh()
            ]);
        }

        return redirect()->route('admin.lookbooks.index')->with('success', 'Lookbook updated successfully!');
    }

    public function destroy(Lookbook $lookbook)
    {
        $lookbook->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Lookbook deleted successfully!'
            ]);
        }

        return redirect()->route('admin.lookbooks.index')->with('success', 'Lookbook deleted successfully!');
    }

    public function uploadImage(Request $request, ImageKitService $imageKit)
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            ]);

            $folder = 'lookbooks';
            $result = $imageKit->uploadCategoryImage($request->file('image'), $folder);

            if ($result && $result['success']) {
                return response()->json([
                    'success' => true,
                    'url' => $result['url'],
                    'file_id' => $result['file_id'],
                    'message' => 'Lookbook image uploaded successfully!'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to upload lookbook image'
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function toggle(Request $request, Lookbook $lookbook)
    {
        $field = $request->input('field');
        $value = $request->input('value');

        if (in_array($field, ['is_active'])) {
            $lookbook->update([$field => $value]);

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
                Lookbook::whereIn('id', $ids)->update(['is_active' => true]);
                return response()->json([
                    'success' => true,
                    'message' => count($ids) . ' lookbook(s) activated successfully!'
                ]);

            case 'deactivate':
                Lookbook::whereIn('id', $ids)->update(['is_active' => false]);
                return response()->json([
                    'success' => true,
                    'message' => count($ids) . ' lookbook(s) deactivated successfully!'
                ]);

            case 'delete':
                Lookbook::whereIn('id', $ids)->delete();
                return response()->json([
                    'success' => true,
                    'message' => count($ids) . ' lookbook(s) deleted successfully!'
                ]);

            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid action'
                ], 400);
        }
    }

    public function export()
    {
        $lookbooks = Lookbook::orderBy('sort_order')->get();

        $filename = 'lookbooks_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($lookbooks) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Title', 'Subtitle', 'Image', 'Link', 'Position', 'Sort Order', 'Status', 'Created At']);

            foreach ($lookbooks as $lookbook) {
                fputcsv($file, [
                    $lookbook->id,
                    $lookbook->title,
                    $lookbook->subtitle,
                    $lookbook->image_url,
                    $lookbook->link,
                    $lookbook->position,
                    $lookbook->sort_order,
                    $lookbook->is_active ? 'Active' : 'Inactive',
                    $lookbook->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
