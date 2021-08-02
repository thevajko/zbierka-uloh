<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/php/factorial), [Riešenie](/../../tree/solution/php/factorial)
> - [Zobraziť zadanie](zadanie.md)

# Faktoriál - PHP

</div>

## Riešenie

Ako prvé je potrebné zistiť, ako sa počíta faktoriál. Na webe, napr. na stránkach [Wikipédie](https://sk.wikipedia.org/wiki/Faktori%C3%A1l), je možné nájsť tento vzorec pre výpočet faktoriálu čísla 5:

<div class="end">

```
5! = 5 * 4 * 3 * 2 * 1 = 120
```
</div>


Na stránkach Wikipédie nájdeme dva *pseudokódy* s cyklom a použitím rekurzie. V tomto riešení si vyberieme nerekurzívny pseudokód, ktorý vyzerá nasledovne:

```c
long double factorial (int n) {
    long double b = 1;
    while (n--)
        b*=n+1;
    return b;
}
```

V prvom kroku prepíšeme pseudokód do jazyka PHP. Začneme deklaráciou funkcie, ktorej kód umiestníme na začiatok súboru. Pozor, súbor musí mať koncovku `.php`. Na výpočet použijeme cyklus `while`, v ktorom postupne zmenšujeme hodnotu parametra `$cislo` dekrementáciou, výsledok vzniká postupným násobením:

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

V zadaní je uvedené, že máme vypísať postupne faktoriál od čísla 0 po 10. To vieme urobiť vytvorením cyklu, ktorý sa spustí 11x a práve tu použijeme cyklus `for`. Všimnite si, ako sa navzájom mixuje HTML a PHP kód. Táto časť kódu bude vyzerať nasledovne:

```php
<ul>
    <?php for ($i = 0; $i < 10; $i++) { ?>
    <li><?php echo $i . "! = ". factorial($i)?></li>
    <?php } ?>
</ul>
```

<div style="page-break-after: always;"></div>

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
