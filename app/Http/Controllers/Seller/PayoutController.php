<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\SellerPayout;
use App\Models\SellerCommission;

class PayoutController extends Controller
{
    public function index(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        
        $query = $seller->payouts()->with('processedBy');
        
        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        $payouts = $query->latest()->paginate(20);
        
        // Calculate available balance
        $availableBalance = $seller->getPendingPayout();
        
        return view('seller.payouts.index', compact('payouts', 'availableBalance'));
    }

    public function show($id)
    {
        $seller = Auth::guard('seller')->user();
        $payout = $seller->payouts()->with('processedBy')->findOrFail($id);
        
        return view('seller.payouts.show', compact('payout'));
    }

    public function requestPayout(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        
        $request->validate([
            'amount' => 'required|numeric|min:100',
        ]);
        
        $availableBalance = $seller->getPendingPayout();
        
        if ($request->amount > $availableBalance) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient balance. Available: ₹' . number_format($availableBalance, 2),
            ], 400);
        }
        
        try {
            DB::beginTransaction();
            
            // Create payout request
            $payout = SellerPayout::create([
                'seller_id' => $seller->id,
                'amount' => $request->amount,
                'tax_amount' => 0, // Calculate if needed
                'net_amount' => $request->amount,
                'status' => 'pending',
                'request_date' => now(),
            ]);
            
            // Log activity
            $seller->logActivity('payout_requested', 'Requested payout of ₹' . number_format($request->amount, 2));
            
            // Create notification
            $seller->notifications()->create([
                'type' => 'payout_requested',
                'title' => 'Payout Request Submitted',
                'message' => 'Your payout request of ₹' . number_format($request->amount, 2) . ' has been submitted for processing.',
                'data' => json_encode(['payout_id' => $payout->id]),
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Payout request submitted successfully!',
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error submitting payout request: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function commissions(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        
        $query = $seller->commissions()->with('orderItem.order');
        
        $commissions = $query->latest()->paginate(20);
        
        // Calculate totals
        $totalEarned = $seller->commissions()->sum('seller_amount');
        $totalCommission = $seller->commissions()->sum('commission_amount');
        
        return view('seller.payouts.commissions', compact('commissions', 'totalEarned', 'totalCommission'));
    }
}
