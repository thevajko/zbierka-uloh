<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/php/faktorial-php), [Riešenie](/../../tree/solution/php/faktorial-php).
> - [Zobraziť zadanie](zadanie.md)

# Faktoriál - PHP

</div>

## Riešenie

Ako prvé je potrebné zistiť, ako sa počíta faktoriál. Na webe, napr. mna stránkach [Wikipédie](https://sk.wikipedia.org/wiki/Faktori%C3%A1l), je možné nájsť tento vzorec pre výpočet faktoriálu čísla 5:

```
5! = 5 * 4 * 3 * 2 * 1 = 120
```

Na stránkach Wikipédie nájdeme dva *pseudokódy* s cyklom a použitím rekurzie. V tomto riešení si vyberieme nerekurzívny pseudo-kód, ktorý vyzerá nasledovne:

```c
long double factorial (int n) {
    long double b = 1;
    while (n--)
        b*=n+1;
    return b;
}
```

V prvom kroku prepíšeme pseudokód do jazyka PHP, začneme deklaráciou funkcie, ktorej kód umiestnime na začiatok súboru. Pozor, súbor musí mať koncovku `.php`. Na výpočet použijeme cyklus `while`, v ktorom postupne zmenšujeme hodnotu parametra `$cislo` dekrementáciou, výsledok vzniká postupným násobením:

```php
function factorial($number)
{
    $result = 1;
    while (--$number > 0) {
        $result *= $number + 1;
    }
    return $result;
}
```

V zadaní je uvedené, že máme vypísať v danom formáte postupne faktoriál od čísla 0 po 10. To vieme urobiť vytvorením cyklu, ktorý sa spustí 11x a práve tu použijeme cyklus `for`. Všimnite si, ako sa nazvájom mixuje HTML a PHP kód. Táto časť kódu bude vyzerať nasledovne:

```php
<?php // formater corrector ?>
<ul>
    <?php for ($i = 0; $i < 10; $i++) { ?>
    <li><?php echo $i . "! = ". factorial($i)?></li>
    <?php } ?>
</ul>
```

Na záver, keď všetko spojíme dokopy a pridáme štandardnú HTML kostru, celé riešenie bude vyzerať:

```php
<?php
function factorial($number)
{
    $result = 1;
    while (--$number > 0) {
        $result *= $number + 1;
    }
    return $result;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Faktoriál</title>
</head>
<body>
<ul>
    <?php for ($i = 0; $i < 10; $i++) { ?>
    <li><?php echo $i . "! = " . factorial($i) ?></li>
    <?php } ?>
</ul>
</body>
</html>
```

<div class="solution">

Kompletné zdrojové kódy hotového riešenia môžete nájsť na tejto URL adrese:

[https://github.com/thevajko/zbierka-uloh/tree/solution/php/factorial](https://github.com/thevajko/zbierka-uloh/tree/solution/php/factorial)

![URL adresa hotového riešenia](images_factorial/qr-factorial.png)
</div>