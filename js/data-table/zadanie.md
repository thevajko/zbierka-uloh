<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/js/data-table), [Riešenie](/../../tree/solution/js/data-table).
> - [Zobraziť riešenie](riesenie.md)
</div>

# Dátová tabuľka
<div class="info"> 

**Hlavný jazyk príkladu**: JS

**Ostatné použité jazyky**: HTML

**Obťažnosť**: 3/5

**Obsah príkladu**: JSON dáta a ich spracovanie, zoraďovanie dát, manipulácia s kolekciou DOM elementov, dynamické vytváranie elementov a vkladanie nových elementov.
</div>

## Zadanie

Vytvorte skript, ktorý dokáže zobraziť kolekciu dát v HTML tabuľke. Zadanie musí spĺňať nasledujúce podmienky:

1. Ako vstup predpokladajte pole dátových objektov. Objekty si môžete vygenerovať napr. pomocou
   [JSON generátora](https://www.json-generator.com/). <span class="hidden">Prípadne ako zdroj dát použite štruktúru definovanú v
   skripte [`users-data.js`](users-data.js) a  [`products-data.js`](products-data.js).</span>
1. Implementujte zoraďovanie podľa každého stĺpca vzostupe a zostupne.
2. V záhlaví tabuľky zobrazte názvy atribútov objektov v kolekcii.
3. Filtrujte riadky na základe danej hodnoty. Zobrazte iba tie riadky, kde v ľubovolnom stĺpci nájdete zhodu s hľadaným
   výrazom.
4. Skript navrhnite tak, aby sa dal na jednej stránke použiť opakovane, teda mohli sa zobraziť viaceré nezávislé tabuľky.

Aplikáciu vytvorte pomocou JavaScriptu.


