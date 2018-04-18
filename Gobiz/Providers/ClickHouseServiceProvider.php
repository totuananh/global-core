<?php

namespace App\Providers;

use Clickhouse\Client;
use Illuminate\Support\ServiceProvider;

class ClickHouseServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        $this->mergeConfigFrom(__DIR__ . '/../../config/clickhouse.php', 'clickhouse');
        $config = config('clickhouse');

        $this->app->singleton('clickhouse', function ($app) use($config) {

            return (new Client($config['host'], $config['port'], $config['username'], $config['password']));
        });
    }

    public function provides()
    {
        return ['clickhouse'];
    }

}
