<?php

namespace Gobiz\SystemLog\Middleware;

use Closure;
use Gobiz\SystemLog\ApiAccessLoggerInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LogApiAccess
{
    /**
     * @var ApiAccessLoggerInterface
     */
    protected $apiAccessLogger;

    /**
     * LogApiAccess constructor
     *
     * @param ApiAccessLoggerInterface $apiAccessLogger
     */
    public function __construct(ApiAccessLoggerInterface $apiAccessLogger)
    {
        $this->apiAccessLogger = $apiAccessLogger;
    }

    /**
     * @param Request $request
     * @param Closure $next
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($response instanceof JsonResponse) {
            $this->apiAccessLogger->log($request, $response);
        }

        return $response;
    }
}