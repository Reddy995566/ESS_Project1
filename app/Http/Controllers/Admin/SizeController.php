<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    use LogsActivity;
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $statusFilter = $request->get('status_filter', 'all');
        $sortField = $request->get('sort_field', 'id');
        $sortDirection = $request->get('sort_direction', 'desc');
        $perPage = $request->get('per_page', 10);

        $query = Size::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('abbreviation', 'like', '%' . $search . '%');
            });
        }

        if ($statusFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($statusFilter === 'inactive') {
            $query->where('is_active', false);
        }

        $query->orderBy($sortField, $sortDirection);
        $sizes = $query->paginate($perPage);

        $totalSizes = Size::count();
        $activeSizes = Size::where('is_active', true)->count();

        return view('admin.sizes.index', [
            'sizes' => $sizes,
            'totalSizes' => $totalSizes,
            'activeSizes' => $activeSizes,
            'search' => $search,
            'statusFilter' => $statusFilter,
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
            'perPage' => $perPage,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:sizes,name,NULL,id,deleted_at,NULL',
            'abbreviation' => 'nullable|string|max:10',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        try {
            $size = Size::create($validated);

            self::logActivity('created', "Created new size: {$size->name}", $size);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Size created successfully!',
                    'size' => [
                        'id' => $size->id,
                        'name' => $size->name,
                        'abbreviation' => $size->abbreviation,
                        'sort_order' => $size->sort_order,
                        'is_active' => (bool) $size->is_active,
                        'created_at' => $size->created_at->format('M d, Y'),
                    ]
                ]);
            }

            return redirect()->route('admin.sizes.index')->with('success', 'Size created successfully!');
        } catch (\Exception $e) {
            \Log::error('Size creation failed', ['error' => $e->getMessage()]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create size. Error: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()->withErrors(['error' => 'Failed to create size. Please try again.']);
        }
    }

    public function update(Request $request, Size $size)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:sizes,name,' . $size->id . ',id,deleted_at,NULL',
            'abbreviation' => 'nullable|string|max:10',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        try {
            $oldData = $size->only(['name', 'abbreviation']);
            $size->update($validated);

            self::logActivity('updated', "Updated size: {$size->name}", $size, $oldData, $validated);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Size updated successfully!',
                    'size' => [
                        'id' => $size->id,
                        'name' => $size->name,
                        'abbreviation' => $size->abbreviation,
                        'sort_order' => $size->sort_order,
                        'is_active' => (bool) $size->is_active,
                        'created_at' => $size->created_at->format('M d, Y'),
                    ]
                ]);
            }

            return redirect()->route('admin.sizes.index')->with('success', 'Size updated successfully!');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update size. Error: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()->withErrors(['error' => 'Failed to update size. Please try again.']);
        }
    }

    public function destroy(Size $size)
    {
        try {
            $name = $size->name;
            $size->delete();

            self::logActivity('deleted', "Deleted size: {$name}");

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Size deleted successfully!'
                ]);
            }

            return redirect()->route('admin.sizes.index')
                ->with('success', 'Size deleted successfully!');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete size. Please try again.'
                ], 500);
            }

            return redirect()->route('admin.sizes.index')
                ->with('error', 'Failed to delete size. Please try again.');
        }
    }

    public function toggle(Request $request, Size $size)
    {
        try {
            $field = $request->input('field');
            $value = $request->input('value');

            if (!in_array($field, ['is_active'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid field: ' . $field
                ], 400);
            }

            $boolValue = filter_var($value, FILTER_VALIDATE_BOOLEAN);
            $size->update([$field => $boolValue]);

            $message = 'Size status updated successfully!';

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update size'
            ], 500);
        }
    }

    public function bulkAction(Request $request)
    {
        try {
            $action = $request->input('action');
            $ids = $request->input('ids', []);

            if (empty($ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No sizes selected'
                ], 400);
            }

            $count = 0;

            switch ($action) {
                case 'activate':
                    $count = Size::whereIn('id', $ids)->update(['is_active' => true]);
                    $message = "$count size(s) activated successfully!";
                    break;

                case 'deactivate':
                    $count = Size::whereIn('id', $ids)->update(['is_active' => false]);
                    $message = "$count size(s) deactivated successfully!";
                    break;

                case 'delete':
                    $count = Size::whereIn('id', $ids)->delete();
                    $message = "$count size(s) deleted successfully!";
                    break;

                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid action'
                    ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to perform bulk action'
            ], 500);
        }
    }

    public function export()
    {
        $sizes = Size::orderBy('sort_order')->get();

        $filename = 'sizes_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($sizes) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Name', 'Abbreviation', 'Sort Order', 'Status', 'Created At']);

            foreach ($sizes as $size) {
                fputcsv($file, [
                    $size->id,
                    $size->name,
                    $size->abbreviation,
                    $size->sort_order,
                    $size->is_active ? 'Active' : 'Inactive',
                    $size->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}