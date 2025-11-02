<?php

namespace App\Providers;

use App\Repositories\RedisCartRepository;
use App\Services\CartService;
use App\Services\RecentlyViewedService;
use App\Services\WishlistService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(RedisCartRepository::class, fn () => new RedisCartRepository());

        $this->app->singleton(CartService::class, function ($app) {
            return new CartService($app->make(RedisCartRepository::class));
        });

        $this->app->singleton(WishlistService::class, fn () => new WishlistService());
        $this->app->singleton(RecentlyViewedService::class, fn () => new RecentlyViewedService());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
