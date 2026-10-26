<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register application services here.
    }

    public function boot(): void
    {
        View::composer('layouts.app', function ($view): void {
            $user = auth()->user();
            $brandSettings = $user?->company?->whiteLabelSettings;

            $view->with('brandSettings', $brandSettings);
        });

        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
