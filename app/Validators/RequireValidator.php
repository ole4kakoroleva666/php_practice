<?php

namespace Validators;

use Src\Validator\AbstractValidator;

class RequireValidator extends AbstractValidator
{
    protected string $message = 'Поле :field обязательно для заполнения';

    public function rule(): bool
    {
        if (is_array($this->value)) {
            return !empty($this->value);
        }

        return trim((string)$this->value) !== '';
    }
}