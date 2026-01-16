<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\Setting;

class SettingsComposer
{
    public function compose(View $view)
    {
        $settings = [
            'business_name' => Setting::get('site_name', config('app.name', 'Fashion Store')),
            'site_logo' => Setting::get('site_logo', null),
            'address' => Setting::get('site_address', '184 Main Rd E, St Albans VIC 3021, Australia'),
            'email' => Setting::get('site_email', 'info@example.com'),
            'phone' => Setting::get('site_phone', '+001 2233 456'),
            'facebook' => Setting::get('social_facebook', 'https://www.facebook.com/'),
            'twitter' => Setting::get('social_twitter', 'https://twitter.com/'),
            'instagram' => Setting::get('social_instagram', 'https://www.instagram.com/'),
            'linkedin' => Setting::get('social_linkedin', 'https://www.linkedin.com/'),
            'pinterest' => Setting::get('social_pinterest', 'https://www.pinterest.com/'),
        ];

        $view->with('settings', $settings);
    }
}
