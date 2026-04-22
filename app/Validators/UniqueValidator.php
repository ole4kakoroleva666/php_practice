<?php

namespace Validators;

use Illuminate\Database\Capsule\Manager as Capsule;
use Src\Validator\AbstractValidator;

class UniqueValidator extends AbstractValidator
{
    protected string $message = 'Значение поля :field уже существует';

    public function rule(): bool
    {
        if (empty($this->args[0]) || empty($this->args[1])) {
            return false;
        }

        $table = $this->args[0];
        $column = $this->args[1];

        return !Capsule::table($table)
            ->where($column, $this->value)
            ->exists();
    }
}