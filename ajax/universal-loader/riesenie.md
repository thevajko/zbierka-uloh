<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/ajax/universal-loader), [Riešenie](/../../tree/solution/ajax/universal-loader)
> - [Zobraziť zadanie](zadanie.md)

# Univerzálny loader (JS, AJAX, CSS)

</div>

## Riešenie

### Grafické zobrazenie komponentu

Na začiatok si pripravíme grafickú reprezentáciu nášho komponentu. Začneme s HTML kódom:

```html
<div id="ajaxLoader">
  <div class="spinner"></div>
  <span id="requestCounter">Zostáva: 5</span>
</div>
```
Vytvoríme si jeden `div` element, do ktorého umiestnime vizuálny komponent (*spinner*) a miesto na zobrazenie počtu nedokončených žiadostí.

Kvôli dizajnu by sme chceli docieliť, aby sa element `ajaxLoader` zobrazil roztiahnutý na celú stránku a mal polopriehľadné pozadie. Pre vizuálny komponent zobrazíme jednoduchú animáciu.

```css
#ajaxLoader {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.6);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  color: white;
}
.spinner {
    border: 12px solid #f3f3f3;
    border-top: 12px solid #1970aa;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 2s linear infinite;
    margin: 10px;
}
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
```

Pozíciu `ajaxLoader` elementu sme nastavili na `fixed` a rozmery na `100%`, aby sme dosiahli, že tento prvok bude za každých okolností zobrazený na celú stránku. Farbu pozadia sme nastavili pomocou `rgba` CSS funkcie tak, že farba je čierna a priehľadnosť je nastavená na `60%`. Pre usporiadanie prvkov v tomto elemente používame *flexbox*. Kontajner `ajaxLoader` sme nastavili ako *flexbox* kontajner, ktorý obsahuje prvky zarovnané na stred a jednotlivé prvky sa budú usporadúvať do stĺpca.

Pre zobrazenie *spinner* komponentu sme využili jednoduchú CSS animáciu. Je to `div` element, ktorý sme pomocou `border-radius: 50%` zobrazili ako kruh. Tomuto kruhu sme nechali priehľadné pozadie a nastavili mu `12px` rámček, čím sme dostali kružnicu. Hornému rámčeku sme zmenili farbu na modrú a zvyšným častiam rámčeka sme nechali bielu farbu. Toto spôsobilo, že dostaneme kružnicu, kde 1/4 kruhu má inú farbu ako zvyšok.

Otáčanie kruhu sme dosiahli pomocou jednoduchej animácie, ktorá tento element rotuje o 360 stupňov. Jedna rotácia trvá 2s a je lineárna - kruh sa bude otáčať konštantnou rýchlosťou a animácia sa opakuje donekonečna.

Na deklaráciu animácie sa používa definícia `@keyframes`. Určuje stav elementu v určitých záchytných bodoch. V našom prípade máme definované, že na začiatku animácie bude element otočený o 0 stupňov a na konci o 360 stupňov.

Výsledný *spinner* komponent vyzerá nasledovne:

![Ukážka vzhľadu *spinner* komponentu](images_universal_loader/spinner.png)

### Aplikačná logika komponentu

Keď sa pozrieme do zadania a východzieho kódu, môžeme vidieť, že AJAX žiadosti sa posielajú pomocou funkcie `fetch()`. Našou úlohou teda bude vytvoriť jednoduchú obaľovaciu funkciu (*wrapper*), ktorý nahradí funkciu `fetch()`.

```javascript
async function loaderFetch(...args) 
{
  let loader = document.createElement("div");
  loader.id = "ajaxLoader";
  loader.innerHTML = `<div class="spinner"></div><span id="requestCounter"></span>`;
  document.getElementsByTagName("body")[0].append(loader);
  try {
    return await window.fetch(...args);
  }
  finally {
    //Remove ajax loader from DOM
    loader.remove();
  }
}
```

Funkcia `fetch()` je asynchrónna, preto aj naša funkcia musí byť asynchrónna. Funkcia `loaderFetch()` má variabilný počet parametrov (`...args`), pretože aj samotná funkcia `fetch()` môže byť volaná s rôznymi parametrami. 

> Ak deklarujeme parameter funkcie ako `...parametre`, tak v premennej `parametre` budeme mať pole jednotlivých parametrov, ktoré boli zadané pri volaní. Pri volaní originálnej funkcie `fetch()` tieto parametre potom "rozbalíme" pomocou syntaxe `...args`, vďaka čomu sa originálna funkcia zavolá s rovnakými parametrami ako naša funkcia.

Na začiatku funkcie dynamicky vytvoríme DOM element, ktorý reprezentuje HTML reprezentáciu celého komponentu. Pomocou `document.getElementsByTagName("body")[0].append(loader);` tento vytvorený element vložíme do DOM stránky.

V ďalšej časti máme blok `try / finally`, ktorý používame preto, lebo vždy po skončení asynchrónneho volania potrebujeme skryť celý komponent *AJAX loader* bez ohľadu na to, či sa operácia podarí, alebo nastane výnimka. Vo vetve `try` sa pokúsime zavolať funkciu `fetch()` a asynchrónne počkáme na skončenie žiadosti. Po skončení vrátime odpoveď. V prípade, že sa stiahnutie nepodarí a nastane výnimka, táto sa znovu vyhodí. Vo vetve `finally` odstránime element z DOM.

Ak chceme našu funkciu otestovať, nahradíme vo funkcii `nacitajZdroj()` volanie `fetch()` za `loaderFetch()`.

