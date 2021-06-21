<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/php/php-form), [Riešenie](/../../tree/solution/php/php-form).

# PHP formulár (PHP)

</div>

## Riešenie

### Návrh

Na riešenie tejto úlohy bude potrebné navrhnúť objektovú štruktúru. Zo zadania jasne vyplýva, že budeme potrebovať triedu `Form`. Táto trieda bude mať metódy na pridávanie formulárových prvkov pomocou metód `addXXX()`. Okrem iného táto trieda bude schopná vrátiť vyplnené hodnoty v asociatívnom poli - `getData()`. Ďalšou funkcionalitou bude detekcia odoslania formulára `isSubmitted()` a kontrola správnosti vyplnených údajov - `isValid()`.

Vzhľadom na to, že formulár má byť univerzálny, jednotlivé časti formuláru budú riešené pomocou ďalšej objektovej štruktúry. Formulár bude obsahovať prvky typu `AFormElement`. Elementom formuláru môže byť tlačítko, text, textové pole, selectbox... Z tohto dôvodu rozdelíme elementy ešte na ďalšiu hierarchiu. Základné elementy, ktoré nebudú poskytovať dáta (napr. tlačítko, dodatočný text...) budú potomkami triedy `AFormElement`, ktorá bude mať jedinú metódu `render()`. Elementy, ktoré budú slúžiť na zadávanie vstupov budú dediť od triedy `AFormField`. Táto trieda bude potomkom triedy `AFormElement` - takže sa budú dať vykresliť. Trieda `AFormField` pridá k základnému elementu ďalšiu funkcionalitu. Pridá možnosť získať vyplnenú hodnotu, pridať validation pravidlá. Vykresľovanie väčšiny formulárových prvkov je veľmi podobné:
```˙html
<label>Nazov</label>
PRVOK
```
Metóda render pripraví štruktúru, vykreslí label a pomocou abstraktnej metódy `renderElement()`, ktorú si v potomkoch prekryjeme vykreslíme obsah daného elementu.

Validácia bude prebiehať tak, že ku každému prvku pridáme tzv. Validátory. Validátor je trieda, ktorá bude dediť od `AValidator` a bude sa pridávať k jednotlivým `AFormElement` pomocou metódy `addRule`.

Výsledný UML diagram celého riešenia je nasledovný:

![UML diagram](http://www.plantuml.com/plantuml/proxy?cache=no&src=https://raw.githubusercontent.com/thevajko/zbierka-uloh/solution/php/php-form/diagram.puml)

### Implementácia

