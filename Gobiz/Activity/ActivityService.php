<?php

namespace Gobiz\Activity;

class ActivityService
{
    /**
     * @return ActivityLogRepositoryInterface
     */
    public static function repository()
    {
        return app(ActivityLogRepositoryInterface::class);
    }

    /**
     * Lưu log activity
     *
     * @param ActivityInterface $activity
     */
    public static function log(ActivityInterface $activity)
    {
        static::repository()->log($activity);
    }
}