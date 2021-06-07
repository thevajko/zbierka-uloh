<div class="hidden">

>  ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/js-a-css/univerzal-loader), [Riešenie](/../../tree/solution/js-a-css/univerzal-loader).
</div>

# Univerzálny loader (JS, AJAX, CSS)

## Zadanie 
Vytvorte skript, ktorý počaš načítavania AJAX požiadavky zobrazí cez celú stránku loading bar. V prípade viacerích súbežných dotazov bude zobrazovať ich počet.
Implementujte riešenie pre AJAX dotazy, ktoré používajú funkciu `fetch`. 

Štartér verzia obsahuje v `HTML` kód, ktorý vytvorí tlačidlo. Tlačidlo po sltlačení načíta pomocou metódy `fetch` dáta zo vzdialeného API a zobrazí ich. Načítanie dát určitú dobu trvá. Počas tejto doby sa bude zobrazovať loading bar.

```html
<button onclick="nacitajData()">Načítaj dáta</button>
<pre id="vysledok"></pre>
```

Funkcia `nacitajData` je definovaná nasledovne:

```javascript
function nacitajZdroj(url) {
  fetch(url, {cache: "no-store"})
    .then(response => response.json())
    .then(json => {
      document.getElementById("vysledok").append(url + " - Načítaných: " + json.length + " záznamov\n");
    });

function nacitajData() {
  nacitajZdroj('https://run.mocky.io/v3/93096a26-6f6b-462b-81da-91512a2c4888?mocky-delay=2500ms');
  nacitajZdroj('https://run.mocky.io/v3/93096a26-6f6b-462b-81da-91512a2c4888?mocky-delay=4000ms');
}
```

### Cieľ príkladu
Cieľom príkladu je vytvorenie kódu, ktorý dokáže zachytávať AJAX požiadavky, využíva asynchrónne funkcie, nahrádza funkciu `fetch` z objektu `window`. Z css ukazuje jednoduché animácie.

<div class="hidden">

[Zobraziť riešenie](riesenie.md).
</div>