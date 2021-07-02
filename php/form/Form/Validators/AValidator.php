<?php
declare(strict_types=1);

abstract class AValidator
{
    private string $errorMessage;

    public function __construct(string $errorMessage = "")
    {
        if (empty($errorMessage)) {
            $errorMessage = $this->getDefaultMessage();
        }
        $this->errorMessage = $errorMessage;
    }

    public abstract function validate(&$value): bool;

    public function getMessage()
    {
        return $this->errorMessage;
    }

    protected abstract function getDefaultMessage(): string;
}