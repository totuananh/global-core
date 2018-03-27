<?php

namespace App\Providers;

use Gobiz\Clickhouse\Client;
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

        $this->app->singleton('clickhouse', function ($app) {

            return (new Client(config('clickhouse')));
        });
    }

    public function provides()
    {
        return ['clickhouse'];
    }

}
