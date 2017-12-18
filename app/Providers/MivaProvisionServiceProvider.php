<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use pdeans\Miva\Provision\Manager as Provision;

class MivaProvisionServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Provision::class, function ($app) {
        	return new Provision(
        		config('store.code'),
        		config('provision.url'),
        		config('provision.token')
        	);
        });
    }
}
