<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/js/tooltip), [Riešenie](/../../tree/solution/js/tooltip)
> - [Zobraziť riešenie](riesenie.md)
</div>

# Popisok (tooltip)
<div class="info"> 

**Hlavný jazyk príkladu**: JS

**Ostatné použité jazyky**: HTML, CSS

**Obťažnosť**: 1/5

**Obsah príkladu**: Obsluha udalosti `windows.onload`, obsluha udalostí myši, použitie metódy `document.querySelectorAll()`, dátové atribúty, použitie atribútu `innerHTML`.
</div>

## Zadanie

Vytvorte skript, ktorý zobrazí popisok, ak používateľ umiestni kurzor nad `span` element v dokumente.

Skript by mal fungovať na HTML kóde, ako je tento:

```html
<div>
    Lorem ipsum dolor sit amet,
    <span>consectetur</span>
    <span class="tooltip">Tooltip: In mollis accumsan sodales.</span>
    adipiscing elit. In
    <span>hendrerit</span>
    <span class="tooltip">Tooltip: Maecenas lobortis quam quis euismod maximus.</span>
    adipiscing elit. ac ex eu aliquam. Etiam lacus orci, egestas et tempor at,
    <span>rutrum</span>
    <span class="tooltip">Tooltip: Curabitur consequat ligula vel tortor consequat, quis mattis mi egestas.</span>
    adipiscing elit. vitae nulla.
</div>
```

Kód obsahuje element `div`, ktorého obsah je tvorený textom generovaným pomocou [*Lorem ipsum generátora*](https://www.lipsum.com/). V tomto texte sa ďalej nachádzajú elementy `span`, kde prvý `span` označuje výraz a druhý `span` s triedou `tooltip` obsahuje text popisku, ktorý sa má používateľovi zobraziť, ak nad prvý `span` umiestni kurzor myši. Len čo používateľ kurzor z prvého elementu `span` premiestni preč, popisok sa skryje. Samozrejme, pri otvorení dokumentu nesmú byť popisky zobrazené.

<div class="hidden">

Štruktúru dokumentu môžete upraviť tak, aby bolo možné úlohu vypracovať. Pre vypracovanie použite JavaScript a CSS.

> ### Pomôcky:
> - [Načítanie JS](../../common/js-onload.md )
> - [JS a string](../../common/js-praca-zo-stringom.md)
> - [CSS pozíciovanie](../../common/css-position.md )

</div>


