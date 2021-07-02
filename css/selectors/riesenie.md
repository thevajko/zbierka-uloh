<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/css/selektory), [Riešenie](/../../tree/solution/css/selektory).

# Selektory

</div>

## Riešenie

Pri vypracovaní riešenia budeme postupovať podľa jednotlivých bodov zadania.

### Rámček okolo tabuľky (bod 1)

> Tabuľka bude mať čierny rámček medzi bunkami a okolo celej tabuľky.

Pomocou CSS môžeme definovať orámovanie pomocou nasledovných pravidiel:

```css
.data {
    border: 1px solid black;
}

.data tr > * {
    padding: 2px;
    border: 1px solid black;
}
```

Tabuľka v HTML kóde mala definovanú `class="data"`, preto sme na naštýlovanie tabuľky použili selektor `.data`. Samotnej tabuľke sme nastavili `1px` vonkajšie orámovanie. Pomocou selektoru `.data tr > *` vyberáme jednotlivé bunky tabuľky. Vzhľadom na to, že bunky v tabuľke sú dvoch typov - `th` (*table head*) a `td` (*table data*) sme selektor napísali tak, že tento selektor vyberie každého priamého potomka (v selektore špecifikované pomocou `>`) elementu `tr`umiestneného v tabuľke. Tento selektor by sme ale mohli napísať rôznymi spôsobmi - napríklad plná varianta by mohla byť aj `table.data > tr > *`, kde tento selector vyberie priameho potomka elementu `tr`, ktorý sa nachádza ako priamy potomok elementu `table` s atribútom `class="data"`. Tento prípad by ale nemusel byť úplne všeobecný, pretože riadky tabuľky môžu byť ešte rozdelené do sekcií:

```html

<table>
    <caption>Popisok tabuľky</caption>
    <thead>
    <tr>
        <th>Hlavička 1</th>
        <th>Hlavička 2</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Telo 1</td>
        <td>Telo 2</td>
    </tr>
    </tbody>
    <tfoot>
    <tr>
        <td>Pätička 1</td>
        <td>Pätička 2</td>
    </tr>
    </tfoot>
</table>
```

Plne zapísaný selektor by takúto tabuľku nevedel naštýlovať. Ďalšou možnosťou by bolo rozdeliť tento selektor na dva, kde jednotlivé selektory sú oddelené čiarkou:

```css 
.data > tr > th, .data > tr > td {

}
```

Po aplikovaní tohto štýlu tabuľka nebude ale vyzerať tak, ako sme požadovali.

![](images_selectors/dvojite-oramovanie.png)

Ako môžeme vidieť na obrázku vyššie, tabuľka má dvojité okraje, ktoré sú oddelené medzerou. Tieto medzery su definované pomocou CSS vlastnosti `border-spacing`. Mohli by sme nastaviť medzeru medzi bunkami na `0px` ale tým pádom by sme mali šírku rámčeka `2px`. Ďalšou CSS vlastnosťou, ktorú môžeme použiť je vlastnosť `border-collapse`, pomocou ktorej vieme duplicitné orámovanie odstrániť automaticky. Pridáme preto do CSS pravidla pre tabuľku:

```css
.data {
    border-collapse: collapse;
}
```

### Formátovanie hlavičky (bod 2)

> Záhlavie tabuľky bude mať zelenú farbu pozadia, text bude tučným písmom a bude centrovaný, prvé písmeno bude mať žltú farbu.

Začneme nastavením farieb a písma. Vzhľadom na to, že hlavička má v HTML iný element ako riadky s dátami, môžeme použiť nasledovný CSS selektor:

```css
.data th {
    background-color: #16a085;
    font-weight: bold;
    color: white;
    padding: 5px;
}
```

Centrovanie textu môžeme vykonať pomocou `text-align: center;`, ale hlavička tabuľky (element `th`) má centrovanie nastavené automaticky, takže nemusíme pridávať žiadné špeciálne CSS vlastnosti.

Druhou časťou tejto úlohy je naštýlovanie prvého písmena. Na toto naštýlovanie môžeme využiť pseudoselektor `::first-letter`, ktorý umožní aplikovanie štýlu na prvé písmeno.

```css
.data th::first-letter {
    color: #f1c40f;
}
```

### Zafarbenie nepárnych riadkov  (bod 3)

> Riadky každý nepárny riadok tabuľky bude mať svetlosivé pozadie.

Pre vyriešenie tejto úlohy potrebujeme použiť selektor, ktorý vyberie len nepárne riadky. V CSS máme k dispozícii selektor `:nth-child`, pomocou ktorého môžeme vybrať n-tého potomka. Základná syntax je síce napr. `:nth-child(3)`, ktorý umožňuje vybrať 3. potomka. Okrem presného čísla môžeme použiť aj predpis na výber elementu. Na výber nepárneho riadku, môžeme použiť konkrétne selektor `:nth-child(2n+1)`. Výber párneho/nepárneho riadku je ale častým prípadom, preto v CSS je možné namiesto `2n+1` použiť výraz `odd` (z anglického nepárne, po prípade `even` pre párne riadky).

