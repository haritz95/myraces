<?php

namespace App\Providers;

use App\Models\NavItem;
use App\Models\Race;
use App\Observers\RaceObserver;
use App\Socialite\StravaProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Facades\Socialite;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Race::observe(RaceObserver::class);

        View::composer('layouts.app', function ($view) {
            $user = auth()->user();
            $isAdmin = $user?->is_admin;

            $accessible = NavItem::ordered()->enabled()->get()
                ->filter(fn (NavItem $item) => $isAdmin || ! $item->is_premium || $user?->is_premium);

            $view->with([
                'sidebarNav' => $accessible,
                'mobileBottomNav' => $accessible->where('location', 'bottom_nav')->values(),
                'mobileDrawer' => $accessible->where('location', 'drawer')->values(),
            ]);
        });

        Socialite::extend('strava', function ($app) {
            $config = $app['config']['services.strava'];

            return Socialite::buildProvider(StravaProvider::class, $config);
        });
    }
}
