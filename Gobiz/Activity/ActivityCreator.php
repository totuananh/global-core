<?php

namespace Gobiz\Activity;

use Gobiz\Support\OptionsAccess;

class ActivityCreator extends OptionsAccess implements ActivityCreatorInterface
{
    /**
     * Make the options config
     *
     * @return array
     */
    protected function makeConfig()
    {
        return [
            'id' => [
                static::PARAM_ALLOWED_TYPES => ['int', 'string'],
            ],
            'username' => [
                static::PARAM_ALLOWED_TYPES => 'string',
            ],
            'name' => [
                static::PARAM_ALLOWED_TYPES => 'string',
            ],
            'partner_id' => [
                static::PARAM_ALLOWED_TYPES => ['int', 'string'],
                static::PARAM_NORMALIZER => 'int',
            ],
        ];
    }

    /**
     * Get the creator id
     *
     * @return int|string
     */
    public function getId()
    {
        return $this->get('id');
    }

    /**
     * Get the creator username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->get('username');
    }

    /**
     * Get the creator name
     *
     * @return string
     */
    public function getName()
    {
        return $this->get('name');
    }

    /**
     * Get the creator partner_id
     *
     * @return int
     */
    public function getPartnerId()
    {
        return $this->get('partner_id');
    }
}