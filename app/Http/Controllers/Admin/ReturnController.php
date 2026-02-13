<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductReturn;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReturnApprovedMail;
use App\Mail\ReturnRejectedMail;
use App\Mail\ReturnRefundedMail;

class ReturnController extends Controller
{
    public function index(Request $request)
    {
        // Debug logging
        \Log::info('Admin Returns Index accessed', [
            'total_returns' => ProductReturn::count(),
            'request_params' => $request->all()
        ]);

        $query = ProductReturn::with(['user', 'order', 'orderItem.product', 'seller'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Date range filter
        if ($request->filled('date_range')) {
            $dateRange = $request->date_range;
            $now = now();
            
            switch ($dateRange) {
                case 'today':
                    $query->whereDate('requested_at', $now->toDateString());
                    break;
                case 'yesterday':
                    $query->whereDate('requested_at', $now->subDay()->toDateString());
                    break;
                case 'this_week':
                    $query->whereBetween('requested_at', [$now->startOfWeek(), $now->endOfWeek()]);
                    break;
                case 'last_week':
                    $startOfLastWeek = $now->subWeek()->startOfWeek();
                    $endOfLastWeek = $now->endOfWeek();
                    $query->whereBetween('requested_at', [$startOfLastWeek, $endOfLastWeek]);
                    break;
                case 'this_month':
                    $query->whereBetween('requested_at', [$now->startOfMonth(), $now->endOfMonth()]);
                    break;
                case 'last_month':
                    $startOfLastMonth = $now->subMonth()->startOfMonth();
                    $endOfLastMonth = $now->endOfMonth();
                    $query->whereBetween('requested_at', [$startOfLastMonth, $endOfLastMonth]);
                    break;
            }
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('return_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('order', function($orderQuery) use ($search) {
                      $orderQuery->where('order_number', 'like', "%{$search}%");
                  });
            });
        }

        $perPage = $request->get('per_page', 20);
        $returns = $query->paginate($perPage);

        // Statistics
        $stats = [
            'total' => ProductReturn::count(),
            'pending' => ProductReturn::where('status', 'pending')->count(),
            'approved' => ProductReturn::where('status', 'approved')->count(),
            'refunded' => ProductReturn::where('status', 'refunded')->count(),
        ];

        // Debug logging
        \Log::info('Admin Returns Data', [
            'returns_count' => $returns->count(),
            'total_returns' => $returns->total(),
            'stats' => $stats
        ]);

