<?php

namespace Gobiz\ElasticSearch;

use Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;

class ElasticSearchServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/elastic.php', 'elastic');

        $this->app->singleton(ElasticSearchService::CLIENT, function () {
            return ClientBuilder::create()
                ->setHosts(config('elastic.hosts', []))
                ->build();
        });
    }

    public function provides()
    {
        return [
            ElasticSearchService::CLIENT,
        ];
    }
}