Po spustení ukážkového príkladu si môžeme všimnúť, že po stlačení tlačidla nám celá obrazovka stmavne a zobrazí sa komponent *spinner*. Problém je ale v tom, že ak náš kód načítava 2 zdroje súčasne, tak sa tento celý komponent *AJAX loader* zobrazí 2x. 

Na ukážke môžeme pozorovať, že po skončení prvej žiadosti sa obrazovka trochu zosvetlí a komponent čaká na skončenie aj druhej žiadosti. Túto situáciu môžeme vyriešiť pridaním počítadla neukončených žiadostí a zobrazovať / skrývať budeme celý komponent *AJAX loader* len v prípade potreby. 

```javascript
let requestCounter = 0;
async function loaderFetch(...args) 
{
  //First ajax request
  if (++requestCounter == 1) {
      let loader = document.createElement("div");
      loader.id = "ajaxLoader";
      loader.innerHTML = `<div class="spinner"></div><span id="request_counter"></span>`;
      document.getElementsByTagName("body")[0].append(loader);
  }
  try {
      return await window.fetch(...args);
  }
  finally {
      //Remove ajax loader from DOM
      if (--requestCounter == 0) {
        document.getElementById("ajaxLoader").remove();
      }
  }
}
```

Zadeklarovali sme si premennú `requestCounter`, ktorú pri každej novej žiadosti o dáta inkrementujeme a po ukončení dekrementujeme. Ak sa spustí prvá žiadosť, zobrazíme komponent *AJAX loader*. Pri skončení poslednej žiadosti komponent odstránime. Oproti pôvodnému riešeniu sme upravili aj vetvu `finally`. Teraz sme na nájdenie elementu na odstránenie použijeme `document.getElementById("ajaxLoader")`. Inak by sa mohlo stať, že odstránime nesprávny *AJAX loader* komponent.

Ďalšou požiadavkou v zadaní bolo vypisovanie počtu prebiehajúcich žiadostí. Tento počet budeme vypisovať do predpripraveného elementu s `id="requestCounter"`.

Pre jednoduchosť si definujeme funkciu `updateRequestCounter()`, ktorú zavoláme pred spustením novej žiadosti a po jej skončení.

```javascript
function updateRequestCounter() {
  document.getElementById("requestCounter").innerText = "Ostáva " + requestCounter;
}
```

### Podpora pre všetky AJAX žiadosti

Pokiaľ by sme chceli aby sa náš *AJAX loader* komponent používal pri všetkých žiadostiach, môžeme funkciu `load()` z objektu `window` nahradiť tou našou. Na to, aby sme to spravili potrebujeme vykonať nasledujúce kroky:

1. Musíme si zapamätať pôvodnú funkciu do nejakej lokálnej premennej.
2. Nahradiť funkciu `load()` tou našou.

Nesmieme zabudnúť, že potom musíme v našej funkcii používať pôvodnú funkciu, nie tú z objektu `window`.

```javascript
let originalFetch = window.fetch;
async function loaderFetch(...args) 
{
    ...
    return await originalFetch(...args);
    ...
}
window.fetch = loaderFetch;
```

Vďaka tomuto kódu už nemusíme používať funkciu `loaderFetch()`, ale môžeme používať priamo `fetch()`. Ďalšou výhodou tohto prístupu je to, že aj iné knižnice, ktoré obsahujú AJAX volania pomocou funkcie `fetch` budú používať náš univerzálny komponent.

### Rady na záver

Aktuálny kód má jeden vedľajší efekt. Do objektu `window` nám pridal nasledovné funkcie a premenné:

- `originalFetch()`
- `loaderFetch()`
- `requestCounter`
- `updateRequestCounter`

Ani jeden z týchto atribútov v princípe nemá čo robiť medzi globálnymi premennými. Riešení tohto problému je niekoľko. Môžeme napríklad použiť OOP a zaobaliť celé riešenie do nejakej triedy.

V prípade takýchto menších skriptov môže byť OOP zbytočne komplikované riešenie. V JavaScripte sa zvykne používať koncept tzv. `Immediately Invoked Function Expression (IIFE)`. Táto IIFE slúži na vytvorenie lokálneho prostredia, v ktorom si môžeme deklarovať vlastné "globálne" premenné, ktoré ale nebudú dostupné mimo nášho kódu.

Hlavnou myšlienkou tohto prístupu je zaobalenie celého kódu do anonymnej funkcie, ktorá sa hneď vykoná:

```javascript
(function() {
  ... 
  Náš kód
  ...
})();
```

Všetky premenné definované v rámci funkcie budú k dispozícií len v danej funkcii. Tento princíp môžeme nájsť vo veľkom množstve JavaScript knižníc. Výsledný kód nášho komponentu môže vyzerať nasledovne:

```javascript
(function() {
  let originalFetch = window.fetch;
  let requestCounter = 0;

  function updateRequestCounter() {
      document.getElementById("requestCounter").innerText = "Ostáva " + requestCounter;
  }

  async function loaderFetch(...args) {
      //First ajax request
      if (++requestCounter == 1) {
          let loader = document.createElement("div");
          loader.id = "ajaxLoader";
          loader.innerHTML = `
            <div class="spinner"></div>
            <span id="requestCounter"></span>`;
          document.getElementsByTagName("body")[0].append(loader);
      }
      updateRequestCounter();
      try {
          return await originalFetch(...args);
      }
      finally {
          //Remove ajax loader from DOM
          if (--requestCounter == 0) {
              document.getElementById("ajaxLoader").remove();
          }
          else {
              updateRequestCounter();
          }
      }
  };

  //Replace original fetch
  window.fetch = loaderFetch;
})();
```

