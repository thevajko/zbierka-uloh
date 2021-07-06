<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/css/dropdownmenu), [Riešenie](/../../tree/solution/css/dropdownmenu).
> - [Zobraziť riešenie](riesenie.md)

# DropDown a DropUp menu - CSS

</div>

## Riešenie

<div class="hidden">

Riešenie je rozdelené do nasledovných podkapitol

(prekliky nemusia fungovať pre lokálny MD interpreter):

1. [Prvá úroveň menu](#prvá-úroveň-menu)
2. [Druhá úroveň](#druhá-úroveň)
3. [Ďalšie úrovne](#ďalšie-úrovne)
4. [Zobrazenia ikonky o prítomnosti sub-menu](#zobrazenia-ikonky-o-prítomnosti-sub-menu)
5. [Doplnenie zvýraznenia výberu](doplnenie-zvýraznenia-výberu)
6. [Záverečné formátovanie](#záverečné-formátovanie)
7. [Upravenie na Drop-up menu](#upravenie-na-drop-up-menu)

</div>

### Prvá úroveň menu

Prvý krok spočíva v skrytí všetkých vnorených elementov `ul` okrem prvej úrovne. Na skrytie všetkých vnorených `ul` elementov použijeme potomkový selektor: `ul ul`. CSS pravidlo bude vyzerať nasledovne:

```css
ul ul {
    display: none;
}
```

Teraz potrebujeme upraviť zobrazenie prvej úrovne tak, aby sa nezobrazovala ako zoznam ale ako menu, teda vedľa seba. To, ako sa ktorý prvok zobrazuje, definuje CSS vlastnosť [`display`](https://www.w3schools.com/cssref/pr_class_display.asp)).

Zoznam sa dá v HTML definovať dvomi značkami `ul` (neočíslovaný zoznam) a `ol` (očíslovaný zoznam). V oboch prípadoch ide o obaľovací komponent, ktorého potomkom môžu byť jedine element `li`. Značka `li` sa zobrazuje ako bloková (má nastavenú hodnotu pre zobrazenie na `display: list-item`), preto sa jednotlivé položky zoznamu zobrazujú pod sebou. Toto zobrazenie je potrebné zmeniť, aby sme ich zobrazili vedľa seba.

Začiatočníckou chybou je zmena hodnoty `display` na `display: inline-block`. Aj keď sa položky zobrazia vedľa seba, vytvára sa medzi nimi prirodzene nežiadúca medzera. Ale prečo? Je to dôsledok toho, akým spôsobom má prehliadač zobrazovať riadkové (*inline*) elementy. Vieme, že prehliadač ignoruje viacnásobné medzery a zalomenia. V tomto prípade, vzhľadom na štruktúru sú medzi jednotlivými elementmi `li` znaky ako zalomenia, medzery a tabulátory interpretované ako medzery.

Aby sme to názorne predviedli, stačí si niekde do kódu stránky vložiť nasledovný HTML kód <span class="hidden"> (alebo otvoriť [fiddle](https://jsfiddle.net/meshosk/Legh36td)) </span>:

```html

<div>
    <span>jeden</span>
    <span>dva</span>
    <span>tri</span>
    <span>styri</span>
    text
    text
    text
</div>
```

Výsledok:

```html
jeden dva tri styri text text text
```

Výsledok tejto štruktúry bude postupnosť jednotlivých textov v riadku oddelených v medzerami. Pokiaľ chceme medzeru odstrániť musíme jednotlivé elementy dať ihneď za sebou <span class="hidden">(otvoriť [fiddle](https://jsfiddle.net/meshosk/p2atzwkd ))</span>:

```html

<div>
    <span>jeden</span><span>dva</span><span>tri</span><span>styri</span>
    text
    text
    text
</div>
```

Výsledok:

```html
jedendvatristyri text text text
```

V našom prípade chceme zachovať pôvodnú HTML štruktúru a nechceme dopĺňať ďalšie elementy, preto zvolíme zobrazenie pomocou [*flexbox*](https://css-tricks.com/snippets/css/a-guide-to-flexbox).

*Flexbox* potrebuje na svoje fungovanie obaľovací element, tzv. *kontainer*, (v našom prípade `ul`) a položky, ktoré sa v ňom majú zobraziť (u nás `li`). Ak chceme aplikovať flexbox na náš príklad s elementom `span`, jeho kód bude vyzerať nasledovne<span class="hidden">(otvoriť [fiddle](https://jsfiddle.net/meshosk/a7Lzsnqh ))</span>:

```html

<html>
<head>
    <style>
        div {
            display: flex;
        }

        span {
            border: 1px solid black;
        }
    </style>
</head>

<body>
<div>
    <span>jeden</span>
    <span>dva</span>
    <span>tri</span>
    <span>styri</span>
    text
    text
    text
</div>
</body>
</html>
```

Ak tento postup aplikujeme na našu úlohu, musíme najprv identifikovať *kontajner* pre [*flexbox*](https://css-tricks.com/snippets/css/a-guide-to-flexbox/). V našom prípade ide o iba prvú úroveň nášho menu. *Kontajner* preto budeme definovať selektorom `#menu > ul`, teda vyberieme element `div` s hodnotou atribútu `id="menu"`, ktorý tvorí hlavný element nášho menu. CSS bude teda vyzerať nasledovne:

```css
#menu > ul {
    display: flex;
}
```

Teraz musíme doplniť zobrazenie zoznamu tak, aby vizuálne pripomínalo menu, čím napovieme používateľovi, aby daný komponent ako menu aj používal (*nie je nič horšie pre používateľa ako neintuitívne GUI*).

Ako prvé zmeníme farbu pozadia menu, budeme formátovať element `div` s `id="menu"`.

Značky `ul` a `li` by mali definovať iba štruktúru menu. Definujeme preto farbu pozadia a odsadenie iba pre `span` tak, aby bolo ľahké pre používateľa určiť, ktorý text predstavuje, ktorú položku menu.

Jedinú výnimku bude tvoriť formátovanie elementu `ul` druhej a ďalšej úrovne, ktorým neskôr pridáme formátovanie v podobe rámčeka a pozadia. Samozrejme, problému sa dá predísť vytvorením obaľovacieho elementu pre ďalšie úrovne. V našom prípade sme ale chceli mať v príklade čo najjednoduchšiu štruktúru.

Pre odstránenie problémov s odsadeniami môžeme v našom prípade urobiť tzv. *globálny reset odsadení* v CSS. Ten používa selektor `*` a ako vlastnosti mu nastavíme vnútorné a vonkajšie odsadenie na hodnotu `0`. Selektor `*` sa následne použije ako hodnota pre všetky štýlovania. Dôsledok je taký, že teraz musíme definovať odsadenia iba tam, kde ich skutočne chceme.

Následne ešte musíme upraviť zobrazenie elementu `li`, tak aby sa nezobrazovali ako položky menu a elementy `span`, aby sa zobrazovali ako blokové značky (inak im nebude možné zadefinovať rozmer a odsadenie).

Vzhľadom na to, že výsledkom úlohy je menu, bude dobrý nápad zamedziť automatické zalamovanie textu v `span`. To urobíme tak, že `span` doplníme CSS vlastnosť `white-space: nowrap;`.

CSS bude teda nasledovné:

```css
* {
    margin: 0;
    padding: 0;
}

#menu {
    background-color: gray;
    padding: 2px;
}

span {
    background-color: aqua;
    display: block;
    padding: 4px 10px;
    margin: 2px;
    white-space: nowrap;
}

li {
    display: block;
}
```

Menu bude vyzerať:

![](images_dropdownmenu/menu-prva-uroven.png)

### Druhá úroveň

Nasleduje vytvorenie štýlu pre druhú úroveň. Pre lepšie ladenie CSS si musíme najprv zobraziť prvú a druhú úroveň. To docielime tým, že upravíme obsah selektoru `ul ul` a doplníme skrytie všetkých elementov `ul` úrovne tri a viac, teda selektorom `ul ul ul`. Upravené CSS bude vyzerať (zobrazené sú iba doplnené a zmenené CSS):

```css
li {
    position: relative;
    display: block;
}

ul ul {
    display: block;
}

ul ul ul {
    display: none;
}
```

Menu bude zobrazovať staticky prvú a druhú úroveň takto:

![](images_dropdownmenu/menu-dva-01.png)

Teraz potrebujeme upraviť CSS vlastnosť `position` pre všetky `li` prvej úrovne na `relative`, aby sme vytvorili základnú plochu pre prípadne `ul` ďalších úrovní.

Všetkým `ul` druhej a ďalších úrovní nastavíme vlastnosť `position` na `absolute`. Tým docielime to, že `ul` sa zobrazia "plávajúco" nad ostatnými elementmi<span class="hidden">(viac o [position tu](../../common/css-position.md) )</span>. Upravené CSS pravidlá sú nasledovné:

```css
li {
    position: relative;
    display: block;
}

ul ul {
    display: block;
    position: absolute;
}

ul ul ul {
    display: none;
}
```

Zobrazenie stránky v tomto kroku bude nasledovné:

![](images_dropdownmenu/menu-dva-02.png)

Ako prvé teraz doplníme zobrazovanie a skrývanie druhej úrovne, pokiaľ používateľ umiestni kurzor nad danú položku `li`, ktorá obsahuje priamo podmenu. Zvolenie priameho potomka je v selektore dôležité, lebo chceme, aby sa zobrazil iba priamy potomok a nie všetky `ul` v danej vetve DOM. Môžeme ešte pridať formátovanie pre `ul` úrovne dva a viac.

Pre zobrazenie opäť použijeme _flexbox_ a upravíme zobrazenie prvkov na vertikálne pomocou `flex-direction: column;`. Predvolene sú podmenu skryté. CSS bude teda nasledovné:

```css
ul ul {
    position: absolute;
    display: none;
    border: 1px solid black;
    background-color: burlywood;
}

li:hover > ul {
    display: flex;
    flex-direction: column;
}
```

Ako je vidieť na nasledujúcom obrázku, menu bude fungovať, ako má, ale iba po druhú úroveň.

![](images_dropdownmenu/menu-fung-01.gif)

### Ďalšie úrovne

Aby sa nám správne zobrazili menu druhej úrovne je potrebné upraviť ich spôsob určovania ich pozície. Nasledujúce podmenu sa má zobraziť výškovo zarovno položkou napravo od nej. To docielime nasledovným CSS pravidlom:

```css
ul ul ul {
    top: 0;
    left: 100%
}
```

`top: 0` určuje, že sa má podmenu zobraziť vertikálne zarovno s elementom `li`, v ktorom je. `left: 100%` umiestňuje podmenu o `100%` veľkosti `li` zľava. Výsledok pridania tohto pravidla je nasledovný:

![](images_dropdownmenu/menu-fung-02.gif)

Všimnime si však, že jednotlivé podmenu nie sú úplne zarovnané. To je dôsledok toho, že sme pri `ul` druhej úrovne pridali rámček a ďalšie vnorené menu sa zobrazí až v tomto rámčeku.

![](images_dropdownmenu/menu-dva-03.png)

Aby sa menu zobrazovalo korektne, musíme vnorený element posunúť o veľkosť rámčeka. V našom prípade stačí upraviť umiestnenie menu aspoň tretej úrovne tak, že ho negatívne posunieme hore o šírku rámčeka.

```css
ul ul ul {
    top: -1px;
    left: 100%
}
```

### Zobrazenie ikonky o prítomnosti sub-menu

Pre zlepšenie používateľského komfortu je veľmi vhodné použivateľovi najako naznačiť, že nejaká položka menu obsahuje dodatočné podmenu. Najčastejšie sa to realizuje indikátorom, napr. znakom `»`. 

Pre doplnenie tohto indikátora existuje viacero spôsobov:

- Doplnenie elementu, ktorý daný znak doplní 
- Namiesto pridania elementu, stačí vytvoriť CSS pravidlo a následne túto triedu doplniť do atribútu `class` daného elementu
- Ak máme štruktúru pevne danú, môžeme priamo zadefinovať CSS selektorom doplnenie tohto znaku

V našom prípade sa pokúsime o aplikovanie poslednej možnosti. Použitím selektora `ul ul span:not(:only-child):after`, ktorý môžeme popísať nasledovne:

- `ul ul span` - sa aplikuje na všetky `span` od druhej úrovne
- `:not(:only-child)` - je [**pseudo-trieda**](https://developer.mozilla.org/en-US/docs/Web/CSS/Pseudo-classes) `:not`   urobí nad získanými `span` filter a vyberie iba tie, ktoré nie sú jedináčik. Teda, majú vedľa seba nejaké súrodenecké   elementy. V našom prípade ide výlučne o položky menu, ktoré obsahujú podmenu.
- `:after` - je [**pseudo-element**](https://developer.mozilla.org/en-US/docs/Web/CSS/Pseudo-elements), pomocou ktorého   vieme definovať nejaký obsah, ktorý sa zobrazí ihneď za elementmi, ktoré sú vybrané selektorom.

CSS pravidlo, ktoré nám pridá indikátor je nasledovné:

```css
ul ul span:not(:only-child)::after {
    color: blue;
    content: "»";
    padding: 3px 3px 3px 10px;
}
```

Výsledok funguje takto:

![](images_dropdownmenu/menu-fung-03.gif)

### Doplnenie zvýraznenia výberu

Ďalšia vec, ktorá spríjemní komfort používateľa je vyznačenie prvkov, ktoré boli inicializované výberom. To realizujeme nasledovným CSS pravidlom:

```css
li:hover > span {
    color: red;
    background-color: yellow;
}
```

Zmenu aplikujeme na `span`, ktorý je priamym potomkom `li`, nad ktorým je aktuálne kurzor myši. Je potrebné si pamätať, že táto indikácia vyplýva z DOM štruktúry a nie z toho, ako sú prvky reálne vykreslené v okne prehliadača.

Výsledok funguje nasledovne:

![](images_dropdownmenu/menu-fung-04.gif)

### Záverečné formátovanie

Nasledovné úpravy ešte zlepšia dizajn celého riešenia:

1. Odstránime farbu pozadia ponecháme iba `span` prvej úrovne.
2. Nastavíme jednotnú farbu pozadia pre `ul` druhej a vyššej úrovne.
3. Každú úroveň jemne farebne odlíšime.

CSS štýly pre bod 1. a 2. budú vyzerať nasledovne:

```css
span {
    background-color: transparent
}

#menu > ul > li:hover > span {
    color: red;
    background-color: yellow;
}

#menu > ul > li > span {
    background-color: #ebebeb;
}

ul ul {
    background-color: #ebebeb;
}
```

A ako posledné doplníme postupne sa stmavujúcu farbu pozadia pre vnorené elementy `ul`:

```css
ul ul {
    background-color: #ebebeb;
}

ul ul ul {
    background-color: #bdbdbd;
}

ul ul ul ul {
    background-color: #949494;
}
```

Finálny výsledok vyzerá nasledovne:

![](images_dropdownmenu/menu-fung-00.gif)

### Upravenie na Drop-up menu

Zmena voči pôvodnému menu spočíva čisto iba v úprave toho, kde a ako sa majú jednotlivé elementy zobraziť. Začneme teda presunutím celého menu na spodok okna prehliadača. To budeme realizovať zmenou hodnoty CSS vlastnosti `position` na hodnotu `fixed`. Tým docielime to, že menu sa bude umiestňovať nad všetky vykreslené prvky v priestore okna prehliadača a ten bude tvoriť aj jeho predka pre výpočet veľkostí.

Aby bolo menu roztiahnuté na celú dĺžku okna, musíme mu zadefinovať vlastnosť `width: 100%`. Upravený štýl zmeníme na:

```css
#menu {
    background-color: gray;
    padding: 2px;
    position: fixed;
    bottom: 0;
    width: 100%;
}
```

Následne musíme upraviť zobrazenie druhej úrovne tak, aby bola zarovnaná vľavo nad predka `li` a aby sa nezobrazovala pod ním, ale nad ním. Dosiahneme to pridaním vlastnosti `bottom: 100%;`, čím spodok podmenu presunieme na vrch nadradeného `li`. CSS vlastnosť upravíme nasledovne:

```css
li:hover > ul {
    display: flex;
    flex-direction: column;
    bottom: 100%;
}
```

Mali by sme dostať nasledovné zobrazenie:

![](images_dropdownmenu/menu-up-01.gif)

Podobne upravíme pozíciu pre menu druhej a ďalšej úrovne. Musíme však zmeniť selektor `ul ul ul` na `ul li:hover > ul ul`, aby sme predišli nutnosti použiť pravidlo `!important` (zvýšenie priority označenej vlastnosti).

```css
ul li:hover > ul ul {
    bottom: 0px;
    left: 100%;
}
```

Následne môžeme upraviť štýl `ul li:hover > ul ul` nasledovne:

```css
ul li:hover > ul ul {
    top: auto;
    bottom: -1px;
    left: 100%;
    margin-left: 0px;
}
```

To je všetko, čo sme potrebovali zmeniť, aby sme drop-down menu prerobili na drop-up menu. Malo by fungovať nasledovne:

![](images_dropdownmenu/menu-up-02.gif)

<div class="solution">

Kompletné zdrojové kódy hotového riešenia môžete nájsť na tejto URL adrese:

[https://github.com/thevajko/zbierka-uloh/tree/solution/css/dropdownmenu](https://github.com/thevajko/zbierka-uloh/tree/solution/css/dropdownmenu)

![URL adresa hotového riešenia](images_dropdownmenu/qr-dropdownmenu.png)
</div>

