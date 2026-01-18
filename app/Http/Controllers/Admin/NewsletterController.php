<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function index()
    {
        $newsletters = Newsletter::latest()->paginate(20);
        $stats = [
            'total' => Newsletter::count(),
            'active' => Newsletter::where('status', 'active')->count(),
            'unsubscribed' => Newsletter::where('status', 'unsubscribed')->count(),
        ];
        return view('admin.newsletters.index', compact('newsletters', 'stats'));
    }

    public function destroy(Newsletter $newsletter)
    {
        $newsletter->delete();
        return redirect()->back()->with('success', 'Subscriber deleted successfully.');
    }
}
