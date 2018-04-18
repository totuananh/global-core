<?php

namespace Gobiz\Providers;


use Gobiz\Elastic\Client;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class ElasticServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        // Load main config file of kafka
        $this->mergeConfigFrom(__DIR__ . '/../Config/elastic.php', 'elastic');

        $this->app->singleton('elastic', function ($app) {
            return new Client(config('elastic'));
        });
    }

    public function provides()
    {
        return ['elastic'];
    }
}