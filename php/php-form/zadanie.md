<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/php/php-form), [Riešenie](/../../tree/solution/php/php-form).
</div>

# PHP formulár (PHP)

## Zadanie
Vytvorte PHP triedu, ktorá umožní programovo (z php) deklarovať ľubovolný formulár. 
Formulár sa bude vedieť vykresliť a automaticky spracovať a zvalidovať.

Trieda umožní:
- definovať formulár
- vyplniť predvolené hodnoty
- pridať validačné pravidlá
- získať vyplnené hodnoty


Použitie formuláru by mohlo byť nasledovné
```php

// Deklarácia
$form = new Form($defaults);
$form->addText("meno", "Meno")
    ->required();
$form->addText("priezvisko", "Priezvisko")
    ->required();
$form->addNumber("vek", "Vek");
$form->addSubmit("Odošli");

// Kontrola odoslania
if ($form->isValid()) {
    $data = $form->getData();
}

// Vykreslenie
$form->render();
```

### Cieľ príkladu
Cieľom príkladu je práca s objektami v PHP, dynamické genrovanie HTML kódu, spracovanie formulárov a validácia vstupov. 

<div class="hidden">

[Zobraziť riešenie](riesenie.md).
</div>