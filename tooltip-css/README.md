# Tooltip - iba CSS

Vytvorte logiku, _čisto pomocou `CSS`_, ktorá bude zobrazovať tooltip, pokiaľ používateľ umiestni nad daný výraz kurzor.
Pokiaľ ho dá preč tooltip zmizne.

Štartér verzia obsahuje v `HTML` podobný kód ako je tento:
```html
<div>
    Lorem ipsum dolor sit amet, 
    <span>consectetur</span>
    <span class="tooltip">Tooltip: asdasd asdasd asd a sd asd asdasda sda</span> 
    adipiscing elit. In 
    <span>hendrerit</span>
    <span class="tooltip">Tooltip: asdasd asdasd asd a sd asd asdasda sda</span> 
    adipiscing elit. ac ex eu aliquam. Etiam lacus orci, egestas et tempor at, 
    <span>rutrum</span>
    <span class="tooltip">Tooltip: asdasd asdasd asd a sd asd asdasda sda</span> 
    adipiscing elit. vitae nulla.
</div>
```


Štruktúru dokumentu môžete upraviť, tak aby bolo možné úlohu vypracovať. Pre vypracovanie použite výlučne `CSS`. 

# Riešenie

V prvom kroku si musíme upraviť struktúru zdrojového súbora, tak aby nám umožnila vyžiť dosiahnutie stanoveného cieľa
pomocou CSS. Tu budeme používať [CSS pseudo-triedu](https://www.w3schools.com/css/css_pseudo_classes.asp) `:hover`. 
Tá je priradená k elementu automaticky, pokiaľ sa nad daným prvkom nachádza kurzor. Logicky chceme docieliť to, že pokiaľ
hlavný element (ten nad ktorý sa ma umiestniť kurzor) zobrazí sa tooltip, ináč je tooltip nezobrazený. `:hover` sa bude teda
dopĺňať do neho, nie do elementu s textom tooltipu.

CSS selektor definuje skupinu elementov na ktoré sa aplikujú dané vlastnsti. Jeden zo spôsobov ako to zadefinovať je
uviešt štruktúru elementov, resp. "cestu štrukúrov" k elementu na ktorý chceme vlastnosti aplikovať. To znamená,
že budeme meniť CSS vlastnosti elemntu s textom tooltipu vzhladom na to, či hlavný element má priradenú triedu `:hover`.

Preto budeme musieť upraviť štruktúru zdrojového HTML. Podľa [štandardu HTML](https://html.spec.whatwg.org/multipage/text-level-semantics.html#the-span-element) ale
nemôžeme vložiť do elementu `span` ďalší `span` alebo `div`. Preto zmeníme element obsahujúci tooltip na `div`, takže
bude vyzerať:

```HTML
<div class="text">
    Lorem ipsum dolor sit amet, 
    <div class="has-tooltip">
        consectetur
        <span class="tooltip">
            asdasdas dasdasd asdasdas dasdasdas das dasdasdas da sd as da sd as d
        </span>
    </div>
    adipiscing elit. Curabitur tempor leo at urna varius, eget scelerisque
    arcu interdum. Aliquam et accumsan diam. Mauris 
    <div class="has-tooltip">
        tincidunt
        <span class="tooltip">
            asdasdas dasdasd asdasdas dasdasdas das dasdasdas da sd as da sd as d
        </span>
    </div>
    
    ...
    
</div>
```

Samozrejme div je bloková značka a je preto potrebné upraviť jej CSS, tak aby sa správala ako inline. Ďalej musíme
zabezpečiť aby sa text tooltipu použivateľov nezobaril. CSS bude teda:

```css
div.has-tooltip {
    display: inline;
    font-weight: bold;
}
div.has-tooltip .tooltip {
    display: none;
}
```

Teraz potrebujeme vytvotiž CSS selektor a vlastnosti, ktoré budú tooltip zobrazovať a skrývať. Chceme docieliť aby
sa text tooltipu zobrazil iba ak bude kurzor nad hlavným elementom. Tu použijeme pseudotriehu `:hover` pomocou selektoru
`div.has-tooltip:hover .tooltip`. 

Predvolene text tootliou skryjeme tým, že mu nastavíme hodnotu `CSS` vlastnosti `display` na hodnotu `none`. Pre opätovné 
zobrazenie vložíme, v našom prípade, hodnotu `block`. CSS bude vyzerať nasledovne:

```css
div.has-tooltip {
    display: inline;
    font-weight: bold;
}
div.has-tooltip .tooltip {
    display: none;
}
div.has-tooltip:hover .tooltip {
    display: block;
}
```
Aby sa text tooltipov zobrazoval konštatne musíme naprv zadefinovať jeho veľkost, stačí šírka, prídáme preto napr. 
`width: 200px;`. Element má transparentné pozadie a aby bol lepšie čitatelný je potrebné ho zafarbiť napr. na bielo 
`background-color: white;`. Bolo by ďalej dobré pridať nejaký rámik pomocou `border: 1px solid black;`. CSS upravíme na
následové:

```css
div.has-tooltip {
    display: inline;
    font-weight: bold;
}
div.has-tooltip .tooltip {
    display: none;
    width: 200px;
    border: 1px solid black;
    position: absolute;
    background-color: white;
    padding: 3px;
}
div.has-tooltip:hover .tooltip {
    display: block;
}
```


Pre zobrazenie tooltipu budeme používať CSS vlastnosť `possition`. Ako prvé musíme nastaviť v prvom elemente tento
atribút na hodnotu `possition: relative;` pre prvý div. To preto aby sme ho mohli použiť ako plochu pre pozíciovanie
samotného tooltipu.

Pre umiestnenie tooltipu mu nastavíme `position: absolute;`. To spôsobí, že element s tooltipom začne "plávať" nad
ostatnými elementmi. Teraz potrebujeme element s tooltipom správne umiestniť. To docielime nastavením CSS atribútov
`left` a `top`.

- `left` definuje vzdialenosť elementu od lavej strany rodičovského elementu.
- `top` definuje vzdialenosť elementu od vrchu rodičovského elementu.

Pri nastavení `position: absolute;` nejakého elemenut sa za jeho "rodičovský element" určuje hierarchycký vyšší
najbliží element, ktorý má nastavený CSS atribút `position` na `relative` alebo `absolute`. V našom prípade to je
prvý element. Tým pádom možeme nastaviť hodnotu `left: 0;` a `top: 120%;`. Tým pádom bude tooltip zarovnaný nalavo
a bude kúsok pod prvým elementom. 

Aby sa tooltip zobraz oval korektne pridáme ešte nasledovnú CSS vlastnosť `z-index: 1;`. Tým docielime to, že sa element
s textom tooltipu zobrazí vždy vo vrstve nad aktuálnimi elementmi; t.j. zobrazí sa vždy nad 

Výsledné CSS bude:

```css
div.has-tooltip {
    display: inline;
    font-weight: bold;
    position: relative;
}
div.has-tooltip .tooltip {
    display: none;
    width: 200px;
    border: 1px solid black;
    position: absolute;
    background-color: white;
    padding: 3px;
    top: 120%;
    left: 0;
    z-index: 1;
}
div.has-tooltip:hover .tooltip {
    display: block;
}
```