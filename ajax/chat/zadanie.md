<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/ajax/chat), [Riešenie](/../../tree/solution/ajax/chat)
> - [Zobraziť riešenie](riesenie.md)
</div>

# Aplikácia na chatovanie
<div class="info"> 

**Hlavný jazyk príkladu**: AJAX

**Ostatné použité jazyky**: PHP, HTML, JavaScript, CSS

**Obťažnosť**: 5/5

**Obsah príkladu**: Objektové programovanie v PHP, spracovanie výnimiek v PHP, *session*, tvorba serverového API rozhrania, PHP Data Objects (PDO), *PDO prepare statements*, databázová vrstva, HTTP stavové kódy, AJAX volania, JSON formát, JavaScript API klient, prísľuby (*promises*), asynchrónne programovanie v JavaScripte, časovače v&nbsp;JavaScripte, kľúčové slová `async`, `await`, použitie *arrow* funkcií, manipulácia s DOM, CSS *flexbox*.

</div>

<div class="hidden">

> Všetky potrebné služby sú v `docker-compose.yml`. Po ich spustení sa vytvorí:
> - webový server, ktorý do __document root__ namapuje adresár tejto úlohy s modulom __PDO__. Port __80__ a bude dostupný na adrese [http://localhost/](http://localhost/). Server má pridaný modul pre ladenie [__Xdebug 3__](https://xdebug.org/) nastavený na port __9000__ v "auto-štart móde" (`xdebug.start_with_request=yes`).
> - databázový server s vytvorenou _databázou_ a tabuľkami `messages` a `users` na porte __3306__ a bude dostupný na `localhost:3306`. Prihlasovacie údaje sú:
>   - MYSQL_ROOT_PASSWORD: db_user_pass
>   - MYSQL_DATABASE: dbchat
>   - MYSQL_USER: db_user
>   - MYSQL_PASSWORD: db_user_pass
> - phpmyadmin server, ktorý sa automatický nastavený na databázový server na porte __8080__ a bude dostupný na adrese [http://localhost:8080/](http://localhost:8080/)

</div>

## Zadanie

Vytvorte chatovaciu aplikáciu, ktorá budem mať nasledovné funkcie:

1. Bude umožňovať posielanie správ.
1. Správy si môže pozerať aj neprihlásený používateľ. 
1. Zobrazovať sa bude vždy 50 najnovších správ.
1. Aby používateľ mohol chatovať, musí zadať svoje meno, t.j. prihlásiť sa.
1. Prihlásenému používateľovi bude možné posielať privátne správy a iba prihlásený používateľ bude vidieť svoje súkromné správy.

Technické spracovanie:

1. Vytvorte samostatného klienta, ktorý bude komunikovať zo serverom pomocou API rozhrania asynchrónne.
1. Komunikáciu realizujte výlučne volaním API servera, s použitím JSON formátu a odpoveďami s HTTP stavovými kódmi.

