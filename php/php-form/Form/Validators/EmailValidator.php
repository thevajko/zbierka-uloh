<?php
declare(strict_types=1);

require_once 'AValidator.php';

class EmailValidator extends AValidator
{
    public function validate(&$value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    protected function getDefaultMessage(): string
    {
        return "Položka nieje platný email";
    }
}