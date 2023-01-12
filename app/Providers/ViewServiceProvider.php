<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('App\Http\View\Composers\UserStatsComposer');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('shared.footer', 'App\Http\View\Composers\FooterPagesComposer');

        View::composer([
            'shared.header',
            'dashboard.index',
            'account.plan'
        ], 'App\Http\View\Composers\UserStatsComposer');
    }
}
