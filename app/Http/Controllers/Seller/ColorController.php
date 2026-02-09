<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Color;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ColorController extends Controller
{
    use LogsActivity;
    public function index(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        $search = $request->get('search', '');
        $statusFilter = $request->get('status_filter', 'all');
        $sortField = $request->get('sort_field', 'id');
        $sortDirection = $request->get('sort_direction', 'desc');
        $perPage = $request->get('per_page', 10);

        $query = Color::query()->where('seller_id', $seller->id);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('hex_code', 'like', '%' . $search . '%');
            });
        }

        if ($statusFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($statusFilter === 'inactive') {
            $query->where('is_active', false);
        }

        $query->orderBy($sortField, $sortDirection);
        $colors = $query->paginate($perPage);

        $totalColors = Color::where('seller_id', $seller->id)->count();
        $activeColors = Color::where('seller_id', $seller->id)->where('is_active', true)->count();

        return view('seller.colors.index', [
            'colors' => $colors,
            'totalColors' => $totalColors,
            'activeColors' => $activeColors,
            'search' => $search,
            'statusFilter' => $statusFilter,
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
            'perPage' => $perPage,
        ]);
    }

    public function store(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:colors,name,NULL,id,deleted_at,NULL',
            'hex_code' => 'nullable|string|regex:/^#[0-9A-F]{6}$/i',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['seller_id'] = $seller->id;
        $validated['is_active'] = $request->has('is_active');

        try {
            $color = Color::create($validated);

            self::logActivity('created', "Created new color: {$color->name}", $color);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Color created successfully!',
                    'color' => [
                        'id' => $color->id,
                        'name' => $color->name,
                        'hex_code' => $color->hex_code,
                        'sort_order' => $color->sort_order,
                        'is_active' => (bool) $color->is_active,
                        'products_count' => 0,
                        'created_at' => $color->created_at->format('M d, Y'),
                    ]
                ]);
            }

            return redirect()->route('seller.colors.index')->with('success', 'Color created successfully!');
        } catch (\Exception $e) {
            \Log::error('Color creation failed', ['error' => $e->getMessage()]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create color. Error: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()->withErrors(['error' => 'Failed to create color. Please try again.']);
        }
    }

    public function update(Request $request, Color $color)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:colors,name,' . $color->id . ',id,deleted_at,NULL',
            'hex_code' => 'nullable|string|regex:/^#[0-9A-F]{6}$/i',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        try {
            $oldData = $color->only(['name', 'hex_code']);
            $color->update($validated);

            self::logActivity('updated', "Updated color: {$color->name}", $color, $oldData, $validated);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Color updated successfully!',
                    'color' => [
                        'id' => $color->id,
                        'name' => $color->name,
                        'hex_code' => $color->hex_code,
                        'sort_order' => $color->sort_order,
                        'is_active' => (bool) $color->is_active,
                        'products_count' => 0,
                        'created_at' => $color->created_at->format('M d, Y'),
                    ]
                ]);
            }

            return redirect()->route('seller.colors.index')->with('success', 'Color updated successfully!');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update color. Error: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()->withErrors(['error' => 'Failed to update color. Please try again.']);
        }
    }

    public function destroy(Color $color)
    {
        try {
            $name = $color->name;
            $color->delete();

            self::logActivity('deleted', "Deleted color: {$name}");

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Color deleted successfully!'
                ]);
            }

            return redirect()->route('seller.colors.index')
                ->with('success', 'Color deleted successfully!');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete color. Please try again.'
                ], 500);
            }

            return redirect()->route('seller.colors.index')
                ->with('error', 'Failed to delete color. Please try again.');
        }
    }

    public function toggle(Request $request, Color $color)
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
            $color->update([$field => $boolValue]);

            $message = 'Color status updated successfully!';

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update color'
            ], 500);
        }
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,activate,deactivate',
            'ids' => 'required|array',
            'ids.*' => 'exists:colors,id'
        ]);

        $action = $request->input('action');
        $ids = $request->input('ids');
        $colors = Color::whereIn('id', $ids);

        try {
            switch ($action) {
                case 'delete':
                    $count = $colors->count();
                    $colors->delete();
                    $message = "Successfully deleted {$count} colors";
                    break;

                case 'activate':
                    $count = $colors->update(['is_active' => true]);
                    $message = "Successfully activated {$count} colors";
                    break;

                case 'deactivate':
                    $count = $colors->update(['is_active' => false]);
                    $message = "Successfully deactivated {$count} colors";
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
        $query = Color::query();

        if ($request->filled('status')) {
            $query->where('is_active', $request->status == '1');
        }

        $colors = $query->orderBy('sort_order')->get();

        $filename = 'colors_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($colors) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'ID', 'Name', 'Hex Code', 'Sort Order', 'Active', 
                'Products Count', 'Created At', 'Updated At'
            ]);

            foreach ($colors as $color) {
                fputcsv($file, [
                    $color->id,
                    $color->name,
                    $color->hex_code,
                    $color->sort_order,
                    $color->is_active ? 'Yes' : 'No',
                    $color->products()->count(),
                    $color->created_at->format('Y-m-d H:i:s'),
                    $color->updated_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}