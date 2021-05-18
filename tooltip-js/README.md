# Tooltip - JS a CSS

Vytvorte skript, ktorý zobrazí doplňujuci text pokiaľ používateľ umiestni kurzor
nad `<span>` elementy v dokumente. 

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
Kód obsahuje `div`, ktorého obsah je tvorený textom generovaný pomocou [Lorem ipsum generátora](https://www.lipsum.com/).
V tomto texte sa ďalej nachádzajú elementy `span`, kde prvý span označuje výraz a druhý `span` s triedou `tooltip` obsahuje 
text doplňujúceho textu, ktorý sa ma používateľovi zovraziť ak nad prvý `span` umiestni kurzor. Akonáhle používateľ
dá kurzor z prvého `span` preč, doplňujúci text sa skryje. Samozrejme, pri otvorení dokumentu nesmú byť doplňujúce texty
zobrazené.

Štruktúru dokumentu môžete upraviť, tak aby bolo možné úlohu vypracovať. Pre vypracovanie sa snažte použiť čistý 
javascript a CSS. 

# Riešenie

V prvom kroku je potrebné vyriešiť skrytie textu samotných doplňujúcich textoch. Jedným zo spôsobov ako to dosiahnuť, 
je premiestnit tento text do `data-*` atribútov ([viac tu](https://www.w3schools.com/tags/att_global_data.asp)). Tým
pádom bude HTML kód vyzerať nasledovne: 

```html
<span data-tooltip="Tooltip: asdasd asdasd asd a sd asd asdasda sda">consectetur</span>
```

Ďaľším krokom je pripravenie zobrazenia textu, pokiaľ naň používateľ umiestni kurzor a jeho skrytie pokiaľ. Túto časť
je možné vypracovať jedine použitím javascriptu. Skript, ktorý bude aktivovať logiku sa musí spustiť automaticky 
po načítani dokumentu. To sa tá docieliť nasledovným kódom:

```javascript
window.onload = function()  {
    // kod co sa spusti po nacitani dokumentu    
}
```

Logika zobrazovanie a skrývania tooltipov je pre všetky `span` elementy v celom dokument rovnaká. Tým pádom musíme najprv
získať kolekciu všetkých span elementov v celom dokumente a potrebujeme pre každý span element pripojiť logiku. To 
realizuejme nasledovne:

```javascript
let spans = document.querySelectorAll("span");
for (let i = 0; i < spans.length; i++) {
    let span = spans[i];
    
}
```

V tomto cykle vytvorime najprv logiku, ktorá text tooltipu získa z atrinútu `data-tooltip` a následne ho zobrazí. 
Zobrazenie tooltipu používateľovi spravíme tak, že jeho text vložíme do ďaľšieho prvého elementu ako subelement.
Podľa [štandardu HTML](https://html.spec.whatwg.org/multipage/text-level-semantics.html#the-span-element) ale 
nemôžeme vložiť do elementu span ďalší span alebo div. Preto zmeníme element obsahujúci tooltip na div, takže 
bude vyzerať:

```html
<div data-tooltip="Tooltip: asdasd asdasd asd a sd asd asdasda sda">consectetur</div>
```

Musíme taktiež upraviť selektor v našom skripte, tak aby sme získali kolekciu div elementov obsahujúci atribút `data-tooltip`.
Bude vyzerať nasledovne:

```javascript
let divs = document.querySelectorAll("div[data-tooltip]");
for (let i = 0; i < divs.length; i++) {
    let div = divs[i];
    
}
```

Teraz pridáme logiku, ktorá sa vykoná, pokiaľ používateľ dá kurzor nad nejaký div s atribútom `data-tooltip`. Tá
vytvorí nový `div` element a umiestni ho ako potomka do `divu`, ktorý ma tooltip. To docielime pridaním funkcie pre udalosť
`onmouseenter` a element vytvoríme modifikovaním obsahu atribútu `innerHTML` prvého elemntu `div`:

```javascript
let divs = document.querySelectorAll("div[data-tooltip]");
for (let i = 0; i < divs.length; i++) {
    let div = divs[i];
    div.onmouseenter  = function() {
        div.innerHTML = div.innerHTML + '<div class="tooltip">' +div.getAttribute("data-tooltip") + '</div>';
    }
    
}
```

Ak používateľ dá z prvého div kurzor preč tooltip skyjeme zmazaním vnoreného elementu div pomocou logiky umiestnenej do 
udalosti. Pokiaľ máme referenciu na nejaký element, možeme pomocou CSS selektoru vyberať jeho subelementy. Kód bude 
nasledovný:

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

Ako posledná časť ostáva správne CSS. Ako prvé musíme upraviť zobrazenie tooltipu. Naša upráva ich zmení na div, ktoré 
sa chovajú ako blokové značky. Taktiež pridáme nejaké vizuálne oddelenie od okolitého textu. 
Selektor stýlu pre div s tooltipom opäť použije selektor, ktorý sme použili v skripte:

```css
div[data-tooltip] {
    display: inline;
    font-weight: bold;
}
```

Samotný tooltip sa prída ako subelement prvého divu. Vieme, že div je bloková značka. Je preto potrebné jej definovať
šírku aby sa tooltip zobrazoval konštatne, prídáme preto napr. `width: 200px;`. Div má transparentné pozadie a aby bol
lepšie čitatelný je potrebné ho zafarbiť napr. na bielo `background-color: white;`. Bolo by ďalej dobré pridať nejaký 
rámik pomocou `border: 1px solid black;`  

CSS teda bude vyzerať nasledovne:

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
a bude kúsok pod prvým elementom. Výsledné CSS bude:

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

Prečo sme nenastavili vertikálnu polohu ako top: 100% alebo bottom: 0? To je preto, lebo náš skript používa na skrytie 
elementu udalosť, keď používateľ dá s prvého elementu kurzor preč. Treba si uvedomiť, že pre tieto udalosti používa
javascritp DOM, nie reálne zobrazenie. Tým pádom aj ked je tooltip vizuálne umietnení mimo prvý element v štruktúre to 
tak nie je. Tým pádom by tooltip ostal zobrazený ked by nan použivatel prešiel kurzorom, čo nechceme.