<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Primary Colors
            [
                'key' => 'color_primary',
                'value' => '#5C1F33',
                'type' => 'color',
                'group' => 'colors',
                'label' => 'Primary Color',
                'description' => 'Main brand color (Maroon/Wine)',
                'sort_order' => 1,
            ],
            [
                'key' => 'color_primary_dark',
                'value' => '#4B0F27',
                'type' => 'color',
                'group' => 'colors',
                'label' => 'Primary Dark',
                'description' => 'Darker shade of primary color',
                'sort_order' => 2,
            ],
            [
                'key' => 'color_primary_light',
                'value' => '#7A2D45',
                'type' => 'color',
                'group' => 'colors',
                'label' => 'Primary Light',
                'description' => 'Lighter shade of primary color',
                'sort_order' => 3,
            ],
            
            // Background Colors
            [
                'key' => 'color_bg_primary',
                'value' => '#FAF5ED',
                'type' => 'color',
                'group' => 'colors',
                'label' => 'Background Primary',
                'description' => 'Main background color (Cream)',
                'sort_order' => 10,
            ],
            [
                'key' => 'color_bg_secondary',
                'value' => '#EDE5DA',
                'type' => 'color',
                'group' => 'colors',
                'label' => 'Background Secondary',
                'description' => 'Secondary background (Light Beige)',
                'sort_order' => 11,
            ],
            [
                'key' => 'color_bg_tertiary',
                'value' => '#EEDDD0',
                'type' => 'color',
                'group' => 'colors',
                'label' => 'Background Tertiary',
                'description' => 'Tertiary background color',
                'sort_order' => 12,
            ],
            
            // Text Colors
            [
                'key' => 'color_text_primary',
                'value' => '#2B2B2B',
                'type' => 'color',
                'group' => 'colors',
                'label' => 'Text Primary',
                'description' => 'Main text color (Dark)',
                'sort_order' => 20,
            ],
            [
                'key' => 'color_text_secondary',
                'value' => '#4B4B4B',
                'type' => 'color',
                'group' => 'colors',
                'label' => 'Text Secondary',
                'description' => 'Secondary text color (Gray)',
                'sort_order' => 21,
            ],
            [
                'key' => 'color_text_muted',
                'value' => '#6B6B6B',
                'type' => 'color',
                'group' => 'colors',
                'label' => 'Text Muted',
                'description' => 'Muted/light text color',
                'sort_order' => 22,
            ],
            
            // Accent Colors
            [
                'key' => 'color_accent_gold',
                'value' => '#E6B873',
                'type' => 'color',
                'group' => 'colors',
                'label' => 'Accent Gold',
                'description' => 'Gold accent color',
                'sort_order' => 30,
            ],
            [
                'key' => 'color_accent_success',
                'value' => '#495530',
                'type' => 'color',
                'group' => 'colors',
                'label' => 'Success/Buy Now',
                'description' => 'Success button color (Olive Green)',
                'sort_order' => 31,
            ],
            
            // Button Colors
            [
                'key' => 'color_btn_primary',
                'value' => '#3D0C1F',
                'type' => 'color',
                'group' => 'colors',
                'label' => 'Button Primary',
                'description' => 'Primary button background (Add to Cart)',
                'sort_order' => 40,
            ],
            [
                'key' => 'color_btn_primary_hover',
                'value' => '#2a0815',
                'type' => 'color',
                'group' => 'colors',
                'label' => 'Button Primary Hover',
                'description' => 'Primary button hover state',
                'sort_order' => 41,
            ],
            [
                'key' => 'color_btn_secondary',
                'value' => '#495530',
                'type' => 'color',
                'group' => 'colors',
                'label' => 'Button Secondary',
                'description' => 'Secondary button background (Buy Now)',
                'sort_order' => 42,
            ],
            [
                'key' => 'color_btn_secondary_hover',
                'value' => '#384225',
                'type' => 'color',
                'group' => 'colors',
                'label' => 'Button Secondary Hover',
                'description' => 'Secondary button hover state',
                'sort_order' => 43,
            ],
            
            // Header/Footer Colors
            [
                'key' => 'color_header_bg',
                'value' => '#FAF5ED',
                'type' => 'color',
                'group' => 'colors',
                'label' => 'Header Background',
                'description' => 'Header background color',
                'sort_order' => 50,
            ],
            [
                'key' => 'color_header_text',
                'value' => '#441227',
                'type' => 'color',
                'group' => 'colors',
                'label' => 'Header Text',
                'description' => 'Header text/icon color',
                'sort_order' => 51,
            ],
            [
                'key' => 'color_footer_bg',
                'value' => '#FAF5ED',
                'type' => 'color',
                'group' => 'colors',
                'label' => 'Footer Background',
                'description' => 'Footer background color',
                'sort_order' => 52,
            ],
            [
                'key' => 'color_footer_text',
                'value' => '#441227',
                'type' => 'color',
                'group' => 'colors',
                'label' => 'Footer Text',
                'description' => 'Footer text color',
                'sort_order' => 53,
            ],
            
            // Border Colors
            [
                'key' => 'color_border',
                'value' => '#D6B27A',
                'type' => 'color',
                'group' => 'colors',
                'label' => 'Border Color',
                'description' => 'Default border color',
                'sort_order' => 60,
            ],
            
            // Font Settings
            [
                'key' => 'font_heading',
                'value' => 'Playfair Display',
                'type' => 'select',
                'group' => 'fonts',
                'label' => 'Heading Font',
                'description' => 'Font for headings and titles',
                'sort_order' => 1,
            ],
            [
                'key' => 'font_body',
                'value' => 'Inter',
                'type' => 'select',
                'group' => 'fonts',
                'label' => 'Body Font',
                'description' => 'Font for body text and paragraphs',
                'sort_order' => 2,
            ],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
