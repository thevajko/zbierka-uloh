<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/js/form-check), [Riešenie](/../../tree/solution/js/form-check)
> - [Zobraziť zadanie](riesenie.md)
</div>

# Kontrola vyplnenia formulára
<div class="info"> 

**Hlavný jazyk príkladu**: JS

**Ostatné použité jazyky**: HTML, CSS

**Obťažnosť**: 3/5

**Obsah príkladu**: Validácia formulárov pomocou HTML a JavaScriptu, regulárne výrazy.
</div>

## Zadanie

Vytvorte skript v jazyku JavaScript, ktorý bude kontrolovať správne vyplnenie formulára. Riešenie musí spĺňať nasledovné podmienky:

1. Hodnoty polí `Meno`, `Priezvisko`, `Mail` a `Vaša správa` nesmú byť pri odoslaní prázdne.
1. Pole `Mail` musí obsahovať e-mailovú adresu v platnom formáte.
1. Pole `Telefónne číslo` môže, ale nemusí mať hodnotu. Ak ju má, je potrebné, aby hodnota mala formát mobilného čísla a medzinárodný tvar so slovenskou predvoľbou, napr. `+421 912 345 678` (zadávať je ho možné s medzerami).
1. Pole `Vaša správa` musí obsahovať minimálne 6 znakov.
1. Pri nájdení chyby vypíšte používateľovi pod príslušným vstupom chybovú hlášku.
1. Zablokujte tlačidlo `Odoslať`, ak formulár obsahuje chyby.