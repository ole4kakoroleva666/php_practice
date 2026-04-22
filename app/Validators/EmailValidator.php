<?php

namespace Validators;

use Src\Validator\AbstractValidator;

class EmailValidator extends AbstractValidator
{
    protected string $message = 'Поле :field должно содержать корректный email';

    public function rule(): bool
    {
        if ($this->value === null || $this->value === '') {
            return true;
        }

        return filter_var($this->value, FILTER_VALIDATE_EMAIL) !== false;
    }
}