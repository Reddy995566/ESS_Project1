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
        
        // Generate icon URLs from logo
        $icon192 = $siteLogo ?: asset('pwa-icon-192.png');
        $icon512 = $siteLogo ?: asset('pwa-icon-512.png');
        
        $manifest = [
            'name' => $siteName,
            'short_name' => substr($siteName, 0, 12),
            'description' => $siteDescription,
            'start_url' => '/',
            'display' => 'standalone',
            'background_color' => '#FAF5ED',
            'theme_color' => '#4b0f27',
            'orientation' => 'portrait-primary',
            'icons' => [
                [
                    'src' => $icon192,
                    'sizes' => '192x192',
                    'type' => 'image/png',
                    'purpose' => 'any maskable'
                ],
                [
                    'src' => $icon512,
                    'sizes' => '512x512',
                    'type' => 'image/png',
                    'purpose' => 'any maskable'
                ]
            ]
        ];
        
        return response()->json($manifest)
            ->header('Content-Type', 'application/manifest+json');
    }
}
