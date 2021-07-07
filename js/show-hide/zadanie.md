<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/js/show-hide), [Riešenie](/../../tree/solution/js/show-hide).
> - [Zobraziť riešenie](riesenie.md)
</div>

# Skrývanie a zobrazovanie HTML elementov
<div class="info"> 

**Hlavný jazyk príkladu**: JS

**Ostatné použité jazyky**: HTML

**Obťažnosť**: 1/5

**Obsah príkladu**: Výber elementov pomocou metódy `querySelectorAll()`, skrývanie a zobrazovanie elementov. 
</div>

## Zadanie
Vytvorte skript, ktorý po kliknutí na element `h1` zobrazí text v elemente `p`. Naopak, ak je text zobrazený, tak ho skryje. 

Skript by mal fungovať na takejto štruktúre HTML kódu:

```html
<div>
    <h1>
        Suspendisse in congue mi
    </h1>
    <p>
        Lorem ipsum dolor sit amet, conse...
    </p>
</div>
<div>
    <h1>
        Interdum et malesuada fames ac ante ipsum primis in faucibus
    </h1>
    <p>
        Cras elementum egestas massa, eu finibus nulla convallis se...
    </p>
</div>
....
```