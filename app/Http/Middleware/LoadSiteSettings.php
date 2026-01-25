<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Setting;

class LoadSiteSettings
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Load site name from database and set it to config
        $siteName = Setting::where('key', 'site_name')->value('value');
        
        if ($siteName) {
            config(['app.name' => $siteName]);
        }
        
        return $next($request);
    }
}
