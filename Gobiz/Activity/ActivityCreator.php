<?php

namespace Gobiz\Activity;

use Gobiz\Support\OptionsAccess;

class ActivityCreator extends OptionsAccess implements ActivityCreatorInterface
{
    const TYPE       = 'type';
    const ID         = 'id';
    const USERNAME   = 'username';
    const NAME       = 'name';
    const PARTNER_ID = 'partner_id';
    const IS_ADMIN   = 'is_admin';

    /**
     * Make the options config
     * type: (system, action, comment)
     * is_admin: (true, false)
     * @return array
     */
    protected function makeConfig()
    {
        return [
            static::TYPE => [
                static::PARAM_ALLOWED_TYPES => static::STRING,
                static::PARAM_DEFAULT => ''
            ],
            static::ID => [
                static::PARAM_NORMALIZER => 'int',
            ],
            static::USERNAME => [
                static::PARAM_NORMALIZER => static::STRING,
            ],
            static::NAME => [
                static::PARAM_NORMALIZER => static::STRING,
            ],
            static::PARTNER_ID => [
                static::PARAM_NORMALIZER => 'int',
            ],
            static::IS_ADMIN => [
                static::PARAM_NORMALIZER => 'int'
            ]
        ];
    }

    /**
     * Get the creator type
     *
     * @return string
     */
    public function getType()
    {
        return $this->get(static::TYPE);
    }

    /**
     * Get the creator id
     *
     * @return int
     */
    public function getId()
    {
        return $this->get(static::ID);
    }

    /**
     * Get the creator username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->get(static::USERNAME);
    }

    /**
     * Get the creator name
     *
     * @return string
     */
    public function getName()
    {
        return $this->get(static::NAME);
    }

    /**
     * Get the creator partner_id
     *
     * @return int
     */
    public function getPartnerId()
    {
        return $this->get(static::PARTNER_ID);
    }

    /**
     * Get the creator is_admin
     *
     * @return int
     */
    public function getIsAdmin()
    {
        return $this->get(static::IS_ADMIN);
    }
}