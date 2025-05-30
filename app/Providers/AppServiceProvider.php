<?php

namespace App\Providers;

use Carbon\Carbon;
use App\Models\Task;
use App\Observers\TaskObserver;
use App\Models\ProjectPerusahaan;
use App\Observers\ProjectObserver;
use App\Listeners\UpdateTaskStatus;
use App\Events\SubtaskStatusChanged;
use Illuminate\Support\Facades\Event;
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
        // ProjectPerusahaan::observe(ProjectPerusahaanObserver::class); menambah project ke app drive
        ProjectPerusahaan::observe(ProjectObserver::class);
        Task::observe(TaskObserver::class);
        Event::listen(
            SubtaskStatusChanged::class,
            UpdateTaskStatus::class
        );
    }
}
