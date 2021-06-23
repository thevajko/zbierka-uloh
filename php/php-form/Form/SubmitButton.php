<?php
declare(strict_types=1);

require_once 'AFormElement.php';

class SubmitButton extends AFormElement
{
    private string $label;

    public function __construct(string $name, string $label)
    {
        parent::__construct($name);
        $this->label = $label;
    }


    public function render(): void
    {
        ?>
        <input name="<?=$this->name?>" type="submit" value="<?=$this->label?>">
        <?php
    }
}