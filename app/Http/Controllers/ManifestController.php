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
        
        $manifest = [
            'name' => $siteName,
            'short_name' => substr($siteName, 0, 12),
            'description' => $siteDescription,
            'start_url' => '/',
            'display' => 'standalone',
            'background_color' => '#FAF5ED',
            'theme_color' => '#4b0f27',
            'orientation' => 'portrait-primary',
        ];
        
        // Only add icons if logo exists
        if ($siteLogo) {
            $manifest['icons'] = [
                [
                    'src' => $siteLogo,
                    'sizes' => '192x192',
                    'type' => 'image/png',
                    'purpose' => 'any maskable'
                ],
                [
                    'src' => $siteLogo,
                    'sizes' => '512x512',
                    'type' => 'image/png',
                    'purpose' => 'any maskable'
                ]
            ];
        }
        
        return response()->json($manifest)
            ->header('Content-Type', 'application/manifest+json');
    }
}
