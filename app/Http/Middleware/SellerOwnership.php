<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerOwnership
{
    /**
     * Handle an incoming request to ensure seller can only access their own data
     */
    public function handle(Request $request, Closure $next, $model = null)
    {
        $seller = Auth::guard('seller')->user();
        
        if (!$seller) {
            return redirect()->route('seller.login');
        }

        // Get the model instance from route parameters
        $routeParams = $request->route()->parameters();
        
        foreach ($routeParams as $param) {
            if (is_object($param) && method_exists($param, 'getAttribute')) {
                $sellerId = $param->getAttribute('seller_id');
                
                if ($sellerId && $sellerId != $seller->id) {
                    abort(403, 'Unauthorized access to this resource.');
                }
            }
        }

        return $next($request);
    }
}