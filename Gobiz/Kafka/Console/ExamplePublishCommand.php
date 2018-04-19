<?php

namespace Gobiz\Kafka\Console;

use Gobiz\Kafka\KafkaService;
use Illuminate\Console\Command;

class ExamplePublishCommand extends Command
{
    protected $signature = 'kafka:pub';

    protected $description = 'Test publish message';

    public function handle()
    {
        KafkaService::dispatcher()->publish('topic_name', $message = json_encode(['time' => time()]));
        $this->info('Published: ' . $message);
    }
}
