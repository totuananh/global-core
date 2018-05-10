<?php

namespace Gobiz\SystemLog;

use Gobiz\ElasticSearch\ElasticSearchService;
use Gobiz\Log\LogService;
use Illuminate\Support\ServiceProvider;

class SystemLogServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(ExceptionLoggerInterface::class, function () {
            return new ExceptionLogger(ElasticSearchService::client(), LogService::logger('exception-logger'));
        });

        $this->app->singleton(ApiAccessLoggerInterface::class, function () {
            return new ApiAccessLogger(ElasticSearchService::client(), LogService::logger('api-access-logger'));
        });
    }

    public function provides()
    {
        return [
            ExceptionLoggerInterface::class,
            ApiAccessLoggerInterface::class,
        ];
    }
}