<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/css/css-smajliky), [Riešenie](/../../tree/solution/css/css-smajliky).
</div>

# Smajlíky (CSS)

## Zadanie

Vytvorte pomocou CSS bez použitia grafiky (obrázky, `svg`) nasledujúce smajlíky.

![Zadanie príkladu Smajlíky](images_css_smajliky/zadanie.png)

Každý smajlík bude dostupný vo veľkostiach `50px`, `100px`, `250px` a `500px`.

Prvé štyri smajlíky sú základné. Každý zo základných smajlíkov sa bude dať otočiť o 180 stupňov. Posledný smajlík na ukážke vznikol otočením prvého smajlíka.

Základná HTML štruktúra smajlíka bude nasledovná:

```html

<div class="smajlik">
    ...
</div>
```

Túto štruktúru je potrebné dodržať. Obsah elementu `div` si prispôsobte podľa potreby. Tento HTML kód vykreslí prvého smajlíka vo veľkosti `50px`. Pokiaľ chceme zmeniť veľkosť smajlíka, pridáme do `div` elementu ďalšiu CSS triedu - `s-100`, `s-250` alebo `s-500` podľa požadovanej veľkosti.

Rotácia smajlíka bude realizovaná pridaním CSS triedy `obrateny` do hlavného elementu `div`.

Pokiaľ budeme požadovať smajlíka s veľkosťou `100px` obráteného o 180 stupňov, deklarácia HTML kódu bude nasledovná:

```html

<div class="smajlik s-100 obrateny">
    ...
</div>
```

Iné podoby smajlíka budú realizované pridaním ďalšej triedy do hlavného `div` elementu smajlíka. Druhý smajlík na obrázku bude mať triedu `vesely`, tretí `licka` a posledný `smutny`.

HTML kód druhého smajlíka bude vyzerať nasledovne:

```html

<div class="smajlik s-250 vesely">
    ...
</div>
```

Pri implementácii počítajte s tým, že smajlík môže mať len jeden tvar - normálny, veselý, smutný. Líčka, ale môžu mať ľubovolnú veľkosť a otočenie.

### Cieľ príkladu

Cieľom tohto príkladu je precvičenie CSS vlastností - veľkosť, pozícia, rámiky, transformácie, funckie `calc`, CSS premenných a použitie pseudotried `::before` a `::after`.

<div class="hidden">

[Zobraziť riešenie](riesenie.md)
</div>