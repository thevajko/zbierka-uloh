<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/js-a-css/analog-clock), [Riešenie](/../../tree/solution/js-a-css/analog-clock).

[Zobraziť zadanie](zadanie.md)

# Analógové hodinky (JS, CSS)
</div>

## Riešenie

Pri riešení tejto úlohy budeme používať čistý javascript a vykresovanie pomocou [canvasu](https://www.w3schools.com/html/html5_canvas.asp). Vykreslovať budeme do elementu `<canvas>` a nastavíme mu pevný rozmer `500x500`. Preto naše HTML bude vyzerať nasledovne:

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

_Canvas_ pre vykreslovanie potrebuje zadávať uhly v __radiánoch__. Samozrejme pre nás bude lepšie pokiaľ to budeme môcť zadávať uhly v stupňoch.

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
  ctx.arc(250,250,250,0,DegToRad(360));
  ctx.stroke();
}
```
### Vykreslenie ručičiek

Teraz si vytvoríme funkciu, ktorú budeme používať pre vykreslenie sekundovej, minútovej a hodinovej ručičky. Funkcia musí umožňovať definovať šírku, dĺžku ručičky od stredu a jej uhol.

Predpokladáme, že `<cavas>` má pevne stanovený rozmer, teda východzí bod pre kreslenie ručičiek bude bod `x=250, y=250`. Pre presunitie "pera" canvasu na túto pozíciu použijeme metódu [`CanvasRenderingContext2D.moveTo()`](CanvasRenderingContext2D.moveTo()). 

Následne potrebujeme urobiť z tohto východzieho bodu čiaru na bod, ktorý je definovaný uhlom a vzdialenosťou. Na toto použijeme goniometrické funkcie `Math.cos()` a `Math.sin()` ([viac info tu](https://stackoverflow.com/questions/23598547/draw-a-line-from-x-y-with-a-given-angle-and-length/23598710) alebo učebnica matematiky ;) ). Čiaru do daľšieho bodu nakreslíme pomocou [`CanvasRenderingContext2D.lineTo()`](https://developer.mozilla.org/en-US/docs/Web/API/CanvasRenderingContext2D/lineTo).

Hrúbku čiary upravíme nastavením atribútu [`CanvasRenderingContext2D.lineWidth`](https://developer.mozilla.org/en-US/docs/Web/API/CanvasRenderingContext2D/lineWidth).

Ostáva este posunúť uhol vykreslenia ručičiek, tak aby hodnota `0` zodpovedala pozícií na 12 hodine. To realizujeme jednoducho prirátaním hodnoty `270` k pôvodne zadanému uhlu.

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


### Vykreslenie ciferníka 


