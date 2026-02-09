<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductReturn;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReturnRequestedMail;

class ReturnController extends Controller
{
    public function create(Request $request, $orderId, $itemId)
    {
        $order = Order::with(['items.product'])->findOrFail($orderId);
        
        // Authorization check
        if (Auth::id() != $order->user_id) {
            abort(403);
        }

        $orderItem = $order->items()->findOrFail($itemId);

        // Check if return is allowed (order delivered and within 3 days)
        if ($order->status !== 'delivered') {
            return redirect()->back()->with('error', 'Returns are only allowed for delivered orders.');
        }

        if (!$order->delivered_at) {
            return redirect()->back()->with('error', 'Return not available - delivery date not confirmed.');
        }

        $threeDaysAgo = now()->subDays(3);
        if ($order->delivered_at->lt($threeDaysAgo)) {
            return redirect()->back()->with('error', 'Return period has expired. Returns are only allowed within 3 days of delivery.');
        }

        // Check if return already exists for this item
        $existingReturn = ProductReturn::where('order_item_id', $itemId)->first();
        if ($existingReturn) {
            return redirect()->back()->with('error', 'Return request already exists for this item.');
        }

        return view('website.user.returns.create', compact('order', 'orderItem'));
    }

    public function store(Request $request, $orderId, $itemId)
    {
        $request->validate([
            'reason' => 'required|in:defective,wrong_item,size_issue,quality_issue,not_as_described,damaged_shipping,changed_mind,other',
            'reason_details' => 'nullable|string|max:1000',
            'quantity' => 'required|integer|min:1',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $order = Order::with(['items.product'])->findOrFail($orderId);
        
        // Authorization check
        if (Auth::id() != $order->user_id) {
            abort(403);
        }

        $orderItem = $order->items()->findOrFail($itemId);

        // Validate quantity
        if ($request->quantity > $orderItem->quantity) {
            return redirect()->back()->with('error', 'Return quantity cannot exceed ordered quantity.');
        }

        // Check if return is still allowed
        if ($order->status !== 'delivered' || !$order->delivered_at || $order->delivered_at->lt(now()->subDays(3))) {
            return redirect()->back()->with('error', 'Return period has expired.');
        }

        // Check if return already exists
        $existingReturn = ProductReturn::where('order_item_id', $itemId)->first();
        if ($existingReturn) {
            return redirect()->back()->with('error', 'Return request already exists for this item.');
        }

        DB::beginTransaction();
        try {
            // Handle image uploads (if any)
            $images = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('returns'), $filename);
                    $images[] = 'returns/' . $filename;
                }
            }

            // Calculate return amount
            $returnAmount = ($orderItem->price * $request->quantity);

            // Create return request
            $return = ProductReturn::create([
                'order_id' => $order->id,
                'order_item_id' => $orderItem->id,
                'user_id' => Auth::id(),
                'seller_id' => $orderItem->seller_id,
                'product_id' => $orderItem->product_id,
                'quantity' => $request->quantity,
                'amount' => $returnAmount,
                'reason' => $request->reason,
                'reason_details' => $request->reason_details,
                'images' => $images,
                'status' => 'pending',
                'refund_method' => 'original_payment',
                'pickup_address' => $order->full_address,
            ]);

            // Send return request email to user
            Mail::to(Auth::user()->email)->send(new ReturnRequestedMail($return));

            DB::commit();

            return redirect()->route('user.returns.show', $return->id)
                ->with('success', 'Return request submitted successfully. You will be notified once it is processed.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to submit return request. Please try again.');
        }
    }

    public function show($id)
    {
        $return = ProductReturn::with(['order', 'orderItem.product', 'seller'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('website.user.returns.show', compact('return'));
    }

    public function index()
    {
        $returns = ProductReturn::with(['order', 'orderItem.product'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('website.user.returns.index', compact('returns'));
    }

    public function cancel($id)
    {
        $return = ProductReturn::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($id);

        $return->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Return request cancelled successfully.');
    }
}