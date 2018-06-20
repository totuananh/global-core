<?php

namespace Gobiz\Activity;

use Gobiz\Support\OptionsAccess;
use InvalidArgumentException;

class Activity extends OptionsAccess implements ActivityInterface
{
    const ID        = 'id';
    const CREATOR   = 'creator';
    const ACTION    = 'action';
    const OBJECTS   = 'objects';
    const IS_PUBLIC = 'is_public';
    const PAYLOAD   = 'payload';
    const TIME      = 'time';
    const MESSAGE   = 'message';

    /**
     * Make the options config
     *
     * @return array
     */
    protected function makeConfig()
    {
        return [
            static::ID => [
                static::PARAM_NORMALIZER => 'string',
            ],
            static::CREATOR => [
                static::PARAM_NORMALIZER => function ($input) {
                    return $this->normalizeCreator($input);
                },
                static::PARAM_DEFAULT => [],
            ],
            static::ACTION => [
                static::PARAM_ALLOWED_TYPES => 'string',
            ],
            static::IS_PUBLIC => [
                static::PARAM_NORMALIZER => 'int'
            ],
            static::MESSAGE => [
                static::PARAM_ALLOWED_TYPES => 'string',
                static::PARAM_DEFAULT => '',
            ],
            static::OBJECTS => [
                static::PARAM_ALLOWED_TYPES => 'array',
                static::PARAM_DEFAULT => [],
            ],
            static::TIME => [
                static::PARAM_NORMALIZER => 'int',
                static::PARAM_DEFAULT => time(),
            ],
            static::PAYLOAD => [
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
     * Lấy log id
     *
     * @return string|null
     */
    public function getId()
    {
        return $this->get(static::ID);
    }

    /**
     * Get the creator
     *
     * @return ActivityCreatorInterface
     */
    public function getCreator()
    {
        return $this->get(static::CREATOR);
    }

    /**
     * Get the action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->get(static::ACTION);
    }

    /**
     * get is public log
     *
     * @return boolean
     */
    public function getIsPublic()
    {
        return $this->get(static::IS_PUBLIC);
    }

    /**
     * get is message
     *
     * @return string
     */
    public function getMessage()
    {
       return $this->get(static::MESSAGE);
    }

    /**
     * Get the objects
     *
     * @return array
     */
    public function getObjects()
    {
        return $this->get(static::OBJECTS);
    }

    /**
     * Get the activity time
     *
     * @return int
     */
    public function getTime()
    {
        return $this->get(static::TIME);
    }

    /**
     * Get the payload
     *
     * @return array
     */
    public function getPayload()
    {
        return $this->get(static::PAYLOAD);
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
            static::CREATOR => [
                ActivityCreator::TYPE => $creator->getType(),
                ActivityCreator::ID => $creator->getId(),
                ActivityCreator::USERNAME => $creator->getUsername(),
                ActivityCreator::NAME => $creator->getName(),
                ActivityCreator::PARTNER_ID => $creator->getPartnerId(),
                ActivityCreator::IS_ADMIN => $creator->getIsAdmin()
            ],
            static::ACTION => $this->getAction(),
            static::IS_PUBLIC => $this->getIsPublic(),
            static::MESSAGE => $this->getMessage(),
            static::OBJECTS => $this->getObjects(),
            static::TIME => $this->getTime(),
            static::PAYLOAD => $this->getPayload(),
        ];
    }
}