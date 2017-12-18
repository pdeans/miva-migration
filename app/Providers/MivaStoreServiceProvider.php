<?php

namespace App\Providers;

use App\Miva\Store;
use Illuminate\Support\ServiceProvider;

class MivaStoreServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Store::class, function ($app) {
            return new Store([
                'url'      => config('store.url'),
                'code'     => config('store.code'),
                'root'     => config('store.root'),
                'graphics' => config('store.graphics'),
            ]);
        });
    }
}
