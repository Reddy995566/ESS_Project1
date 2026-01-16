<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    use LogsActivity;
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $statusFilter = $request->get('status_filter', 'all');
        $sortField = $request->get('sort_field', 'id');
        $sortDirection = $request->get('sort_direction', 'desc');
        $perPage = $request->get('per_page', 10);

        $query = Coupon::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', '%' . $search . '%')
                  ->orWhere('discount_type', 'like', '%' . $search . '%');
            });
        }

        if ($statusFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($statusFilter === 'inactive') {
            $query->where('is_active', false);
        }

        $query->orderBy($sortField, $sortDirection);
        $coupons = $query->paginate($perPage);

        $totalCoupons = Coupon::count();
        $activeCoupons = Coupon::where('is_active', true)->count();
        
        // Count expired coupons (end_date in past)
        $expiredCoupons = Coupon::where('end_date', '<', now())
            ->whereNotNull('end_date')
            ->count();
        
        // Total redemptions/usage across all coupons
        $totalUsage = Coupon::sum('used_count');

        return view('admin.coupons.index', [
            'coupons' => $coupons,
            'totalCoupons' => $totalCoupons,
            'activeCoupons' => $activeCoupons,
            'expiredCoupons' => $expiredCoupons,
            'totalUsage' => $totalUsage,
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
            'code' => 'required|string|max:255|unique:coupons,code,NULL,id,deleted_at,NULL',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['used_count'] = 0;

        try {
            $coupon = Coupon::create($validated);

            self::logActivity('created', "Created new coupon: {$coupon->code}", $coupon);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Coupon created successfully!',
                    'coupon' => [
                        'id' => $coupon->id,
                        'code' => $coupon->code,
                        'discount_type' => $coupon->discount_type,
                        'discount_value' => $coupon->discount_value,
                        'min_purchase' => $coupon->min_purchase,
                        'max_discount' => $coupon->max_discount,
                        'start_date' => $coupon->start_date ? $coupon->start_date->format('Y-m-d') : null,
                        'end_date' => $coupon->end_date ? $coupon->end_date->format('Y-m-d') : null,
                        'usage_limit' => $coupon->usage_limit,
                        'used_count' => $coupon->used_count,
                        'is_active' => (bool) $coupon->is_active,
                        'created_at' => $coupon->created_at->format('M d, Y'),
                    ]
                ]);
            }

            return redirect()->route('admin.coupons.index')->with('success', 'Coupon created successfully!');
        } catch (\Exception $e) {
            \Log::error('Coupon creation failed', ['error' => $e->getMessage()]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create coupon. Error: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()->withErrors(['error' => 'Failed to create coupon. Please try again.']);
        }
    }

    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:coupons,code,' . $coupon->id . ',id,deleted_at,NULL',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        try {
            $oldData = $coupon->only(['code', 'discount_type', 'discount_value', 'is_active']);
            $coupon->update($validated);

            self::logActivity('updated', "Updated coupon: {$coupon->code}", $coupon, $oldData, $validated);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Coupon updated successfully!',
                    'coupon' => [
                        'id' => $coupon->id,
                        'code' => $coupon->code,
                        'discount_type' => $coupon->discount_type,
                        'discount_value' => $coupon->discount_value,
                        'min_purchase' => $coupon->min_purchase,
                        'max_discount' => $coupon->max_discount,
                        'start_date' => $coupon->start_date ? $coupon->start_date->format('Y-m-d') : null,
                        'end_date' => $coupon->end_date ? $coupon->end_date->format('Y-m-d') : null,
                        'usage_limit' => $coupon->usage_limit,
                        'used_count' => $coupon->used_count,
                        'is_active' => (bool) $coupon->is_active,
                        'created_at' => $coupon->created_at->format('M d, Y'),
                    ]
                ]);
            }

            return redirect()->route('admin.coupons.index')->with('success', 'Coupon updated successfully!');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update coupon. Error: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()->withErrors(['error' => 'Failed to update coupon. Please try again.']);
        }
    }

    public function destroy(Coupon $coupon)
    {
        try {
            $code = $coupon->code;
            $coupon->delete();

            self::logActivity('deleted', "Deleted coupon: {$code}");

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Coupon deleted successfully!'
                ]);
            }

            return redirect()->route('admin.coupons.index')
                ->with('success', 'Coupon deleted successfully!');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete coupon. Please try again.'
                ], 500);
            }

            return redirect()->route('admin.coupons.index')
                ->with('error', 'Failed to delete coupon. Please try again.');
        }
    }

    public function toggle(Request $request, Coupon $coupon)
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
            $coupon->update([$field => $boolValue]);

            $status = $boolValue ? 'activated' : 'deactivated';
            self::logActivity('updated', "Changed coupon {$coupon->code} status to: {$status}", $coupon);

            $message = 'Coupon status updated successfully!';

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update coupon'
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
                    'message' => 'No coupons selected'
                ], 400);
            }

            $count = 0;

            switch ($action) {
                case 'activate':
                    $count = Coupon::whereIn('id', $ids)->update(['is_active' => true]);
                    $message = "$count coupon(s) activated successfully!";
                    self::logActivity('updated', "Bulk activated {$count} coupon(s)");
                    break;

                case 'deactivate':
                    $count = Coupon::whereIn('id', $ids)->update(['is_active' => false]);
                    $message = "$count coupon(s) deactivated successfully!";
                    self::logActivity('updated', "Bulk deactivated {$count} coupon(s)");
                    break;

                case 'delete':
                    $count = Coupon::whereIn('id', $ids)->delete();
                    $message = "$count coupon(s) deleted successfully!";
                    self::logActivity('deleted', "Bulk deleted {$count} coupon(s)");
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
        $coupons = Coupon::orderBy('id', 'desc')->get();

        $filename = 'coupons_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($coupons) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Code', 'Type', 'Value', 'Min Purchase', 'Max Discount', 'Start Date', 'End Date', 'Usage Limit', 'Used Count', 'Status', 'Created At']);

            foreach ($coupons as $coupon) {
                fputcsv($file, [
                    $coupon->id,
                    $coupon->code,
                    ucfirst($coupon->discount_type),
                    $coupon->discount_value,
                    $coupon->min_purchase ?? 'N/A',
                    $coupon->max_discount ?? 'N/A',
                    $coupon->start_date ? $coupon->start_date->format('Y-m-d') : 'N/A',
                    $coupon->end_date ? $coupon->end_date->format('Y-m-d') : 'N/A',
                    $coupon->usage_limit ?? 'Unlimited',
                    $coupon->used_count,
                    $coupon->is_active ? 'Active' : 'Inactive',
                    $coupon->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
