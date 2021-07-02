<?php
declare(strict_types=1);

require_once 'AFormField.php';

class SelectField extends AFormField {

    private $values;

    public function __construct($name, string $label, $defaultValue, $form, $values)
    {
        parent::__construct($name, $label, $defaultValue, $form);
        $this->values = $values;
        if (!isset($this->values[$this->value])){
            $this->value = "";
        }
    }
    protected function renderElement(): void
    {
        ?>
        <select name="<?=$this->name?>"
               id="<?=$this->name?>">
            <option value=""> - </option>
        <?php foreach ($this->values as $key => $val) { ?>
            <option value="<?=htmlentities($key, ENT_QUOTES)?>" <?=($this->value == $key) ? "selected" : ""?>><?=htmlentities($val)?></option>
        <?php } ?>
        </select>
        <?php
    }
}