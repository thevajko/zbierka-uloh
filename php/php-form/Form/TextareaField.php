<?php
declare(strict_types=1);

require_once 'AFormField.php';

class TextareaField extends AFormField {

    public function __construct($name, string $label, $defaultValue, $form)
    {
        parent::__construct($name, $label, $defaultValue, $form);
    }


    protected function renderElement(): void
    {
        ?>
        <textarea name="<?=$this->name?>" id="<?=$this->name?>"><?=htmlentities($this->getValue())?></textarea>
        <?php
    }
}