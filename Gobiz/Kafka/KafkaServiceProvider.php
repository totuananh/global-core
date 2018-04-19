<?php

namespace Gobiz\Kafka;

use Illuminate\Support\ServiceProvider;

class KafkaServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/kafka.php', 'kafka');

        $this->app->singleton(ConnectionManagerInterface::class, function () {
            return $this->makeConnectionManager();
        });
    }

    /**
     * @return ConnectionManager
     */
    protected function makeConnectionManager()
    {
        $connections = new ConnectionManager($this->app);

        foreach (config('kafka.connections') as $connection => $config) {
            $connections->register($connection, new Dispatcher($config['brokers']));
        }

        return $connections;
    }

    public function provides()
    {
        return [
            ConnectionManagerInterface::class,
            DispatcherInterface::class,
        ];
    }
}