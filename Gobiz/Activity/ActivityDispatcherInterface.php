<?php

namespace Gobiz\Activity;

interface ActivityDispatcherInterface
{
    /**
     * Dispatch the activity
     *
     * @param ActivityInterface $activity
     */
    public function dispatch(ActivityInterface $activity);

    /**
     * Register activity listener
     *
     * @param callable $listener
     */
    public function listen(callable $listener);
}