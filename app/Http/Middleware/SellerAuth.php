<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('seller')->check()) {
            return redirect()->route('seller.login')->with('error', 'Please login to access seller portal');
        }

        $seller = Auth::guard('seller')->user();

        // Check if seller is approved/active
        if (!$seller->isApproved() && !$seller->isActive()) {
            Auth::guard('seller')->logout();
            
            if ($seller->isPending()) {
                return redirect()->route('seller.login')->with('warning', 'Your application is under review. You will be notified once approved.');
            }
            
            if ($seller->isRejected()) {
                return redirect()->route('seller.login')->with('error', 'Your application has been rejected. Please contact support.');
            }
            
            if ($seller->isSuspended()) {
                return redirect()->route('seller.login')->with('error', 'Your account has been suspended. Please contact support.');
            }
        }

        return $next($request);
    }
}
