<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/css/emoticons), [Riešenie](/../../tree/solution/css/emoticons).
</div>

# Smajlíky
<div class="info"> 

**Hlavný jazyk príkladu**: CSS

**Ostatné použité jazyky**: HTML

**Obťažnosť**: 4/5

**Obsah príkladu**: CSS vlastnosti - veľkosť, pozícia, rámčeky, transformácie, funkcie `calc()`, CSS premenné a použitie pseudotried `::before` a `::after`.
</div>

## Zadanie

Vytvorte pomocou CSS bez použitia grafiky (obrázky, `svg`) nasledujúce smajlíky.

![Zadanie príkladu Smajlíky](images_emoticons/zadanie.png)

Každý smajlík bude dostupný vo veľkostiach `50px`, `100px`, `250px` a `500px`.

Prvé štyri smajlíky sú základné. Každý zo základných smajlíkov sa bude dať otočiť o 180 stupňov. Posledný smajlík na ukážke vznikol otočením prvého smajlíka.

Základná HTML štruktúra smajlíka bude nasledovná:

```html

<div class="smiley">
    ...
</div>
```

Túto štruktúru je potrebné dodržať. Obsah elementu `div` si prispôsobte podľa potreby. Tento HTML kód vykreslí prvého smajlíka vo veľkosti `50px`. Pokiaľ chceme zmeniť veľkosť smajlíka, pridáme do `div` elementu ďalšiu CSS triedu - `s-100`, `s-250` alebo `s-500` podľa požadovanej veľkosti.

Rotácia smajlíka bude realizovaná pridaním CSS triedy `obrateny` do hlavného elementu `div`.

Pokiaľ budeme požadovať smajlíka s veľkosťou `100px` obráteného o 180 stupňov, deklarácia HTML kódu bude nasledovná:

```html

<div class="smiley s-100 obrateny">
    ...
</div>
```

Iné podoby smajlíka budú realizované pridaním ďalšej triedy do hlavného `div` elementu smajlíka. Druhý smajlík na obrázku bude mať triedu `happy`, tretí `cheeks` a posledný `sad`.

HTML kód druhého smajlíka bude vyzerať nasledovne:

```html

<div class="smiley s-250 happy">
    ...
</div>
```

Pri implementácii počítajte s tým, že smajlík môže mať len jeden tvar - normálny, veselý, smutný. Líčka, ale môžu mať ľubovolnú veľkosť a otočenie.

<div class="hidden">

[Zobraziť riešenie](riesenie.md)
</div>