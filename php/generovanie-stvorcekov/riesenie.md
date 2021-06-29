<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/php/generovanie-stvorcekov), [Riešenie](/../../tree/solution/php/generovanie-stvorcekov).

# Generovanie štvorčekov (PHP, CSS)
</div>

## Riešenie


### HTML a CSS
Pri riešení tejto úlohy začneme deklaráciou CSS pravidiel. Hlavným cieľom tejto úlohy je generovanie veľkého množstva štvorčekov rovnakej veľkosti a následné umiestnenie týchto štvorčekov náhodne na obrazovku.
Na to, aby sme mohli umiestniť štvorčeky na ľubovolné miesto na stránke potrebujeme:
1. Roztiahnúť web na celé okno
2. Umiestniť štvorček pomocou napr. absolútneho pozicioningu.

Uzažujme že HTML kód stránky by mohol vyzerať nasledovne:
```html
<body>
  <div></div>
  <div></div>
  <div></div>
  ...
</body>
```

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

Pre telo dokumentu (`body`) nastavíme `padding` a `margin` na 0, aby boli štvorčeky od úplneho okraja okna. Okrem toho nastavíme veľkosť na 100% aby vyplnili celý viewport. Ak nechceme aby sa nám okolo okna zobrazovali scroolbary je potrebné ešte nastaviť `overflow: hidden`.

Každý štvorček bude mať nastavenú pozíciu na `absolute` aby sme ho mohli umiestniť ľubovolne na stránku a veľkosť bude mať 50px.


### PHP
Implementáciu php časti začneme deklarovaním niekoľkých pomocných funkcií.

Vzhľadom na to, že štvorčeky chceme umiestňovať náhodne na základe súradnic v percentách, definujeme si funkciu `nahodnaPozicia`:
```php
function nahodnaPozicia() {
  return rand(0, 100)."%";
}
```

Ďalšou pomocnou funkciou bude funkcia na generovanie náhodnej farby - `nahodnaFarba`. Pri jej implementácii máme niekoľko možností. Prvá možnosť je generovať farbu pomocou jej reťazcového pomenovania - `red`, `green`, `blue`, `cyan`... 
```php
function nahodnaFarba() {
  $farby = ["red", "green", "blue", "yellow", "pink", "cyan", "purple", "black", "grey", "violet"];
  return $farby[rand(0, count($farby))];
}
```
V tomto prípade sme si deklarovali pole dostupných farieb. Výslednú farbu vyberáme tak, že pomocou funkcie [`rand`](https://www.php.net/manual/en/function.rand.php) vygenerujeme náhodný index do tohto poľa.

Výhodou tohto riešenia je to, že si vieme dopredu špecifikovať aké farby chceme. V prípade že by sme potrebovali plne náhodné spektrum farieb potrebujeme si danú farbu nejakým spôsobom vygenerovať.

> Existuje niekoľko spôsobov, ako môžeme  reprezentovať farby.
> - RGB
> - HSB
> - HSL
> - CMYK
>
> Vo webových aplikáciach najčastejšie využívame RGB farebné spektrum. RGB kód farby sa skladá z troch zložiek R - red, G - green, B - blue. Každá z týchto hodnôt môže nadobúdať hodnotu 0 - 255. Existujú dva formáty zápisu RGB farby v CSS.
> 
> **Hexadecimálny** - začína znakom # a za ním nasledujú hodnoty RGB v 16tkovej sústave - dokopy 6 číslic. Napríklad červená farba vyzerá takto: `#FF0000`.
>
> **Desiatkový** - ten sa v CSS zapisuje nasledovne `rgb(red, green, blue);`

Pokiaľ chceme jednoducho vygenerovať farbu, môžeme vygenerovať náhodné číslo z rozsahu 0 - 2^24 (0xFFFFFF). Táto hodnota musí mať aj úvodné nuly - takže ak vygenerujeme hodnotu 0xFF musíme ju doplniť nulami - `0000FF`. PHP kód by mal vyzerať nasledovne:

```php
function nahodnaFarba() {
  return sprintf('#%06X', rand(0, 0xFFFFFF));
}
```

Samotný PHP kód na vygenerovanie štvorčekov bude obsahovať jeden cyklus, ktorý 2000 krát vygeneruje `div` a nastaví mu pozíciu a farbu.

```php
<?php for ($i = 0; $i < 2000; $i++) { ?>
  <div style="
    top: <?=nahodnaPozicia()?>;
    left: <?=nahodnaPozicia()?>;
    background: <?=nahodnaFarba()?>">
  </div>
<?php } ?>
```