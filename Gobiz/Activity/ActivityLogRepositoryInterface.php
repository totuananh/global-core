<?php

namespace Gobiz\Activity;

interface ActivityLogRepositoryInterface
{
    /**
     * Lưu log activity
     *
     * @param ActivityInterface $activity
     */
    public function log(ActivityInterface $activity);

    /**
     * Lấy list activity logs
     *
     * @param ActivityLogFilter $filter
     * @return array
     */
    public function lists(ActivityLogFilter $filter);
}