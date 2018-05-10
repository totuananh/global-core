<?php

namespace Gobiz\SystemLog;

use Carbon\Carbon;
use Elasticsearch\Client;
use Exception;
use Psr\Log\LoggerInterface;

class ExceptionLogger implements ExceptionLoggerInterface
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
     * ExceptionLogger constructor
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
     * Log given exception
     *
     * @param Exception $exception
     * @param array $data
     */
    public function log(Exception $exception, array $data = [])
    {
        try {
            $this->elasticSearch->index([
                'index' => 'exception_' . date('Y_m_d'),
                'type' => 'exceptions',
                'body' => array_merge($data, $this->makeExceptionLogData($exception)),
            ]);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }

    /**
     * @param Exception $exception
     * @return array
     */
    protected function makeExceptionLogData(Exception $exception)
    {
        return [
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'time' => (new Carbon())->toIso8601String(),
        ];
    }
}