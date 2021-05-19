> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/js-a-css/tooltip-js), [Riešenie](/../../tree/solution/js-a-css/tooltip-js).

# Tooltip - JS a CSS [branch solution]

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

[Zobraziť riešenie](riesenie.md).