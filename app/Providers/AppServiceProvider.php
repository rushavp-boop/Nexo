<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Console\Scheduling\Schedule;
use App\Http\Middleware\RoleMiddleware;

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
        // Force HTTPS in production (Fixes Railway Mixed Content issue)
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        Route::aliasMiddleware('role', RoleMiddleware::class);

        // Schedule daily price sync from Kalimati API at 6 AM
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $schedule->command('app:sync-kalimati-prices')
                ->dailyAt('6:00')
                ->withoutOverlapping()
                ->onSuccess(fn() => \Log::info('✅ Market prices synced successfully'))
                ->onFailure(fn() => \Log::error('❌ Market prices sync failed'));
        });
    }
}
