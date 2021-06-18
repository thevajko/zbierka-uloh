<?php

require_once 'AFormElement.php';

class FormSubmit extends AFormElement
{
    private string $label;

    public function __construct(string $name, string $label)
    {
        parent::__construct($name);
        $this->label = $label;
    }


    public function render()
    {
        ?>
        <input name="<?=$this->name?>" type="submit" value="<?=$this->label?>">
        <?php
    }
}