<?php

namespace Gobiz\ApiAuth;

use Closure;

interface ApiAuthManagerInterface
{
    /**
     * Register guard
     *
     * @param string $guard
     * @param ApiGuardInterface|Closure|string $instance
     * @return static
     */
    public function register($guard, $instance);

    /**
     * Determine if the given guard has been registered
     *
     * @param string $guard
     * @return bool
     */
    public function has($guard);

    /**
     * Get guard instance
     *
     * @param string $guard
     * @return ApiGuardInterface
     */
    public function get($guard);

    /**
     * Get the registered guard list
     *
     * @return array
     */
    public function lists();
}