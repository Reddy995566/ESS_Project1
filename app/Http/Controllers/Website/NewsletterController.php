<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:newsletters,email',
        ], [
            'email.unique' => 'This email is already subscribed to our newsletter.',
        ]);

        $newsletter = Newsletter::create($validated);

        // Send notification to admin
        try {
            $adminEmail = config('mail.from.address');
            Mail::to($adminEmail)->send(new \App\Mail\AdminNewsletterNotificationMail($newsletter));
        } catch (\Exception $e) {
            \Log::error('Admin newsletter notification failed: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Thank you for subscribing to our newsletter!'
        ]);
    }
}
