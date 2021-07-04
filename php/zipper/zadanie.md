<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/php/zipper), [Riešenie](/../../tree/solution/php/zipper).
> - [Zobraziť riešenie](riesenie.md)
</div>

# Online zipovač (PHP)
<div class="info"> 

**Hlavný jazyk príkladu**: PHP

**Ostatné použité jazyky**: HTML, JavaScript

**Obtiažnosť**: 1/5

**Obsah príkladu**: Objektové programovanie v PHP, nahrávanie (*upload*) súborov na server a ich spracovanie, externé rozšírenia jazyka PHP, identifikáciu používateľa, *cookies*, polia.
</div>

## Zadanie

Vytvorte PHP skript, ktorý umožní postupne používateľovi pridávať súbory zo svojho počítača na server a potom tieto súbory komprimuje a umožní stiahnuť. Aplikáciu najskôr implementujte tak, že komprimovať sa budú všetky súbory naraz bez ohľadu na to, kto súbor nahral. Neskôr implementujte funkciu aplikácie, aby výsledný komprimovaný súbor obsahoval len súbory, ktoré nahral daný používateľ.

<div class="hidden">

[Zobraziť riešenie](riesenie.md).

> Toto riešenie obsahuje všetky potrebné služby v `docker-compose.yml`. Po ich spustení sa vytvorí:
> - webový server, ktory do __document root__ namapuje adresár tejto úlohy s modulom __PDO__. Port __80__ a bude dostupný na adrese [http://localhost/](http://localhost/). Server má pridaný modul pre ladenie [__Xdebug 3__](https://xdebug.org/) nastavený na port __9000__.

</div>