<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::latest()->paginate(20);

        $stats = [
            'total' => Contact::count(),
            'new' => Contact::where('status', 'new')->count(),
            'read' => Contact::where('status', 'read')->count(),
        ];

        return view('admin.contacts.index', compact('contacts', 'stats'));
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return redirect()->back()->with('success', 'Contact inquiry deleted successfully.');
    }

    public function toggleStatus(Contact $contact)
    {
        $contact->status = $contact->status === 'new' ? 'read' : 'new';
        $contact->save();

        return response()->json([
            'success' => true,
            'status' => $contact->status,
            'message' => 'Status updated successfully'
        ]);
    }
}
