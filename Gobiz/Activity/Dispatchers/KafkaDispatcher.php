<?php

namespace Gobiz\Activity\Dispatchers;

use Exception;
use Gobiz\Activity\Activity;
use Gobiz\Activity\ActivityDispatcherInterface;
use Gobiz\Activity\ActivityInterface;
use Gobiz\Kafka\Client as KafkaClient;
use Psr\Log\LoggerInterface;

class KafkaDispatcher implements ActivityDispatcherInterface
{
    /**
     * @var KafkaClient
     */
    protected $producer;

    /**
     * @var KafkaClient
     */
    protected $consumer;

    /**
     * @var string
     */
    protected $topic;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * KafkaDispatcher constructor
     *
     * @param KafkaClient $producer
     * @param KafkaClient $consumer
     * @param string $topic
     * @param LoggerInterface $logger
     */
    public function __construct(KafkaClient $producer, KafkaClient $consumer, $topic, LoggerInterface $logger = null)
    {
        $this->producer = $producer;
        $this->consumer = $consumer;
        $this->topic = $topic;
        $this->logger = $logger;
    }

    /**
     * Dispatch the activity
     *
     * @param ActivityInterface $activity
     */
    public function dispatch(ActivityInterface $activity)
    {
        $this->producer->pub([
            'topic' => $this->topic,
            'msg' => json_encode($activity->getActivityAsArray()),
        ]);
    }

    /**
     * Register activity listener
     *
     * @param callable $listener
     */
    public function listen(callable $listener)
    {
        $this->consumer->sub(['topic' => $this->topic], function ($message) use ($listener) {
            $this->logger->debug('Receiving message: ' . $message->offset);
            $this->invokeListener($listener, $message->payload);
            $this->logger->debug('Received message: ' . $message->offset);
        });
    }

    /**
     * @param callable $listener
     * @param string $encodedActivity
     */
    protected function invokeListener(callable $listener, $encodedActivity)
    {
        try {
            $activity = new Activity(json_decode($encodedActivity, true));
        } catch (\Exception $e) {
            $this->logException($e);
            return;
        }

        call_user_func($listener, $activity);
    }

    /**
     * @param Exception $exception
     */
    protected function logException(Exception $exception)
    {
        if (!$this->logger) {
            return;
        }

        $this->logger->error($exception->getMessage(), [
            'code' => $exception->getCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ]);
    }
}