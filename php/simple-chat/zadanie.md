<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/php/simple-chat), [Riešenie](/../../tree/solution/php/simple-chat).
> - [Zobraziť riešenie](riesenie.md)
</div>

# Jednoduchý chat  (DB, PHP, JS, AJAX, CSS)

## Zadanie

Vytvorte jednoduchý chat, ktorý:

- Bude umožňovať chatovať posielaním správ
- Chat si môže pozerať hocikto. Zobrazte vždy 50 najnovších správ.
- Aby používateľ mohol chatovať musí zadať svoje heslo, t.j. "prihlásiť sa ním"
- Prihlásenému používateľovi bude možné posielať privátne správy a iba "prihlásený" používateľ bude vidieť svoje súkromné správy.
- Ideálne zapracujte:
  - vytvorte samostatného klienta, ktorý bude komunikovať zo serverom pomocou _API_ asynchrónne
  - komunikácia výlučne volaním _API_ servera, JSON a HTTP kódmi
  
Aj ked má táto úloha názov _"Jednoduchý chat"_ je tým myslená jednoduchá funkcionalita, ktorú ponúka. Samotná implementácia, tak aby splnila všetky zadané podmienky jednoduchá rozhodne nie je.

### Cieľ príkladu
Cieľom príkladu je vytvorenie robustnejšieho klienta v čistom javascripte, ktorý je zameraný na asynchrónnu komunikáciu pomocou technológie AJAX. Príklad prechádza od celkového ošetrenia komunikácie s webovým API, manipuláciou DOM, navrhnutím komunikácie pomocou _HTTP kódov a JSONom, serverovou časť a databázovou vrstvou. 