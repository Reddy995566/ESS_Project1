<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    use LogsActivity;
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $statusFilter = $request->get('status_filter', 'all');
        $sortField = $request->get('sort_field', 'id');
        $sortDirection = $request->get('sort_direction', 'desc');
        $perPage = $request->get('per_page', 10);

        $query = Brand::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($statusFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($statusFilter === 'inactive') {
            $query->where('is_active', false);
        }

        $query->orderBy($sortField, $sortDirection);
        $brands = $query->paginate($perPage);

        $totalBrands = Brand::count();
        $activeBrands = Brand::where('is_active', true)->count();

        return view('admin.brands.index', [
            'brands' => $brands,
            'totalBrands' => $totalBrands,
            'activeBrands' => $activeBrands,
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
            'name' => 'required|string|max:255|unique:brands,name,NULL,id,deleted_at,NULL',
            'description' => 'nullable|string|max:1000',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        try {
            $brand = Brand::create($validated);

            self::logActivity('created', "Created new brand: {$brand->name}", $brand);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Brand created successfully!',
                    'brand' => [
                        'id' => $brand->id,
                        'name' => $brand->name,
                        'description' => $brand->description,
                        'sort_order' => $brand->sort_order,
                        'is_active' => (bool) $brand->is_active,
                        'created_at' => $brand->created_at->format('M d, Y'),
                    ]
                ]);
            }

            return redirect()->route('admin.brands.index')->with('success', 'Brand created successfully!');
        } catch (\Exception $e) {
            \Log::error('Brand creation failed', ['error' => $e->getMessage()]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create brand. Error: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()->withErrors(['error' => 'Failed to create brand. Please try again.']);
        }
    }

    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id . ',id,deleted_at,NULL',
            'description' => 'nullable|string|max:1000',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        try {
            $oldData = $brand->only(['name', 'is_active']);
            $brand->update($validated);

            self::logActivity('updated', "Updated brand: {$brand->name}", $brand, $oldData, $validated);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Brand updated successfully!',
                    'brand' => [
                        'id' => $brand->id,
                        'name' => $brand->name,
                        'description' => $brand->description,
                        'sort_order' => $brand->sort_order,
                        'is_active' => (bool) $brand->is_active,
                        'created_at' => $brand->created_at->format('M d, Y'),
                    ]
                ]);
            }

            return redirect()->route('admin.brands.index')->with('success', 'Brand updated successfully!');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update brand. Error: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()->withErrors(['error' => 'Failed to update brand. Please try again.']);
        }
    }

    public function destroy(Brand $brand)
    {
        try {
            $name = $brand->name;
            $brand->delete();

            self::logActivity('deleted', "Deleted brand: {$name}");

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Brand deleted successfully!'
                ]);
            }

            return redirect()->route('admin.brands.index')
                ->with('success', 'Brand deleted successfully!');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete brand. Please try again.'
                ], 500);
            }

            return redirect()->route('admin.brands.index')
                ->with('error', 'Failed to delete brand. Please try again.');
        }
    }

    public function toggle(Request $request, Brand $brand)
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
            $brand->update([$field => $boolValue]);

            $message = 'Brand status updated successfully!';

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update brand'
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
                    'message' => 'No brands selected'
                ], 400);
            }

            $count = 0;

            switch ($action) {
                case 'activate':
                    $count = Brand::whereIn('id', $ids)->update(['is_active' => true]);
                    $message = "$count brand(s) activated successfully!";
                    break;

                case 'deactivate':
                    $count = Brand::whereIn('id', $ids)->update(['is_active' => false]);
                    $message = "$count brand(s) deactivated successfully!";
                    break;

                case 'delete':
                    $count = Brand::whereIn('id', $ids)->delete();
                    $message = "$count brand(s) deleted successfully!";
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
        $brands = Brand::orderBy('sort_order')->get();

        $filename = 'brands_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($brands) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Name', 'Description', 'Sort Order', 'Status', 'Created At']);

            foreach ($brands as $brand) {
                fputcsv($file, [
                    $brand->id,
                    $brand->name,
                    $brand->description,
                    $brand->sort_order,
                    $brand->is_active ? 'Active' : 'Inactive',
                    $brand->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}