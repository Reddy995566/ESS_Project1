<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    const CACHE_KEY = 'site_settings';

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description',
        'sort_order',
    ];

    /**
     * Get a setting value by key
     */
    public static function get($key, $default = null)
    {
        $settings = Cache::remember(self::CACHE_KEY, 300, function () {
            return self::pluck('value', 'key')->toArray();
        });

        return $settings[$key] ?? $default;
    }

    /**
     * Set a setting value
     */
    public static function set($key, $value)
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        Cache::forget(self::CACHE_KEY);
        Cache::forget('site_color_css');

        return $setting;
    }

    /**
     * Get all settings by group
     */
    public static function getByGroup($group)
    {
        return self::where('group', $group)
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Clear settings cache
     */
    public static function clearCache()
    {
        Cache::forget(self::CACHE_KEY);
        Cache::forget('site_color_css');
    }

    /**
     * Get all color settings as CSS variables
     */
    public static function getColorCss()
    {
        return Cache::remember('site_color_css', 300, function () {
            $colors = self::where('group', 'colors')->get();
            $css = ':root {' . PHP_EOL;

            foreach ($colors as $color) {
                $varName = '--' . str_replace('_', '-', $color->key);
                $css .= "    {$varName}: {$color->value};" . PHP_EOL;
            }

            return $css . '}';
        });
    }
}
