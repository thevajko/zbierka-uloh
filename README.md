> ## Rozcestník
> - prepnúť na [main](/../../tree/main) alebo [solution](/../../tree/solution)

# Zbierka úloh pre VAII [branch solution]
Tento repozitár je zbierka úloh pre študentov predmetu VAII [Fakulty riadenia a informatiky (FRI)](https://www.fri.uniza.sk/). Obsahuje úlohy zo zadaním, postupom riešenia a samotné výsledné riešenie (úloha môže mať, samozrejme viacero správnych 
riešení).

Repozitár obsahuje dva branche:
- [_main_](/../../tree/main) - obsahuje zadanie a inicializačnú logiku
- [_solution_](/../../tree/solution) - obsahuje fungujúce riešenia

# Docker
V súčasnosti jeden z najpoužívanejších spôsobov virtuálizácie, ktorá výrazne zjednocuje a zjednodušuje vývoj a nasadzovanie rôznych aplikácií. Umožňuje veľmi pohodlne nakonfigurovať a zostaviť služby ako lokálne tak aj na serveroch. Umožňuje vytvoriť jednotné a stabilné prostredie.

Niektoré naše úlohy vyžadujú pre ich vypracovanie webový server s PHP a relačnú databázu. Pre odľahčenie a odstránenie nutnosti všetko konfigurovať pripájame, ku každej úlohe, ktorá to vyžaduje súbor `docker-compose.yml`. Tento súbor obsahuje potrebnú nami vytvorenú konfiguráciu, tak aby ste nestrácali zbytočne čas konfiguráciou a inštaláciou potrebných služieb.

Pre použitie _Dockera_ na lokálnom PC sa používa aplikácia _Docker Desktop_, ktorá je dostupná pre _Linux_, _Windows_ a _MacOS_. Pár poznámok k jej stiahnutiu:

1.  Ak máte _OS Windows_, potrebuje vyššiu verziu ako je _Home_. Licenciu pre vyššiu verziu ma každý študent zdarma k 
    dispozícií, nakoľko je FRI zaradená do licenčného programu Microsoft Azure DevTools For Teaching. Viac informácií nájdete
    [na oficiálnych stránkach fakulty](https://www.fri.uniza.sk/stranka/softver-a-internet).
2.  Je potrebné vytvorenie konta pre [Docker](https://www.docker.com/)
3.  Až po jeho vytvorení je možné používať aplikáciu [Docker Desktop](https://www.docker.com/products/docker-desktop)
4.  Túto inštaláciu zvládne každý informatik, každopádne pre prípad núdze skúste [oficiálnu dokumentáciu](https://docs.docker.com/desktop/).

Samozrejme, pre spustenie potrebných služieb môžete použiť ľubovoľným spôsob.

# Zoznam úloh

Úlohy sú rozdelené podľa toho, ktorú technológiu používajú a obsahujú označenie náročnosti (stupnica od 1 po 5, kde 5 je najväčšia náročnosť)

## CSS
1. [Tooltip](css/tooltip-css/zadanie.md) (CSS) - obtiažnosť 2
1. [Gulečník](css/css-lopticka/zadanie.md) (CSS) - obtiažnosť 1
1. [Slnečna sústava](css/css-planety/zadanie.md) (CSS) - obtiažnosť 2
1. [Galéria](css/galeria/zadanie.md) (CSS) - obtiažnosť 2
1. [Drop-Down a Drop-Up menu](css/dropdownmenu/zadanie.md) (CSS) - obtiažnosť 3
1. [CSS Smajlíky](css/css-smajliky/zadanie.md) (CSS) - obtiažnosť 3
   
## JS a CSS
1. [ShowHide](js-a-css/showhide/zadanie.md) (JS, CSS) - obtiažnosť 1
1. [Tooltip](js-a-css/tooltip-js/zadanie.md) (JS, CSS) - obtiažnosť 2
1. [Analógové hodinky](js-a-css/analog-clock/zadanie.md) (JS, CSS) - obtiažnosť 2
1. [Univerzal loader](js-a-css/univerzal-loader/zadanie.md) (JS, AJAX, CSS) - obtiažnosť 2
1. [JS Table](js-a-css/jstable/zadanie.md) (JS, CSS) - obtiažnosť 3
1. [Kontrola formulára](js-a-css/form-check/zadanie.md) (JS, CSS) - obtiažnosť 2
1. [Pexeso](js-a-css/pexeso/zadanie.md) (JS, CSS) - obtiažnosť 2
1. [Hra mucha](js-a-css/fly/zadanie.md) (JS, CSS) - obtiažnosť 4

## PHP
1. [Faktoriál](php/faktorial-php/zadanie.md) (PHP) - obtiažnosť 1
1. [Generovanie štvorčekov](php/generovanie-stvorcekov/zadanie.md) (PHP, CSS) - obtiažnosť 1
1. [Jednoduchá tabuľka z Db](php/dbtable/zadanie.md) (DB, PHP, CSS) - obtiažnosť 2

## Komplexné úlohy

___

# Doplňujúci materiál
Prepájame ešte menší zoznam praktických vecí s ktorými sa môžete stretnúť pri vývoji webových aplikácií.

## CSS
1. [CSS position](common/css-position.md)

## JS
1. [Načítavanie skriptov](common/js-onload.md)
1. [String v JS](common/js-praca-zo-stringom.md)
1. [Dynamické vytváranie elementov](common/js-dynamicke-vytvaranie-elementov.md)




