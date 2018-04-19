<?php

namespace Gobiz\Log;

use Psr\Log\LoggerInterface;

interface LoggerFactoryInterface
{
    /**
     * Make the new logger
     *
     * @param string $name
     * @return LoggerInterface
     */
    public function make($name);
}