<?php

namespace Validators;

use Src\Validator\AbstractValidator;

class DateValidator extends AbstractValidator
{
    protected string $message = 'Поле :field должно содержать корректную дату';

    public function rule(): bool
    {
        if ($this->value === null || $this->value === '') {
            return true;
        }

        return strtotime((string)$this->value) !== false;
    }
}