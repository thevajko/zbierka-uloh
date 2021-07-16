<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/css/emoticons), [Riešenie](/../../tree/solution/css/emoticons).
    [Zobraziť riešenie](riesenie.md)
</div>

# Emotikony
<div class="info"> 

**Hlavný jazyk príkladu**: CSS

**Ostatné použité jazyky**: HTML

**Obťažnosť**: 4/5

**Obsah príkladu**: CSS vlastnosti - veľkosť, pozícia, rámčeky, transformácie, funkcia `calc()`, CSS vrstvy, CSS premenné a použitie pseudotried `::before` a `::after`.
</div>

## Zadanie

Vytvorte pomocou CSS bez použitia grafiky (obrázky, `svg`) nasledujúce emotikony.

![Zadanie príkladu Emotikony](images_emoticons/zadanie.png)

Každý emotikon bude dostupný vo veľkostiach `50px`, `100px`, `250px` a `500px`.

Prvé štyri emotikony sú základné. Každý zo základných emotikonov sa bude dať otočiť o 180 stupňov. Posledný emotikon na ukážke vznikol otočením prvého emotikona.

Základná HTML štruktúra emotikona bude nasledovná:

```html
<div class="smiley">
    ...
</div>
```

Túto štruktúru je potrebné dodržať. Obsah elementu `div` si prispôsobte podľa potreby. Tento HTML kód vykreslí prvého emotikona vo veľkosti `50px`. Pokiaľ chceme zmeniť veľkosť emotikona, pridáme do `div` elementu ďalšiu CSS triedu - `s-100`, `s-250` alebo `s-500` podľa požadovanej veľkosti.

Rotácia emotikona bude realizovaná pridaním CSS triedy `upside-down` do hlavného elementu `div`.

Pokiaľ budeme požadovať emotikona s veľkosťou `100px` obráteného o 180 stupňov, deklarácia HTML kódu bude nasledovná:

```html
<div class="smiley s-100 upside-down">
    ...
</div>
```

Iné podoby emotikona budú realizované pridaním ďalšej triedy do hlavného `div` elementu emotikona. Druhý emotikon na obrázku bude mať triedu `happy`, tretí `cheeks` a posledný `sad`.

HTML kód druhého emotikona bude vyzerať nasledovne:

```html
<div class="smiley s-250 happy">
    ...
</div>
```

Pri implementácii počítajte s tým, že emotikon môže mať len jeden tvar - normálny, veselý, smutný alebo líčka, ale môže mať ľubovolnú veľkosť a otočenie.