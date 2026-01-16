<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\BulkOrder;
use Illuminate\Http\Request;

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

        BulkOrder::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you for your inquiry. We will contact you soon!'
            ]);
        }

        return redirect()->back()->with('success', 'Thank you for your inquiry. We will contact you soon!');
    }
}
