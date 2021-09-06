<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/css/tooltip), [Riešenie](/../../tree/solution/css/tooltip)
> - [Zobraziť zadanie](zadanie.md)

# Tooltip - iba CSS

</div>

## Riešenie

V prvom kroku si musíme upraviť štruktúru zdrojového súboru tak, aby sme dosiahli stanovený cieľ len pomocou CSS. Budeme používať [*CSS pseudotriedu*](https://www.w3schools.com/css/css_pseudo_classes.asp) `:hover`. Tá je priradená k elementu automaticky webovým prehliadačom, pokiaľ sa nad daným prvkom nachádza kurzor myši. 

Aby sme mohli pseudotriedu `:hover` použiť, musíme upraviť štruktúru zdrojového HTML kódu. Umiestnime preto element s popiskom ako potomka elementu, ku ktorému sa vzťahuje. Podľa [štandardu HTML](https://html.spec.whatwg.org/multipage/text-level-semantics.html#the-span-element), ale nemôžeme vložiť do elementu `span` ďalší `span` alebo `div` element, takže zmeníme element `span` obsahujúci popisok na element `div`.

Dôvodom pre zmenu štruktúry je spôsob, akým budeme používať CSS pre zobrazenie popiskov. Predvolene sú popisky skryté a zobraziť sa majú iba, ak nad textom, ktorý má popisok, je kurzor myši. 

Nakoľko CSS selektor definuje skupinu elementov, najprv vytvoríme selektor, ktorý vyberie všetky elementy popiskov v elementoch, ktoré majú popisok a tie skryjeme. Ďalšie pravidlo zadefinujeme pre všetky elementy popiskov v elementoch, ktoré majú popisok a *je nad nimi kurzor myši* a tie zobrazíme.

Preto budeme musieť HTML kód upraviť nasledovne:

```html
<div class="text">
    Lorem ipsum dolor sit amet,
    <div class="has-tooltip">
        consectetur
        <span class="tooltip">
        In mollis accumsan sodales.
      </span>
    </div>
    adipiscing elit. In
    <div class="has-tooltip">
        hendrerit
        <span class="tooltip">
        Maecenas lobortis quam quis euismod maximus.
      </span>
    </div>
    ... 
</div>
```

Samozrejme `<div>` je bloková značka, a preto je potrebné upraviť jej CSS tak, aby sa správala ako *riadková* značka. Ďalej musíme zabezpečiť, aby sa text popisku používateľovi (zatiaľ) nezobrazil. CSS bude teda:

```css
div.has-tooltip {
    display: inline;
    font-weight: bold;
}
div.has-tooltip .tooltip {
    display: none;
}
```

Teraz potrebujeme vytvoriť CSS štýl, ktorý bude popisok zobrazovať a skrývať. Chceme docieliť, aby sa text popisku zobrazil iba, ak bude kurzor nad elementom, ktorý označuje, že úsek textu bude mať popisok. Použijeme pseudotriedu `:hover` pomocou selektoru `div.has-tooltip:hover .tooltip`.

Predvolený text popisku skryjeme tak, že mu nastavíme hodnotu CSS vlastnosti `display` na hodnotu `none`. V našom prípade pre opätovné zobrazenie vložíme hodnotu `block`. CSS bude vyzerať nasledovne:

```css
div.has-tooltip:hover .tooltip {
    display: block;
}
```

Ak chceme, aby sa text popiskov zobrazoval vždy rovnako, musíme najprv zadefinovať jeho šírku (`width: 200px;`). Element má transparentné pozadie a kvôli lepšej čitateľnosti je potrebné ho zafarbiť, napr. na bielo `background-color: white;`. Ďalej by bolo dobré pridať nejaký rámček pomocou `border: 1px solid black;`. CSS upravíme nasledovne:

```css
div.has-tooltip .tooltip {
    display: none;
    width: 200px;
    border: 1px solid black;
    position: absolute;
    background-color: white;
    font-weight: normal;
    padding: 3px;
}
```

Pre zobrazenie popisku budeme používať CSS vlastnosť `position`. Ako prvé musíme nastaviť v rodičovskom elemente túto vlastnosť na hodnotu `position: relative;`. To preto, aby sme ho mohli použiť ako plochu pre určenie pozície samotného popisku.

Pre umiestnenie popisku mu nastavíme `position: absolute;`. To spôsobí, že element s&nbsp;popiskom začne "plávať" nad ostatnými elementmi. Teraz potrebujeme element s&nbsp;popiskom správne umiestniť. To docielime nastavením CSS vlastností `left` a `top`:

- `left` definuje vzdialenosť elementu od ľavej strany rodičovského elementu. 
- `top` definuje vzdialenosť elementu od vrchu rodičovského elementu.

Pri nastavení `position: absolute;` nejakého elementu sa za jeho "rodičovský element" považuje hierarchicky najbližší vyšší element, ktorý má nastavený CSS atribút `position` na `relative` alebo `absolute`. V našom prípade je to prvý element. Tým pádom môžeme nastaviť hodnoty `left: 0;` a `top: 120%;`. Popisok bude zarovnaný naľavo a bude sa nachádzať kúsok pod prvým elementom.

Pridaná CSS vlastnosť `z-index: 1;` zabezpečí, že sa element s textom popisku zobrazí vždy vo vrstve nad aktuálnymi elementmi; t.j. nad nimi.
<div class="end">

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
</div>
Na záver ešte k popisku pridáme automatický text na začiatok, aby sme zvýraznili, že ide o popisok (*tooltip*), pomocou pseudoelementu `::before`:

```css
div.has-tooltip .tooltip::before {     
    content: "Tooltip: ";
    font-weight: bold;
}
```