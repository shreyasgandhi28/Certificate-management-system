<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
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

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Use custom pagination views
        Paginator::defaultView('vendor.pagination.admin-tailwind');
        Paginator::defaultSimpleView('vendor.pagination.simple-tailwind');
    }
}
