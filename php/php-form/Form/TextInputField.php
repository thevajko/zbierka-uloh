<?php
declare(strict_types=1);

require_once 'AFormField.php';

class TextInputField extends AFormField {

    private $type;

    public function __construct($name, string $label, $defaultValue, $form, $type = "text")
    {
        parent::__construct($name, $label, $defaultValue, $form);
        $this->type = $type;
    }


    protected function renderElement(): void
    {
        ?>
        <input type="<?=$this->type?>"
               name="<?=$this->name?>"
               id="<?=$this->name?>"
               value="<?=htmlentities("".$this->getValue(), ENT_QUOTES)?>">
        <?php
    }
}