<?php

namespace Gobiz\Activity;

interface ActivityLoggerInterface
{
    /**
     * Log the given activity
     *
     * @param ActivityInterface $activity
     */
    public function log(ActivityInterface $activity);
}