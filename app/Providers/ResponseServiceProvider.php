<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
       $this->app->bind('App\Interfaces\ResponseInterface', function() {
            return new \App\Services\ResponseService();
       });
    }
}
