<div class="hidden">

> > ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/js-a-css/showhide), [Riešenie](/../../tree/solution/js-a-css/showhide).

# ShowHide - (JS, CSS)
</div>

## Riešenie
Táto úloha sa da vyriešiť jedine s pomocou javascriptu. Prvým krokom bude vytvorenie logiky, ktorá sa spustí automaticky
po načítani dokumentu. To sa tá docieliť nasledovným kódom:

```javascript
window.onload = function()  {
    // kod co sa spusti po nacitani dokumentu    
}
```

Ako prvé skritp po inicializovaní dokumentu získať kolekciu všetkých `<h1>` elementov v celom dokumente nasledovne:

```javascript
window.onload = function() {
    let h1s = document.querySelectorAll("h1");
    for (let i = 0; i < h1s.length; i++) {
        let h1 = h1s[i];

    }
}
```

Logika sa pre zobrazovanie a skývanie sa ma vykonať po kliknutí na h1. Spustenie tejto logiky realizujeme pomocou
pridenia funkcie pre [udalosť onclick](https://www.w3schools.com/jsref/event_onclick.asp).

```javascript
window.onload = function() {
    let h1s = document.querySelectorAll("h1");
    for (let i = 0; i < h1s.length; i++) {
        let h1 = h1s[i];
        h1.onclick  = function() { 
            
        }
    }
}
```

Na to aby sme daný element p mohli skrývať a zobrazovobať potrebujeme získať jeho inštanciu. Pre jej získanie možeme
využiť fakt, že všetky elementy HTML dokumentu sú zoradené v hierarchickej stromovej [štruktúre DOM](https://developer.mozilla.org/en-US/docs/Web/API/Document_Object_Model/Introduction).

Keď sa pozrieme na HTML štruktúru úlohy vidíme, že ako element H1 tak element P sú podomkami práve vždy jedného
rodičovského elementu div. Existuje viacero prístupov ako prechádzať elementz v DOM štruktúre. Vzhľadom na to, že v našom
prípade jeden div obsahuje vždy iba jeden element h1 a p stačí ak získame ďaľšieho súrodenca elementu h1 (ten bude vždy
element p). Referencia na [susedný element](https://developer.mozilla.org/en-US/docs/Web/API/Element/nextElementSibling)
je v parametre `HTMLElement.nextElementSibling`.

```javascript
window.onload = function() {
    let h1s = document.querySelectorAll("h1");
    for (let i = 0; i < h1s.length; i++) {
        let h1 = h1s[i];
        h1.onclick  = function() {
            let p = h1.nextElementSibling;
        }
    }
}
```

Skrývanie a zobrazovanie <p> elementov realizujeme upravením CSS vlastnosti display. Javascript môže priamo modifikovať
CSS daného elemenu prostredníctvom [atribútu style](https://www.w3schools.com/jsref/prop_html_style.asp).
Ak chceme nejaký element skryť realizujeme to nasledovne:

```javascript
domElement.style.display = "none";
```

Priradená hodnota je vložená prostredníctvom stringu a vieme ju ako priradiť tak aj získať. to využijeme pri rozhodovaní,
či daný element `<p>` skryjeme alebo zobrazíme. Ak bude `domElement.style.display` obsahovať hodnotu `none` znamena to,
že je element p skrytý a je ho potrebné zobraziť v inom prípade sa skryje.

```javascript
window.onload = function()  {
    let h1s = document.querySelectorAll("h1");
    for (let i =0; i < h1s.length; i++) {
        let h1 = h1s[i];
        h1.onclick  = function() {
          let p = h1.nextElementSibling;
          if (p.style.display == "none") {
              p.style.display = "inline";
          } else {
              p.style.display = "none";
          }
        }
    }
}
```

Ako posledný krok upravíme CSS a doplníme formatovanie a zmenu kurzoru pre element h1, tak aby indikoval použivateľovi,
že sa naň dá kliknuť. Zmenu kurzora prevedieme pridaním CSS vlastnosti `cursor: pointer;`. CSS bude vyzerať nasledovne:

```css
body {
    margin: 1% 20%;
}
h1 {
    cursor: pointer;
}
```