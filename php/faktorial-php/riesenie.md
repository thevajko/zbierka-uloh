<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/php/faktorial-php), [Riešenie](/../../tree/solution/php/faktorial-php).
> - [Zobraziť zadanie](zadanie.md)

# Faktoriál - PHP
</div>

## Riešenie
Ako prvé je potrebné zistiť ako má výpočet faktoriálu prebiehať. Pokiaľ sa pozrieme na stránky
[Wikipédie](https://sk.wikipedia.org/wiki/Faktori%C3%A1l), zistíme, že výpočet prebieha následovne:
```
5! = 5 * 4 * 3 * 2 * 1 = 120
```
Vo Wikipédií máme popísane dva pseudokódy s cyklom a rekurzívny. V tomto riešení vyberáme nerekurzívny
pseudo-kód, ktorý vyzerá nasledovne:

```c
long double faktorial (int n) {
    long double b = 1;
    while (n--)
        b*=n+1;
    return b;
}
```
V prvom kroku prepíšeme pseudo-kód do jazyka PHP, začneme deklaráciou funkcie:

```php

function fakt($cislo){
    $vysledok = 1;
    while (--$cislo  > 0 ) {
        $vysledok *= $cislo+1;
    }
    return $vysledok;
}
```

Zadanie určuje, že máme vypísať v danom formáte postupne faktorial od čísla 0 po 10. To vieme urobiť vytvorením cyklu,
ktorý sa spustí 10 a práve tu použijeme cyklus for. Kód pre výstup bude vyzerať nasledovne:

```php
<?php // formater corrector ?>
<ul>
    <?php for ($i = 0; $i < 10; $i++) { ?>
    <li><?php echo $i . "! = ". fakt($i)?></li>
    <?php } ?>
</ul>
```
