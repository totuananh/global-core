<?php

namespace Gobiz\ApiAuth;

interface ApiGuardInterface
{
    /**
     * Determine if the current user is authenticated.
     *
     * @return bool
     */
    public function check();

    /**
     * Get the currently authenticated user.
     *
     * @return ApiAuthenticatable|null
     */
    public function user();

    /**
     * Get the ID for the currently authenticated user.
     *
     * @return int|null
     */
    public function id();
}