Výsledné pravidlo bude vyzerať nasledovne:

```css
.data tr:nth-child(odd) td {
    background-color: #ecf0f1;
}
```

Tento selektor vyberie každý element `td`, ktorý je v nepárnom riadku tabuľky s atribútom `class="data"`.

### Formátovanie stĺpca s priezviskom  (bod 4)

> Stĺpec s priezviskami bude napísaný veľkými písmenami.

Pre výber stĺpca s priezviskom môžeme opäť použiť selektor `nth-child`.

```css
/* Priezvisko uppercase */
.data tr td:nth-child(2) {
    text-transform: uppercase;
}
```

### Formátovanie riadkov na základe umiestnenia myšky (bod 5)

> Pri umiestnení myšky nad riadkom tabuľky

Pokiaľ chceme pomocou CSS meniť vlastnosti na základe pozície myšky môžeme použiť vlastnosť `:hover`, ktorá umožňuje definovať pravidlá pre elementy, nad ktorými sa nachádza myš.

#### Sivé pozadie celého riadku (bod 5.1)

Na vyriešenie tejto úlohy môžeme použiť pravidlo:

```css
.data tr:hover td {
    background-color: #bdc3c7;
}
```

Pomocou tohto pravidla nastavíme šedú farbu pozadia každej bunke v riadku, nad ktorým na nachádza kurzor myši (vlasnosť `:hover` sme aplikovali na element `tr`).

#### Formátovanie kolónky s menom (bod 5.2)

> Text v stĺpci `Meno` bude mať červenú farbu, ale iba pokiaľ nebude myš v bunke s menom. Ak bude myš v bunke s menom, text bude mať štandardnú čiernu farbu.

Pre vyriešenie tohto bodu zadania potrebujeme napísať selektor, ktorý vyberie prvý stĺpec v tabuľke v prípade, že myš sa nachádza na riadku, ale nie na stĺpci s menom. Selektor na výber riadka, na ktorom je myš, sme si ukázali v predchádzajúcej úlohe. Pokiaľ chceme v riadku vybrať prvý stĺpec, môžeme využiť selektor `:nth-child` s parametrom `1`. Môžeme využiť ale aj skrátenú verziu `:first-child`, ktorá robí presne to isté ako `:nth-child(1)`.

Tento problém sa dá vyriešiť aj dvoma pravidlami, prvé pravidlo nastaví farbu prvému stĺpcu v prípade, že bude kurzor myši nad daným riadkom. A druhé pravidlo prepíše farbu späť na čiernu, v prípade že myš bude nad konkrétnou bunkou. Pravidlá by mohli vyzerať nasledovne:

```css
.data tr:hover td:first-child {
    color: #e74c3c;
}

.data tr:hover td:first-child:hover {
    color: black;
}
```

Toto riešenie sa dá aj zjednodušiť. Okrem duplicity tohto riešenia je ešte aj problém s tým, že ak by sme zmenili farbu textu v tabuľke tak ju musíme zmeniť aj na tomto mieste, čo prináša ďalšiu duplicitu.

Pre zjednodušenie môžeme využiť selektor `:not()` ktorý umožnuje znegovať určitú časť selektoru. V našom prípade chceme vybrať prvú bunku v riadku kde je myš, ak myš nie je práve na tejto bunke.

```css
.data tr:hover td:first-child:not(:hover) {
    color: #e74c3c;
}
```

Ako môžeme vidieť na tejto ukážke, jednotlivé selektory sa dajú ľubovoľne kombinovovať.

#### Formátovanie číselných buniek (bod 5.3)

> Bunky v stĺpcoch `Číslo 1` až `Číslo 3` budú mať nasledovné správanie:
> 1. Vždy budú zarovnané na stred.
> 2. Ak na nich nebude myš, tak budú mať modré pozadie.
> 3. Ak bude myš na niektorom z nich, tak daná bunka bude mať zelené pozadie a bunka (bunky) s číslami za ním budú mať pozadie žlté. Pozor, bunke s odkazom nemeníme farbu pozadia.

Pre riešenie tohto problému vieme využiť už známe `:nth-child` selektory. Začneme prvou časťou - zarovnanie čísel na stred.

Pre každý stĺpec môžeme použiť vlastný selektor `:nth-child(4)` až `:nth-child(6)`. Druhou možnosťou je spojiť tieto selektory do jedného. Vieme na to použiť nasledovný trik: selektor `:nth-child(n+4)` zvolí všetkých potomkov, okrem prvých 3, tj. 4, 5, 6, ... Tým pádom vieme jednoducho vybrať všetky stĺpce okrem prvých troch. Druhým trikom, ktorý vieme použiť, je selektor `:nth-child(-n+6)`, ktorý naopak vyberie prvých 6 stĺpcov. Kombináciou týchto selektorov dostaneme pravidlo na zarovnenie textu na stred. Pri kombinácii sa vyberú len tie elementy, ktoré spĺňajú všetky podmienky, tj. spraví sa prienik, a nasledovný selektor zvolí stĺpce 4,5,6:

```css
.data tr td:nth-child(n+4):nth-child(-n+6) {
    text-align: center;
}
```

