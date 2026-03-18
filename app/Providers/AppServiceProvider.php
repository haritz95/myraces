<?php

namespace App\Providers;

use App\Models\NavItem;
use App\Socialite\StravaProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Facades\Socialite;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            $mobileNavItems = NavItem::forMobile();
            $view->with([
                'mobileBottomNav' => $mobileNavItems->where('location', 'bottom_nav'),
                'mobileDrawer' => $mobileNavItems->where('location', 'drawer')->where('is_enabled', true),
            ]);
        });

        Socialite::extend('strava', function ($app) {
            $config = $app['config']['services.strava'];

            return Socialite::buildProvider(StravaProvider::class, $config);
        });
    }
}
