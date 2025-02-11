<?php

namespace App\Providers;

use App\Models\C4Game;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class CustomRouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        Route::model('c4game', C4Game::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
