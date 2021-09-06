<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/php/zipper), [Riešenie](/../../tree/solution/php/zipper)
> - [Zobraziť riešenie](riesenie.md)
</div>

# Online komprimácia súborov
<div class="info"> 

**Hlavný jazyk príkladu**: PHP

**Ostatné použité jazyky**: HTML, JavaScript

**Obťažnosť**: 3/5

**Obsah príkladu**: Objektové programovanie v PHP, nahrávanie (*upload*) súborov na server a ich spracovanie, práca so súbormi a adresármi, komprimácia súborov, posielanie súborov na klienta, externé rozšírenia jazyka PHP, identifikáciu používateľa, práca s&nbsp;*cookies* V PHP, pokročilejšia práca s poliami, výnimky.

</div>

<div class="hidden">

> Toto riešenie obsahuje všetky potrebné služby v `docker-compose.yml`. Po ich spustení sa vytvorí:
> - webový server, ktory do __document root__ namapuje adresár tejto úlohy s modulom __PDO__. Port __80__ a bude dostupný na adrese [http://localhost/](http://localhost/). Server má pridaný modul pre ladenie [__Xdebug 3__](https://xdebug.org/) nastavený na port __9000__.

</div>

## Zadanie

Vytvorte PHP skript, ktorý umožní postupne používateľovi pridávať súbory zo svojho počítača na server, tieto súbory skomprimuje a umožní stiahnuť. Aplikáciu najskôr implementujte tak, že komprimovať sa budú všetky súbory naraz bez ohľadu na to, kto súbor nahral. Neskôr implementujte funkciu aplikácie, aby výsledný komprimovaný súbor obsahoval len súbory, ktoré nahral daný používateľ.
