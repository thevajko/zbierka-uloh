<?php
declare(strict_types=1);

require_once 'AValidator.php';

class RequiredValidator extends AValidator
{
    public function validate(&$value): bool {
        return !empty($value);
    }

    protected function getDefaultMessage(): string
    {
        return "Položka musí byť vyplnená";
    }
}