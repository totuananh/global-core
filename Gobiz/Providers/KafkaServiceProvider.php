<?php

namespace Gobiz\Providers;


use Gobiz\Kafka\Client;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class KafkaServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $brokerTopicConfig = [
            'log'       => [],
            'metric'    => [],
            'queue'     => ['p0', 'p1', 'p2', 'p3', 'p4', 'p5'],
            'log'       => [],
        ];

        foreach ($brokerTopicConfig as $key => $brokerType) {
            foreach ($brokerType as $type) {
                $path = __DIR__ . '/../Config/kafka/broker/'.$key.'/'.$type.'.php';

                if (file_exists($path)) {
                    $this->mergeConfigFrom($path, 'kafka-'.$type);
                }
            }
        }

        // Load main config file of kafka
        $this->mergeConfigFrom(__DIR__ . '/../Config/kafka.php', 'kafka');

        $this->app->singleton('kafka.pub', function ($app) {
            return new Client(config('kafka'), 'pub');
        });

        $this->app->singleton('kafka.sub', function ($app) {
            return new Client(config('kafka'), 'sub');
        });
    }

    public function provides()
    {
        return ['kafka'];
    }
}