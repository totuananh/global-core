<?php

namespace Gobiz\Activity;

use Carbon\Carbon;
use DateTime;
use Elasticsearch\Client;
use Illuminate\Support\Arr;

class ActivityLogRepository implements ActivityLogRepositoryInterface
{
    /**
     * @var Client
     */
    protected $elasticSearch;

    /**
     * @var string
     */
    protected $index;

    /**
     * @var string
     */
    protected $type;

    /**
     * ActivityLogRepository constructor
     *
     * @param Client $elasticSearch
     * @param string $index
     * @param string $type
     */
    public function __construct(Client $elasticSearch, $index, $type)
    {
        $this->elasticSearch = $elasticSearch;
        $this->index = $index;
        $this->type = $type;
    }

    /**
     * Lưu log activity
     *
     * @param ActivityInterface $activity
     */
    public function log(ActivityInterface $activity)
    {
        return $this->elasticSearch->index([
            'index' => $this->index . '_' . date('Y_m_d'),
            'type' => $this->type,
            'body' => array_merge($activity->getActivityAsArray(), [
                'partner_id' => $activity->getCreator()->getPartnerId(),
            ]),
        ]);
    }

    /**
     * Lấy list activity logs
     *
     * @param ActivityLogFilter $filter
     * @return array
     */
    public function lists(ActivityLogFilter $filter)
    {
        $res = $this->elasticSearch->search([
            'index' => $this->index . '_*',
            'type' => $this->type,
            'body' => [
                'query' => $this->makeQuery($filter),
                'sort' => [['time' => 'desc']],
                'from' => ($filter->page - 1) * $filter->per_page,
                'size' => $filter->per_page,
            ],
        ]);

        return [
            'logs' => array_map(function (array $doc) {
                return $this->makeActivityLog($doc);
            }, Arr::get($res, 'hits.hits', [])),
            'total' => Arr::get($res, 'hits.total', 0),
        ];
    }

    /**
     * @param ActivityLogFilter $filter
     * @return array
     */
    protected function makeQuery(ActivityLogFilter $filter)
    {
        $queryFilter = [
            ['term' => ['partner_id' => $filter->partner_id]],
        ];

        if ($filter->creator_id) {
            $queryFilter[] = ['term' => ['creator.id' => $filter->creator_id]];
        }

        if ($filter->creator_username) {
            $queryFilter[] = ['term' => ['creator.username.keyword' => $filter->creator_username]];
        }

        if ($filter->action) {
            $queryFilter[] = ['terms' => ['action.keyword' => $filter->action]];
        }

        if ($filter->object) {
            foreach ($filter->object as $objectType => $objectId) {
                $queryFilter[] = ['term' => ['objects.' . $objectType => $objectId]];
            }
        }

        if ($filter->time_from || $filter->time_to) {
            $queryFilter[] = ['range' => ['time' => $this->makeQueryRange($filter->time_from, $filter->time_to)]];
        }

        return ['bool' => ['filter' => $queryFilter]];
    }

    /**
     * @param DateTime|null $from
     * @param DateTime|null $to
     * @return array
     */
    protected function makeQueryRange($from, $to)
    {
        $query = [];

        if ($from) {
            $query['gte'] = (new Carbon($from))->startOfDay()->getTimestamp();
        }

        if ($to) {
            $query['lte'] = (new Carbon($to))->endOfDay()->getTimestamp();
        }

        return $query;
    }

    /**
     * @param array $doc
     * @return Activity
     */
    protected function makeActivityLog(array $doc)
    {
        return new Activity(array_merge($doc['_source'], [
            'id' => $doc['_id'],
        ]));
    }
}