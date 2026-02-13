<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SellerPayout;
use App\Models\Seller;
use App\Models\SellerWallet;
use App\Models\SellerWalletTransaction;
use Illuminate\Support\Facades\DB;

class SellerPayoutController extends Controller
{
    public function index(Request $request)
    {
        $query = SellerPayout::with(['seller.user']);

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by seller
        if ($request->has('seller_id') && $request->seller_id != '') {
            $query->where('seller_id', $request->seller_id);
        }

        // Search by payout number or seller name
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('payout_number', 'like', '%' . $search . '%')
                  ->orWhereHas('seller', function($sq) use ($search) {
                      $sq->where('business_name', 'like', '%' . $search . '%')
                        ->orWhereHas('user', function($uq) use ($search) {
                            $uq->where('name', 'like', '%' . $search . '%');
                        });
                  });
            });
        }

        $payouts = $query->latest()->paginate(25);

        // Calculate statistics
        $totalPending = SellerPayout::where('status', 'pending')->sum('amount');
        $totalProcessing = SellerPayout::where('status', 'processing')->sum('amount');
        $totalCompleted = SellerPayout::where('status', 'completed')->sum('amount');
        $totalPayouts = SellerPayout::count();

        // Get sellers for filter dropdown
        $sellers = Seller::with('user')->where('status', 'approved')->get();

        return view('admin.seller-payouts.index', compact(
            'payouts',
            'totalPending',
            'totalProcessing', 
            'totalCompleted',
            'totalPayouts',
            'sellers'
        ));
    }

    public function show(SellerPayout $payout)
    {
        $payout->load(['seller.user', 'processedBy']);
        
        // Get bank details from notes
        $bankDetails = json_decode($payout->notes, true) ?? [];
        
        return view('admin.seller-payouts.show', compact('payout', 'bankDetails'));
    }

    public function approve(Request $request, SellerPayout $payout)
    {
        \Log::info('Approve method called', [
            'payout_id' => $payout->id,
            'status' => $payout->status,
            'expects_json' => $request->expectsJson(),
            'headers' => $request->headers->all()
        ]);

        if ($payout->status !== 'pending') {
            \Log::warning('Payout not pending', ['status' => $payout->status]);
            return response()->json(['success' => false, 'message' => 'Only pending payouts can be approved.']);
        }

        try {
            DB::beginTransaction();

            $payout->update([
                'status' => 'processing',
                'processed_by' => auth('admin')->id(),
                'processed_at' => now(),
                'notes' => $payout->notes . "\n\nApproved by admin on " . now()->format('Y-m-d H:i:s')
            ]);

            // Send notification to seller
            $payout->seller->sendNotification(
                'payout_processed',
                'Payout Approved',
                'Your payout request of ₹' . number_format($payout->amount, 2) . ' has been approved and is being processed.',
                json_encode(['payout_id' => $payout->id, 'amount' => $payout->amount])
            );

            // Send email notification
            try {
                \Mail::to($payout->seller->user->email)->send(new \App\Mail\PayoutApprovedMail($payout));
            } catch (\Exception $e) {
                \Log::error('Failed to send payout approved email', [
                    'payout_id' => $payout->id,
                    'error' => $e->getMessage()
                ]);
            }

            DB::commit();

            \Log::info('Payout approved successfully', ['payout_id' => $payout->id]);

            return response()->json(['success' => true, 'message' => 'Payout approved successfully.']);

        } catch (\Exception $e) {
            DB::rollback();
            
            \Log::error('Failed to approve payout', [
                'payout_id' => $payout->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json(['success' => false, 'message' => 'Failed to approve payout: ' . $e->getMessage()]);
        }
    }

    public function reject(Request $request, SellerPayout $payout)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        if (!in_array($payout->status, ['pending', 'processing'])) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Only pending or processing payouts can be rejected.']);
            }
            return back()->with('error', 'Only pending or processing payouts can be rejected.');
        }

        try {
            DB::beginTransaction();

            // Refund amount back to seller wallet
            $wallet = $payout->seller->wallet;
            if ($wallet) {
                $wallet->addFunds(
                    $payout->amount,
                    'refund',
                    'Payout rejected - funds refunded #' . $payout->payout_number,
                    ['payout_id' => $payout->id]
                );
            }

            $payout->update([
                'status' => 'failed',
                'processed_by' => auth('admin')->id(),
                'processed_at' => now(),
                'notes' => $payout->notes . "\n\nRejected by admin on " . now()->format('Y-m-d H:i:s') . "\nReason: " . $request->rejection_reason
            ]);

            // Send notification to seller
            $payout->seller->sendNotification(
                'payout_rejected',
                'Payout Rejected',
                'Your payout request of ₹' . number_format($payout->amount, 2) . ' has been rejected. Reason: ' . $request->rejection_reason,
                json_encode(['payout_id' => $payout->id, 'amount' => $payout->amount, 'reason' => $request->rejection_reason])
            );

            // Send email notification
            try {
                \Mail::to($payout->seller->user->email)->send(new \App\Mail\PayoutRejectedMail($payout, $request->rejection_reason));
            } catch (\Exception $e) {
                \Log::error('Failed to send payout rejected email', [
                    'payout_id' => $payout->id,
                    'error' => $e->getMessage()
                ]);
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Payout rejected and funds refunded.']);
            }

            return back()->with('success', 'Payout rejected and funds refunded to seller wallet.');

        } catch (\Exception $e) {
            DB::rollback();
            
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to reject payout: ' . $e->getMessage()]);
            }
            
            return back()->with('error', 'Failed to reject payout: ' . $e->getMessage());
        }
    }

    public function complete(Request $request, SellerPayout $payout)
    {
        $request->validate([
            'transaction_id' => 'required|string|max:255',
            'transaction_date' => 'required|date',
            'notes' => 'nullable|string|max:1000'
        ]);

        if ($payout->status !== 'processing') {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Only processing payouts can be completed.']);
            }
            return back()->with('error', 'Only processing payouts can be completed.');
        }

        try {
            DB::beginTransaction();

            $payout->update([
                'status' => 'completed',
                'transaction_id' => $request->transaction_id,
                'transaction_date' => $request->transaction_date,
                'processed_by' => auth('admin')->id(),
                'processed_at' => now(),
                'notes' => $payout->notes . "\n\nCompleted by admin on " . now()->format('Y-m-d H:i:s') . 
                          "\nTransaction ID: " . $request->transaction_id . 
                          ($request->notes ? "\nNotes: " . $request->notes : '')
            ]);

            // Update seller wallet total withdrawn
            $wallet = $payout->seller->wallet;
            if ($wallet) {
                $wallet->increment('total_withdrawn', $payout->amount);
            }

            // Send notification to seller
            $payout->seller->sendNotification(
                'payout_processed',
                'Payout Completed',
                'Your payout of ₹' . number_format($payout->amount, 2) . ' has been successfully transferred to your bank account. Transaction ID: ' . $request->transaction_id,
                json_encode(['payout_id' => $payout->id, 'amount' => $payout->amount, 'transaction_id' => $request->transaction_id])
            );

            // Send email notification
            try {
                \Mail::to($payout->seller->user->email)->send(new \App\Mail\PayoutCompletedMail($payout));
            } catch (\Exception $e) {
                \Log::error('Failed to send payout completed email', [
                    'payout_id' => $payout->id,
                    'error' => $e->getMessage()
                ]);
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Payout completed successfully.']);
            }

            return back()->with('success', 'Payout marked as completed successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to complete payout: ' . $e->getMessage()]);
            }
            
            return back()->with('error', 'Failed to complete payout: ' . $e->getMessage());
        }
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,complete',
            'payout_ids' => 'required|array',
            'payout_ids.*' => 'exists:seller_payouts,id'
        ]);

        $payouts = SellerPayout::whereIn('id', $request->payout_ids)->get();
        $successCount = 0;
        $errorCount = 0;

        foreach ($payouts as $payout) {
            try {
                switch ($request->action) {
                    case 'approve':
                        if ($payout->status === 'pending') {
                            $payout->update([
                                'status' => 'processing',
                                'processed_by' => auth('admin')->id(),
                                'processed_at' => now()
                            ]);
                            $successCount++;
                        }
                        break;

                    case 'reject':
                        if (in_array($payout->status, ['pending', 'processing'])) {
                            // Refund to wallet
                            $wallet = $payout->seller->wallet;
                            if ($wallet) {
                                $wallet->addFunds(
                                    $payout->amount,
                                    'refund',
                                    'Bulk rejection - funds refunded #' . $payout->payout_number,
                                    ['payout_id' => $payout->id]
                                );
                            }
                            
                            $payout->update([
                                'status' => 'failed',
                                'processed_by' => auth('admin')->id(),
                                'processed_at' => now()
                            ]);
                            $successCount++;
                        }
                        break;
                }
            } catch (\Exception $e) {
                $errorCount++;
            }
        }

        $message = "Bulk action completed. Success: {$successCount}";
        if ($errorCount > 0) {
            $message .= ", Errors: {$errorCount}";
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return back()->with('success', $message);
    }
}