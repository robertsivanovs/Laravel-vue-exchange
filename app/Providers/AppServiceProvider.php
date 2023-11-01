<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\RateDataProviderInterface;
use App\Services\RateService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(RateDataProviderInterface::class, RateService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
