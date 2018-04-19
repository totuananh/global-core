<?php

namespace Gobiz\Kafka\Console;

use Gobiz\Kafka\KafkaService;
use Illuminate\Console\Command;

class ExampleSubscribeCommand extends Command
{
    protected $signature = 'kafka:sub';

    protected $description = 'Test subscribe message';

    public function handle()
    {
        // Khai báo consumer name để hệ thống lưu lại offset message cuối mà consumer đã subscribe
        KafkaService::dispatcher()->subscribe('topic_name', 'consumer_name', function ($message) {
            $this->info(print_r($message, true));
        });
    }
}
