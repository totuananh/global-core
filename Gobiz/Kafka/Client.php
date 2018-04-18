<?php

namespace Gobiz\Kafka;

use Illuminate\Support\Facades\Config;
use Kafka\Consumer;
use Kafka\ConsumerConfig;

class Client
{
    private $config;
    private $broker_type;
    private $broker;
    private $current_topic;
    private $producer;
    private $consumer;
    private $op;

    public function __construct($config, $type)
    {
        $this->op       = $type;
        $this->config   = $config;
        $this->broker   = $this->config['brokers'][$this->config['broker_default']];
        switch ($type) {
            case 'pub':
                $this->connPub();
                break;
        }
    }

    /**
     * @desc Lựa chọn broker nào để gửi message (phân loại các message trên các cluster khác nhau)
     * @param $broker_type
     */
    public function setBrokerType($broker_type)
    {
        if (!isset($this->config['brokers'][$broker_type])) {
            $broker_type = $this->config['broker_default'];
        }

        $this->broker_type  = $broker_type;
        $this->broker       = $this->config['brokers'][$this->broker_type];
    }

    /**
     * @desc Hàm kết nối đến zookeeper cho publish message
     */
    private function connPub()
    {
        $this->producer = \Kafka\ProducerConfig::getInstance();
        $this->producer->setMetadataRefreshIntervalMs(10000);
        $this->producer->setMetadataBrokerList($this->broker['kk']['HOST'].':'.$this->broker['kk']['PORT']);
        $this->producer->setBrokerVersion('1.0.0');
        $this->producer->setRequiredAck(1);
        $this->producer->setIsAsyn(false);
        $this->producer->setProduceInterval(500);
    }

    /**
     * @desc Hàm kết nối đến zookeeper cho subscribe/consumer topic
     */
    private function initSub(Array $input)
    {
        $conf = new \RdKafka\Conf();

        // Set the group id. This is required when storing offsets on the broker
        $conf->set('group.id', $this->getGroupId());

        $rk = new \RdKafka\Consumer($conf);
        $rk->addBrokers($this->broker['kk']['HOST'].':'.$this->broker['kk']['PORT']);

        $topicConf = new \RdKafka\TopicConf();
        $topicConf->set('auto.commit.interval.ms', 100);

        // Set the offset store method to 'file'
        $topicConf->set('offset.store.method', 'file');
        $topicConf->set('offset.store.path', sys_get_temp_dir());
        $topicConf->set('auto.offset.reset', 'beginning');

        return [$rk, $topicConf];
    }

    /**
     * @desc Hàm bắn message đi kafka
     * @param array $input
     * @return mixed - dữ liệu trả về sau khi bắn message sang kafka
     * @throws \Exception
     */

    public function pub(Array $input)
    {
        $this->validatorTopic($input);
        $topic = $this->getTopicName($input);

        $producer = new \Kafka\Producer();

        $producer->send([
            [
                'topic' => $topic,
                'value' => $input['msg'],
                'key'   => ''
            ],
        ]);
    }

    public function sub(Array $input, $callback)
    {
        $this->validatorTopic($input);
        $topicName = $this->getTopicName($input);

        list($rk, $topicConf) = $this->initSub($input);

        $topic = $rk->newTopic($topicName, $topicConf);

        // Start consuming partition 0
        $topic->consumeStart(0, RD_KAFKA_OFFSET_END);

        while (true) {
            $message = $topic->consume(0, 120*10000);
            switch ($message->err) {
                case RD_KAFKA_RESP_ERR_NO_ERROR:
                    $callback($message);
                    break;
                case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                    //echo "No more messages; will wait for more\n";
                    break;
                case RD_KAFKA_RESP_ERR__TIMED_OUT:
                    //echo "Timed out\n";
                    break;
                default:
                    throw new \Exception($message->errstr(), $message->err);
                    break;
            }
        }
    }

    /**
     * Dùng để lấy tên topic
     * @return mixed
     */
    private function getTopicName()
    {
        $topic = $this->current_topic;

        if (is_array($topic)) {
            if (isset($topic['name'])) {
                return $topic['name'];
            }
        } else {
            return $topic;
        }
    }

    /**
     * Dùng để lấy tên group topic
     * @return mixed
     */
    private function getGroupId()
    {
        $topic = $this->current_topic;

        if (is_array($topic)) {
            if (isset($topic['consumer_group'])) {
                return $topic['consumer_group'];
            }
        } else {
            return null;
        }
    }

    /**
     * Hàm xác thực các thông tin về partition và topic
     * @param array $input
     * @throws \Exception
     */
    private function validatorTopic(Array $input)
    {
        $partition  = (isset($input['p']) ? $input['p'] : 0);
        $topics     = config('kafka-p'.$partition);

        if ($this->op == 'pub') {
            // Chỉ có publish message mới validate về partition và topic
            if (!is_array($topics)) {
                throw new \Exception('Không tìm thấy cấu hình partition '.$partition);
            }

            if (!isset($topics[$input['topic']])) {
                throw new \Exception('Không tìm thấy topic '.$input['topic'].' trong partition '.$partition);
            }
        }

        $this->current_topic = $topics[$input['topic']];
    }
}

