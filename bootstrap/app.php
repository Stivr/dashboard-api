<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: [
            __DIR__.'/../routes/api.php',
            'prefix'     => 'api',
            'middleware' => 'api',
        ],
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Define your "api" middleware group if desired
        $middleware->group('api', [
            // e.g. \Illuminate\Routing\Middleware\SubstituteBindings::class,
            // \Illuminate\Http\Middleware\SetCacheHeaders::class,
            // or any typical "api" middlewares you want
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
