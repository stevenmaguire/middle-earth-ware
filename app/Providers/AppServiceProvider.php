<?php

namespace App\Providers;

use App\Contracts;
use App\Services;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            Contracts\Photoable::class,
            Services\Flickr::class
        );

        $this->app->bind(
            Contracts\Mappable::class,
            Services\Tiler::class
        );
    }
}
