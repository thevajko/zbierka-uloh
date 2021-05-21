> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/css/dropdownmenu), [Riešenie](/../../tree/solution/css/dropdownmenu).

# DropDown menu - CSS [branch solution]

Cieľom úlohy je vytvoriť roletové menu aké obsahujú bežne desktopové aplikácie. Ako má menu fungovať 
demonštruje nasledovný gif:

![](.riesenie_images/menu-fungovanie.gif)

Počiatočný HTML dokument obsahuje toto menu zadefinované pomocou štruktúry `<ul>` elementov a vyzerá 
nasledovne:
```html
 <div id="menu">
        <ul>
            <li>
                <span>Súbor</span>
                <ul>
                    <li>
                        <span>Vytvoriť nový</span>
                        <ul>
                            <li><span>PDF</span></li>
                            <li><span>PPT</span></li>
                            <li><span>TXT</span></li>
                            <li><span>HTML</span></li>
                        </ul>
                    </li>
                    <li><span>Uložiť</span></li>
                    <li>
                        <span>Exportovať</span>
                        <ul>
                            <li>
                                <span>Web</span>
    ...
```
Všimnite si však, že samotné `<ul>` a `<li>` definujú _iba_ štruktúru a
položky samotné sú definované ako `<span>`. Vnorenie jednotlivých `<ul>` v `<li>` teda 
definuje ktorý `<ul>` je sub-menu ktorého menu.

Pre riešenie použite výlučne iba CSS.

# Riešenie

Prvý krok spočíva v skrytí všetkých vnorených `<ul>`, teda okrem prvej úrovne. Selektor, ktorým 
skryjeme všetky vnorené `<ul>` bude `ul ul`. Celkovo CSS bude nasledovné:

```css
ul ul {
    display: none;
}
```
Teraz potrebujeme upraviť zobrazenie prvej úrovne, tak aby sa nezobrazovala ako zoznama ale ako menu,
teda vedľa seba. To ako sa ktorý prvok zobrazuje definuje CSS vlastnosť `display` 
([viac tu](https://www.w3schools.com/cssref/pr_class_display.asp)). 

Zoznam sa dá v `HTML` definovať dvomi značkami `<ul>` (neočíslovaný zoznam) a `<ol>` (očíslovaný zoznam). 
V oboch prípadoch sa jedná o obalovací komponent, ktorého potomkami môžu byť jedine element `<li>`.
V oboch prípadoch je obalovací element predvolene zobrazovaný ako blokova značna, má nastavenú
hodnotu pre zobrazenie na `display: block`. Toto meniť nebudeme.

Zmeniť ale musíme zobrazenie jednotlivých `<li>`, ktoré majú nastavenú túto hodnotu ako: `display: list-item`.
Aby sme dostali požadované chovanie, musíme ju zmeniť na riadkové zobrazenie. Upravíme preto 
túto hodnotu na `display: inline-block`, to preto aby sme mohli následne zadefinovať prípadný rozmer. 
CSS bude teda nasledovné:

```css
ul ul {
    display: none;
}

li {
    display: inline-block;
}
```

Teraz upravíme zobrazenie zoznamu tak, aby vizuálne pripomínalo menu, čím napovieme používateľovi
aby daný komponent ako menu aj naozaj používal.

Musíme preto zmeniť pozadie menu, nepoužijeme na to ale prvý element `<ul>` ale priamo `<div id=menu>`.
Dôvod je taký, že značky `<ul>` a `<li>` by mali definovať iba štruktúru menu. Definujeme preto aj farbu pozadia
a odsadenie mezdi `<li>` tak aby bolo ľahké pre používateľa určiť, ktorý text predstavuje ktorú položku menu.

Pre odstránenie problemov s odsadeniami možeme v našom prípade urobiť tzv. _globalny reset odsední_ v `CSS`. Pri ňom 
použijeme selektor `*` a vo vlastnostiach nastavime vnútorné a vonkajšie odsadenie na hodnotu `0`. Selektor `*` sa následne
použije ako hodnotota pre všetky štýlovania. Tým pádom musíme doplniť odsadenie iba tam, kde ho reálne potrebujeme.

Bude dobrý nápad, ako zamedzíme automatickému zalamovaniu textu v `<span>` elementoch, nakoľko toto chovanie nie je moc
vhodné pre roletové menu. To urobíme tak, že `<span>` doplníme `CSS` vlastnosť `white-space: nowrap;`. Aby položka menu,
vždy vypĺňala predka (toto chceme kôly existencí ďaľšík sub-menu) a mohli sme doplnit odsadenia, upravíme jej zobrazenie
z riadkovej na blokovú.

CSS bude teda:
```css
* {
    padding: 0;
    margin: 0;
}
#menu {
    background-color: gray; 
    padding: 2px;
}
ul ul {
    display: none;
}
li {
    display: inline-block;
}
span {
    background-color: aqua;
    white-space: nowrap;
    display: block;
}
```
Nastáva tu ale jeden problém. Medzi jednotlivými položkami sa nachádza medzera aj ked sme ju nikde nedefinovali. To je 
spôsobené tým, že medzi značkami `<li>` je miesto, ktoré prehliadač interpretuje ako medzeru. Nejedná sa o chybu ale
o dôsledok akým spôsobom má prehliadač zobrazovať riadkové (`inline`) elementy. Vieme, že prehliadáč ignoruje 
viacnásobné medzery a zalomenia. V tomto prípade, vzhľadom na štruktúru sí medzi jednotlivými elementmi `<li>` zalomenia 
a tabulatori, ktoré sú v zobrazení stránky interpretované ako medzery.

Aby sme to názorne predviedli, stačí si niekde do kódu stránky vložiť nasledovný `HTML` kód:
```html
<span>
    <span>asdasd</span>
    <span>asdasd</span>
    <span>asdasd</span>
    <span>asdasd</span>
    asdasd
    asdasdasd
    asd
</span>
```
Výsledok:
```html
asdasd asdasd asdasd asdasd asdasd asdasdasd asd
```
Výsledok tejto štruktúry bude postupnosť jednotlivých textov v riadku oddelených v medzerami. Pokiaľ chceme medzeru 
odstrániť musíme jednotlivé elementy dať ihneď za sebou
```html
  <span>
        <span>asdasd</span><span>asdasd</span><span>asdasd</span><span>asdasd</span>
        asdasd
        asdasdasd
        asd
    </span>
```
Výsledok:
```html
asdasdasdasdasdasdasdasd asdasd asdasdasd asd
```
## Doplňujúce štýľovanie
