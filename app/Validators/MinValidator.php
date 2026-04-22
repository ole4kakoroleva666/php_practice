<?php

namespace Validators;

use Src\Validator\AbstractValidator;

class MinValidator extends AbstractValidator
{
    protected string $message = 'Поле :field должно содержать минимум :arg0 символов';

    public function rule(): bool
    {
        $min = (int)($this->args[0] ?? 0);

        return mb_strlen(trim((string)$this->value)) >= $min;
    }
}