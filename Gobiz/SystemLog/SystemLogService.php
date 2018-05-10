<?php

namespace Gobiz\SystemLog;

class SystemLogService
{
    /**
     * @return ExceptionLoggerInterface
     */
    public static function exception()
    {
        return app(ExceptionLoggerInterface::class);
    }

    /**
     * @return ApiAccessLoggerInterface
     */
    public static function apiAccess()
    {
        return app(ApiAccessLoggerInterface::class);
    }
}