<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
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
        $settings = Cache::remember('site_settings', 3600, function () {
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

        Cache::forget('site_settings');

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
        Cache::forget('site_settings');
    }

    /**
     * Get all color settings as CSS variables
     */
    public static function getColorCss()
    {
        $colors = self::where('group', 'colors')->get();
        $css = ':root {' . PHP_EOL;
        
        foreach ($colors as $color) {
            $varName = '--' . str_replace('_', '-', $color->key);
            $css .= "    {$varName}: {$color->value};" . PHP_EOL;
        }
        
        $css .= '}';
        return $css;
    }
}
