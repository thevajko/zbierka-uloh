<?php
declare(strict_types=1);

require_once 'AValidator.php';

class NumberValidator extends AValidator
{
    public function validate(&$value): bool
    {
        if (is_int($value)) {
            $value = (int) $value;
            return true;
        }
        else if (is_float($value)) {
            $value = (float) $value;
            return true;
        }
        return false;
    }

    protected function getDefaultMessage(): string
    {
        return "Neplatná číselná hodnota";
    }
}