<?php

namespace Gobiz\Validation;

interface ValidatorInterface
{
    /**
     * Perform validate
     *
     * @throws ValidationException
     */
    public function validate();
}