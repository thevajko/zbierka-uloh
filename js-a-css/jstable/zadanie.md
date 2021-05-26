> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/js-a-css/jstable), [Riešenie](/../../tree/solution/js-a-css/jstable).

# JS Table - JS a CSS [branch solution]

Vytvorte skript, ktorý dokáže kolekciu ľubovolných JS objektov zobraziť v HTML tabuľke a umožní v nej:
1. Ako vstup predpokladajte pole objektov, ktorých atribúty budú konštantne. Objekty si môžete nagenerovať napr. pomocou tohto [JSON generátora](https://www.json-generator.com/). Prípadne ako zdroj použite polia definované v skripte [`users-data.js`](users-data.js) a  [`products-data.js`](products-data.js).
1. zoraďovať daného stĺpca vzostupe a zostupne
2. filtrovať riadky na základe danej hodnoty; zobrazí iba tie riadky kde v ľubovolnom stĺpci nájde zhodu z hľadaným výrazom. 
3. Skript navrhnite tak aby na jednej stránke dal použiť opakovane, teda mohli sa zobraziť viaceré samostatne reagujúce tabuľky.

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

V druhom prípade toto parsovanie a získavanie referencií odpadá ale na druhú stranu nám vzniká "ukecanejší" kód, nakoľko musíme každú inštanciu elementu inicialiovať (nastaviť im ručne požadované hodnoty parametrov). 

Pri riešení našej úlohy však budeme používať oba. Pre telo tabuľky  použijeme vytvaranie riadkov pomocou stringu a hlavičku pomocou priameho vytvárania elementov, nakoľko bude obsahovať logiku pre zoradovanie.

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
        let table = `<table>${header}${body}</table>`;
        this.HTMLElement.innerHTML = table;
    }
    renderHeader(){
        return `<tr><td>body</td></tr>`
    }
    renderRows(){
        return `<tr><th>header</th></tr>`
    }
}
```

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <script type="application/javascript" src="products-data.js"></script>
    <script type="application/javascript" src="users-data.js"></script>
    <script type="application/javascript" src="jstable.js"></script>
</head>
<body>
    <div id="table01"></div>
    <script>
        var table = new JsTable(usersData, document.getElementById("table01"));
    </script>
</body>
</html>
```
