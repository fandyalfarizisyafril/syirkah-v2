<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Setting;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        Gate::define('manage-content', fn ($user) => in_array($user->role, ['administrator', 'editor'], true));
        Gate::define('manage-inquiries', fn ($user) => in_array($user->role, ['administrator', 'sales'], true));
        Gate::define('manage-settings', fn ($user) => $user->role === 'administrator');

        RateLimiter::for('inquiry', fn ($request) => Limit::perMinute(5)->by($request->ip()));
        RateLimiter::for('login', fn ($request) => Limit::perMinute(5)->by($request->ip()));

        View::composer(['layouts.public', 'public.*', 'partials.inquiry-cta'], function ($view) {
            $view->with('settings', Setting::values());
        });
        View::composer('layouts.public', fn ($view) => $view->with('footerCategories', Category::published()->ordered()->get()));
        Paginator::defaultView('partials.pagination');
    }
}