Pre ďalšie dve časti skombinujeme pravidlá, ktoré sme už definovali. Začneme modrým podfarbením čísel pri umiestnení myši nad daním riadkom:

```css
.data tr:hover td:nth-child(n+4):nth-child(-n+6) {
    background-color: #3498db;
}
```

V ďalšej časti úlohy bolo vyžadované zobrazenie zelenej farby bunky, keď nad ňou bude myš. Tu môžeme využiť podobný prístup ako v bode 5.2.:

```css
.data tr td:hover:nth-child(n+4):nth-child(-n+6) {
    background-color: #2ecc71;
}
```

Posledným problémom v tejto časti je zobrazenie žltého pozadia pre bunky, ktoré sú za myšou označenou bunkou. Pre tento účel môžeme použiť selektor `~`, ktorý umožní vybrať všetky elementy, ktoré nasledujú za určitým špecifikovaným elementom. V našom prípade chceme nájsť všetky `td` elementy, ktoré sa nachádzajú za bunkou, nad ktorou je myš. Prvú časť môžeme použiť z predchádzajúceho prípadu a doplníme za ňu výber nasledovníkov pomocou `~`:

```css
.data tr td:hover:nth-child(n+4) ~ td:nth-child(-n+6) {
    background-color: #f1c40f;
}
```

V tomto prípade sme prvú časť zjednodušili a podmienku, že ide len o prvých 6 stĺpcov sme presunuli do časti za `~`.

### Skrytie stĺpca s výsledkom (bod 6)

> V HTML je definovaný aj stĺpec `Výsledok`, ten vo výslednej tabuľke nezobrazte.

Na túto úlohu môžeme využiť selektor `nth-last-child`, pomocou ktorého zvolíme a skryjeme daný stĺpec. Správaním je veľmi podobný ako už spomínaný selektor `nth-child`, ibaže tento selektor počíta prvky od konca:

```css
.data tr > *:nth-last-child(2) {
    display: none;
}
```

V tomto príklade si môžete všimnúť, že opäť používame `*`. Je to kvôli tomu, že okrem dát uložených v elementoch `td`, potrebujeme skryť aj hlavičku uloženú v `th`.

### Formátovanie odkazov (bod 7) 

> Odkazy v stĺpci `Link` sa budú správať nasledovne:
> 1. Ak bude odkaz zabezpečený (protokol HTTPS) zobrazte ho zelenou farbou.
> 2. Ak bude odkaz nezabezpečený (protokol HTTP) zobrazte ho červenou farbou.
> 3. Ak to bude relatívny odkaz, zobrazte ho modrou farbou.
> 4. Ak to bude odkaz na súbor typu PDF (odkaz končí `.pdf`) dopíšte za text odkazu, že ide o PDF - `(PDF)`.

Pre riešenie tejto úlohy potrebujeme využiť atribútové selektory. Takýto selektor môže vyzerať nasledovne: `element[atribut="hodnota"]`. Začneme postupne, v prvej časti potrebujeme nájsť odkazy, ktoré smerujú na zabezpečené stránky, to znamená, že ich URL adresa začína `https:`. Použijeme preto nasledovný selektor:

```css
.data td a[href^="https:"] {
    color: green;
}
```

V tomto príklade sme použili atribút `href`, ktorého hodnota musí začínať (modifikátor `^=`) `https:`. Rovnaké pravidlo vieme definovať aj pre HTTP odkazy.

```css
.data td a[href^="http:"] {
    color: red;
}
```

Pre relatívne odkazy môžeme pre jednoduchosť použiť pravidlo, ktoré pomocou negácie `not()` vyberie všetky linky, ktoré nezačínajú `http`.

```css
.data td a:not([href^="http"]) {
    color: blue;
}
```

Poslednou časťou tejto úlohy bolo doplnenie slova `(PDF)` k odkazom, ktoré smerujú na `.pdf` súbor. Toto môžeme dosiahnuť kombináciou podobného selektora ako v predchádzajúcom prípade a pseudoelementu `::after`. Na rozdiel od predchádzajúceho prípadu nepoužijeme `^=`, ktoré kontroluje začiatok hodnoty, ale použijeme `$=`, ktoré kontroluje koniec hodnoty atribútu.

```css
.data td a[href$=".pdf"]::after {
    content: "(PDF)";
}
```

Tento selektor pridá k odkazu, ktorý končí na `.pdf` pseudoelement, ktorý bude mať obsah `(PDF)`.

### Ukotvenie hlavičky (bod 8)

> Zabezpečte, aby pri veľkej tabuľke zostávala hlavička vždy viditeľná.

Často sa stáva, že na stránke je veľa dát, a keď je tabuľka veľmi veľká a je potrebné použiť posuvník, tak v strede tabuľky už nevieme identifikovať stĺpce. Pomocou CSS vieme ukotviť hlavičku tak, že použijeme `position: sticky`:

```css
.data th {
    position: sticky;
    top: -1px;
}
```

Pre lepší vzhľad výsledku sme ešte nastavili aj pozíciu `top: -1px;`, aby hore neostával biely pásik. Tým je celý príklad hotový.