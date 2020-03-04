<?php

namespace Msh\PayMob;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class PayMobServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            // Config file.
            __DIR__ . '/config/paymob.php' => config_path('paymob.php'),
        ]);
    }

    /**
     * Register any package services.
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function register()
    {
        $this->app->bind('PayMob', function () {
            return new PayMob();
        });

        $this->app->make('Msh\PayMob\PayMobController');

        include __DIR__.'/routes.php';
    }
}
