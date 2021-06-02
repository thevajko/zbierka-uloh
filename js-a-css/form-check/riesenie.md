<div class="hidden">

> > ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/js-a-css/form-check), [Riešenie](/../../tree/solution/js-a-css/form-check).
> - [Zobraziť zadanie](zadanie.md)

# Kontrola formulára (JS, CSS)
</div>
## Zadanie

Nedovoľte formulár odoslať pokiaľ:
- Nie je vyplnená hodnota pre __meno__, __priezvisko__, __mail__ a __vaša správa__.
- __Mail__ musí obsahovať mail v platnom formáte.
- __telefónne číslo__ môže aj nemusí mat hodnotu. Ak ju má, je potrebné aby malo slovenky medzinárodný tvar `+421 912 345 678` (zadávať je ho možne s medzerami).
- __Vaša správa__ musím obsahovať minimálne 6 znakov.
- Pri nájdení chyby vypíšte používateľovi pod príslušným vstupom chybovú hlášku.
- Zablokujte tlačítko __Odoslať__ pokiaľ formulár obsahuje chyby.

## Riešenie

Ako už vieme, formulár je definovaný pomocou elementu `<form>` do ktorého sa pridávajú elementy `<input>`, `<textarea>` a `<select>` umožňujúce zadať používateľovi vstup (pridať dáta do formulára pre odoslanie).

### Čisté HTML5 riešenie
Základným prvkom používateľského vstupu predstavuje element `<input>` ktorého atribút [`type`](https://www.w3schools.com/html/html_form_input_types.asp) bližšie definuje druh očakávaného vstupu a jeho vzhľad.

Ďalším jeho dôležitým atribútom je `pattern` v ktorom sa ako hodnota uvádza regulárny výraz. Ten sa následne používa pre validáciu vstupu, ktorý zadal používateľ.


Ako prvé budeme kontrolovať, či majú vstupy hodnotu v správnom tvare. V prípade __mailu__ 
môžeme použiť rovno typ zadefinovať vstupné pole ako typ `email`, teda:

```html
<input type="email" id="mail">
```

Ten ale nie je vždy dostačujúci, nakoľko nie každý prehliadač kontoluje zadanú hodnotu poriadne. Lepšie preto bude použiť atribút `pattern`, kde zadáme regulárny výraz, ktorý bude kontrolovať ci hodnota v tvare emailovej adresy.

To ako má výraz vyzerať vieme ľahko bud vytvoriť alebo nájsť na internete. Jeden z týchto výrazov je napr `/^\S+@\S+\.\S+$/` ([zdroj tu](https://stackoverflow.com/questions/201323/how-to-validate-an-email-address-using-a-regular-expression)). Element pre zadanie mailu teda zapíšeme:

```html
 <input type="text" id="mail" pattern="/^\S+@\S+\.\S+$/">
```

To isté bude platiť pre telefónne číslo zo slovenskou predvoľbou, pre ktorý bude platiť regulárny výraz `/^\+421([0-9]{9}|(( {0,1}[0-9]{3}){3}))$/`.  Element pre zadanie mobilného čísla bude zapísaný nasledovne:

```html
<input type="text" id="mobil" pattern="/^\+421([0-9]{9}|(( {0,1}[0-9]{3}){3}))$/">
```

Problém nastáva pri elemente `<textarea>`, ktorý nemá atribút `pattern`, tu budeme musieť logiku validácie vstupu vytvoriť pomocou javascriptu. To však budeme implementovať neskôr.

Teraz pridáme atribút `required` do `<input>` elementov pre zadávanie pre __meno__, __priezvisko__, __mail__ a __vaša správa__. Pridanie atribútu `required` bude vyzerať nejako takto:

```html
 <input type="text" id="mail" pattern="/^\S+@\S+\.\S+$/" required>
```

Teraz, keď odošleme formulár kliknutím na tlačidlo `Odoslať`, formulár sa neodošle a prehliadač zobrazí pri prvom prvku s nevyplnenou hodnotou alebo nevhodnou hodnotu chybovú hlášku: 

![](.form-check-images/form-check-01.png)

Bolo by však dobré aj vizuálne používateľovi zobraziť, ktoré prvky formulára obsahujú chybu. Tu môžeme použit to, že prehliadač automaticky do neplatných prvkov formulára pridá [pseudo-triedu `:invalid`](https://developer.mozilla.org/en-US/docs/Web/CSS/:invalid). Taktiež je táto `:invalid` pridaný aj do formulára, ktorý chybu obsahuje. Stačí nám preto pridať jednoduché _CSS_, ktoré zafarbí pozadie týchto prvkov na červeno:

```html
<style>
    :invalid:not(form) { 
        background-color: red;
    }
</style>
```

Selektor `:invalid:not(form)` vyberá všetky prvky, ktoré majú priradenú pseudo-triedu `:invalid` a nie sú elementy `<form>` pomocou `:not(form)`.  Formulár sa bude teraz zobrazovať nasledovne:

![](.form-check-images/form-check-02.png)


Týmto sme vyčerpali možnosti, ktoré máme pre validáciu použitím výlučne HTML5 bez javascriptu. Ešte by som pridal nasledovné poznámky:

- Momentálne neexistuje spôsob, ktorým vieme iba pomocou HTML zadefinovať obsah chybových hlášok.
- Nie je možné zablokovať tlačítko pre odoslanie.
- Nie je spôsob akým zobrazíme všetky chybové hlášky súčasne.


### Javascript riešenie