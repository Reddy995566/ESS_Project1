<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{

    public function handle(Request $request, Closure $next): Response
    {
        if (!session('admin_authenticated')) {

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized - Please login again',
                    'redirect' => route('admin.login.form')
                ], 401);
            }

            return redirect()->route('admin.login.form');
        }

        return $next($request);
    }
}