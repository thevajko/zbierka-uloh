## JS a spustenie skriptov
 
`JS` skripty sa spúšťajú v takom poradí v akom sú deklarované ich `<script>`
 značky. To znamená, že ak máme takého `HTML`:

```html
<html>
    <head>
        <script>
            function log(char){
                console.log(char);    
            }
            log("A");
        </script>
        <script>
            log("B");
        </script>
    </head>
    <body>
        <script>
            log("C");
        </script>
        <h1>Nadpis</h1>
        <script>
            log("D");
        </script>
    </body>
</html>
```
Bude v konzole prehliadača nasledovný výstup:

```
A
B
C
D
```

> Online ukážka: [https://jsfiddle.net/meshosk/mxswvkoj](https://jsfiddle.net/meshosk/mxswvkoj/)

V praxi to znamená, že v dobe vykonania skriptov s výpisom `A`, `B` a `C` v _DOM štruktúre_ neexistuje element `<h1>`. Pre demonštráciu doplníme do funkcie `log()` výpis počtu všetkých elementov `<h1>` v dokumente:

```html
<html>
    <head>
        <script>
            function log(char){
                console.log(char);
                console.log(document.querySelectorAll("h1").length);
            }
            log("A");
        </script>
        <script>
            log("B");
        </script>
    </head>
    <body>
        <script>
            log("C");
        </script>
        <h1>
            Nadpis
            <script>
                log("H1");
            </script>
        </h1>
        <script>
            log("D");
        </script>
    </body>
</html>
```
Keď si stránku po úprave otvoríme v konzole prehliadača bude výstup nasledovný:
```
A
0
B
0
C
0
H1
1
D
1
```

> Online ukážka: [https://jsfiddle.net/meshosk/t4gLrkf2/](https://jsfiddle.net/meshosk/t4gLrkf2/)

Toto, samozrejme, platí aj pre deklaráciu a inicializáciu premenných, tried a funkcií. Je preto vždy potrebné myslieť na správne poradie vkladania jednotlivých skriptov tvoriacich webovú aplikáciu.

### Spustenie logiky po načítaní stránky

Pri použití čistého `JS` existujú dve možnosti ako spustiť logiku až po načítaní stránky:

1. Skript, ktorý spúšťa alebo vykonáva logiku je potrebné umiestniť ako posledný tag pred uzatváracím tagom elementu `<body>`. Prípadne pridať `<script>` ihneď na koniec alebo za požadovaný element. Tu ale nie je úplne zaručené, že skript spustíte až po "naozajstnej" inicializácií _DOM_.
2. Využiť udalosť `onload` na objekte `window`

Zápis teda umiestníme do ľubovolného relevantného `<script>` a zápis vyzerá nasledovne:

```javascript
window.onload = function()  {
    // kod co sa spusti po nacitani dokumentu    
}
```

Náš príklad bude vyzerať takto:

```html
<html>
    <head>
        <script>
            function log(char){
                console.log(char);
                console.log(document.querySelectorAll("h1").length);
            }
            window.onload = function()  {
                log("onload");
            }
        </script>
        <script>
            log("A");
        </script>
    </head>
    <body>
        <script>
            log("B");
        </script>
        <h1>
            Nadpis
        </h1>
        <script>
            log("C");
        </script>
    </body>
</html>
```
Nakoľko sa udalosť `window.onload` spúšťa až po inicializovaní celého _DOM_, je logické, že bude vykonaná ako posledná. V konzole nájdeme tento výstup:

```html
A
0
B
0
C
1
onload
1
```

> ### Upozornenie
> Do `window.onload` __iba priraďujeme__, nejedná sa teda o pridávanie logiky do kontajnera. 


> Online ukážka: [https://jsfiddle.net/meshosk/aojrt485/](https://jsfiddle.net/meshosk/aojrt485/)