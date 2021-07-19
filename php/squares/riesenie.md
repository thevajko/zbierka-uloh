<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/php/squares), [Riešenie](/../../tree/solution/php/squares)
> - [Zobraziť zadanie](zadanie.md)

# Generovanie štvorčekov (PHP, CSS)

</div>

## Riešenie

### HTML dokument 

Pri riešení tejto úlohy začneme deklaráciou CSS pravidiel. Hlavným cieľom tejto úlohy je generovanie veľkého množstva štvorčekov rovnakej veľkosti a následné umiestnenie týchto štvorčekov náhodne do okna prehliadača. Na to, aby sme mohli umiestniť štvorčeky na ľubovolné miesto na stránke, potrebujeme:

1. Roztiahnuť element `body` na celé okno prehliadača.
2. Umiestniť štvorček pomocou napr. absolútnej pozície.

Uvažujme, že HTML kód stránky by mohol vyzerať nasledovne:

```html
<body>
    <div></div>
    <div></div>
    <div></div>
    ...
</body>
```

### CSS štýl

Každý `div` element bude reprezentovať jeden štvorček.

```css
body {
    padding: 0;
    margin: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
}
div {
    position: absolute;
    width: 50px;
    height: 50px;
}
```

Pre telo dokumentu (element `body`) nastavíme CSS vlastnosti `padding` a `margin` na `0`, aby sa mohli štvorčeky zobrazovať od úplného okraja okna. Okrem toho nastavíme veľkosť na `100%`, aby vyplnili celé okno. Ak nechceme, aby sa nám okolo okna zobrazovali posuvníky, je potrebné ešte nastaviť `overflow: hidden`.

Každý štvorček bude mať nastavenú pozíciu na `absolute`, aby sme ho mohli umiestniť ľubovolne na stránku a bude mať veľkosť `50px`.

### PHP skript

Implementáciu PHP časti začneme deklarovaním niekoľkých pomocných funkcií.

Vzhľadom na to, že štvorčeky chceme umiestňovať náhodne na základe súradníc v percentách, definujeme si funkciu `randPosition()`, ktorú umiestnime medzi značky `<?php` a `?>`.

```php
function randPosition() 
{
    return rand(0, 100) . "%";
}
```

Ďalšou pomocnou funkciou bude funkcia na generovanie náhodnej farby - `randColor()`. Pri jej implementácii máme niekoľko možností. Prvá možnosť je generovať farbu pomocou jej mena - `red`, `green`, `blue`, `cyan`, ...

```php
function randColor() 
{
    $colors = ["red", "green", "blue", "yellow", "pink", "cyan", "purple", "black", "grey", "violet"];
    return $colors[rand(0, count($colors))];
}
```

V tomto prípade sme si deklarovali pole dostupných farieb. Výslednú farbu vyberáme tak, že pomocou funkcie [`rand`](https://www.php.net/manual/en/function.rand.php) vygenerujeme náhodný index do tohto poľa.

Výhodou tohto riešenia je to, že vieme dopredu špecifikovať, aké farby chceme. V prípade, že by sme potrebovali plne náhodné spektrum farieb, potrebujeme si danú farbu nejakým spôsobom vygenerovať.

> Vo webových aplikáciach najčastejšie využívame RGB formát pre zápis farby. RGB kód farby sa skladá z troch zložiek R - *red*, G - *green*, B - *blue*. Každá z týchto hodnôt môže nadobúdať hodnotu `0` - `255` (`0` - `FF` hexadecimálne). Existujú dva formáty zápisu RGB farby v CSS:

> 1. *Hexadecimálny* - začína znakom `#` a za ním nasledujú hodnoty RGB v 16-tkovej sústave, spolu 6 číslic. Napríklad červená farba vyzerá takto: `#FF0000`. 
> 2. *Decimálny* - ten sa v CSS zapisuje nasledovne `rgb(red, green, blue);`

Pokiaľ chceme jednoducho vygenerovať farbu, môžeme vygenerovať náhodné číslo z rozsahu 0 - 2^24 (`0xFFFFFF`). Táto hodnota musí mať aj úvodné nuly. Ak vygenerujeme hodnotu `0xFF` musíme ju doplniť nulami - `0000FF`. Jej kód bude vyzerať nasledovne:

```php
function randColor()
{
    return sprintf('#%06X', rand(0, 0xFFFFFF));
}
```
Keď sa rozhodneme pre jednu z týchto funkcií, jej kód umiestnime za kód funkcie `randPosition()` tak, aby zostala v bloku `<?php` a `?>`.

Samotný PHP kód na vygenerovanie štvorčekov bude obsahovať jeden cyklus, ktorý 2000 krát vygeneruje element `div` a nastaví mu pozíciu a farbu.

```php
<?php for ($i = 0; $i < 2000; $i++) { ?>
    <div style="
        top: <?=randPosition()?>;
        left: <?=randPosition()?>;
        background: <?=randColor()?>">
    </div>
<?php } ?>
```

Celé riešenie doplníme o základný kód HTML kostry.