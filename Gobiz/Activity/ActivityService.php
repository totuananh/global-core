<?php

namespace Gobiz\Activity;

class ActivityService
{
    /**
     * @return ActivityLoggerInterface
     */
    public static function logger()
    {
        return app(ActivityLoggerInterface::class);
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