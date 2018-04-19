<?php

namespace Gobiz\Activity\Loggers;

use Elasticsearch\Client;
use Gobiz\Activity\ActivityInterface;
use Gobiz\Activity\ActivityLoggerInterface;

class ElasticSearchLogger implements ActivityLoggerInterface
{
    /**
     * @var Client
     */
    protected $elastic;

    /**
     * @var string
     */
    protected $index;

    /**
     * @var string
     */
    protected $type;

    /**
     * ElasticSearchLogger constructor
     *
     * @param Client $elastic
     * @param string $index
     * @param string $type
     */
    public function __construct(Client $elastic, $index, $type)
    {
        $this->elastic = $elastic;
        $this->index = $index;
        $this->type = $type;
    }

    /**
     * Log the given activity
     *
     * @param ActivityInterface $activity
     */
    public function log(ActivityInterface $activity)
    {
        $this->elastic->index([
            'index' => $this->index . '_' . date('Y_m_d'),
            'type' => $this->type,
            'body' => array_merge($activity->getActivityAsArray(), [
                'partner_id' => $activity->getCreator()->getPartnerId(),
            ]),
        ]);
    }
}