<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class ThemeSettingsController extends Controller
{
    // Available Google Fonts - Popular & Shopify Theme Fonts
    public static $availableFonts = [
        // Serif Fonts (Elegant/Traditional)
        'Playfair Display' => 'Playfair Display',
        'Cormorant Garamond' => 'Cormorant Garamond',
        'Libre Baskerville' => 'Libre Baskerville',
        'Lora' => 'Lora',
        'Merriweather' => 'Merriweather',
        'Crimson Text' => 'Crimson Text',
        'EB Garamond' => 'EB Garamond',
        'Spectral' => 'Spectral',
        'Source Serif Pro' => 'Source Serif Pro',
        'Noto Serif' => 'Noto Serif',
        'PT Serif' => 'PT Serif',
        'Bitter' => 'Bitter',
        'Vollkorn' => 'Vollkorn',
        'Cardo' => 'Cardo',
        'Old Standard TT' => 'Old Standard TT',
        
        // Sans-Serif Fonts (Modern/Clean)
        'Inter' => 'Inter',
        'Poppins' => 'Poppins',
        'Montserrat' => 'Montserrat',
        'Open Sans' => 'Open Sans',
        'Roboto' => 'Roboto',
        'Lato' => 'Lato',
        'Nunito' => 'Nunito',
        'Nunito Sans' => 'Nunito Sans',
        'Raleway' => 'Raleway',
        'Work Sans' => 'Work Sans',
        'DM Sans' => 'DM Sans',
        'Outfit' => 'Outfit',
        'Plus Jakarta Sans' => 'Plus Jakarta Sans',
        'Manrope' => 'Manrope',
        'Figtree' => 'Figtree',
        'Sora' => 'Sora',
        'Urbanist' => 'Urbanist',
        'Jost' => 'Jost',
        'Quicksand' => 'Quicksand',
        'Mulish' => 'Mulish',
        'Rubik' => 'Rubik',
        'Karla' => 'Karla',
        'Cabin' => 'Cabin',
        'Josefin Sans' => 'Josefin Sans',
        'Assistant' => 'Assistant',
        'Barlow' => 'Barlow',
        'Archivo' => 'Archivo',
        'Source Sans Pro' => 'Source Sans Pro',
        'PT Sans' => 'PT Sans',
        'Fira Sans' => 'Fira Sans',
        'Noto Sans' => 'Noto Sans',
        'Oxygen' => 'Oxygen',
        'Exo 2' => 'Exo 2',
        'Titillium Web' => 'Titillium Web',
        'Overpass' => 'Overpass',
        'Maven Pro' => 'Maven Pro',
        'Questrial' => 'Questrial',
        'Hind' => 'Hind',
        
        // Display/Decorative Fonts
        'Abril Fatface' => 'Abril Fatface',
        'Bebas Neue' => 'Bebas Neue',
        'Oswald' => 'Oswald',
        'Anton' => 'Anton',
        'Righteous' => 'Righteous',
        'Alfa Slab One' => 'Alfa Slab One',
        'Lobster' => 'Lobster',
        'Pacifico' => 'Pacifico',
        'Dancing Script' => 'Dancing Script',
        'Great Vibes' => 'Great Vibes',
        'Sacramento' => 'Sacramento',
        'Satisfy' => 'Satisfy',
        'Tangerine' => 'Tangerine',
        'Allura' => 'Allura',
        'Alex Brush' => 'Alex Brush',
    ];

    public function index()
    {
        $colorSettings = SiteSetting::where('group', 'colors')
            ->orderBy('sort_order')
            ->get();
            
        $fontSettings = SiteSetting::where('group', 'fonts')
            ->orderBy('sort_order')
            ->get();
            
        $availableFonts = self::$availableFonts;

        return view('admin.theme-settings.index', compact('colorSettings', 'fontSettings', 'availableFonts'));
    }

    public function update(Request $request)
    {
        $colors = $request->input('colors', []);
        $fonts = $request->input('fonts', []);

        foreach ($colors as $key => $value) {
            SiteSetting::set($key, $value);
        }
        
        foreach ($fonts as $key => $value) {
            SiteSetting::set($key, $value);
        }

        SiteSetting::clearCache();

        return response()->json([
            'success' => true,
            'message' => 'Theme settings updated successfully!'
        ]);
    }

    public function resetToDefault()
    {
        // Run the seeder to reset to defaults
        $seeder = new \Database\Seeders\SiteSettingsSeeder();
        $seeder->run();

        SiteSetting::clearCache();

        return response()->json([
            'success' => true,
            'message' => 'Theme settings reset to default!'
        ]);
    }

    public function getColorCss()
    {
        $css = SiteSetting::getColorCss();
        return response($css)->header('Content-Type', 'text/css');
    }
}
