<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SellerWallet;
use App\Models\SellerWalletTransaction;
use App\Models\SellerPayout;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        
        // Get or create wallet
        $wallet = $seller->wallet ?? $this->createWallet($seller);
        
        // Get transactions with filters
        $query = $wallet->transactions()->with(['order', 'payout']);
        
        // Filter by type
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }
        
        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }
        
        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Search by transaction ID or description
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_id', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }
        
        $transactions = $query->latest()->paginate(25);
        
        // Calculate statistics
        $totalCredits = $wallet->transactions()->credit()->completed()->sum('amount');
        $totalDebits = $wallet->transactions()->debit()->completed()->sum('amount');
        $totalTransactions = $wallet->transactions()->count();
        $pendingTransactions = $wallet->transactions()->where('status', 'pending')->count();
        
        return view('seller.wallet.index', compact(
            'wallet',
            'transactions',
            'totalCredits',
            'totalDebits',
            'totalTransactions',
            'pendingTransactions'
        ));
    }

    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100|max:50000',
            'bank_account' => 'required|string',
            'ifsc_code' => 'required|string',
            'account_holder' => 'required|string',
        ]);

        $seller = Auth::guard('seller')->user();
        $wallet = $seller->wallet;

        if (!$wallet || !$wallet->canWithdraw($request->amount)) {
            return back()->with('error', 'Insufficient wallet balance for withdrawal.');
        }

        try {
            // Create payout request
            $payout = SellerPayout::create([
                'seller_id' => $seller->id,
                'payout_number' => SellerPayout::generatePayoutNumber(),
                'amount' => $request->amount,
                'commission_amount' => 0.00, // No commission deduction for withdrawals
                'net_amount' => $request->amount, // No fees for now
                'period_start' => now()->startOfMonth(),
                'period_end' => now()->endOfMonth(),
                'status' => 'pending',
                'payment_method' => 'bank_transfer',
                'notes' => json_encode([
                    'bank_account' => $request->bank_account,
                    'ifsc_code' => $request->ifsc_code,
                    'account_holder' => $request->account_holder,
                ]),
            ]);

            // Create wallet transaction
            $wallet->deductFunds(
                $request->amount,
                'withdrawal',
                'Withdrawal request #' . $payout->id,
                ['payout_id' => $payout->id]
            );

            return back()->with('success', 'Withdrawal request submitted successfully. It will be processed within 2-3 business days.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to process withdrawal request: ' . $e->getMessage());
        }
    }

    public function transactions(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        $wallet = $seller->wallet;

        if (!$wallet) {
            return response()->json(['transactions' => []]);
        }

        $transactions = $wallet->transactions()
            ->with(['order', 'payout'])
            ->latest()
            ->limit(50)
            ->get();

        return response()->json([
            'transactions' => $transactions->map(function($transaction) {
                return [
                    'id' => $transaction->id,
                    'transaction_id' => $transaction->transaction_id,
                    'type' => $transaction->type,
                    'category' => $transaction->category,
                    'amount' => $transaction->formatted_amount,
                    'description' => $transaction->description,
                    'status' => $transaction->status,
                    'created_at' => $transaction->created_at->format('M d, Y h:i A'),
                    'type_icon' => $transaction->type_icon,
                    'category_icon' => $transaction->category_icon,
                ];
            })
        ]);
    }

    private function createWallet($seller)
    {
        return SellerWallet::create([
            'seller_id' => $seller->id,
            'balance' => 0.00,
            'pending_balance' => 0.00,
            'total_earned' => 0.00,
            'total_withdrawn' => 0.00,
            'is_active' => true,
        ]);
    }
}