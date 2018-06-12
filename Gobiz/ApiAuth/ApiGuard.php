<?php

namespace Gobiz\ApiAuth;

class ApiGuard implements ApiGuardInterface
{
    /**
     * @var ApiAuthenticatable|null
     */
    protected $user;

    /**
     * ApiGuard constructor
     *
     * @param ApiAuthenticatable|null $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Determine if the current user is authenticated.
     *
     * @return bool
     */
    public function check()
    {
        return !!$this->user();
    }

    /**
     * Get the currently authenticated user.
     *
     * @return ApiAuthenticatable|null
     */
    public function user()
    {
        return $this->user;
    }

    /**
     * Get the ID for the currently authenticated user.
     *
     * @return int|null
     */
    public function id()
    {
        return ($user = $this->user()) ? $user->getAuthIdentifier() : null;
    }
}