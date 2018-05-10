<?php

namespace Gobiz\SystemLog;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface ApiAccessLoggerInterface
{
    /**
     * Log api access
     *
     * @param Request $request
     * @param JsonResponse $response
     */
    public function log(Request $request, JsonResponse $response);
}