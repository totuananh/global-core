<?php

namespace Gobiz\Activity;

class ActivityService
{
    /**
     * @return ActivityDispatcherInterface
     */
    public static function dispatcher()
    {
        return app(ActivityDispatcherInterface::class);
    }

    /**
     * @return ActivityLoggerInterface
     */
    public static function logger()
    {
        return app(ActivityLoggerInterface::class);
    }

    /**
     * Push activity vào kafka
     *
     * @param ActivityInterface $activity
     */
    public static function dispatch(ActivityInterface $activity)
    {
        static::dispatcher()->dispatch($activity);
    }

    /**
     * Lưu activity vào elastic search
     *
     * @param ActivityInterface $activity
     */
    public static function log(ActivityInterface $activity)
    {
        static::logger()->log($activity);
    }
}