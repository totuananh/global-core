<?php

namespace Gobiz\SystemLog;

use Carbon\Carbon;
use Elasticsearch\Client;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Psr\Log\LoggerInterface;

class ApiAccessLogger implements ApiAccessLoggerInterface
{
    /**
     * @var Client
     */
    protected $elasticSearch;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var array
     */
    protected $secureParams = ['password', 'pass'];

    /**
     * ApiAccessLogger constructor
     *
     * @param Client $elasticSearch
     * @param LoggerInterface $logger
     */
    public function __construct(Client $elasticSearch, LoggerInterface $logger)
    {
        $this->elasticSearch = $elasticSearch;
        $this->logger = $logger;
    }

    /**
     * Log api access
     *
     * @param Request $request
     * @param JsonResponse $response
     */
    public function log(Request $request, JsonResponse $response)
    {
        try {
            $index = $this->makeIndexName();

            if (!$this->hasIndex($index)) {
                $this->createIndex($index);
            }

            $this->elasticSearch->index([
                'index' => $index,
                'type' => 'api_access',
                'body' => $this->makeApiAccessData($request, $response),
            ]);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }

    /**
     * @return string
     */
    protected function makeIndexName()
    {
        return 'api_access_' . date('Y_m_d');
    }

    /**
     * @param string $index
     * @return bool
     */
    protected function hasIndex($index)
    {
        return $this->elasticSearch->indices()->exists(compact('index'));
    }

    /**
     * @param string $index
     */
    protected function createIndex($index)
    {
        $this->elasticSearch->indices()->create([
            'index' => $index,
            'body' => [
                'mappings' => [
                    'api_access' => [
                        'properties' => [
                            'input' => [
                                'enabled' => false,
                            ],
                            'response' => [
                                'enabled' => false,
                            ],
                        ]
                    ]
                ]
            ]
        ]);
    }

    /**
     * @param Request $request
     * @param JsonResponse $response
     * @return array
     */
    protected function makeApiAccessData($request, $response)
    {
        return [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'method' => $request->method(),
            'route' => $this->getRequestRouteName($request),
            'url' => $request->url(),
            'status' => $response->status(),
            'time' => (new Carbon())->toIso8601String(),
            'input' => $this->exceptSecureData($request->input()),
            'response' => $this->exceptSecureData($response->getData(true)),
        ];
    }

    /**
     * @param Request $request
     * @return string
     */
    protected function getRequestRouteName($request)
    {
        if (!is_array($route = $request->route())) {
            return '';
        }

        return (string)Arr::get($route, '1.as');
    }

    /**
     * @param $data
     * @return array|string
     */
    protected function exceptSecureData($data)
    {
        if (is_array($data)) {
            $output = [];
            foreach ($data as $param => $value) {
                $output[$param] = $this->isSecureParam($param) ? '***' : $this->exceptSecureData($value);
            }

            return $output;
        }

        if (is_object($data)) {
            return get_class($data);
        }

        return $data;
    }

    /**
     * @param string $param
     * @return bool
     */
    protected function isSecureParam($param)
    {
        return Str::contains(strtolower($param), $this->secureParams);
    }
}