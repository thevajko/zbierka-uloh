<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/js-a-css/tooltip-js), [Riešenie](/../../tree/solution/js-a-css/tooltip-js).

# Tooltip (JS, CSS)
</div>

## Riešenie
V prvom kroku je potrebné vyriešiť skrytie textu samotných doplňujúcich textoch. Jedným zo spôsobov ako to dosiahnuť, je premiestniť tento text do `data-*` atribútov ([viac tu](https://www.w3schools.com/tags/att_global_data.asp)). Tým pádom bude `HTML` kód vyzerať nasledovne:

```html
<span data-tooltip="Tooltip: asdasd asdasd asd a sd asd asdasda sda">consectetur</span>
```

Ďalším krokom je pripravenie zobrazenia textu, pokiaľ naň používateľ umiestni kurzor a jeho skrytie pokiaľ. Túto časť je možné vypracovať jedine použitím `JS`. Skript, ktorý bude aktivovať logiku sa musí spustiť automaticky po načítaní dokumentu ([viac o spúštaní skriptov tu](../../common/js-onload.md)). To sa tá docieliť nasledovným kódom:

```javascript
window.onload = function()  {
    // kod co sa spusti po nacitani dokumentu    
}
```

Logika zobrazovanie a skrývania tooltipov je pre všetky `<span>` elementy v celom dokument rovnaká. Tým pádom musíme najprv získať kolekciu všetkých span elementov v celom dokumente a potrebujeme pre každý span element pripojiť logiku. To realizujme nasledovne:

```javascript
let spans = document.querySelectorAll("span");
for (let i = 0; i < spans.length; i++) {
    let span = spans[i];
    
}
```

V tomto cykle vytvorime najprv logiku, ktorá text tooltipu získa z atribútu `data-tooltip` a následne ho zobrazí. Zobrazenie tooltipu používateľovi spravíme tak, že jeho text vložíme do ďalšieho prvého elementu ako sub-element. Podľa [štandardu HTML](https://html.spec.whatwg.org/multipage/text-level-semantics.html#the-span-element) ale nemôžeme vložiť do elementu span ďalší span alebo div. Preto zmeníme element obsahujúci tooltip na div, takže bude vyzerať:

```html
<div data-tooltip="Tooltip: asdasd asdasd asd a sd asd asdasda sda">consectetur</div>
```

Musíme taktiež upraviť _selektor_ v našom skripte, tak aby sme získali kolekciu `<div>` elementov obsahujúci atribút `data-tooltip`. Bude vyzerať nasledovne:

```javascript
let divs = document.querySelectorAll("div[data-tooltip]");
for (let i = 0; i < divs.length; i++) {
    let div = divs[i];
    
}
```

Teraz pridáme logiku, ktorá sa vykoná, pokiaľ používateľ dá kurzor nad nejaký div s atribútom `data-tooltip`. Tá vytvorí nový `<div>` element a umiestni ho ako potomka do `<div>`, ktorý ma tooltip. To docielime pridaním funkcie pre udalosť `onmouseenter` a element vytvoríme modifikovaním obsahu atribútu `innerHTML` prvého elementu `<div>`:

```javascript
let divs = document.querySelectorAll("div[data-tooltip]");
for (let i = 0; i < divs.length; i++) {
    let div = divs[i];
    div.onmouseenter  = function() {
        div.innerHTML = div.innerHTML + '<div class="tooltip">' +div.getAttribute("data-tooltip") + '</div>';
    }
    
}
```

Ak používateľ dá z prvého div kurzor preč tooltip skryjeme zmazaním vnoreného elementu div pomocou logiky umiestnenej do udalosti. Pokiaľ máme referenciu na nejaký element, môžeme pomocou `CSS` _selektoru_ vyberať jeho sub-elementy. Kód bude nasledovný:

```javascript
let divs = document.querySelectorAll("div[data-tooltip]");
for (let i = 0; i < divs.length; i++) {
    let div = divs[i];
    div.onmouseenter  = function() {
        div.innerHTML = div.innerHTML + '<div class="tooltip">' +div.getAttribute("data-tooltip") + '</div>';
    }
    div.onmouseleave = function() {
        div.querySelector("div").remove();
    }
}
```

Ako posledná časť ostáva správne doplniť `CSS`. Ako prvé musíme upraviť zobrazenie tooltipu. Naša upráva ich zmení na `<div>`, ktoré sa chovajú ako blokové značky. Taktiež pridáme nejaké vizuálne oddelenie od okolitého textu. _Selektor_ stýlu pre `<div>` s tooltipom opäť použije _selektor_, ktorý sme použili v skripte:

```css
div[data-tooltip] {
    display: inline;
    font-weight: bold;
}
```

Samotný tooltip sa prídá ako sub-element prvého `<div>`. Vieme, že div je bloková značka. Je preto potrebné jej definovať šírku aby sa tooltip zobrazoval konštantne, pridáme preto napr. `width: 200px;`. Tento `<div`> má transparentné pozadie a aby bol lepšie čitateľný je potrebné ho zafarbiť napr. na bielo `background-color: white;`. Bolo by ďalej dobré pridať nejaký rámik pomocou `border: 1px solid black;`. CSS teda bude vyzerať nasledovne:

```css
div[data-tooltip] {
    display: inline;
    font-weight: bold;
}

.tooltip {
    width: 200px;
    border: 1px solid black;
    background-color: white;
    padding: 3px;
}
```

Pre zobrazenie tooltipu budeme používať `CSS` vlastnosť `possition`. Ako prvé musíme nastaviť v prvom elemente tento atribút na hodnotu `possition: relative;` pre prvý div. To preto aby sme ho mohli použiť ako plochu pre pozíciovanie samotného tooltipu.

Pre umiestnenie tooltipu mu nastavíme `position: absolute;`. To spôsobí, že element s tooltipom začne "plávať" nad ostatnými elementmi. Teraz potrebujeme element s tooltipom správne umiestniť. To docielime nastavením `CSS` atribútov`left` a `top`.

- `left` definuje vzdialenosť elementu od ľavej strany rodičovského elementu.
- `top` definuje vzdialenosť elementu od vrchu rodičovského elementu.

Pri nastavení `position: absolute;` nejakého element sa za jeho "rodičovský element" určuje hierarchicky vyšší najbližší element, ktorý má nastavený `CSS` atribút `position` na `relative` alebo `absolute`. V našom prípade to je prvý element. Tým pádom môžeme nastaviť hodnotu `left: 0;` a `top: 120%;`. Tým pádom bude tooltip zarovnaný naľavo a bude kúsok pod prvým elementom ([podrobnejšie o CSS position](../../common/css-position.md)). Výsledné `CSS` bude:

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

Prečo sme nenastavili vertikálnu polohu ako `top: 100%` alebo `bottom: 0`? To je preto, lebo náš skript používa na skrytie elementu udalosť, keď používateľ dá s prvého elementu kurzor preč. Treba si uvedomiť, že pre tieto udalosti používa _javascript DOM_, nie reálne zobrazenie. Tým pádom aj ked je tooltip vizuálne umiestnení mimo prvý element v štruktúre to tak nie je. Tým pádom by tooltip ostal zobrazený ked by naň používateľ prešiel kurzorom, čo nechceme.