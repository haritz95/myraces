<?php

namespace App\Providers;

use App\Socialite\StravaProvider;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Facades\Socialite;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Socialite::extend('strava', function ($app) {
            $config = $app['config']['services.strava'];

            return Socialite::buildProvider(StravaProvider::class, $config);
        });
    }
}
