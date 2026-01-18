<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class ManifestController extends Controller
{
    public function manifest()
    {
        $siteName = Setting::get('site_name', 'Fashion Store');
        $siteDescription = Setting::get('site_description', 'Premium Fashion Store - Shop Latest Trends');
        $siteLogo = Setting::get('site_logo', '');
        
        // Use database logo if available, otherwise fallback to uploaded icon
        $iconUrl = $siteLogo ?: asset('website/1768647579_696b6b9b91f3a.png');
        
        $manifest = [
            'name' => $siteName,
            'short_name' => substr($siteName, 0, 12),
            'description' => $siteDescription,
            'start_url' => '/',
            'scope' => '/',
            'display' => 'standalone',
            'background_color' => '#FAF5ED',
            'theme_color' => '#4b0f27',
            'orientation' => 'portrait-primary',
            'icons' => [
                [
                    'src' => $iconUrl,
                    'sizes' => '192x192',
                    'type' => 'image/png',
                    'purpose' => 'any'
                ],
                [
                    'src' => $iconUrl,
                    'sizes' => '512x512',
                    'type' => 'image/png',
                    'purpose' => 'any'
                ],
                [
                    'src' => $iconUrl,
                    'sizes' => '192x192',
                    'type' => 'image/png',
                    'purpose' => 'maskable'
                ],
                [
                    'src' => $iconUrl,
                    'sizes' => '512x512',
                    'type' => 'image/png',
                    'purpose' => 'maskable'
                ]
            ]
        ];
        
        return response()->json($manifest)
            ->header('Content-Type', 'application/manifest+json');
    }
}
