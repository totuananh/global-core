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
                static::PARAM_NORMALIZER => 'int',
            ],
            'username' => [
                static::PARAM_NORMALIZER => 'string',
            ],
            'name' => [
                static::PARAM_NORMALIZER => 'string',
            ],
            'partner_id' => [
                static::PARAM_NORMALIZER => 'int',
            ],
        ];
    }

    /**
     * Get the creator id
     *
     * @return int
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