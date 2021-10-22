<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/php/crud), [Riešenie](/../../tree/solution/php/crud)
> - [Zobraziť riešenie](riesenie.md)
</div>

# Základné operácie s dátovou tabuľkou
<div class="info"> 

**Hlavný jazyk príkladu**: PHP

**Ostatné použité jazyky**: HTML, JavaScript, CSS

**Obťažnosť**: 5/5

**Obsah príkladu**: Štruktúra aplikácie vo viacerých súboroch, operácie čítania, pridávanie, zmeny a mazanie dát v DB, PHP Data Objects (PDO), *PDO prepare statements*, ochrana voči XSS útoku.

</div>

<div class="hidden">

> Všetky potrebné služby sú v `docker-compose.yml`. Po ich spustení sa vytvorí:
> - webový server, ktorý do __document root__ namapuje adresár tejto úlohy s modulom __PDO__. Port __80__ a bude dostupný na adrese [http://localhost/](http://localhost/). Server má pridaný modul pre ladenie [__Xdebug 3__](https://xdebug.org/) nastavený na port __9000__.
> - databázový server s vytvorenou _databázou_ a tabuľkou `users` s dátami na porte __3306__ a bude dostupný na `localhost:3306`. Prihlasovacie údaje sú:
    >   - MYSQL_ROOT_PASSWORD: db_user_pass
>   - MYSQL_DATABASE: crud
>   - MYSQL_USER: db_user
>   - MYSQL_PASSWORD: db_user_pass
> - phpmyadmin server, ktorý sa automatický nastavený na databázový server na porte __8080__ a bude dostupný na adrese [http://localhost:8080/](http://localhost:8080/)

</div>

## Zadanie

1. Vytvorte jednu databázovú entitu (napríklad zoznam osôb). 
2. Pre túto entitu implementujte v PHP operácie Create, Read, Update a Delete (CRUD operácie).
3. Na zobrazenie dát využite HTML tabuľku. 
4. Na vytvorenie nového záznamu a editáciu existujúceho navrhnite formulár. 
5. Možnosť editácie a odstránenia bude dostupná z tabuľky so zoznamom osôb.