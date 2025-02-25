<?php

namespace App\Providers;

use Carbon\Carbon;
use App\Models\ProjectPerusahaan;
use Illuminate\Support\ServiceProvider;
use App\Observers\ProjectPerusahaanObserver;

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
        Carbon::setLocale('id');
        ProjectPerusahaan::observe(ProjectPerusahaanObserver::class);
    }
}
