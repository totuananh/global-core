<?php

namespace Gobiz\Validation;

class Rule
{
    /**
     * Return true if given input is empty
     *
     * @param mixed $input
     * @return bool
     */
    public static function isEmpty($input)
    {
        return is_array($input) || is_object($input)
            ? empty($input)
            : strval($input) === '';
    }
}