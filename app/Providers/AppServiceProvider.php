<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\View\Composers\SettingsComposer;

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
    }
}