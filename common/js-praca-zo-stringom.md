## String v JS

Textový reťazec môžeme definovať v `JS` nasledovne:

```javascript
let text1 = "Toto je text";
let text2 = 'Toto je text';
```
Pre každý zápis je použitý iný znak, ktorým anotujeme začiatok a koniec textového reťazca. Z pohľadu `JS` je úplne jedno, ktorú variantu zvolíme. To, ktorý znak použijeme závisí predovšetkým od kontextu. 

Pokiaľ chceme do premennej uložiť text, ktorý obsahuje ako hodnotu znak, ktorým sme anotovali začiatok stingu, musíme pred jeho použitím uviest __escape__ znak ``` \ ```. Takýto zápis by vyzeral nasledovne:

```javascript
let text1 = "Toto \"je\" text"; // Toto "je" text
let text2 = 'Toto \'je\' text'; // Toto 'je' text
```

Pokiaľ v textovom reťazci použijeme alternatívny znak pre anotáciu textového reťazca v kode nič sa nedeje a nemusíme použiť __escape__ znak:

```javascript
let text1 = "Toto 'je' text"; // Toto 'je' text
let text2 = 'Toto "je" text'; // Toto "je" text
```

Použiť sa to dá v prípade ak chceme do textového reťazca vpísať `HTML`. V tom prípade zvolíme za anotačný znak reťazca znak `'`, nakoľko `HTML` používa pre anotáciu hodnôt atribútov znak `"`. Kód bude potom vyzerať:

```javascript
// prehľadnejší zápis
let html = '<a class="red link" title="Späť na úvod">Späť</a>';

// a tu máme ten druhý...
let html2 = "<a class=\"red link\" title=\"Späť na úvod\">Späť</a>";
```

Obsah textového reťazca sa tak stáva pre prográmatora čiteľnejším, lebo nemusíme použiť __escape__ znak.

Ak chceme do textového reťazca vložiť hodnotu premennej, dá sa to realizovať nasledovne pomocou operátora `+`;

```javascript
let pocet = 5;
let html =  "Celkový počet je " + pocet + " kusov."; // Celkový počet je 5 kusov.
```

### Template literals

Pokiaľ ale chceme docieliť viac prehľadné vkladanie premenných alebo chceme zadať textový reťazec na viacej riadkov, musíme použiť anotáciu pomocou __template literals__ - znaku ``` ` ``` a bude vyzerať následne:

```javascript
let pocet = 5;
let html =  `Celkový počet je ${pocet} kusov.`; // Celkový počet je 5 kusov.
```

Z kódu je jasné, že premennú sme obalili do konštrukcie `${VYRAZ}`, do ktorej môžeme umiestniť buď premennú alebo výraz, ktorý vráti nejakú hodnotu:

```javascript
let html =  `Celkový počet je ${ 2 * (5 - 2)} kusov.`; // Celkový počet je 6 kusov.
```
 
Najčastejšie sa, ako aj samotný názov _template literals_, používa tento spôsob zápisu pre definovanie šablón. Môžeme uviesť krátku ukážku:

```javascript
function render(nadpis, obsah) {
    return `
        <div class="section">
            <h2>${nadpis}</h2>
            <p>${obsah}</p>
        </div>
    `;
}
```

Viac informácií o _template literals_ [nájdete tu](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Template_literals).