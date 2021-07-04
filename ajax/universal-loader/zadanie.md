<div class="hidden">

>  ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/js-a-css/univerzal-loader), [Riešenie](/../../tree/solution/js-a-css/univerzal-loader).
</div>

# Komponent pre zobrazenie spracovania AJAX žiadostí
<div class="info"> 

**Hlavná technológia príkladu**: AJAX

**Ostatné použité technológie**: HTML, JavaScript, CSS

**Obtiažnosť**: 1/5

**Obsah príkladu**: AJAX požiadavky, asynchrónne funkcie, spracovanie výnimiek v JavaScripte, funkcia `fetch()`, CSS jednoduché animácie, *spinner* komponent, AJAX *loader* komponent *flexbox*.
</div>

## Zadanie 
Vytvorte skript, ktorý počas načítavania AJAX požiadavky zobrazí cez celú stránku komponent s informáciu o sťahovaní dát (*AJAX loader*). V prípade viacerých súbežných sťahovaní dát bude zobrazovať ich počet. Implementujte riešenie pre AJAX požiadavky, ktoré používajú funkciu `fetch()`. 

Vychádzať budeme z verzie obsahujúcej HTML kód, ktorý vytvorí tlačidlo. Tlačidlo po stlačení (obsluha udalosti 
`onclick`) načíta pomocou metódy `fetch()` dáta zo vzdialeného API a zobrazí ich. Načítanie dát určitú dobu trvá. Počas tejto doby sa bude zobrazovať náš komponent.

```html
<button onclick="loadData()">Načítaj dáta</button>
<pre id="results"></pre>
```

Funkcia `loadData()`, ktorá využíva `loadUrl()` je definovaná nasledovne:

```javascript
function loadUrl(url) {
    fetch(url, {cache: "no-store"})
        .then(response => response.json())
        .then(json => {
            document.getElementById("results").append(url + " - Načítaných: " + json.length + " záznamov\n");
        });
}

function loadData() 
{
    loadUrl('https://run.mocky.io/v3/93096a26-6f6b-462b-81da-91512a2c4888?mocky-delay=2500ms');
    loadUrl('https://run.mocky.io/v3/93096a26-6f6b-462b-81da-91512a2c4888?mocky-delay=4000ms');
}
```

<div class="hidden">

[Zobraziť riešenie](riesenie.md).
</div>