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

**Obsah príkladu**: Objektová architektúra aplikácie, triedy a rozhrania, PHP Data Objects (PDO), *PDO preprared statements*, GET parametre, zoraďovanie, filtrovanie a stránkovanie dát, príkaz `switch`, ochrana voči *SQL injection* útoku, ochrana voči *Cross Site Scripting* útoku.  
</div>

## Zadanie

Vytvorte aplikáciu v jazyku PHP, ktorá bude schopná zobraziť obsah ľubovolnej databázovej tabuľky a bude umožňovať:

1. načítanie a zobrazenie všetkých dát vo forme HTML tabuľky,
1. zoradenie dát vzostupne a zostupne kliknutím na záhlavie tabuľky, 
1. stránkovanie zobrazených dát (zobrazenie napr. len 10 záznamov na jednej stránke).