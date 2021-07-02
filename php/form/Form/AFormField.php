<?php
declare(strict_types=1);

require_once 'AFormElement.php';
require_once 'Validators/RequiredValidator.php';
require_once 'Validators/EmailValidator.php';
require_once 'Validators/NumberValidator.php';

abstract class AFormField extends AFormElement {

    protected $value;
    private string $label;
    protected Form $form;
    /**
     * @var AValidator[]
     */
    protected array $validators = [];
    protected array $errors = [];

    private bool $validated = false;

    public function __construct($name, string $label, $defaultValue, $form)
    {
        parent::__construct($name);
        $this->value = $defaultValue;
        $this->form = $form;
        if (isset($_POST[$this->name])) {
            $this->value = $this->parseValue($_POST[$this->name]);
        }
        $this->label = $label;
    }


    public function isValid(): bool {
        $this->validate();
        return empty($this->errors);
    }

    public function getValue() {
        $this->validate();
        return $this->value;
    }

    public function render(): void {
        $this->validate();
        ?>
        <label for="<?=$this->name?>"><?=$this->label?></label>
        <?php $this->renderElement() ?>
        <?php if ($this->form->isSubmitted() && !empty($this->errors)) { ?>
            <span class="form-errors"><?=join("<br />", $this->errors)?></span>
        <?php } ?>
        <?php
    }

    public function addRule(AValidator $validator): self {
        $this->validators[] = $validator;
        return $this;
    }

    public function required(string $message = ""): self {
        $this->addRule(new RequiredValidator($message));
        return $this;
    }

    protected abstract function renderElement(): void;

    protected function parseValue($value) {
        return trim($value);
    }

    protected function validate()
    {
        if ($this->validated) return;
        foreach ($this->validators as $validator) {
            if (!$validator->validate($this->value)) {
                $this->errors[] = $validator->getMessage();
            }
        }
        $this->validated = true;
    }

}