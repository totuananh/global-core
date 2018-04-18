<?php

namespace Gobiz\Log;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class LoggerFactory implements LoggerFactoryInterface
{
    /**
     * @var string
     */
    protected $storagePath;

    /**
     * LoggerFactory constructor
     *
     * @param string $storagePath
     */
    public function __construct($storagePath)
    {
        $this->storagePath = rtrim($storagePath, '/');
    }

    /**
     * Make the new logger
     *
     * @param string $name
     * @return LoggerInterface
     */
    public function make($name)
    {
        return new Logger($name, [$this->makeRotatingFileHandler($name)]);
    }

    /**
     * Make the daily file log handler
     *
     * @param string $name
     * @return RotatingFileHandler
     */
    protected function makeRotatingFileHandler($name)
    {
        return new RotatingFileHandler($this->getLogFile($name));
    }

    /**
     * Get the path of the log file
     *
     * @param string $name
     * @return string
     */
    protected function getLogFile($name)
    {
        return $this->storagePath . '/' . $name . '.log';
    }
}