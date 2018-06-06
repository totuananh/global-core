<?php

namespace Gobiz\Validation;

use Exception;

class ValidationException extends Exception
{
    /**
     * @var array
     */
    protected $errors = [];

    /**
     * ValidationException constructor
     *
     * @param string|int $message
     * @param int $code
     * @param array $errors
     */
    public function __construct($message, $code = 0, array $errors = [])
    {
        // Case: new ValidationException($code);
        if (func_num_args() === 1 && is_int($message)) {
            $code = $message;
            $message = '';

        // Case: new ValidationException($code, $errors);
        } elseif (func_num_args() === 2 && is_int($message) && is_array($code)) {
            $errors = $code;
            $code = $message;
            $message = '';
        }

        parent::__construct($message, $code);

        $this->errors = $errors;
    }

    /**
     * Get the errors
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}