<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/php/form), [Riešenie](/../../tree/solution/php/form)
> - [Zobraziť zadanie](zadanie.md)

# PHP formulár (PHP)

</div>

## Riešenie

<div class="hidden">

> Všetky potrebné služby sú v `docker-compose.yml`. Po ich spustení sa vytvorí:
> - webový server, ktorý do __document root__ namapuje adresár tejto úlohy s modulom __PDO__. Port __80__ a bude dostupný na adrese [http://localhost/](http://localhost/). Server má pridaný modul pre ladenie [__Xdebug 3__](https://xdebug.org/) nastavený na port __9000__.

</div>

### Analýza a návrh riešenia

Na riešenie tejto úlohy bude potrebné navrhnúť objektovú štruktúru. Zo zadania vyplýva, že budeme potrebovať triedu `Form`. Táto trieda bude mať metódy na pridávanie formulárových prvkov pomocou metód `addXXX()` (kde `XXX` je názov formulárového poľa). Okrem iného táto trieda bude schopná vrátiť vyplnené hodnoty v asociatívnom poli pomocou metódy `getData()`. Ďalšou funkcionalitou bude detekcia odoslania formulára `isSubmitted()` a kontrola správnosti vyplnených údajov metódou `isValid()`.

Vzhľadom na to, že formulár má byť univerzálny, jednotlivé časti formuláru budú riešené pomocou ďalšej objektovej štruktúry. Formulár bude obsahovať prvky typu `AFormElement`. Elementom formuláru môže byť tlačidlo, text, textové pole, výberový zoznam, atď. Z tohto dôvodu usporiadame elementy ešte do hlbšej hierarchie. Základné elementy, ktoré nebudú poskytovať dáta (napr. tlačidlo, text, ...) budú potomkami triedy `AFormElement`, ktorá bude mať jedinú metódu `render()`. Elementy, ktoré budú slúžiť na zadávanie vstupov budú dediť od triedy `AFormField`. Táto trieda bude potomkom triedy `AFormElement` - takže sa budú dať vypísať. Trieda `AFormField` pridá k základnému elementu ďalšiu funkcionalitu: pridanie možnosť získať vyplnenú hodnotu a pridanie validačných pravidiel. Výpis väčšiny formulárových polí je veľmi podobný:

```html
<label>Nazov</label>
PRVOK
```

Metóda `render()` pripraví štruktúru, vypíše popisok (element `label`) a pomocou abstraktnej metódy `renderElement()`, ktorú si v potomkoch prekryjeme, vykreslíme obsah daného elementu.

Validácia bude prebiehať tak, že ku každému prvku pridáme tzv. validátory. **Validátor** bude trieda, ktorá bude dediť od `AValidator` a bude sa pridávať k jednotlivým `AFormElement` pomocou metódy `addRule()`.

Výsledný UML diagram celého riešenia je nasledovný:

![UML diagram tried](http://www.plantuml.com/plantuml/proxy?cache=no&src=https://raw.githubusercontent.com/thevajko/zbierka-uloh/solution/php/form/diagram.puml)

### Implementácia formulára

#### Základné prvky

Začneme implementáciou jednoduchších tried. Ako prvé sa pokúsime implementovať zobrazenie formulára.

Trieda `AFormElement` je jednoduchou triedou, ktorá okrem abstraktnej metódy neobsahuje skoro nič iné:

```php
abstract class AFormElement
{
  protected string $name;
    
  public function __construct(string $name)
  {
    $this->name = $name;
  }

  public abstract function render(): void;
}
```

Okrem samotnej metódy `render()` obsahuje aj konštruktor a atribút `$name`. Každý prvok vo formulári musí byť nejakým spôsobom pomenovaný, aby sme sa k nemu prípadne vedeli neskôr dostať. Aj iné ako HTML formulárové prvky budú mať v našej implementácii svoj názov. Ak to bude potrebné, je možnosť pridať *get* metódu pre atribút `$name`. My sme ho ale označili ako `protected`, takže každý z potomkov bude mať prístup k názvu elementu. Môžeme si všimnúť, že celá trieda, ale aj metóda `render()` sú označené kľúčovým slovom `abstract`.

#### Tlačidlo na odoslanie formulára

Ďalšou triedou, na ktorú sa pozrieme je trieda `SubmitButton`. Táto trieda reprezentuje tlačidlo typu `submit`.

```php
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
```

V konštruktore definujeme atribút `$label`, čo predstavuje text, ktorý bude zobrazený na tlačidle. V metóde `render()` sme definovali HTML kód daného tlačidla. Atribút `$name` sme nastavili podľa názvu komponentu (deklarovaný v predkovi) a atribút `$value` obsahuje text, ktorý bude na tlačidle. Dôležité na tomto príklade je to, že nesmieme zabudnúť zavolať konštruktor predka - `parent::__construct($name);`.

#### Základný formulárový prvok

Ďalej si pripravíme triedu `AFormField`. Táto trieda je abstraktným predkom všetkých formulárových prvkov a vyzerá nasledovne:

<div class="end">

```php
abstract class AFormField extends AFormElement 
{
    protected $value;
    private string $label;
    protected Form $form;
    
    public function __construct($name, string $label, $defaultValue, $form)
    {
        parent::__construct($name);
        $this->value = $defaultValue;
        $this->label = $label;
        $this->form = $form;
        if (isset($_POST[$this->name])) {
            $this->value = trim($_POST[$this->name]);
        }
    }

    protected abstract function renderElement(): void;
}
```
</div>

Trieda si pamätá hodnotu formulárového prvku v atribúte `value`. Ak bol formulár odoslaný, tak nastavíme túto hodnotu z poľa `$_POST`. V opačnom prípade tam nastavíme východziu hodnotu, ktorú sme dostali ako parameter konštruktora. Okrem konštruktora si deklarujeme abstraktnú metódu `renderElement()`, pomocou ktorej budú potomkovia definovať konkrétny formulárový prvok.

Ďalej si deklarujeme metódu `render()`. Tá bude spoločná pre všetky formulárové prvky. Metóda vypíše element `label` a pomocou metódy `renderElement()` vypíše telo daného elementu.

```php
abstract class AFormField extends AFormElement {
    // ...
    public function render(): void {
      ?>
      <label for="<?=$this->name?>"><?=$this->label?></label>
      <?php $this->renderElement() ?>
      <?php
    }
}
```

#### Implementácia zobrazenia textového poľa

Konkrétna definícia textového poľa v triede `TextInputField` už nebude zložitá. Jediné, čo potrebujeme implementovať, je metóda `renderElement()`, ktorá vypíše HTML `input` element a doplní príslušné dáta:

<div class="end">

```php
class TextInputField extends AFormField {

  protected function renderElement(): void
  {
    ?>
    <input type="text"
    name="<?=$this->name?>"
    id="<?=$this->name?>"
    value="<?=htmlentities($this->value, ENT_QUOTES)?>">
    <?php
  }
}
```
</div>

Názov a `id` elementu sme implementovali rovnakým spôsobom ako pri tlačidle typu `submit`. Do atribútu `value` sme nastavili aktuálnu hodnotu prvku formulárového poľa. Tá môže byť východzia, alebo už získaná z `$_POST`. Táto hodnota pochádza od používateľa, takže ju musíme vhodne upraviť (cez *escaping*), aby nám nemohla "rozbiť" formulár. Na úpravu hodnoty sme použili PHP funkciu [`htmlentities()`](https://www.php.net/manual/en/function.htmlentities).

#### Implementácia zobrazenia formuláru

Poslednou triedou, ktorá nám zostáva, je trieda `Form`. Tá bude obsahovať zoznam formulárových prvkov a bude vedieť vypísať formulár a získať z neho dáta.

```php
class Form {
  /** @var AFormElement[] */
  private array $formFields = [];
  
  public function render(): void 
  {
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
}
```

Táto trieda si bude v atribúte `$formFields` pamätať zoznam všetkých formulárových prvkov. Výpis formulára pozostáva z dvoch častí. Najskôr vypíšeme HTML kód samotného formulára - `div` element a v ňom element `form`. Následne prejdeme všetky formulárové prvky cyklom `foreach` a zobrazíme každý zvlášť. Na konci by sme mali dostať kompletný HTML kód formulára.

#### Získanie vyplnených údajov

Pokiaľ by sme chceli získať dáta z vyplneného formulára, doplníme metódu `getData()`.

```php
class Form {
    // ...
    public function getData(): array 
    {
      return array_map(fn($x) => $x->getValue(),
        array_filter($this->formFields, fn($x) => $x instanceof AFormField));
    }
}
```

Táto metóda prejde všetky formulárové prvky. Najskôr ich ale prefiltruje pomocou metódy [`array_filter()`](https://www.php.net/manual/en/function.array_filter). Pri získavaní hodnôt chceme len inštancie typu `AFormField`, pretože len tie obsahujú "hodnotu". Po prefiltrovaní aplikujeme funkciu [`array_map()`](https://www.php.net/manual/en/function.array_map), pomocou ktorej z `AFormField` prvkov vytiahneme vyplnenú hodnotu.

Vyššie spomenutý kód by sa dal prepísať aj pomocou jednoduchého `foreach` cyklu nasledovne:

<div class="end">

```php
class Form {
    // ...
    public function getData(): array 
    {
      $data = [];
      foreach ($this->formFields as $key => $value) {
        if ($value instanceof AFormField) {
          $data[$key] = $value->getValue();
        }
      }
      return $data;
    }
}
```
</div>

Výsledkom tejto metódy bude asociatívne pole, ktoré bude obsahovať hodnoty vyplnené vo formulári vo formáte `"názovPrvku" => "hodnota"`.

#### Pridávanie polí do formulára

Pre pridávanie jednotlivých polí do formulára si musíme upraviť triedu `Form`. Každé pole má v konštruktore ako parameter svoju východziu hodnotu. Pre lepšiu manipuláciu nastavíme všetky východzie hodnoty už priamo v parametri konštruktora triedy `Form` v&nbsp;podobe asociatívneho poľa `"názovPrvku" => "hodnota"`. Tieto východzie hodnoty si musíme uložiť do atribútu a následne si ich budeme pri pridávaní polí vyberať z tohto atribútu.

```php
class Form 
{
  //...
  private array $defaultValues = [];

  public function __construct(array $defaultValues = [])
  {
    $this->defaultValues = $defaultValues;
  }
  //...
```

Pred implementáciou metódy na pridanie textového poľa si ešte pripravíme pomocnú metódu, ktorá sa pokúsi načítať východziu hodnotu pre daný prvok formulára.

<div class="end">

```php
class Form 
{
    //...
    private function getDefaultValue($key) 
    {
      return (isset($this->defaultValues[$key])) ? $this->defaultValues[$key] : "";
    }
    // ...
}
```
</div>

Táto metóda sa pokúsi nájsť hodnotu v atribúte `$defaultValues` a v prípade, že sa tam nenachádza, vráti prázdny reťazec.

Metóda na pridanie textového poľa by mohla vyzerať nasledovne:

```php
class Form 
{
    //...
    public function addText($name, $label): TextInputField {
      $field = new TextInputField($name, $label, $this->getDefaultValue($name), $this);
      $this->formFields[$name] = $field;
      return $field;
    }
    // ...
}
```

Vytvoríme novú inštanciu triedy `TextInputField`. Uložíme si ju pod kľúčom `$name` do zoznamu prvkov `$this->formFields` a vrátime vytvorenú inštanciu. Táto metóda vráti inštanciu preto, aby sme mohli ešte v prípade potreby s ňou ďalej pracovať cez tzv. zreťazené volanie metód (angl. *fluent style methods chaining*), z ktorých každá vráti `$this`.

Pridanie tlačidla na odosielanie formulára bude vyzerať veľmi podobne. Najskôr si ale deklarujeme konštantu, pod ktorou budeme tento prvok vkladať do formulára. Typicky sa používa názov `submit`.

<div class="end">

```php
class Form {
  private const FORM_SUBMIT_NAME = "submit";
  
  //...
  public function addSubmit(string $label): SubmitButton
  {
    $field = new SubmitButton(self::FORM_SUBMIT_NAME, $label);
    $this->formFields[self::FORM_SUBMIT_NAME] = $field;
    return $field;
  }
  //...
}
```
</div>

Pridanie tlačidla je rovnaké ako v prípade textového poľa, len v tomto prípade vytvoríme inštanciu `SubmitButton`.

### Validácia vstupov

Aktuálne implementovaný formulár obsahuje len vkladanie základných textových polí bez validácie vstupov. Na základe návrhu validácia bude implementovaná pomocou jednoduchej objektovej štruktúry.

#### Základná trieda pre všetky validátory

Všetky validačné pravidlá budú potomkom triedy `AValidator`, ktorá vyzerá nasledovne:

```php
abstract class AValidator
{
  private string $errorMessage;

  public function __construct(string $errorMessage = "")
  {
    if (empty($errorMessage)) {
      $errorMessage = $this->getDefaultMessage();
    }
    $this->errorMessage = $errorMessage;
  }

  public function getMessage()
  {
    return $this->errorMessage;
  }

  public abstract function validate(&$value): bool;
  
  protected abstract function getDefaultMessage(): string;
}
```

Táto trieda obsahuje atribút `$errorMessage` a *get* metódu `getMessage()` chybovej správy, ktorú validátor vypíše v prípade, že validácia formulára bude neúspešná. Túto správu je možné nastaviť cez konštruktor. Druhým spôsobom definície chybovej správy je abstraktná metóda `getDefaultMessage()`, ktorú musí prekryť každý potomok a pomocou nej definovať východziu správu, ktorá sa použije, ak pri vytváraní validátora nedefinujeme žiadnu správu.

Okrem toho validátor obsahuje metódu `validate()`, v ktorej jednotlivé validátory (potomkovia `AValidator`) budú kontrolovať vstup. Táto metóda má návratovú hodnotu typu `boolean`. Ak validácia prebehne v poriadku, metóda vráti `true`, inak v prípade chyby vráti `false`. 

Ďalšou špecialitou tejto metódy je deklarácia parametra. Môžeme si všimnúť, že pred názov parametra sme pridali znak `&`. Takto deklarovaný parameter je vstupno-výstupný parameter, takže metóda `validate()` môže v prípade potreby modifikovať spracovávanú hodnotu. 

Typickým príkladom môže byť číselný vstup, kedy je potrebné transformovať textový reťazec na číselnú hodnotu. Tento princíp je prebratý zo spôsobu filtrovania vstupných dát v PHP pomocou funkcie [`filter-var()`](https://www.php.net/manual/en/function.filter-var.php).

#### Validátor povinnej položky

Najzákladnejším validátorom je validácia vyplnenia položky. Pre tento účel si implementujeme validátor s názvom `RequiredValudator`:

```php
class RequiredValidator extends AValidator
{
  public function validate(&$value): bool {
    return !empty($value);
  }

  protected function getDefaultMessage(): string
  {
    return "Položka musí byť vyplnená";
  }
}
```

Tento validátor obsahuje jednoduchú kontrolu vstupného poľa pomocou funkcie [`empty()`](https://www.php.net/manual/en/function.empty.php). Okrem toho definuje metódu na získanie východzej chybovej správy.

#### Pridanie validátorov k formulárovým poliam

Každý formulárový prvok môže mať niekoľko validátorov. Začneme preto s rozšírením triedy `AFormField`.

Najskôr pridáme niekoľko atribútov:

```php
abstract class AFormField extends AFormElement {
    /**
    * @var AValidator[]
    */
    protected array $validators = [];
    protected array $errors = [];
    
    private bool $validated = false;
    
    //...
}
```

Atribút `$validators` bude obsahovať pole zo všetkými validátormi, ktoré sa vzťahujú na daný formulárový prvok. Pole `$errors` bude obsahovať zoznam chýb po validácii a atribút `$validated` je pomocný a budeme ho používať ako ochranu pred viacnásobnou validáciou toho istého formulárového prvku.

Hlavným problémom validácie je to, že ju nemôžeme vykonať priamo v konštruktore, a to z toho dôvodu, že formulárový prvok najskôr vznikne a až potom mu priradíme validačné pravidlá. 

Validovať hodnotu prvku bude potrebné v metóde `isValid()`, pri získavaní dát pomocou metódy `getValue()` a pri vykresľovaní prvku, aby sme vedeli vypísať prípadné chyby. Aby sme sa vyhli duplicite dát, pripravíme si v triede `AFormField` pomocnú metódu `validate()`:

```php
abstract class AFormField extends AFormElement {
    // ...
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
    // ...
}
```

Táto metóda využíva atribút `$validated` a v prípade, že validácia už prebehla, tak rovno na prvom riadku ukončí metódu. Následne iterujeme cez zoznam všetkých validátorov a postupne validujeme vstupnú hodnotu voči každému jednému z nich.

Vďaka tejto metóde môžeme pridať metódu `isValid()` a upraviť metódu na získanie hodnoty `getValue()`:

```php
abstract class AFormField extends AFormElement {
    // ...
    public function isValid(): bool {
      $this->validate();
      return empty($this->errors);
    }
    
    public function getValue() {
      $this->validate();
      return $this->value;
    }
    // ...
}
```

Metóda `isValid()` vráti `true` v prípade, že po validácii nemáme žiadne validačné chyby. Volanie validácie v metóde `getValue()` sme pridali preto, aby sa nám upravili vyplnené hodnoty (napr. aby `getValue()` v prípade číselného poľa vrátilo číslo po aplikovaní daného validátora).

Metódu `render()` upravíme tak, aby vypisovala nájdené validačné chyby nasledovne:

```php
abstract class AFormField extends AFormElement {
    // ...
    public function render(): void 
    {
      $this->validate();
      ?>
        <label for="<?=$this->name?>"><?=$this->label?></label>
        <?php $this->renderElement() ?>
        <?php if ($this->form->isSubmitted() && !empty($this->errors)) { ?>
          <span class="form-errors"><?=join("<br />", $this->errors)?></span>
        <?php } ?>
      <?php
    }
    // ...
}
```

Oproti pôvodnej implementácii pribudli dve veci. Na začiatok metódy `render()` sme pridali validáciu a do výpisu sme pridali `span` element, do ktorého vložíme jednotlivé chyby z validácie.

Zaujímavá je aj podmienka na výpis chýb. Tá sa skladá z dvoch častí. Chyby vypisujeme len v prípade, že nejaké sú a zároveň, ak bol formulár odoslaný. Podmienku `$this->form->isSubmitted()` sme použili preto, aby sa validačné chyby nezobrazili hneď pri prvom vykreslení formulára, ale až po odoslaní. To je z toho dôvodu, lebo z&nbsp;používateľského hľadiska nie je dobré, aby prázdny formulár ešte pred vyplnením obsahoval množstvo chybových správ.

Metóda `isSubmitted()` v triede `Form` vyzerá nasledovne:

```php
class Form {
    // ...
    public function isSubmitted()
    {
      return isset($_POST[self::FORM_SUBMIT_NAME]);
    }
    // ...
}
```

Kontrolujeme v nej, či v poli `$_POST` máme informáciu o tom, že bolo stlačené tlačidlo na odoslanie formuláru.

Pridávanie pravidiel k položkám formulára zabezpečuje metóda `addRule()` v triede `AFormField`, ktorá vyzerá nasledovne:

```php
abstract class AFormField extends AFormElement {
    // ...
    public function addRule(AValidator $validator): self 
    {
      $this->validators[] = $validator;
      return $this;
    }
    // ...
}
```

Táto metóda len pridá validátor, ktorý dostane ako parameter, do zoznamu validátorov. Zaujímavé na tejto metóde je to, že ako návratovú hodnotu má `self`, t.j. metóda vráti objekt, na ktorom bola táto metóda volaná. Toto sa využíva pri zjednodušenom volaní metód, kde pridávanie validátorov môžeme pekne zreťaziť.

```php
$form->addText("meno", "Krstné meno")
  ->addRule(new Validator1())
  ->addRule(new Validator2());
```

Povinné položky sú častým validačným pravidlom vo formulári, preto si pridáme tzv. *helper* metódu, ktorá nám umožní jednoduchšie nastavenie, či je dané pole povinné:

```php
abstract class AFormField extends AFormElement {
    // ...
    public function required(string $message = ""): self 
    {
      $this->addRule(new RequiredValidator($message));
      return $this;
    }
    // ...
}
```

Následné použitie tejto metódy môže vyzerať takto:

<div class="end">

```php
$form->addText("meno", "Krstné meno")
  ->required("Položka krstné meno je vyžadovaná")
  ->addRule(new Validator2());
```
</div>

Ako môžeme vidieť, metóda `required()` umožňuje definovať vlastnú chybovú správu, takže namiesto východzieho `Položka musí byť vyplnená` sa pri nevyplnení poľa vypíše správa `Položka krstné meno je vyžadovaná`.

#### Validácia celého formulára

Keď máme definovaný mechanizmus na validáciu jednotlivých položiek, môžeme pristúpiť k implementácii metódy na validáciu celého formulára. Na to, aby bol formulár valídny, musí byť v prvom rade odoslaný a v druhom rade všetky jeho položky musia byť valídne. Túto kontrolu môžeme implementovať nasledovne:

```php
class Form 
{
  //...

  public function isValid(): bool {
    if (!$this->isSubmitted()) return false;
    foreach ($this->formFields as $field) {
      if ($field instanceof AFormField && !$field->isValid()) {
        return false;
      }
    }
    return true;
  }
  //...
}
```

Opäť, ako aj v prípade metódy `getData()`, kontrolujeme validitu len pri položkách typu `AFormField`.

### Rozšírenie základnej funkcionality

#### Pridanie ďalších validátorov, validácia na klientovi

Pre ukážku môžeme demonštrovať niekoľko ďalších základných validátorov. Začneme klasickou validáciou emailových adries.

Na začiatok si pripravíme triedu `EmailValidator`, ktorá môže vyzerať nasledovne:

```php
class EmailValidator extends AValidator
{
  public function validate(&$value): bool
  {
    return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
  }

  protected function getDefaultMessage(): string
  {
    return "Položka nie je platný email";
  }
}
```

Platnosť emailovej adresy kontrolujeme pomocou už spomínanej funkcie [`filter-var()`](https://www.php.net/manual/en/function.filter-var.php).

Základné použitie by mohlo vyzerať:

```php
$form->addText("email", "Emailová adresa")
  ->required()
  ->addRule(new EmailValidator());
```

Pokiaľ by sme ale chceli pridať HTML5 validáciu, museli by sme zmeniť typ HTML `input` elementu na `email`. Upravíme pre to triedu `TextInputField` tak, aby umožňovala nastaviť ľubovoľný typ elementu.

```php
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
```

Pridali sme atribút `$type`, ktorý bude uchovávať typ `input` prvku. Tento atribút nastavíme v konštruktore a v metóde `renderElement()` zmeníme pôvodné `type="text"` na `type="<?=$this->type?>"`.

Pre lepšie pridávanie týchto elementov môžeme upraviť triedu `Form` takto:

```php
class Form {
 // ...
    public function addText($name, $label, $type = "text"): TextInputField 
    {
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
    public function addEmail($name, $label): TextInputField
    {
      return $this->addText($name, $label, "email");
    }
    public function addPassword($name, $label): TextInputField 
    {
      return $this->addText($name, $label, "password");
    }
    public function addNumber($name, $label): TextInputField 
    {
      return $this->addText($name, $label, "number");
    }
 // ...
}
```

Metóda `addText()` umožňuje pridať typ textového elementu. Ďalej môžeme na základe typu pridať určité validačné pravidlá, napr. ak máme email, tak sa môže automaticky validovať ako email. Ďalej sme si pripravili pomocné metódy `addEmail()`, `addPassword()` a `addNumber()`, ktoré nám zjednodušia pridávanie ďalších typov elementov.

Pre úplnosť `NumberValidator` vyzerá nasledovne:

```php
class NumberValidator extends AValidator
{
  public function validate(&$value): bool
  {
    if (is_numeric($value)) {
      $value = (int) $value;
      return true;
    }
    return false;
  }

  protected function getDefaultMessage(): string
  {
    return "Neplatná číselná hodnota";
  }
}
```

#### Ďalšie typy formulárových polí

Nakoniec si ukážeme ešte jeden formulárový prvok, a to výberový zoznam (značka `<select>`).

Výberový zoznam funguje tak, že ako parameter dostane pole dostupných hodnôt, z&nbsp;ktorých bude môcť používateľ vybrať. Takéto pole je štandardne typu `"klúč" => "hodnota"`, kde kľúčom môže byť napr. `id` záznamu z databázy a hodnota bude text, ktorý sa bude zobrazovať používateľovi. Základnou validáciou pri každom výberovom zozname je kontrola prípustných hodnôt. Používateľ nemôže odoslať hodnotu, ktorá nie je v&nbsp;zozname. Celú túto požiadavku implementujeme jednoduchou kontrolou prípustných hodnôt v konštruktore triedy `SelectField`.

```php
class SelectField extends AFormField 
{
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
```

<div style="page-break-after: always;"></div>

Nakoniec ešte pridáme do triedy `Form` metódu na pridanie výberového zoznamu:

```php
class Form {
    // ...
    public function addSelect($name, $label, $values): SelectField 
    {
      $field = new SelectField($name, $label, $this->getDefaultValue($name), $this, $values);
      $this->formFields[$name] = $field;
      return $field;
    }
    // ...
}
```

Deklarácia komplexného formuláru by mohla vyzerať nasledovne:

<div class="end">

```php
$form = new Form(["meno" => "test"]);

$form->addText("meno", "Meno")
    ->required();
$form->addText("priezvisko", "Priezvisko")
    ->required();
$form->addText("mail", "Emailová adresa", "email")
    ->required();
$form->addNumber("vek", "Vek");
$form->addPassword("heslo", "Heslo");

$form->addSelect("oci", "Farba očí", ["g" => "Zelená", "r" => "Červená", "b" => "Modrá"]);

$form->addSubmit("Odošli");

if ($form->isSubmitted() && $form->isValid()) {
    $data = $form->getData();
    //Process form data
    var_dump($data);
}
else {
    $form->render();
}
```
</div>