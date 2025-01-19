<?php

namespace App\Providers;

use App\Repositories\Ads\Contracts\AdsRepositoryInterface;
use App\Services\Ads\AdsService;
use App\Services\Store\StoreService;
use App\Services\Store\ProductService;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Common\BaseRepository;
use App\Services\Common\MemoryImageService;
use App\Repositories\Ads\EloquentAdsRepository;
use App\Repositories\Store\EloquentStoreRepository;
use App\Services\Ads\Contracts\AdsServiceInterface;
use App\Repositories\Store\EloquentProductRepository;
use App\Services\Store\Contracts\StoreServiceInterface;
use App\Services\Common\Contracts\ImageServiceInterface;
use App\Services\Store\Contracts\ProductServiceInterface;
use App\Repositories\Store\EloquentProductImageRepository;
use App\Repositories\Common\Contracts\BaseRepositoryInterface;
use App\Repositories\Store\Contracts\StoreRepositoryInterface;
use App\Repositories\Store\Contracts\ProductRepositoryInterface;
use App\Repositories\Store\Contracts\ProductImageRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);

        $this->app->bind(ProductRepositoryInterface::class, EloquentProductRepository::class);
        $this->app->bind(ProductServiceInterface::class, ProductService::class);

        $this->app->bind(StoreRepositoryInterface::class, EloquentStoreRepository::class);
        $this->app->bind(StoreServiceInterface::class, StoreService::class);

        $this->app->bind(ProductImageRepositoryInterface::class, EloquentProductImageRepository::class);

        $this->app->bind(ImageServiceInterface::class, MemoryImageService::class);

        $this->app->bind(AdsRepositoryInterface::class, EloquentAdsRepository::class);
        $this->app->bind(AdsServiceInterface::class, AdsService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
