<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        channels: __DIR__ . '/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias(['guest', \App\Http\Middleware\RedirectIfAuthenticated::class]);
        $middleware->alias(['password.confirm', \Illuminate\Auth\Middleware\RequirePassword::class]);
        $middleware->alias(['signed', \Illuminate\Routing\Middleware\ValidateSignature::class]);
        $middleware->alias(['throttle', \Illuminate\Routing\Middleware\ThrottleRequests::class]);
        $middleware->alias(['verified', \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class]);

        // Create an admin middleware group for all admin-related middleware
        $middleware->group('admin', [
            \App\Http\Middleware\IsAdmin::class,
            \App\Http\Middleware\Privileges\AdvertisementsMiddleware::class,
            \App\Http\Middleware\Privileges\PrivilegesMiddleware::class,
            \App\Http\Middleware\Privileges\QuestionsMiddleware::class,
            \App\Http\Middleware\Privileges\TransfersMiddleware::class,
            \App\Http\Middleware\Privileges\UsersMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
