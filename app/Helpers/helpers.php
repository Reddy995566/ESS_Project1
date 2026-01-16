<?php

use App\Models\SiteSetting;

if (!function_exists('site_setting')) {
    /**
     * Get a site setting value
     */
    function site_setting($key, $default = null)
    {
        return SiteSetting::get($key, $default);
    }
}

if (!function_exists('theme_color')) {
    /**
     * Get a theme color value
     */
    function theme_color($key, $default = null)
    {
        return SiteSetting::get('color_' . $key, $default);
    }
}
