<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('production') && ! $this->app->runningInConsole()) {
            try {
                // Runs only once per cold start / container boot if migrations haven't been checked yet
                if (! defined('MIGRATIONS_CHECKED')) {
                    define('MIGRATIONS_CHECKED', true);
                    Artisan::call('migrate', ['--force' => true]);
                }
            } catch (\Throwable $e) {
                // Log error to stderr without crashing the whole request if DB is temporarily unreachable
                error_log('Migration failed: ' . $e->getMessage());
            }
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
