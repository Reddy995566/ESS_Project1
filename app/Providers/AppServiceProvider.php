<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\View\Composers\SettingsComposer;
use App\Models\Product;
use App\Observers\ProductObserver;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {

    }

    public function boot(): void
    {
        // Share settings with admin views
        View::composer([
            'admin.components.sidebar',
            'admin.layouts.app'
        ], SettingsComposer::class);
        
        // Register observers
        Product::observe(ProductObserver::class);
    }
}