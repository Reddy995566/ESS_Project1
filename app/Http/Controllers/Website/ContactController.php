<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('website.contact');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string',
        ]);

        $contact = Contact::create($validated);

        // Send notification email to admin
        try {
            $adminEmail = config('mail.from.address');
            Mail::to($adminEmail)->send(new \App\Mail\AdminContactNotificationMail($contact));
        } catch (\Exception $e) {
            \Log::error('Admin contact notification email failed: ' . $e->getMessage());
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you for contacting us! We will get back to you soon.'
            ]);
        }

        return redirect()->back()->with('success', 'Thank you for contacting us! We will get back to you soon.');
    }
}
