<?php

namespace Gobiz\Activity;

use Gobiz\Activity\Dispatchers\KafkaDispatcher;
use Gobiz\Activity\Loggers\ElasticSearchLogger;
use Gobiz\ElasticSearch\ElasticSearchService;
use Gobiz\Log\LogService;
use Illuminate\Support\ServiceProvider;

class ActivityServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/activity_log.php', 'activity_log');

        $this->app->singleton(ActivityLogRepositoryInterface::class, function () {
           return new ActivityLogRepository(
               ElasticSearchService::client(),
               config('activity_log.elastic_index'),
               config('activity_log.elastic_type')
           );
        });
    }

    public function provides()
    {
        return [
            ActivityLogRepositoryInterface::class,
        ];
    }
}