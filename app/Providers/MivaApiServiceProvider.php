<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use pdeans\Miva\Api\Manager as Api;

class MivaApiServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Api::class, function ($app) {
            return new Api([
                'url'          => config('api.url'),
                'store_code'   => config('store.code'),
                'access_token' => config('api.token'),
                'private_key'  => config('api.key'),
            ]);
        });
    }
}
