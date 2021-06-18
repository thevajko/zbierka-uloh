<?php
declare(strict_types=1);

require_once 'AFormField.php';

class SelectField extends AFormField {

    private $values;

    public function __construct($name, string $label, $defaultValue, $form, $values)
    {
        $this->values = $values;
        parent::__construct($name, $label, $defaultValue, $form);
    }

    protected function parseValue($value)
    {
        $value = parent::parseValue($value);
        if (!isset($this->values[$value])){
            $value = "";
        }
        return $value;
    }


    protected function renderElement(): void
    {
        ?>
        <select name="<?=$this->name?>"
               id="<?=$this->name?>"
               value="<?=htmlentities($this->getValue(), ENT_QUOTES)?>">
            <option value=""> - </option>
        <?php foreach ($this->values as $key => $val) { ?>
            <option value="<?=htmlentities($key, ENT_QUOTES)?>" <?=($this->getValue() == $key) ? "selected" : ""?>><?=htmlentities($val)?></option>
        <?php } ?>
        </select>
        <?php
    }
}