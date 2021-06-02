<div class="hidden">

>  ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/js-a-css/univerzal-loader), [Riešenie](/../../tree/solution/js-a-css/univerzal-loader).
</div>

# Univerzálny loader (JS, AJAX, CSS)

## Zadanie 
Vytvorte skript, ktorý počaš načítavania AJAX požiadavky zobrazí cez celú stránku loading bar. V prípade viacerích súbežných dotazov bude zobrazovať ich počet.
Implementujte riešenie pre AJAX dotazy, ktoré používajú funkciu `fetch`. 

Štartér verzia obsahuje v `HTML` kód, ktorý vytvorí tlačidlo. Tlačidlo po sltlačení načíta pomocou metódy `fetch` dáta zo vzdialeného API a zobrazí ich.

```html
<button onclick="nacitajData()">Načítaj dáta</button>
<pre id="vysledok"></pre>
```

Funkcia `nacitajData` je definovaná nasledovne:

```javascript
const delay = ms => new Promise(resolve => setTimeout(resolve, ms));

function nacitajZdroj(url) {
  fetch(url, {cache: "no-store"})
    .then(response => response.json())
    .then(json => {
      document.getElementById("vysledok").append(url + " - Načítaných: " + json.length + " záznamov\n");
    });
}

async function nacitajData() {
  let zdroje = [
    'https://jsonplaceholder.typicode.com/posts', 
    'https://jsonplaceholder.typicode.com/todos',
    'https://jsonplaceholder.typicode.com/users',
    'https://jsonplaceholder.typicode.com/albums',];
    for (let url of zdroje) {
      nacitajZdroj(url);
      //Počká 200ms medzi načítaním jednotlivých zdrojov
      await delay(200);
    }
}
```


<div class="hidden">

[Zobraziť riešenie](riesenie.md).
</div>