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

**Obťažnosť**: 3/5

**Obsah príkladu**: Objektové programovanie v PHP, abstraktné triedy, viditeľnosť metód, dynamické generovanie HTML formulárov, validácia formulárových polí, spracovanie formulárov v PHP. 
</div>

<div class="hidden">

> Všetky potrebné služby sú v `docker-compose.yml`. Po ich spustení sa vytvorí:
> - webový server, ktorý do __document root__ namapuje adresár tejto úlohy s modulom __PDO__. Port __80__ a bude dostupný na adrese [http://localhost/](http://localhost/). Server má pridaný modul pre ladenie [__Xdebug 3__](https://xdebug.org/) nastavený na port __9000__.

</div>

## Zadanie
Vytvorte PHP triedu, ktorá umožní programovo (z PHP) deklarovať ľubovoľný HTML formulár. Formulár sa bude vedieť zobraziť, automaticky spracovať a vykonať validáciu.

Trieda umožní:
- definovať formulár,
- vyplniť predvolené hodnoty,
- pridať validačné pravidlá,
- získať vyplnené hodnoty.

Použitie formuláru by mohlo byť nasledujúce:

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