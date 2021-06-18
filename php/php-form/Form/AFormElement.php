<?php
declare(strict_types=1);

abstract class AFormElement
{
    protected string $name;

    /**
     * AFormElement constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public abstract function render();
}