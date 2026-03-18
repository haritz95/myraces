<?php

use App\Http\Middleware\CheckNavItemAccess;
use App\Http\Middleware\EnsureIsAdmin;
use App\Http\Middleware\EnsurePremium;
use App\Http\Middleware\EnsureUserIsNotBanned;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            SetLocale::class,
            EnsureUserIsNotBanned::class,
        ]);

        $middleware->alias([
            'admin' => EnsureIsAdmin::class,
            'premium' => EnsurePremium::class,
            'nav.access' => CheckNavItemAccess::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
