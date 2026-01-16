<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\ImageKitService;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        $activeTab = $request->get('tab', 'general');

        $settings = [
            'imagekit_public_key' => Setting::get('imagekit_public_key', ''),
            'imagekit_private_key' => Setting::get('imagekit_private_key', ''),
            'imagekit_url_endpoint' => Setting::get('imagekit_url_endpoint', ''),
            'site_name' => Setting::get('site_name', config('app.name', 'Fashion Store')),
            'site_email' => Setting::get('site_email', ''),
            'site_phone' => Setting::get('site_phone', ''),
            'site_address' => Setting::get('site_address', ''),
            'site_logo' => Setting::get('site_logo', ''),
            'site_favicon' => Setting::get('site_favicon', ''),
            
            // Social Media Links
            'social_facebook' => Setting::get('social_facebook', ''),
            'social_twitter' => Setting::get('social_twitter', ''),
            'social_instagram' => Setting::get('social_instagram', ''),
            'social_linkedin' => Setting::get('social_linkedin', ''),
            'social_youtube' => Setting::get('social_youtube', ''),
            
            // Maintenance Mode
            'maintenance_mode' => Setting::get('maintenance_mode', false),
            'maintenance_message' => Setting::get('maintenance_message', 'We are currently under maintenance. Please check back soon!'),
            
            // SEO Settings
            'seo_meta_title' => Setting::get('seo_meta_title', ''),
            'seo_meta_description' => Setting::get('seo_meta_description', ''),
            'seo_meta_keywords' => Setting::get('seo_meta_keywords', ''),
            'seo_og_image' => Setting::get('seo_og_image', ''),
            'seo_google_analytics' => Setting::get('seo_google_analytics', ''),
            'seo_google_site_verification' => Setting::get('seo_google_site_verification', ''),
            'seo_sitemap_enabled' => Setting::get('seo_sitemap_enabled', true),
            
            // Payment Gateway Settings (Razorpay)
            'razorpay_key_id' => Setting::get('razorpay_key_id', ''),
            'razorpay_key_secret' => Setting::get('razorpay_key_secret', ''),
            'razorpay_enabled' => Setting::get('razorpay_enabled', false),
        ];

        return view('admin.settings.index', [
            'activeTab' => $activeTab,
            'settings' => $settings
        ]);
    }

    public function updateImageKit(Request $request)
    {
        try {
            $request->validate([
                'imagekit_public_key' => 'required|string',
                'imagekit_private_key' => 'required|string',
                'imagekit_url_endpoint' => 'required|url',
            ]);

            Setting::set('imagekit_public_key', $request->imagekit_public_key);
            Setting::set('imagekit_private_key', $request->imagekit_private_key);
            Setting::set('imagekit_url_endpoint', $request->imagekit_url_endpoint);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'ImageKit settings saved successfully!'
                ]);
            }

            return redirect()->route('admin.settings', ['tab' => 'imagekit'])
                ->with('success', 'ImageKit settings saved successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed: ' . implode(' ', $e->validator->errors()->all())
                ], 422);
            }
            throw $e;
        }
    }

    public function updateGeneral(Request $request)
    {
        try {
            $request->validate([
                'site_name' => 'required|string|max:255',
                'site_email' => 'required|email',
                'site_phone' => 'nullable|string|max:20',
                'site_address' => 'nullable|string|max:500',
            ]);

            Setting::set('site_name', $request->site_name);
            Setting::set('site_email', $request->site_email);
            Setting::set('site_phone', $request->site_phone ?? '');
            Setting::set('site_address', $request->site_address ?? '');

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'General settings saved successfully!'
                ]);
            }

            return redirect()->route('admin.settings', ['tab' => 'general'])
                ->with('success', 'General settings saved successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed: ' . implode(' ', $e->validator->errors()->all())
                ], 422);
            }
            throw $e;
        }
    }
    
    public function updatePaymentGateway(Request $request)
    {
        try {
            $request->validate([
                'razorpay_key_id' => 'nullable|string',
                'razorpay_key_secret' => 'nullable|string',
                'razorpay_enabled' => 'nullable|boolean',
            ]);
            
            // Save Razorpay settings
            Setting::set('razorpay_key_id', $request->razorpay_key_id ?? '');
            Setting::set('razorpay_key_secret', $request->razorpay_key_secret ?? '');
            Setting::set('razorpay_enabled', $request->has('razorpay_enabled'));

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment gateway settings saved successfully!'
                ]);
            }

            return redirect()->route('admin.settings', ['tab' => 'payment'])
                ->with('success', 'Payment gateway settings saved successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed: ' . implode(' ', $e->validator->errors()->all())
                ], 422);
            }
            throw $e;
        }
    }
    
    public function uploadLogo(Request $request, ImageKitService $imageKit)
    {
        try {
            $request->validate([
                'logo' => 'required|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
                'type' => 'required|in:logo,favicon'
            ]);
            
            $type = $request->input('type');
            $folder = 'settings';
            
            $result = $imageKit->uploadCategoryImage($request->file('logo'), $folder);
            
            if ($result && $result['success']) {
                // Save to settings
                $settingKey = $type === 'logo' ? 'site_logo' : 'site_favicon';
                Setting::set($settingKey, $result['url']);
                
                return response()->json([
                    'success' => true,
                    'url' => $result['url'],
                    'message' => ucfirst($type) . ' uploaded successfully!'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload ' . $type
            ], 500);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function updateSocialMedia(Request $request)
    {
        try {
            $request->validate([
                'social_facebook' => 'nullable|url',
                'social_twitter' => 'nullable|url',
                'social_instagram' => 'nullable|url',
                'social_linkedin' => 'nullable|url',
                'social_youtube' => 'nullable|url',
            ]);
            
            Setting::set('social_facebook', $request->social_facebook ?? '');
            Setting::set('social_twitter', $request->social_twitter ?? '');
            Setting::set('social_instagram', $request->social_instagram ?? '');
            Setting::set('social_linkedin', $request->social_linkedin ?? '');
            Setting::set('social_youtube', $request->social_youtube ?? '');

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Social media links saved successfully!'
                ]);
            }

            return redirect()->route('admin.settings', ['tab' => 'social'])
                ->with('success', 'Social media links saved successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed: ' . implode(' ', $e->validator->errors()->all())
                ], 422);
            }
            throw $e;
        }
    }
    
    public function updateMaintenance(Request $request)
    {
        try {
            $request->validate([
                'maintenance_mode' => 'nullable|boolean',
                'maintenance_message' => 'nullable|string|max:1000',
            ]);
            
            Setting::set('maintenance_mode', $request->has('maintenance_mode'));
            Setting::set('maintenance_message', $request->maintenance_message ?? 'We are currently under maintenance. Please check back soon!');

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Maintenance settings saved successfully!'
                ]);
            }

            return redirect()->route('admin.settings', ['tab' => 'maintenance'])
                ->with('success', 'Maintenance settings saved successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed: ' . implode(' ', $e->validator->errors()->all())
                ], 422);
            }
            throw $e;
        }
    }
    
    public function updateSEO(Request $request)
    {
        try {
            $request->validate([
                'seo_meta_title' => 'nullable|string|max:255',
                'seo_meta_description' => 'nullable|string|max:500',
                'seo_meta_keywords' => 'nullable|string|max:500',
                'seo_google_analytics' => 'nullable|string',
                'seo_google_site_verification' => 'nullable|string',
                'seo_sitemap_enabled' => 'nullable|boolean',
            ]);
            
            Setting::set('seo_meta_title', $request->seo_meta_title ?? '');
            Setting::set('seo_meta_description', $request->seo_meta_description ?? '');
            Setting::set('seo_meta_keywords', $request->seo_meta_keywords ?? '');
            Setting::set('seo_google_analytics', $request->seo_google_analytics ?? '');
            Setting::set('seo_google_site_verification', $request->seo_google_site_verification ?? '');
            Setting::set('seo_sitemap_enabled', $request->has('seo_sitemap_enabled'));

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'SEO settings saved successfully!'
                ]);
            }

            return redirect()->route('admin.settings', ['tab' => 'seo'])
                ->with('success', 'SEO settings saved successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed: ' . implode(' ', $e->validator->errors()->all())
                ], 422);
            }
            throw $e;
        }
    }
    
    public function uploadOGImage(Request $request, ImageKitService $imageKit)
    {
        try {
            $request->validate([
                'og_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);
            
            $folder = 'settings';
            $result = $imageKit->uploadCategoryImage($request->file('og_image'), $folder);
            
            if ($result && $result['success']) {
                Setting::set('seo_og_image', $result['url']);
                
                return response()->json([
                    'success' => true,
                    'url' => $result['url'],
                    'message' => 'OG Image uploaded successfully!'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload OG image'
            ], 500);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function generateSitemap()
    {
        try {
            $products = \App\Models\Product::where('status', 'active')->get();
            $categories = \App\Models\Category::where('is_active', true)->get();
            
            $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
            
            // Homepage
            $sitemap .= '  <url>' . "\n";
            $sitemap .= '    <loc>' . url('/') . '</loc>' . "\n";
            $sitemap .= '    <lastmod>' . date('Y-m-d') . '</lastmod>' . "\n";
            $sitemap .= '    <changefreq>daily</changefreq>' . "\n";
            $sitemap .= '    <priority>1.0</priority>' . "\n";
            $sitemap .= '  </url>' . "\n";
            
            // Products
            foreach ($products as $product) {
                $sitemap .= '  <url>' . "\n";
                $sitemap .= '    <loc>' . url('/product/' . $product->slug) . '</loc>' . "\n";
                $sitemap .= '    <lastmod>' . $product->updated_at->format('Y-m-d') . '</lastmod>' . "\n";
                $sitemap .= '    <changefreq>weekly</changefreq>' . "\n";
                $sitemap .= '    <priority>0.8</priority>' . "\n";
                $sitemap .= '  </url>' . "\n";
            }
            
            // Categories
            foreach ($categories as $category) {
                $sitemap .= '  <url>' . "\n";
                $sitemap .= '    <loc>' . url('/category/' . $category->slug) . '</loc>' . "\n";
                $sitemap .= '    <lastmod>' . $category->updated_at->format('Y-m-d') . '</lastmod>' . "\n";
                $sitemap .= '    <changefreq>weekly</changefreq>' . "\n";
                $sitemap .= '    <priority>0.7</priority>' . "\n";
                $sitemap .= '  </url>' . "\n";
            }
            
            $sitemap .= '</urlset>';
            
            // Save sitemap to public folder
            file_put_contents(public_path('sitemap.xml'), $sitemap);
            
            return response()->json([
                'success' => true,
                'message' => 'Sitemap generated successfully!',
                'url' => url('/sitemap.xml')
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate sitemap: ' . $e->getMessage()
            ], 500);
        }
    }
}
