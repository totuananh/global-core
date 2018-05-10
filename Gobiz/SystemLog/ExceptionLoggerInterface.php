<?php
namespace Gobiz\SystemLog;

use Exception;

interface ExceptionLoggerInterface
{
    /**
     * Log given exception
     *
     * @param Exception $exception
     * @param array $data
     */
    public function log(Exception $exception, array $data = []);
}