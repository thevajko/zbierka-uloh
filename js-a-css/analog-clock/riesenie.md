<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/js-a-css/analog-clock), [Riešenie](/../../tree/solution/js-a-css/analog-clock)
> - [Zobraziť zadanie](zadanie.md)

# Analógové hodinky (JS, CSS)
</div>

## Riešenie

Pri riešení tejto úlohy budeme používať čistý javascript a vykresľovanie pomocou [canvasu](https://www.w3schools.com/html/html5_canvas.asp). Vykresľovať budeme do elementu `<canvas>` a nastavíme mu pevný rozmer `500x500`. Preto naše HTML bude vyzerať nasledovne:

```html
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Title</title>
    </head>
    <body>
        <canvas width="500" height="500"></canvas>
    </body>
</html>
```

_Canvas_ pre vykresľovanie potrebuje zadávať uhly v __radiánoch__. Samozrejme pre nás bude lepšie pokiaľ to budeme môcť zadávať uhly v stupňoch.

Konveziu zo stupňov realizujeme nasledovným prepočtom: `uhol v radianoch = uhol v stupňoch * PI / 180`, kde `PI / 180 = 0.01745329251`. Vzhľadom na to, kde prepočet chceme použiť môžeme vytvoriť nasledovnú konverznú funkciu :

```javascript
function DegToRad(d)
{
    return d * 0.01745;
}
```

Ako prvé nakreslíme kruh, ktorý bude vypĺňať cely obsah elementu `<canvas>` a bude zarovnaný na jeho stred. Na to potrebujeme ale získať referenciu na `HTMLElement` a `context` pomocou ktorého vieme s javascriptu naň kresliť. To realizujeme nasledovným kódom:

```javascript
window.onload = function(){
    let canvas = document.querySelector("canvas");
    let ctx = canvas.getContext("2d");
}
```

Kreslenie kruhu sa realizuje použitím metódy contextu [`CanvasRenderingContext2D.arc()`](https://developer.mozilla.org/en-US/docs/Web/API/CanvasRenderingContext2D/arc). Táto metóda potrebuje zadať _polohu stredu_, _rádius_, _začiatočný_ a _konečný uhol_ kreslenia. Pred samotnou definíciou metódy `arc()` je potrebné povedať canvas contextu, že chceme definovať novú postupnosť krokov pre vykreslenie pomocou [`CanvasRenderingContext2D.beginPath()`](https://developer.mozilla.org/en-US/docs/Web/API/CanvasRenderingContext2D/beginPath). Následne až môžeme definovať čo chceme kresliť. Toto ale nestačí, lebo sme iba definovali ako sa ma čo nakresliť. Samotný pokyn pre vykreslenie realizujeme pomocou [`CanvasRenderingContext2D.stroke()`](https://developer.mozilla.org/en-US/docs/Web/API/CanvasRenderingContext2D/stroke), ktorá na základe definícií vykreslí požadovanú grafiku do elementu `<canvas>`.

V našom prípade bude kód, ktorý nakreslí kruh, vyzerať nasledovne:

```javascript
window.onload = function(){
  let canvas = document.querySelector("canvas");
  let ctx = canvas.getContext("2d");

  ctx.beginPath();
  ctx.arc(250, 250, 250, 0, DegToRad(360));
  ctx.stroke();
}
```
Výsledkom skriptu je nasledovný kruh:

![](.analog_clock_images/aclock-one.png)

### Vykreslenie ručičiek

Teraz si vytvoríme funkciu, ktorú budeme používať pre vykreslenie sekundovej, minútovej a hodinovej ručičky. Funkcia musí umožňovať definovať šírku, dĺžku ručičky od stredu a jej uhol.

Predpokladáme, že `<cavas>` má pevne stanovený rozmer, teda východzí bod pre kreslenie ručičiek bude bod `x=250, y=250`. Pre presunitie "pera" canvasu na túto pozíciu použijeme metódu [`CanvasRenderingContext2D.moveTo()`](CanvasRenderingContext2D.moveTo()). 

Následne potrebujeme urobiť z tohto východzieho bodu čiaru na bod, ktorý je definovaný uhlom a vzdialenosťou. Na toto použijeme goniometrické funkcie `Math.cos()` a `Math.sin()` ([viac info tu](https://stackoverflow.com/questions/23598547/draw-a-line-from-x-y-with-a-given-angle-and-length/23598710) alebo učebnica matematiky ;) ). Čiaru do daľšieho bodu nakreslíme pomocou [`CanvasRenderingContext2D.lineTo()`](https://developer.mozilla.org/en-US/docs/Web/API/CanvasRenderingContext2D/lineTo).

Hrúbku čiary upravíme nastavením atribútu [`CanvasRenderingContext2D.lineWidth`](https://developer.mozilla.org/en-US/docs/Web/API/CanvasRenderingContext2D/lineWidth).

Ostáva ešte posunúť uhol vykreslenia ručičiek, tak aby hodnota `0` zodpovedala pozícií na 12 hodine. To realizujeme jednoducho prirátaním hodnoty `270` k pôvodne zadanému uhlu. 

```javascript
function DrawWatchHand(ctx, uhol, length, width){
    let x = 250;
    let y = 250;
    let angl = DegToRad(uhol + 270);

    ctx.beginPath();
    ctx.lineWidth = width;
    ctx.moveTo(x, y);
    ctx.lineTo(x + length * Math.cos(angl), y + length * Math.sin(angl));
    ctx.stroke();
}
```

Logiku vykreslenia ciferníka presunieme do funkcie `DrawCircle()`:

```javascript
function DrawCircle(ctx){
    ctx.lineWidth = 2;
    ctx.beginPath();
    ctx.arc(250, 250, 250, 0, DegToRad(360));
    ctx.stroke();
}
```

Teraz si skúsime vykresliť cifetník a nejaké ručičke aby sme si otestovali správnosť logiky nasledovne: 

```javascript
window.onload = function(){
  let canvas = document.querySelector("canvas");
  let ctx = canvas.getContext("2d");

  DrawCircle(ctx);
  DrawWatchHand(ctx, 0, 200, 5);
  DrawWatchHand(ctx, 90, 210, 1);
  DrawWatchHand(ctx, 190, 100, 15);
}
```

Výsledná kresba by mala vyzerať nasledovne:

![](.analog_clock_images/aclock-02.png)

### Vykreslenie ciferníka 

Pre vykreslenie hodinových a minútovích značiek vytvoríme novú funkciu `DrawLineMarker()`. Tá bude obsahovať mierne upravenú logiku funkcie `DrawWatchHand()`.

Rozdiel bude v tom kde sa bude daná značka začínať vykreslovať. Minútova alebo hodinová značka je čiara okolo ciferníka, ktorá smeruje smerom do jeho strehu, pričim jej dĺžka je krátka.

Pri vykreslovaní teda začíname v strede, následne sa pod daným uhlom posunieme a až následne kreslíme čiaru v danej dĺžke až po kraj ciferníka. 

Funkcia `DrawLineMarker()` bude vyzerať nasledovne:

```javascript
function DrawLineMarker(ctx, uhol, markerLength){

    let angl = DegToRad(uhol + 270);
    let r = 250 - markerLength;

    let sx = 250  + r * Math.cos(angl);
    let sy = 250 + r * Math.sin(angl);

    ctx.beginPath();
    ctx.lineWidth = 1;
    ctx.moveTo(sx,sy );
    ctx.lineTo(sx + markerLength * Math.cos(angl), sy + markerLength * Math.sin(angl));
    ctx.stroke();
}
```

Môžeme ju otestovať nasledovným kódom:

```javascript
window.onload = function(){
  let canvas = document.querySelector("canvas");
  let ctx = canvas.getContext("2d");

  DrawCircle(ctx);
  DrawLineMarker(ctx, 0, 10);
  DrawLineMarker(ctx, 10, 20);
  DrawLineMarker(ctx, 20, 30);
  DrawLineMarker(ctx, 40, 40);
}
```
Vykreslí sa:

![](.analog_clock_images/aclock-03.png)


## Vykreslenie ciferníka

Samotné vykreslenie ciferníka budeme vytvárať v samostatnej funkcií `MakeTick()`. Jej úlohou bude vykresliť aktuálny lokálny čas na ciferník.

Začneme najprv vykreslením hranice ciferníka zavolaním `DrawCircle()`. Následne vytvoríme cyklus, ktorým budeme vykresľovať jednotlivé hodinové a minútové značky. Vieme, že kruh má 360 stupňov. Má __12 hodinových značiek__ a každý hodinový úsek je rozdelený na __5 minútových úsekov__. Tým pádom kreslíme značku každých `360 / 12 / 5 = 6` stupňov.

Môžeme to pre prehľadnosť zapísať ako cyklus `for` s nastavením `for(let i = 0 ; i < (360/6) ; i++)`. 

Ostáva nám už iba oddeliť hodinové značky od minútových pomocou rôznej veľkosti. Hodinová značka má byť vykreslená každých `360 / 12 = 30` stupňov. To môžeme, kontrolovať podmienkou či je aktuálna hodnota premennej `i` _deliteľná_ hodnotou `30` bezozvyšku podmienkou `if (i*6 % 30 == 0)` a v prípade ak áno, nakreslíme značku dlhšiu.

Kód bude vyzerať následovne:

```javascript
function MakeTick(ctx){
   
    DrawCircle(ctx);
    // cifernik
    for(let i = 0 ; i < (360 / 6) ; i++) {
        if (i * 6 % 30 == 0) {
            DrawLineMarker(ctx, i*6, 30)
        } else {
            DrawLineMarker(ctx, i*6, 10)
        }
    }
}
```
Ciferník bude vyzerať následovne:

![](.analog_clock_images/aclock-04.png) 

Teraz doplníme do zobrazenia ručičky a ich jednotlive uhly vyrátame nasledove:

- __Sekundová ručička__ - sekúnd je v minúte __60__, tým pádom nám stačí akutálny počet sekúnd vynásobiť hodnotou `360 - 60 = 6`. Prepočet sekúnd na uhol sekundovej ručičky môžeme zapísať ako `sekundy * 6`. 
- __Minútová ručička__ - hodina má __60__ minút, čo tvorí priestor minúty __6__ stupňov.  Pozíciu musíme ešte doplniť o posun sekúnd tak, že __6__ rozdelíme na 60 sekúnd t.j. `6/60 = 0.1`. Prepočet minút a sekúnd na uhol minútovej ručičky môžeme zapísať ako `minúty * 6 + sekundy * 0.1`. 
- __Hodinová ručička__ - hodiny, klasicky, majú na ciferníku maximálnu hodnotu hodín __12__, teda hodina má `360 / 12 = 30` stupňov. Pre upresnenie pozície ešte prirátame posun o minúty, kďe minúty budeme násobiť `30 / 60 = 0.5`. Prepočet hodín a minút na pozíciu hodinovej ručičky 12 hodinových hodín môžeme zapísať ako `hodiny * 30 + minuty *0.5`. 


V javascripte získame aktuálny čast vytvorením novej inštancie triedy [`Date`](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Date). Tá obsahuje atribúty `Date.getSeconds()`,`Date.getMinutes()` a `Date.getHours()`. 

`Date.getHours()` síce vracia hodnotu v 24 hodinovom formáte, ale kedže sa táto hodnota preratúva na uhol nebude to mať žiaden vplyv na výsledne zobrazenie. 

Funkcia `MakeTick()` bude po doplnení: 

```javascript
function MakeTick(ctx){
   
    DrawCircle(ctx);
    // cifernik
    for(let i = 0 ; i < (360 / 6); i++) {
        if (i * 6 % 30 == 0) {
            DrawLineMarker(ctx, i * 6, 30)
        } else {
            DrawLineMarker(ctx, i * 6, 10)
        }
    }
    let time = new Date();
    DrawWatchHand(ctx, time.getSeconds() * 6, 210, 1);
    DrawWatchHand(ctx, time.getMinutes() * 6 + time.getSeconds() * 0.1, 180, 3);
    DrawWatchHand(ctx, time.getHours() * 30 + time.getMinutes() * 0.5, 150, 5);
}
```
Hodiny sa zobrazia nasledovne:

![](.analog_clock_images/aclock-05.png)

Ako posledné potrebujeme aby sa funkcia `MakeTick()` spušťala každú sekundu a vytvoril sa tak dojem, že sa "hodiny idú". To docielime periodickým spúštaním pomocou [`setInterval()`](https://www.w3schools.com/jsref/met_win_setinterval.asp) každú sekundu.

Budeme však musieť `<canvas>` pred každým prekreslením vyčistiť (celý premaľovať na bielo) pomocou [`CanvasRenderingContext2D.clearRect()`](https://developer.mozilla.org/en-US/docs/Web/API/CanvasRenderingContext2D/clearRect).

Upravená funkcia `MakeTick()`:

```javascript
function MakeTick(ctx){

    ctx.clearRect(0, 0, 500, 500);
    
    DrawCircle(ctx);
    // cifernik
    for(let i = 0 ; i < (360 / 6); i++) {
        if (i * 6 % 30 == 0) {
            DrawLineMarker(ctx, i * 6, 30)
        } else {
            DrawLineMarker(ctx, i * 6, 10)
        }
    }
    let time = new Date();
    DrawWatchHand(ctx, time.getSeconds() * 6, 210, 1);
    DrawWatchHand(ctx, time.getMinutes() * 6 + time.getSeconds() * 0.1, 180, 3);
    DrawWatchHand(ctx, time.getHours() * 30 + time.getMinutes() * 0.5, 150, 5);
}
```

A spúšťanie v `window.onload`:

```javascript
window.onload = function(){
    let canvas = document.querySelector("canvas");
    let ctx = canvas.getContext("2d");
    
    setInterval( function() {
        MakeTick(ctx);
    }, 1000);
    MakeTick(ctx);
}
```

Výsledok:

![](.analog_clock_images/aclock-01.gif)