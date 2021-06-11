<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/php/zipper), [Riešenie](/../../tree/solution/php/zipper).
> - [Zobraziť riešenie](riesenie.md)
</div>

# Online zipovač (PHP)

## Zadanie

Vytvorte PHP skrípt ktorý umožní postupne užívateľovi pridávať súbory do svojho počítača na server a potom tieto súbory
zastupuje a umožní stiahnuť. Aplikáciu najskôr implementujte tak že komprimovať sa budú všetky súbory naraz bez ohľadu
na to kto súbor nahral. neskôr implementujte vlastnosť aby aby výsledný komprimovaný súbor obsahoval len súbory ktoré
nahral daný používateľ.

### Cieľ príkladu

Cieľom príkladu je vyskúšať si upload súborov na server a ich spracovanie okrem toho si takisto vyskúšame použitie
externých rozšírený pre PHP a ich využitie neposlednom rade využijeme cookies na identifikáciu používateľa.

<div class="hidden">

[Zobraziť riešenie](riesenie.md).
</div>


<div class="hidden">

> Toto riešenie obsahuje všetky potrebné služby v `docker-compose.yml`. Po ich spustení sa vytvorí:
> - webový server, ktory do __document root__ namapuje adresár tejto úlohy s modulom __PDO__. Port __80__ a bude dostupný na adrese [http://localhost/](http://localhost/). Server má pridaný modul pre ladenie [__Xdebug 3__](https://xdebug.org/) nastavený na port __9000__.

</div>