<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot()
{
    if ($this->app->environment('production') || env('APP_FORCE_HTTPS', false)) {
            URL::forceScheme('https');
        }

    

    Log::info('Application boot completed');
    Paginator::useTailwind();
}
    public const HOME = '/student/dashboard';
}
