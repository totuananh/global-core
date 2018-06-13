<?php

namespace Gobiz\ApiAuth;

use Gobiz\Support\Manager;

class ApiAuthManager extends Manager implements ApiAuthManagerInterface
{
    /**
     * Get the storage key of driver
     *
     * @param string $driver
     * @return string
     */
    protected function makeStorageKey($driver)
    {
        return 'ApiAuth.' . $driver;
    }
}