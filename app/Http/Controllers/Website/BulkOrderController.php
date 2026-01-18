<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\BulkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BulkOrderController extends Controller
{
    public function index()
    {
        return view('website.bulk-orders');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'business_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'city_state' => 'required|string|max:255',
            'products_required' => 'required|string',
            'quantity_required' => 'required|string|max:255',
        ]);

        $bulkOrder = BulkOrder::create($validated);

        // Send notification email to admin
        try {
            $adminEmail = config('mail.from.address');
            Mail::to($adminEmail)->send(new \App\Mail\AdminBulkOrderNotificationMail($bulkOrder));
        } catch (\Exception $e) {
            \Log::error('Admin bulk order notification email failed: ' . $e->getMessage());
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you for your inquiry. We will contact you soon!'
            ]);
        }

        return redirect()->back()->with('success', 'Thank you for your inquiry. We will contact you soon!');
    }
}
