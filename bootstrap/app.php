<?php

use Illuminate\Foundation\Application;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => RoleMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('laporan:reminder --days=3')
                ->dailyAt('08:00')
                ->timezone('Asia/Jakarta');
        $schedule->command('laporan:reminder --days=1')
                ->dailyAt('08:00')
                ->timezone('Asia/Jakarta');
        $schedule->command('laporan:reminder --days=0')
                ->dailyAt('08:00')
                ->timezone('Asia/Jakarta');
    })
    ->create();
    
