<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/php/squares), [Riešenie](/../../tree/solution/php/squares)
> - [Zobraziť riešenie](riesenie.md)
</div>

# Generovanie štvorčekov
<div class="info"> 

**Hlavný jazyk príkladu**: PHP

**Ostatné použité jazyky**: HTML, CSS

**Obťažnosť**: 1/5

**Obsah príkladu**: Generovanie HTML a CSS kódu pomocou PHP, vytváranie a volanie funkcií, cyklus `for`, náhodné čísla, polia v PHP. 
</div>

<div class="hidden">

> Všetky potrebné služby sú v `docker-compose.yml`. Po ich spustení sa vytvorí:
> - webový server, ktorý do __document root__ namapuje adresár tejto úlohy s modulom __PDO__. Port __80__ a bude dostupný na adrese [http://localhost/](http://localhost/). Server má pridaný modul pre ladenie [__Xdebug 3__](https://xdebug.org/) nastavený na port __9000__.

</div>

## Zadanie
Vytvorte skript v jazyku PHP, ktorý v kombinácii s CSS vyplní celú stránku štvorčekami o veľkosti `50px` x `50px`. Každý štvorček bude mať náhodnú pozíciu a farbu. Štvorčekov na jednej stránke zobrazte 2000. 

![Ukážka náhodne rozmiestnených farebných štvorčekov](images_squares/zadanie.png)
