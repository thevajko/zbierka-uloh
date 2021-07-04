<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/php/faktorial-php), [Riešenie](/../../tree/solution/php/faktorial-php).
> - [Zobraziť riešenie](riesenie.md)
</div>

# Faktoriál
<div class="info"> 

**Hlavná technológia príkladu**: PHP

**Ostatné použité technológie**: HTML

**Obtiažnosť**: 1/5

**Obsah príkladu**: Základy jazyka PHP, premmené, tvorba a vlanie funkcií. 
</div>

## Zadanie
Vytvorte skript v jazyku PHP, ktorý bude schopný zobraziť a vypočítať [faktoriál](https://sk.wikipedia.org/wiki/Faktori%C3%A1l) pre čísla od 0 po 10. Výsledky zobrazte nasledovne:

```html
<ul>
    <li>0! = 1</li>
    <li>1! = 1</li>
    <li>2! = 2</li>
    <li>3! = 6</li>
    <li>4! = 24</li>
    <li>5! = 120</li>
    <li>6! = 720</li>
    <li>7! = 5040</li>
    <li>8! = 40320</li>
    <li>9! = 362880</li>
</ul>
```
<div class="hidden">

[Zobraziť riešenie](riesenie.md).

> Toto riešenie obsahuje všetky potrebné služby v `docker-compose.yml`. Po ich spustení sa vytvorí:
> - webový server, ktory do __document root__ namapuje adresár tejto úlohy s modulom __PDO__. Port __80__ a bude dostupný na adrese [http://localhost/](http://localhost/). Server má pridaný modul pre ladenie [__Xdebug 3__](https://xdebug.org/) nastavený na port __9000__.
</div>