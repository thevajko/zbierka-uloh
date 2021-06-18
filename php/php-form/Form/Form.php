<?php
declare(strict_types=1);

require_once 'AFormField.php';
require_once 'TextInputField.php';
require_once 'TextareaField.php';
require_once 'SelectField.php';
require_once 'FormSubmit.php';

/**
 * Trieda reprezentujúca formulár
 */
class Form {

    private const FORM_SUBMIT_NAME = "submit";

    /**
     * @var AFormElement[]
     */
    private array $formFields = [];

    private array $defaultValues = [];

    public function __construct(array $defaultValues)
    {
        $this->defaultValues = $defaultValues;
    }

    /**
     * Vráti hodnoty z formulára
     */
    public function getData(): array {
        return array_map((x) -> x.getValue(),
            array_filter($this->formFields, (x) -> x instanceof AFormField));
    }

    /**
     * Vráti informáciu o tom, či formulár obsahuje validačné chyby
     */
    public function isValid(): bool {
        foreach ($this->formFields as $field) {
            if ($field instanceof AFormField && !$field->isValid()) {
                return false;
            }
        }
        return true;
    }

    public function isSubmitted()
    {
        return isset($_POST[self::FORM_SUBMIT_NAME]);
    }

    /** 
     * Vyrenderuje html kód formulára
     */
    public function render(): void {
        ?>
        <div class="form-container">
            <form method="post">
                <?php
                foreach ($this->formFields as $field) {
                    ?>
                    <div class="form-element">
                        <?php $field->render(); ?>
                    </div>
                    <?php
                }
                ?>
            </form>
        </div>
        <?php
    }


    private function getDefaultValue($key) {
        return (isset($this->defaultValues[$key])) ? $this->defaultValues[$key] : "";
    }

    public function addText($name, $label, $type = "text"): TextInputField {
        $field = new TextInputField($name, $label, $this->getDefaultValue($name), $this, $type);
        $this->formFields[$name] = $field;

        switch ($type) {
            case 'email':
                $field->addRule(new EmailValidator());
                break;
            case 'number':
                $field->addRule(new NumberValidator());
                break;
        }

        return $field;
    }

    public function addPassword($name, $label): TextInputField {
        return $this->addText($name, $label, "password");
    }

    public function addNumber($name, $label): TextInputField {
        return $this->addText($name, $label, "number");
    }

    public function addSelect($name, $label, $values): SelectField {
        $field = new SelectField($name, $label, $this->getDefaultValue($name), $this, $values);
        $this->formFields[$name] = $field;
        return $field;
    }

    public function addTextArea($name, $label): TextareaField {
        $field = new TextareaField($name, $label, $this->getDefaultValue($name), $this);
        $this->formFields[$name] = $field;
        return $field;
    }

    public function addSubmit(string $label)
    {
        $field = new FormSubmit(self::FORM_SUBMIT_NAME, $label);
        $this->formFields[self::FORM_SUBMIT_NAME] = $field;
        return $field;
    }
}