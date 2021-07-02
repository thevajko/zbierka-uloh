<?php


class Column
{
    private string $field;
    private string $title;
    private ?Closure $renderer;

    /**
     * Column constructor.
     * @param string $field
     * @param string $title
     * @param callable $renderer
     */
    public function __construct(string $field, string $title, Closure $renderer)
    {
        $this->field = $field;
        $this->title = $title;
        $this->renderer = $renderer;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function render($row): string
    {
        $renderer = $this->renderer;
        return $renderer($row);
    }


}