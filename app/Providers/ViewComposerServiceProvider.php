<?php

namespace App\Providers;

use App\Models\Setting;
use App\Models\Category;
use App\Models\Fabric;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{

    public function register(): void
    {

    }

    public function boot(): void
    {

        View::composer(['admin.components.sidebar', 'components.admin-layout'], function ($view) {
            $view->with('siteName', Setting::get('site_name', config('app.name', 'The Trusted Store')));
        });

        // Share categories with header for mega menu
        View::composer('website.includes.header', function ($view) {
            $categories = Category::with(['children' => function ($query) {
                $query->active()->orderBy('sort_order');
            }])
            ->parents()
            ->active()
            ->showInNavbar()
            ->orderBy('sort_order')
            ->get();
            
            // Share active fabrics for navbar
            $fabrics = Fabric::active()
                ->where('show_in_navbar', true)
                ->orderBy('sort_order')
                ->get();
            
            $view->with('megaMenuCategories', $categories)
                 ->with('megaMenuFabrics', $fabrics);
        });
    }
}