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