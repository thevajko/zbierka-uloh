<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/php/data-table), [Riešenie](/../../tree/solution/php/data-table)
> - [Zobraziť riešenie](riesenie.md)
</div>

# Dátová tabuľka
<div class="info"> 

**Hlavný jazyk príkladu**: PHP

**Ostatné použité jazyky**: HTML

**Obťažnosť**: 5/5

**Obsah príkladu**: Objektová architektúra aplikácie, triedy a rozhrania, PHP Data Objects (PDO), *PDO prepared statements*, GET parametre, zoraďovanie, filtrovanie a stránkovanie dát, príkaz `switch`, ochrana voči *SQL injection* útoku, ochrana voči *Cross Site Scripting* útoku.  

</div>

<div class="hidden">

Predpokladáme, že databázový server je spustený a obsahuje tabuľku s dátami, ktoré sú v súbore `data.sql`.

> Všetky potrebné služby sú v `docker-compose.yml`. Po ich spustení sa vytvorí:
> - webový server, ktorý do __document root__ namapuje adresár tejto úlohy s modulom __PDO__. Port __80__ a bude dostupný na adrese [http://localhost/](http://localhost/). Server má pridaný modul pre ladenie [__Xdebug 3__](https://xdebug.org/) nastavený na port __9000__.
> - databázový server s vytvorenou _databázou_ a tabuľkou `users` s dátami na porte __3306__ a bude dostupný na `localhost:3306`. Prihlasovacie údaje sú:
    >   - MYSQL_ROOT_PASSWORD: heslo
>   - MYSQL_DATABASE: dbtable
>   - MYSQL_USER: db_user
>   - MYSQL_PASSWORD: db_user_pass
> - phpmyadmin server, ktorý sa automatický nastevený na databázový server na porte __8080__ a bude dostupný na adrese [http://localhost:8080/](http://localhost:8080/)

</div>

## Zadanie

Vytvorte aplikáciu v jazyku PHP, ktorá bude schopná zobraziť obsah ľubovoľnej databázovej tabuľky a bude umožňovať:

1. načítanie a zobrazenie všetkých dát vo forme HTML tabuľky,
1. zoradenie dát vzostupne a zostupne kliknutím na záhlavie tabuľky, 
1. stránkovanie zobrazených dát (zobrazenie napr. len 10 záznamov na jednej stránke).

