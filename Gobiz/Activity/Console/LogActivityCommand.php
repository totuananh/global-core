<?php

namespace Gobiz\Activity\Console;

use Gobiz\Activity\ActivityInterface;
use Gobiz\Activity\ActivityService;
use Illuminate\Console\Command;

class LogActivityCommand extends Command
{
    protected $signature = 'Activity:Log';

    protected $description = 'Subscribe topic ACTIVITY_LOG trên kafka và lưu vào elastic search';

    public function handle()
    {
        ActivityService::dispatcher()->listen(function (ActivityInterface $activity) {
            ActivityService::log($activity);
        });
    }
}