<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/js-a-css/showhide), [Riešenie](/../../tree/solution/js-a-css/showhide)
> - [Zobraziť zadanie](zadanie.md)

# ShowHide - (JS, CSS)

</div>

## Riešenie

Táto úloha sa dá vyriešiť iba s pomocou JavaScriptu. Prvým krokom bude vytvorenie časti kódu, ktorý sa spustí až po načítaní HTML dokumentu zo servera. To sa tá docieliť nasledovným kódom:

```javascript
window.onload = function () {
    // kód, ktorý sa spustí po načítaní dokumentu    
}
```

Ako prvé po stiahnutí stránky zo servera skript získa kolekciu všetkých `h1` elementov v celom dokumente:

```javascript
window.onload = function () {
    let h1s = document.querySelectorAll("h1");
    for (let i = 0; i < h1s.length; i++) {
        let h1 = h1s[i];
    }
}
```

Funkcia sa pre zobrazovanie a skrývanie má vykonať po kliknutí na element `h1`. Spustenie tejto funkcie realizujeme pomocou priradenia funkcie na obsluhu udalosti [`onclick`](https://www.w3schools.com/jsref/event_onclick.asp).

```javascript
window.onload = function () {
    let h1s = document.querySelectorAll("h1");
    for (let i = 0; i < h1s.length; i++) {
        let h1 = h1s[i];
        h1.onclick = function () {
        }
    }
}
```

Na to, aby sme daný element `p` mohli skrývať a zobrazovať, potrebujeme získať jeho inštanciu. Pre jej získanie môžeme využiť fakt, že všetky elementy HTML dokumentu sú zoradené v hierarchickej stromovej [štruktúre DOM](https://developer.mozilla.org/en-US/docs/Web/API/Document_Object_Model/Introduction).

Keď sa pozrieme na HTML štruktúru úlohy vidíme, že ako element `h1`, tak element `p` sú potomkami práve vždy jedného rodičovského elementu `div`. Existuje viacero prístupov, ako prechádzať elementy v DOM štruktúre. Vzhľadom na to, že v našom prípade jeden `div` obsahuje vždy iba jeden element `h1` a `p`, stačí, ak získame ďalšieho súrodenca elementu `h1` (bude to vždy element `p`). Referencia na [susedný element](https://developer.mozilla.org/en-US/docs/Web/API/Element/nextElementSibling) sa nachádza v atribúte `HTMLElement.nextElementSibling`.

```javascript
window.onload = function () {
    let h1s = document.querySelectorAll("h1");
    for (let i = 0; i < h1s.length; i++) {
        let h1 = h1s[i];
        h1.onclick = function () {
            let p = h1.nextElementSibling;
        }
    }
}
```

Skrývanie a zobrazovanie elementov `p` realizujeme upravením CSS vlastnosti `display`. JavaScript môže priamo modifikovať CSS daného elementu prostredníctvom atribútu [`style`](https://www.w3schools.com/jsref/prop_html_style.asp). Ak chceme nejaký element skryť, urobíme to nasledovne:

```javascript
domElement.style.display = "none";
```

Priradená hodnota je vložená prostredníctvom reťazca a vieme ju priradiť aj získať. To využijeme pri rozhodovaní, či daný element `p` skryjeme, alebo zobrazíme. Ak bude `domElement.style.display` obsahovať hodnotu `none`, znamená to, že je element `p` skrytý a je ho potrebné zobraziť. V inom prípade sa skryje.

```javascript
window.onload = function () {
    let h1s = document.querySelectorAll("h1");
    for (let i = 0; i < h1s.length; i++) {
        let h1 = h1s[i];
        h1.onclick = function () {
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

Posledný krok bude úprava CSS a doplnenie formátovania, ako aj zmena kurzora pre element `h1` tak, aby indikoval používateľovi, že sa naň dá kliknúť. Zmenu kurzora zabezpečíme pridaním CSS vlastnosti `cursor: pointer;`. CSS bude vyzerať nasledovne:

```css
body {
    margin: 1% 20%;
}

h1 {
    cursor: pointer;
}
```