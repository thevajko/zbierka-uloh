<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/php/form), [Riešenie](/../../tree/solution/php/form)
> - [Zobraziť riešenie](riesenie.md)
</div>

# Dynamický formulár
<div class="info"> 

**Hlavný jazyk príkladu**: PHP

**Ostatné použité jazyky**: HTML

**Obťažnosť**: 4/5

**Obsah príkladu**: Objektové programovanie v PHP, abstraktné triedy, dynamické generovanie HTML formulárov, spracovanie formulárov v PHP, validácia vstupov z formulára. 
</div>

## Zadanie
Vytvorte PHP triedu, ktorá umožní programovo (z PHP) deklarovať ľubovolný formulár. Formulár sa bude vedieť vykresliť a automaticky spracovať a vykonať validáciu.

Trieda umožní:
- definovať formulár,
- vyplniť predvolené hodnoty,
- pridať validačné pravidlá,
- získať vyplnené hodnoty.


Použitie formuláru by mohlo byť nasledovné:

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