<div class="hidden">

> > ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/js-a-css/jstable), [Riešenie](/../../tree/solution/js-a-css/jstable).
</div>

# JS Table (JS, CSS)

Vytvorte skript, ktorý dokáže kolekciu ľubovolných JS objektov zobraziť v HTML tabuľke a umožní v nej:
1. Ako vstup predpokladajte pole objektov, ktorých atribúty budú konštantne. Objekty si môžete nagenerovať napr. pomocou tohto [JSON generátora](https://www.json-generator.com/). Prípadne ako zdroj použite polia definované v skripte [`users-data.js`](users-data.js) a  [`products-data.js`](products-data.js).
1. zoraďovať daného stĺpca vzostupe a zostupne
2. v hlavičke tabuľky zobrazte názvy atribútov objektov v kolekcii
3. filtrovať riadky na základe danej hodnoty; zobrazí iba tie riadky kde v ľubovolnom stĺpci nájde zhodu z hľadaným výrazom. 
4. Skript navrhnite tak aby na jednej stránke dal použiť opakovane, teda mohli sa zobraziť viaceré samostatne reagujúce tabuľky.

Logiku vytvorte v čistom JS.

# Riešenie

Vzhľadom na zadanie riešenia bude najlepšie riešenie vytvoriť JS triedu `JsTable`, ktorej každá inštancia bude spravovať samostatne svoju kolekciu dát. Nutné vstupné parametre konštruktora tejto triedy budú:

1. Kolekcia dát s homogénnou štruktúrov
2. Kontajnerový element, kde sa ma tabuľka zobraziť

Návrh triedy bude vyzerať:

```javascript
class JsTable {
    constructor(dataCollection, HTMLElement) {
        this.dataCollection = dataCollection;
        this.HTMLElement = HTMLElement;
    }
}
```

Teraz implementujeme logiku, ktoré budú zhotovovať samotnú tabuľku. Do triedy `JsTable` pridáme nasledovné metódy:

1. `renderTable` - skompletizuje jednotlivé časti tabuľky
2. `renderHeader` - vytvorí hlavičku tabuľky
3. `renderRows` - vytvorí telo tabuľky

V `JS` existujú dva spôsoby akým je možné dynamicky vytvárať nové _HTML elementy_:

1. Pomocou stringu v ktorom sa priamo napíše HTML kód tak akoby sme ho písali do samotného HTML kódu stránky.
2. Pomocou metódy `document.createElement()`.

Rozdiel je samozrejme v efektívnosti. Prí spôsob je samozrejme menej efektívny nakoľko prehliadač musí string najskôr rozparsrovať a následne povytvárať jednotlivé elementy. Taktiež nedostaneme priamo referencie na jednotlivé vytvorené elementy a sme nútený ich dopytovať.

V druhom prípade toto parsovanie a získavanie referencií odpadá ale na druhú stranu nám vzniká "ukecanejší" kód, nakoľko musíme každú inštanciu elementu inicializovať (nastaviť im ručne požadované hodnoty parametrov). 

Pri riešení našej úlohy však budeme používať oba. Pre telo tabuľky použijeme vytvaranie riadkov pomocou stringu a hlavičku pomocou priameho vytvárania elementov, nakoľko bude obsahovať logiku pre zoraďovanie.

Aby sme si otestovali dynamické vytváranie elementov doplníme triedu `JsTable` nasledovne:

```javascript
class JsTable {
    constructor(dataCollection, HTMLElement) {
        this.dataCollection = dataCollection;
        this.HTMLElement = HTMLElement;

        this.renderTable();
    }
    renderTable(){
        let header = this.renderHeader();
        let body = this.renderRows();
        let table = `<table border="1">${header}${body}</table>`;
        this.HTMLElement.innerHTML = table;
    }
    renderHeader(){
        return `<tr><td>header</td></tr>`
    }
    renderRows(){
        return `<tr><th>rows</th></tr>`
    }
}
```
Ako kolekciu dát použijeme pole definované v súbore `users-data.js`, ktoré sa uloží do globálnej premennej `usersData`.

Pri `<table>` je pridané nastavenie atribútu `border="1"` aby bol viditeľný okraj tabuľky. V HTML vytvoríme logiku, ktorá ked sa spustí, tak vytvorí novú inštanciu triedy `JsTable` a doplní správne parametre:  
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <script type="application/javascript" src="products-data.js"></script>
    <script type="application/javascript" src="users-data.js"></script>
    <script type="application/javascript" src="jstable.js"></script>
    <script>
        window.onload = function () {
            let table = new JsTable(usersData, document.getElementById("table01"));
        }
    </script>
</head>
<body>
    <div id="table01"></div>
</body>
</html>
```

Výsledok by sa mal zobraziť takto:

![](.zadanie_images/table-01.png)

## Jednoduché zobrazenie

V hlavičke majú byť zobrazené názvy atribútov objektov v kolekcií. `JS` Umožňuje získať zoznam názvov atribútov ľubovoľnej inštancie zavolaním `Object.keys()` ([demonštrácia tu](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Object/keys)).  Nakoľko predpokladáme, že kolekcia dát obsahuje rovnaké objekty, pre získanie atribútov stačí vybrať prvý objekt:

```javascript
let firstItem = this.dataCollection[0]; 
let atributes = Object.keys(firstItem);    
```

Návratová hodnota `Object.keys()` je pole, ktoré obsahuje názvy atribútov v stringu. Tie potrebujeme dostať do poroby hlavičky tabuľky. Hľavička tabuľky je riadok `<tr>`, ktorý obsahuje jednotlivé hlavičky riadkov v elementoch `<th>`. 
Pre iteráciu všetkých získaných názvov atribútov použijeme metódu pola [`Array.prototype.forEach()`](https://www.w3schools.com/jsref/jsref_foreach.asp).

Pre dynamické vytvorenie elementov hlavičky použijeme vytváranie elementov pomocou stringu. Pre definovanie stringov použijeme [template Literals](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Template_literals) Metódu `JsTable.renderHeader()` upravíme následovne:

```javascript
renderHeader() { 
    let firstItem = this.dataCollection[0];
    let headerText = "";
    Object.keys(firstItem).forEach((attributeName,i) => {
        headerText += `<th>${attributeName}</th>`
    });
    return `<tr>${headerText}</tr>`
}
```
Pokiaľ teraz spustíme skript tabuľka sa zobrazí doplnená o názvy atribútov v hlavičke tabuľky. Výsledná tabuľka sa zobrazí nasledovne:

![](.zadanie_images/table-02.png)

Pri generovaní obsahu v metóde `JsTable.renderRows()` iba rozšírime logiku, ktorú sme vložili do metódy `JsTable.renderHeader()`. Generovanie kódu pre jeden riadok je rovnaký ako pri hlavičke s tým rozdielom, že hodnota sa umiestni namiesto do elementu `<th>` do `<td>`.

Pre každú položku v kolekcií budeme vytvárať samostatný riadok.

Ako posledné potrebujeme získať hodnoty z každého objektu v kolekcií v takom poradí v akom sú popísané v hlavičke. V `JS` môžeme pristúpiť k hodnote atribútom objektu cez index. V nasledovnom kóde sú uvedené dve možnosti prístupu k hodnote atribútu:

```javascript
class Trieda {
    atrb = "hodnota";
}

let obj = new Trieda();

obj.atrb; // hodnota
obj["atrb"]; // hodnota
```

Postupnosť krokov môžeme zapísať nasledovne:
1. Inicializujeme si premennú `bodyText` do ktorej budeme priebežne pridávať kód jednotlivých riadkov.
2. do premennej `keys` priradíme pole s názvami atribútov objektov v kolekcií.
3. Následne budeme prechádzať kolekciu dát kde
    1. Inicializujeme premennú `rowText`
    2. Budeme prechádzať pole `keys`, kde pre každú položku:
        1. do premennej  `rowText` pridáme string s HTML pre element `<td>` s hodnotou daného atribútu
    3. do premennej `bodyText` pridáme hodnotu z `rowText`,ktorú obalíme tagom riadu `<tr>`.
4. vrátime obsah premennej `bodyText`

Výsledný kód metódy `JsTable.renderRows()` bude:

```javascript
renderRows() {
    let bodyText = "";
    let keys = Object.keys(this.dataCollection[0]);
    this.dataCollection.forEach( (item, i) => {
        let rowText = "";
        keys.forEach((attributeName,i) => {
            rowText += `<td>${item[attributeName]}</td>`
        });
        bodyText += `<tr>${rowText}</tr>`
    } )
    return bodyText;
}
```

Tabuľka teraz vypíše celú kolekciu nasledovne: 

![](.zadanie_images/table-03.png)

## Zoraďovania podla stĺpca