        return view('admin.returns.index', compact('returns', 'stats'));
    }

    public function show($id)
    {
        $return = ProductReturn::with(['user', 'order', 'orderItem.product', 'seller', 'processedByAdmin'])
            ->findOrFail($id);

        return view('admin.returns.show', compact('return'));
    }

    public function approve(Request $request, $id)
    {
        $return = ProductReturn::findOrFail($id);

        if ($return->status !== 'pending') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Return can only be approved if it is pending.'
                ], 400);
            }
            return redirect()->back()->with('error', 'Return can only be approved if it is pending.');
        }

        DB::beginTransaction();
        try {
            $return->update([
                'status' => 'approved',
                'approved_at' => now(),
                'processed_by_admin' => Auth::guard('admin')->id(),
                'admin_notes' => $request->admin_notes
            ]);

            // Send approval email to user
            Mail::to($return->user->email)->send(new ReturnApprovedMail($return));

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Return approved successfully.'
                ]);
            }
            
            return redirect()->back()->with('success', 'Return approved successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to approve return: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to approve return: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $return = ProductReturn::findOrFail($id);

        if ($return->status !== 'pending') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Return can only be rejected if it is pending.'
                ], 400);
            }
            return redirect()->back()->with('error', 'Return can only be rejected if it is pending.');
        }

        DB::beginTransaction();
        try {
            $return->update([
                'status' => 'rejected',
                'rejected_at' => now(),
                'rejection_reason' => $request->rejection_reason,
                'processed_by_admin' => Auth::guard('admin')->id(),
                'admin_notes' => $request->admin_notes
            ]);

            // Send rejection email to user
            Mail::to($return->user->email)->send(new ReturnRejectedMail($return));

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Return rejected successfully.'
                ]);
            }
            
            return redirect()->back()->with('success', 'Return rejected successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to reject return: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to reject return: ' . $e->getMessage());
        }
    }

    public function markPickedUp(Request $request, $id)
    {
        $return = ProductReturn::findOrFail($id);

        if ($return->status !== 'approved') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Return must be approved before marking as picked up.'
                ], 400);
            }
            return redirect()->back()->with('error', 'Return must be approved before marking as picked up.');
        }

        DB::beginTransaction();
        try {
            $return->update([
                'status' => 'picked_up',
                'picked_up_at' => now(),
                'tracking_number' => $request->tracking_number,
                'admin_notes' => $request->admin_notes
            ]);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Return marked as picked up successfully.'
                ]);
            }
            
            return redirect()->back()->with('success', 'Return marked as picked up successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to mark return as picked up: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to mark return as picked up: ' . $e->getMessage());
        }
    }

    public function processRefund(Request $request, $id)
    {
        $request->validate([
            'refund_amount' => 'required|numeric|min:0'
        ]);

        $return = ProductReturn::findOrFail($id);

        if ($return->status !== 'picked_up') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Return must be picked up before processing refund.'
                ], 400);
            }
            return redirect()->back()->with('error', 'Return must be picked up before processing refund.');
        }

        DB::beginTransaction();
        try {
            $return->update([
                'status' => 'refunded',
                'refunded_at' => now(),
                'refund_amount' => $request->refund_amount,
                'admin_notes' => $request->admin_notes
            ]);

            // Here you would integrate with payment gateway to process actual refund
            // For now, we'll just mark it as refunded

            // Send refund confirmation email to user
            Mail::to($return->user->email)->send(new ReturnRefundedMail($return));

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Refund processed successfully.'
                ]);
            }
            
            return redirect()->back()->with('success', 'Refund processed successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to process refund: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to process refund: ' . $e->getMessage());
        }
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,delete',
            'return_ids' => 'required|array',
            'return_ids.*' => 'exists:returns,id'
        ]);

        $returnIds = $request->return_ids;
        $action = $request->action;

        DB::beginTransaction();
        try {
            switch ($action) {
                case 'approve':
                    ProductReturn::whereIn('id', $returnIds)
                        ->where('status', 'pending')
                        ->update([
                            'status' => 'approved',
                            'approved_at' => now(),
                            'processed_by_admin' => Auth::guard('admin')->id()
                        ]);
                    $message = 'Selected returns approved successfully.';
                    break;

                case 'reject':
                    ProductReturn::whereIn('id', $returnIds)
                        ->where('status', 'pending')
                        ->update([
                            'status' => 'rejected',
                            'rejected_at' => now(),
                            'rejection_reason' => 'Bulk rejection by admin',
                            'processed_by_admin' => Auth::guard('admin')->id()
                        ]);
                    $message = 'Selected returns rejected successfully.';
                    break;

                case 'delete':
                    ProductReturn::whereIn('id', $returnIds)->delete();
                    $message = 'Selected returns deleted successfully.';
                    break;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Bulk action failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function export(Request $request)
    {
        $query = ProductReturn::with(['user', 'order', 'orderItem.product', 'seller']);

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_range')) {
            $dateRange = $request->date_range;
            $now = now();
            
            switch ($dateRange) {
                case 'today':
                    $query->whereDate('requested_at', $now->toDateString());
                    break;
                case 'yesterday':
                    $query->whereDate('requested_at', $now->subDay()->toDateString());
                    break;
                case 'this_week':
                    $query->whereBetween('requested_at', [$now->startOfWeek(), $now->endOfWeek()]);
                    break;
                case 'last_week':
                    $startOfLastWeek = $now->subWeek()->startOfWeek();
                    $endOfLastWeek = $now->endOfWeek();
                    $query->whereBetween('requested_at', [$startOfLastWeek, $endOfLastWeek]);
                    break;
                case 'this_month':
                    $query->whereBetween('requested_at', [$now->startOfMonth(), $now->endOfMonth()]);
                    break;
                case 'last_month':
                    $startOfLastMonth = $now->subMonth()->startOfMonth();
                    $endOfLastMonth = $now->endOfMonth();
                    $query->whereBetween('requested_at', [$startOfLastMonth, $endOfLastMonth]);
                    break;
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('return_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('order', function($orderQuery) use ($search) {
                      $orderQuery->where('order_number', 'like', "%{$search}%");
                  });
            });
        }

        $returns = $query->get();

        // Generate CSV
        $filename = 'returns_export_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($returns) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'Return Number',
                'Order Number',
                'Customer Name',
                'Customer Email',
                'Product Name',
                'Quantity',
                'Amount',
                'Refund Amount',
                'Status',
                'Reason',
                'Reason Details',
                'Requested At',
                'Approved At',
                'Rejected At',
                'Picked Up At',
                'Refunded At',
                'Admin Notes',
                'Seller Notes',
                'Rejection Reason'
            ]);

            // CSV Data
            foreach ($returns as $return) {
                fputcsv($file, [
                    $return->return_number,
                    $return->order->order_number,
                    $return->user->name,
                    $return->user->email,
                    $return->orderItem->product_name,
                    $return->quantity,
                    $return->amount,
                    $return->refund_amount ?? '',
                    ucfirst($return->status),
                    $return->reason_label,
                    $return->reason_details ?? '',
                    $return->requested_at ? $return->requested_at->format('Y-m-d H:i:s') : '',
                    $return->approved_at ? $return->approved_at->format('Y-m-d H:i:s') : '',
                    $return->rejected_at ? $return->rejected_at->format('Y-m-d H:i:s') : '',
                    $return->picked_up_at ? $return->picked_up_at->format('Y-m-d H:i:s') : '',
                    $return->refunded_at ? $return->refunded_at->format('Y-m-d H:i:s') : '',
                    $return->admin_notes ?? '',
                    $return->seller_notes ?? '',
                    $return->rejection_reason ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}