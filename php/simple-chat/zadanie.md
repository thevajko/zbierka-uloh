<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/php/simple-chat), [Riešenie](/../../tree/solution/php/simple-chat).
> - [Zobraziť riešenie](riesenie.md)
</div>

# *Chat* aplikácia (DB, PHP, JS, AJAX, CSS)

## Zadanie

Vytvorte *chat* aplikáciu, ktorá budem mať nasledovné funkcie:

- Bude umožňovať posielať správ.
- Správy si môže pozerať aj neprihlásený používateľ. -Zobrazovať sa bude vždy 50 najnovších správ.
- Aby používateľ mohol chatovať, musí zadať svoje heslo, t.j. prihlásiť sa.
- Prihlásenému používateľovi bude možné posielať privátne správy a iba prihlásený používateľ bude vidieť svoje súkromné správy.

Technické spracovanie:

- Vytvorte samostatného klienta, ktorý bude komunikovať zo serverom pomocou API rozhrania asynchrónne.
- Komunikáciu realizujte výlučne volaním API servera, s použitím JSON formátu a odpoveďmi s HTTP stavovými kódmi.

### Cieľ príkladu

Cieľom príkladu je vytvorenie robustnejšieho klienta v čistom javascripte, ktorý je zameraný na asynchrónnu komunikáciu pomocou technológie AJAX. Príklad prechádza od celkového ošetrenia komunikácie s webovým API, manipuláciou DOM, navrhnutím komunikácie pomocou HTTP stavových kódov a JSON formátom, serverovou časť a databázovou vrstvou. 