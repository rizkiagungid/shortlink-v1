<?php

namespace App\Providers;

use App\Models\Domain;
use App\Models\Link;
use App\Observers\DomainObserver;
use App\Observers\LinkObserver;
use App\Observers\PixelObserver;
use App\Observers\SpaceObserver;
use App\Observers\UserObserver;
use App\Models\Pixel;
use App\Models\Space;
use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Fix for utf8mb migration @https://laravel.com/docs/master/migrations#creating-indexes
        Schema::defaultStringLength(191);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        Space::observe(SpaceObserver::class);
        Link::observe(LinkObserver::class);
        Domain::observe(DomainObserver::class);
        User::observe(UserObserver::class);
        Pixel::observe(PixelObserver::class);
    }
}
