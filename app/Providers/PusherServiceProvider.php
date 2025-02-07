<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Pusher\Pusher;

class PusherServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(Pusher::class, function ($app) {
            $pusherKey = config('broadcasting.connections.pusher.key');

            $pusherSecret = config('broadcasting.connections.pusher.secret');
            $pusherAppId = config('broadcasting.connections.pusher.app_id');
            $pusherCluster = config('broadcasting.connections.pusher.options.cluster');

            return new Pusher(
                $pusherKey,
                $pusherSecret,
                $pusherAppId,
                [
                    'cluster' => $pusherCluster,
                    'useTLS' => true,
                ]
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
