<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
        Schema::defaultStringLength(191);
        Gate::define('viewLogViewer', fn ($user = null) => $user?->isAdmin() === true);

        RateLimiter::for('api-writes', function (Request $request) {
            return [
                Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip()),
                Limit::perMinute(120)->by($request->ip()),
            ];
        });

        RateLimiter::for('api-uploads', function (Request $request) {
            return [
                Limit::perMinute(12)->by(optional($request->user())->id ?: $request->ip()),
                Limit::perMinute(30)->by($request->ip()),
            ];
        });
    }
}
