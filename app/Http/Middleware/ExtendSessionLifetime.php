<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExtendSessionLifetime
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated and has remember token
        if (Auth::check() && Auth::user()->remember_token) {
            // Extend session lifetime to 1 year (525600 minutes)
            config(['session.lifetime' => 525600]);
            
            // Set cookie lifetime to 1 year as well
            $request->session()->put('extended_session', true);
        }

        // Check for seller authentication
        if (Auth::guard('seller')->check() && Auth::guard('seller')->user()->remember_token) {
            config(['session.lifetime' => 525600]);
            $request->session()->put('extended_session', true);
        }

        // Check for admin authentication
        if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->remember_token) {
            config(['session.lifetime' => 525600]);
            $request->session()->put('extended_session', true);
        }

        return $next($request);
    }
}