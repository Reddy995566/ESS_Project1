<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->prefix('seller')
                ->group(base_path('routes/seller.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin.auth' => \App\Http\Middleware\AdminAuth::class,
            'seller.auth' => \App\Http\Middleware\SellerAuth::class,
            'extend.session' => \App\Http\Middleware\ExtendSessionLifetime::class,
        ]);
        
        // Load site settings from database on every request
        $middleware->append(\App\Http\Middleware\LoadSiteSettings::class);
        
        // Add no-cache headers for HTML responses (prevents browser caching)
        $middleware->append(\App\Http\Middleware\NoCacheHeaders::class);
        
        // Extend session lifetime for authenticated users with remember token
        $middleware->append(\App\Http\Middleware\ExtendSessionLifetime::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle ModelNotFoundException gracefully
        $exceptions->render(function (\Illuminate\Database\Eloquent\ModelNotFoundException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resource not found'
                ], 404);
            }

            // For admin routes, redirect to index with error message
            if ($request->is('admin/*')) {
                $modelName = class_basename($e->getModel());
                return redirect()->back()
                    ->with('error', $modelName . ' not found. It may have been deleted.');
            }

            // For website routes, show 404 page
            abort(404);
        });
    })->create();
