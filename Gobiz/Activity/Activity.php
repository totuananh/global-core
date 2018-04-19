<?php

namespace Gobiz\Activity;

use Gobiz\Support\OptionsAccess;
use InvalidArgumentException;

class Activity extends OptionsAccess implements ActivityInterface
{
    /**
     * Make the options config
     *
     * @return array
     */
    protected function makeConfig()
    {
        return [
            'creator' => [
                static::PARAM_NORMALIZER => function ($input) {
                    return $this->normalizeCreator($input);
                },
            ],
            'action' => [
                static::PARAM_ALLOWED_TYPES => 'string',
            ],
            'objects' => [
                static::PARAM_ALLOWED_TYPES => 'array',
                static::PARAM_DEFAULT => [],
            ],
            'description' => [
                static::PARAM_ALLOWED_TYPES => 'string',
            ],
            'time' => [
                static::PARAM_NORMALIZER => 'int',
                static::PARAM_DEFAULT => time(),
            ],
            'payload' => [
                static::PARAM_ALLOWED_TYPES => 'array',
                static::PARAM_DEFAULT => [],
            ],
        ];
    }

    /**
     * @param mixed $input
     * @return ActivityCreator
     * @throws InvalidArgumentException
     */
    protected function normalizeCreator($input)
    {
        if ($input instanceof ActivityCreatorInterface) {
            return $input;
        }

        if (is_array($input)) {
            return new ActivityCreator($input);
        }

        throw new InvalidArgumentException('The creator must is instance of ActivityCreatorInterface or is an array');
    }

    /**
     * Get the creator
     *
     * @return ActivityCreatorInterface
     */
    public function getCreator()
    {
        return $this->get('creator');
    }

    /**
     * Get the action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->get('action');
    }

    /**
     * Get the objects
     *
     * @return array
     */
    public function getObjects()
    {
        return $this->get('objects');
    }

    /**
     * Get the description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->get('description');
    }

    /**
     * Get the activity time
     *
     * @return int
     */
    public function getTime()
    {
        return $this->get('time');
    }

    /**
     * Get the payload
     *
     * @return array
     */
    public function getPayload()
    {
        return $this->get('payload');
    }

    /**
     * Get the activity as array
     *
     * @return array
     */
    public function getActivityAsArray()
    {
        $creator = $this->getCreator();

        return [
            'creator' => [
                'id' => $creator->getId(),
                'username' => $creator->getUsername(),
                'name' => $creator->getName(),
                'partner_id' => $creator->getPartnerId(),
            ],
            'action' => $this->getAction(),
            'objects' => $this->getObjects(),
            'description' => $this->getDescription(),
            'time' => $this->getTime(),
            'payload' => $this->getPayload(),
        ];
    }
}