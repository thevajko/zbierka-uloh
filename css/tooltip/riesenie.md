<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/css/tooltip), [Riešenie](/../../tree/solution/css/tooltip).
> - [Zobraziť zadanie](zadanie.md)

# Tooltip - iba CSS

</div>

## Riešenie

V prvom kroku si musíme upraviť štruktúru zdrojového súboru tak, aby sme dosiahli stanovený cieľ len pomocou CSS. Budeme používať [CSS pseudo-triedu](https://www.w3schools.com/css/css_pseudo_classes.asp) `:hover`. Tá je priradená k elementu automaticky, pokiaľ sa nad daným prvkom nachádza kurzor myši. Chceme docieliť to, že pokiaľ hlavný element (ten, nad ktorý sa ma umiestniť kurzor) zobrazí sa popisok, inak je popisok nezobrazený. `:hover` sa bude teda dopĺňať do neho, nie do elementu s textom popisku.

CSS selektor definuje skupinu elementov, na ktoré sa aplikujú dané vlastnosti. Jeden zo spôsobov ako to zadefinovať, je uviesť štruktúru elementov, resp. "cestu štruktúrou" k elementu, na ktorý chceme vlastnosti aplikovať. To znamená, že budeme meniť CSS vlastnosti elementu s textom popisku vzhľadom na to, či hlavný element má priradenú triedu `:hover`.

Preto budeme musieť upraviť štruktúru zdrojového HTML. Podľa [štandardu HTML](https://html.spec.whatwg.org/multipage/text-level-semantics.html#the-span-element), ale nemôžeme vložiť do elementu `span` ďalší `span` alebo `div` element. Preto zmeníme element obsahujúci popisok na `div` tak, že bude vyzerať nasledovne:

```HTML

<div class="text">
    Lorem ipsum dolor sit amet,
    <div class="has-tooltip">
        consectetur
        <span class="tooltip">
        Tooltip: In mollis accumsan sodales.
      </span>
    </div>
    adipiscing elit. In
    <div class="has-tooltip">
        hendrerit
        <span class="tooltip">
        Tooltip: Maecenas lobortis quam quis euismod maximus.
      </span>
    </div>
    ... 
</div>
```

Samozrejme `div` je bloková značka, a preto je potrebné upraviť jej CSS, tak aby sa správala ako *inline* značka. Ďalej musíme zabezpečiť, aby sa text popisku používateľovi nezobrazil. CSS bude teda:

```css
div.has-tooltip {
    display: inline;
    font-weight: bold;
}

div.has-tooltip .tooltip {
    display: none;
}
```

Teraz potrebujeme vytvoriť CSS selektor a vlastnosti, ktoré budú popisok zobrazovať a skrývať. Chceme docieliť, aby sa text popisku zobrazil iba, ak bude kurzor nad elementom, ktorý označuje, že úsek textu bude mať popisok. Tu použijeme pseudotriedu `:hover` pomocou selektoru `div.has-tooltip:hover .tooltip`.

Predvolený text popisku skryjeme tak, že mu nastavíme hodnotu CSS vlastnosti `display` na hodnotu `none`. V našom prípade pre opätovné zobrazenie vložíme hodnotu `block`. CSS bude vyzerať nasledovne:

```css
div.has-tooltip:hover .tooltip {
    display: block;
}
```

Ak chceme, aby sa text popiskov zobrazoval vždy rovnako, musíme najprv zadefinovať jeho šírku (`width: 200px;`). Element má transparentné pozadie a kvôli lepšej čitateľnosti je potrebné ho zafarbiť napr. na bielo `background-color: white;`. Ďalej by bolo dobré pridať nejaký rámček pomocou `border: 1px solid black;`. CSS upravíme nasledovne:

```css
div.has-tooltip .tooltip {
    display: none;
    width: 200px;
    border: 1px solid black;
    position: absolute;
    background-color: white;
    padding: 3px;
}
```

Pre zobrazenie popisku budeme používať CSS vlastnosť `position`. Ako prvé musíme nastaviť v prvom elemente tento atribút na hodnotu `position: relative;` pre prvý element `div`. To preto, aby sme ho mohli použiť ako plochu pre určenie pozície samotného popisku.

Pre umiestnenie popisku mu nastavíme `position: absolute;`. To spôsobí, že element s popiskom začne "plávať" nad ostatnými elementmi. Teraz potrebujeme element s popiskom správne umiestniť. To docielime nastavením CSS atribútov `left` a `top`:

- `left` definuje vzdialenosť elementu od ľavej strany rodičovského elementu. 
- `top` definuje vzdialenosť elementu od vrchu rodičovského elementu.

Pri nastavení `position: absolute;` nejakého elementu sa za jeho "rodičovský element" považuje hierarchicky najbližší vyšší element, ktorý má nastavený CSS atribút `position` na `relative` alebo `absolute`. V našom prípade je to prvý element. Tým pádom môžeme nastaviť hodnoty `left: 0;` a `top: 120%;`. Popisok bude zarovnaný naľavo a bude sa nachádzať kúsok pod prvým elementom.

Kvôli korektnému zobrazeniu popisku pridáme ešte CSS vlastnosť `z-index: 1;`. Tým dosiahneme to, že sa element s textom popisku zobrazí vždy vo vrstve nad aktuálnymi elementmi; t.j. zobrazí sa vždy nad nimi.

```css
div.has-tooltip {
    position: relative;
}

div.has-tooltip .tooltip {
    top: 120%;
    left: 0;
    z-index: 1;
}
```

<div class="solution">

Kompletné zdrojové kódy hotového riešenia môžete nájsť na tejto URL adrese:

[https://github.com/thevajko/zbierka-uloh/tree/solution/css/tooltip](https://github.com/thevajko/zbierka-uloh/tree/solution/css/tooltip)

![URL adresa hotového riešenia](images_tooltip-css/qr-tooltip-css.png)
</div>