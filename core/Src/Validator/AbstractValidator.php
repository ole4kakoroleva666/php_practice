<?php

namespace Src\Validator;

abstract class AbstractValidator
{
    protected string $field = '';
    protected $value;
    protected array $args = [];
    protected array $messageKeys = [];
    protected string $message = '';

    public function __construct(string $fieldName, $value, array $args = [], ?string $message = null)
    {
        $this->field = $fieldName;
        $this->value = $value;
        $this->args = $args;
        $this->message = $message ?? $this->message;

        $this->messageKeys = [
            ':value' => is_scalar($this->value) ? (string)$this->value : '',
            ':field' => $this->field,
            ':arg0' => $this->args[0] ?? '',
            ':arg1' => $this->args[1] ?? '',
        ];
    }

    public function validate()
    {
        if (!$this->rule()) {
            return $this->messageError();
        }

        return true;
    }

    private function messageError(): string
    {
        $message = $this->message;

        foreach ($this->messageKeys as $key => $value) {
            $message = str_replace($key, (string)$value, $message);
        }

        return $message;
    }

    abstract public function rule(): bool;
}