<?php

namespace Gobiz\Validation;

abstract class Validator implements ValidatorInterface
{
    /**
     * Input
     *
     * @var array
     */
    protected $input = [];

    /**
     * Validator constructor
     *
     * @param array $input
     */
    public function __construct(array $input)
    {
        $this->input = $input;
    }

    /**
     * Get input
     *
     * @param null|string $key
     * @param mixed $default
     * @return array|mixed
     */
    public function input($key = null, $default = null)
    {
        if (is_null($key)) {
            return $this->input;
        }

        return isset($this->input[$key]) ? $this->input[$key] : $default;
    }
}