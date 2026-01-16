<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BulkOrder;
use Illuminate\Http\Request;

class BulkOrderController extends Controller
{
    public function index()
    {
        $bulkOrders = BulkOrder::latest()->paginate(20);

        $stats = [
            'total' => BulkOrder::count(),
            'new' => BulkOrder::where('status', 'new')->count(),
        ];

        return view('admin.bulk-orders.index', compact('bulkOrders', 'stats'));
    }

    public function destroy(BulkOrder $bulkOrder)
    {
        $bulkOrder->delete();
        return redirect()->back()->with('success', 'Bulk order deleted successfully.');
    }
}
