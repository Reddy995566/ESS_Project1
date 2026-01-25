<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share site settings with all views
        View::composer('*', function ($view) {
            // Cache settings to avoid multiple DB queries
            static $siteSettings = null;
            
            if ($siteSettings === null) {
                $siteSettings = [
                    'site_name' => Setting::get('site_name', 'The Trusted Store'),
                    'site_email' => Setting::get('site_email', ''),
                    'site_phone' => Setting::get('site_phone', ''),
                    'site_address' => Setting::get('site_address', ''),
                    'site_logo' => Setting::get('site_logo', ''),
                    'site_favicon' => Setting::get('site_favicon', ''),
                    'social_facebook' => Setting::get('social_facebook', ''),
                    'social_twitter' => Setting::get('social_twitter', ''),
                    'social_instagram' => Setting::get('social_instagram', ''),
                    'social_linkedin' => Setting::get('social_linkedin', ''),
                    'social_youtube' => Setting::get('social_youtube', ''),
                ];
            }
            
            $view->with('siteSettings', $siteSettings);
        });
    }
}
