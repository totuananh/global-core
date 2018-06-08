<?php

namespace Gobiz\Kafka;

use RdKafka\Conf;
use RdKafka\Consumer;
use RdKafka\ConsumerTopic;
use RdKafka\Producer;
use RdKafka\Queue;
use RdKafka\TopicConf;
use RuntimeException;

class Dispatcher implements DispatcherInterface
{
    /**
     * @var string
     */
    protected $brokers;

    /**
     * @var string
     */
    protected $offsetStorePath;

    /**
     * @var Producer
     */
    protected $producer;

    /**
     * Dispatcher constructor
     *
     * @param string $brokers
     * @param string $offsetStorePath
     */
    public function __construct($brokers, $offsetStorePath = null)
    {
        $this->brokers = $brokers;
        $this->offsetStorePath = $offsetStorePath ?: sys_get_temp_dir();
    }

    /**
     * Publish message to the given topic
     *
     * @param string $topic
     * @param string $payload
     * @param null|string $key
     */
    public function publish($topic, $payload, $key = null)
    {
        $conf = new TopicConf();
        $conf->set('message.timeout.ms', 1000);

        $this->makeProducer()
            ->newTopic($topic, $conf)
            ->produce(RD_KAFKA_PARTITION_UA, 0, $payload, $key);

    }

    /**
     * @return Producer
     */
    protected function makeProducer()
    {
        if (is_null($this->producer)) {
            $this->producer = new Producer();
            $this->producer->addBrokers($this->brokers);
        }

        return $this->producer;
    }

    /**
     * Subscribe message of the given topics
     *
     * @param string|array $topics
     * @param string $groupId
     * @param callable $listener
     */
    public function subscribe($topics, $groupId, callable $listener)
    {
        $consumer = $this->makeConsumer($groupId);

        $queue = $consumer->newQueue();

        foreach ((array)$topics as $topic) {
            $topic = $this->makeConsumerTopic($consumer, $topic);
            $topic->consumeQueueStart(0, RD_KAFKA_OFFSET_STORED, $queue);
        }

        while (true) {
            $this->consumeMessage($queue, $listener);
        }
    }

    /**
     * @param string $groupId
     * @return Consumer
     */
    protected function makeConsumer($groupId)
    {
        $conf = new Conf();
        $conf->set('group.id', $groupId);

        $consumer = new Consumer($conf);
        $consumer->addBrokers($this->brokers);
        
        return $consumer;
    }

    /**
     * @param Consumer $consumer
     * @param string $topic
     * @return ConsumerTopic
     */
    protected function makeConsumerTopic(Consumer $consumer, $topic)
    {
        $topicConf = new TopicConf();
        $topicConf->set('auto.commit.interval.ms', 1000);
        $topicConf->set('offset.store.method', 'file');
        $topicConf->set('offset.store.path', $this->offsetStorePath);
        $topicConf->set('auto.offset.reset', 'beginning');

        return $consumer->newTopic($topic, $topicConf);
    }

    /**
     * @param Queue $queue
     * @param callable $listener
     * @throws RuntimeException
     */
    protected function consumeMessage(Queue $queue, callable $listener)
    {
        $message = $queue->consume(120*1000);

        if (!isset($message->err)) {
            return;
        }

        switch ($message->err) {
            case RD_KAFKA_RESP_ERR_NO_ERROR:
                $listener($message);
                return;
            case RD_KAFKA_RESP_ERR__PARTITION_EOF:
            case RD_KAFKA_RESP_ERR__TIMED_OUT:
                return;
            default:
                throw new RuntimeException($message->errstr(), $message->err);
        }
    }
}