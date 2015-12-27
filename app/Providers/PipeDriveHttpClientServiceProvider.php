<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

/**
 * Class PipeDriveHttpClientServiceProvider
 * @package App\Providers
 */
class PipeDriveHttpClientServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Interfaces\PipeDriveHttpClientInterface','App\Services\PipeDriveHttpClient');
    }
}
