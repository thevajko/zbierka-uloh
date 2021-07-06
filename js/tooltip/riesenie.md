<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/js/tooltip), [Riešenie](/../../tree/solution/js/tooltip).
> - [Zobraziť zadanie](zadanie.md)

# Tooltip (JS, CSS)

</div>

## Riešenie

V prvom kroku je potrebné vyriešiť skrytie textu popiskou. Jedným zo spôsobov, ako to dosiahnuť, je premiestniť tento text do ([`data-*` *atribútov*](https://www.w3schools.com/tags/att_global_data.asp)). Tým pádom bude HTML kód vyzerať nasledovne:

```html
<span data-tooltip="Tooltip: In mollis accumsan sodales.">consectetur</span>
```

Ďalším krokom je príprava zobrazenia textu, pokiaľ naň používateľ umiestni kurzor a jeho skrytie, ak odtiaľ odíde. Túto časť je možné vypracovať jedine s použitím JavaScriptu. Skript, ktorý bude vykonávať túto činnosť, sa musí spustiť automaticky po načítaní dokumentu<span class="hidden"> ([viac o spúštaní skriptov tu](../../common/js-onload.md))</span>. To sa tá dosiahnuť nasledovným kódom:

```javascript
window.onload = function () {
    // kód, ktorý sa spustí po načítaní dokumentu    
}
```

Logika zobrazovania a skrývania popiskov je pre všetky `span` elementy v celom dokumente rovnaká. Najprv musíme získať kolekciu všetkých `span` elementov v celom dokumente a potom pre každý `span` element pripojíme obsluhu udalosti. To realizujme nasledovne:

```javascript
let spans = document.querySelectorAll("span");
for (let i = 0; i < spans.length; i++) {
    let span = spans[i];

}
```

V tomto cykle vytvorime najprv logiku, ktorá text popisku získa z atribútu `data-tooltip` a následne ho zobrazí. Zobrazenie popisku používateľovi spravíme tak, že jeho text vložíme do ďalšieho prvého elementu ako vnorený element. Podľa [štandardu HTML](https://html.spec.whatwg.org/multipage/text-level-semantics.html#the-span-element) ale nemôžeme vložiť do elementu `span` ďalší `span` alebo `div`. Preto zmeníme element obsahujúci popisok na `div`, takže bude vyzerať:

```html
<div data-tooltip="Tooltip: In mollis accumsan sodales.">consectetur</div>
```

Musíme taktiež upraviť selektor v našom skripte tak, aby sme získali kolekciu `div` elementov obsahujúci atribút `data-tooltip`. Bude vyzerať nasledovne:

```javascript
let divs = document.querySelectorAll("div[data-tooltip]");
for (let i = 0; i < divs.length; i++) {
    let div = divs[i];

}
```

Teraz pridáme kód, ktorý sa vykoná, ak používateľ premiestni kurzor nad nejaký `div` s atribútom `data-tooltip`. Tá vytvorí nový `<div>` element a umiestni ho ako potomka do `<div>`, ktorý ma popisok. To dosiahnem pridaním funkcie pre udalosť `onmouseenter` a element vytvoríme modifikovaním obsahu atribútu `innerHTML` prvého elementu `div`:

```javascript
let divs = document.querySelectorAll("div[data-tooltip]");
for (let i = 0; i < divs.length; i++) {
    let div = divs[i];
    div.onmouseenter = function () {
        div.innerHTML = div.innerHTML + '<div class="tooltip">' + div.getAttribute("data-tooltip") + '</div>';
    }

}
```

Ak používateľ premiestni z prvého `div` kurzor preč, popisok skryjeme zmazaním vnoreného elementu `div` pomocou funkcie obsluhy udalosti. Pokiaľ máme referenciu na nejaký element, môžeme pomocou CSS selektoru vyberať jeho potomkov. Kód bude nasledovný:

```javascript
let divs = document.querySelectorAll("div[data-tooltip]");
for (let i = 0; i < divs.length; i++) {
    let div = divs[i];
    div.onmouseenter = function () {
        div.innerHTML = div.innerHTML + '<div class="tooltip">' + div.getAttribute("data-tooltip") + '</div>';
    }
    div.onmouseleave = function () {
        div.querySelector("div").remove();
    }
}
```

Ešte zostáva správne doplniť CSS. Ako prvé musíme upraviť zobrazenie popisku. Naša úprava ich zmení na `div` elementy, ktoré sa správajú ako blokové značky. Taktiež pridáme nejaké vizuálne oddelenie od okolitého textu. Selektor štýlu pre `div` s popiskom opäť použije selektor, ktorý sme použili v skripte (tentoraz v CSS):

```css
div[data-tooltip] {
    display: inline;
    font-weight: bold;
}
```

Samotný tooltip sa prídá ako potomok prvého `div` elementu. Vieme, že `div` je bloková značka. Je preto potrebné jej definovať šírku, aby sa popisok zobrazoval rovnako. Pridáme preto napr. `width: 200px;`. Tento `div` má transparentné pozadie a aby bol lepšie čitateľný, zafarbíme ho napr. na bielo `background-color: white;`. Bolo by ďalej dobré pridať nejaký rámček pomocou `border: 1px solid black;`. CSS teda bude vyzerať nasledovne:

```css
.tooltip {
    width: 200px;
    border: 1px solid black;
    background-color: white;
    padding: 3px;
}
```

Pre zobrazenie popisku budeme používať CSS vlastnosť `position`. Ako prvé musíme nastaviť v prvom elemente tento atribút na hodnotu `position: relative;` pre prvý `div` element. To preto, aby sme ho mohli použiť ako plochu pre umiestnenie samotného popisku.

Pre umiestnenie popisku nastavíme `position: absolute;`. To spôsobí, že element s popiskom začne "plávať" nad ostatnými elementmi. Teraz potrebujeme element s popiskom správne umiestniť. To docielime nastavením CSS atribútov`left` a `top`.

- `left` definuje vzdialenosť elementu od ľavej strany rodičovského elementu.
- `top` definuje vzdialenosť elementu od vrchu rodičovského elementu.

Pri nastavení `position: absolute;` nejakého element sa za jeho *rodičovský element* určuje hierarchicky najbližší vyšší element, ktorý má nastavený CSS atribút `position` na `relative` alebo `absolute`. V našom prípade to je prvý element. Tým pádom môžeme nastaviť hodnotu `left: 0;` a `top: 120%;`. Potom bude popisok zarovnaný vľavo a bude pod prvým elementom<span class="hidden"> ([podrobnejšie o CSS position](../../common/css-position.md))</span>. Výsledné CSS bude:

```CSS
div[data-tooltip] {
    display: inline;
    font-weight: bold;
    position: relative;
}

.tooltip {
    display: block;
    width: 200px;
    border: 1px solid black;
    position: absolute;
    background-color: white;
    padding: 3px;
    top: 120%;
    left: 0;
}
```

Prečo sme nenastavili vertikálnu polohu ako `top: 100%` alebo `bottom: 0`? Dôvodom je, že náš skript používa na skrytie elementu udalosť, keď používateľ premiestni z elementu `div` kurzor preč. Treba si uvedomiť, že pre tieto udalosti používa DOM, nie reálne zobrazenie. Tým pádom, aj keď je popisok vizuálne umiestnený mimo prvý element, v DOM  štruktúre to tak nie je. Bohužiaľ potom by popisok zostal zobrazený, keď by naň používateľ prešiel kurzorom myši, čo nechceme.
