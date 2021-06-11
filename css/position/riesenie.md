<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/css/position), [Riešenie](/../../tree/solution/css/position).

# Pozícia elementov (CSS)

</div>

## Riešenie

Na riešenie úlohy budeme potrebovať vytvoriť HTML súbor a CSS súbor. V HTMl súbore budú základné elementy, ktoré budeme
potrebovať pre riešenie úlohy a CSS súbor bude obsahovať všetky pravidlá. CSS súbor umiestnime do adresára
`css`, aj keď tento príklad nie je rozsiahly, je dobré dodržiavať štandardné umiestnenie súborov. Obrázky umiestnime do
adresára `img`.

#### HTML súbor

Súbor HTML bude obsahovať odkaz na externý CSS súbor, pričom využije relatívnu cestu k adresáru, čím dosiahneme ľahkú
prenositeľnosť celého riešenia:

```html

<link rel="stylesheet" href="css/styl.css">
```

Telo HTML dokumentu bude tvorené kontajnermi pre jednotlivé elementy. V zadaní sa nachádza požiadavka nakresliť jedno
slnko a štyri vtáčiky. Preto vytvoríme spolu 5 kontajnerov a každému z nich pridáme nejakú CSS triedu pomocou
atribútu `class`. Pozadie vložíme do značky body, ale bez nutnosti definovania triedy.

```html

<body>
<div class="slnko"></div>
<div class="bird"></div>
<div class="bird"></div>
<div class="bird"></div>
<div class="bird"></div>
</body>
```

#### CSS súbor

CSS súbor bude obsahovať všetky pravidlá. Najskôr si pripravíme pozadie. Na definovanie pozadia využijeme selektor
značky `body`. Pozadie bude tvorené obrázkom definovaným vlastnosťou `background-image` a nebude sa opakovať
(opakovanie je možné použiť, ak máme obrázok menší ako je kontajner, v ktorom bude umiestnený a takto nastavíme, či sa
bude v osi X a Y sa bude opakovať - dlaždičový efekt). Obrázok roztiahneme na celú plochu tela HTML stránky pomocou
vlastnosti `background-size`. Nastavenie `cover` zabezpečí, že sa obrázok roztiahne na celú plochu kontajnera (v tomto
prípade okna prehlidača), aj keby sa mal obrázok deformovať roztiahnutím, alebo orezať. Element body má štandarne v
prehliadači nastavený okraj 8px z každej strany, aby sme tento okraj zrušili a obrázok mali roztiahnutý na celé okno
nastavíme jeho hodnotu na 0. Na záver musíme ešte nastaviť výšku na 100%, inak sa pozadie neroztiahne na celý vertikálnu
výšku okna prehlidača. Výsledné pravidlo bude vyzerať takto:

```css
body {
    background-image: url("../img/sky.jpg");
    background-repeat: no-repeat;
    background-size: cover;
    margin: 0;
    height: 100%;
}
```

Druhým krokom bude umiestnenie slnka doprostred okna prehliadača. Pomocou selektora triedy definujeme najtrv obrázok
pozadia. Keďže originálny obrázok je väčší (500 x 500 px) nastavením vlastností `width` a `height` na 300px obrázok
zmenšíme. Aj keď toto nie je optimálne riešenie, pretože veľkosť obrázku sa mení až na strane klienta a tým pádom sa cez
sieť prenáša originálny súbor, pre tento jednoduchý príklad toto riešenie bude postačovať. Vlastnosť
`background-size` využijeme na to, aby sa zmenšený obrázok zobrazil celý a pri rozbrazení sa neodrezal. Dôležitým
nastavením je hodnota `absolute` vlastnosti `position`. Týmto nastavením získame možnosť nastavovať pozíciu prvku
kdekoľvek v okne prehliadača pomocou vlastností `top` a `left`. Tieto vlastnosti určujú vzdialenosť od vrchu a ľavej
strany okraja. Obrázok chceme mať vždy uprostred, preto zvolíme hodnotu `50%`, a tak bude pri každej zmene veľkosti okna
slnko v strede. Ak však máme byť úplne presný, v strede bude pravý horný hor obrázku. Ak by sme chceli, aby v strede
okna bol stred obrázku slnka, potrebujeme obrázok posunúť o polovicu jeho veľkosti vľavo a hore. Pomôžeme si CSS
vlastnosťou `transform` a obrázok pomocou `translate` posunieme o 50% jeho výšky, resp. šírky. Pravidlo bude vyzerať
takto:

```css
.slnko {
    background-image: url("../img/sun.png");
    background-size: cover;
    width: 300px;
    height: 300px;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}
```

Po aplikovaní tohto pravidla bude čiastkové riešenie vyzerať nasledovne:

![](images_position/sun.jpg)

Teraz do riešenia doplníme vtáčiky. Pri definovaní štýlu vtáčika využijeme všetky postupy, ako sme použili pri 
obrázku slnka. Keďže obrázok budeme potrebovať na viacerých miestach, nebudeme v tomto pravidle žiadne umiestňovanie,
ale necháme to na iné pravidlá. Jediné, čo nastavíme, bude to, že element bude používať absolútnu pozíciu.

```css
.bird{
    background-image: url("../img/bird.png");
    background-size: cover;
    position: absolute;
    width: 150px;
    height: 150px;
}
```

Ako sme spomínali, budeme potrebovať 4 elementy s obrázkom vtáčika. Mohli by sme teda vytvoriť štyri elementy a 
každý by mal rovnaké CSS vlastnosti. To by však nebol veľmi efektívny spôsob a veľa vlastností by sa zbytočne 
opakovalo. Lepšie bude využiť možnosť definovania viacerých tried pri jednom elemente a tým vlastnosti skombinovať. 
Pripravíme si teda štyri CSS pravidlá, pričom každé bude definovať iné nastavenie pozície prvku:

```css
.top{
    top: 0;
}

.bottom {
    bottom: 0;
}

.left{
    left: 0;
}

.right{
    right: 0;
}
```

Tieto pravidlá potom skombinujeme v HTML atribúte `class`:

```html
		<div class="bird top left"></div>
		<div class="bird top right"></div>
		<div class="bird bottom left"></div>
		<div class="bird bottom right"></div>
```

Takto dosiahneme, že pravidlá sa nebudú zbytočne opakovať a celé riešenie bude prehľadné. Podobným spôsom vytvoríme 
aj efekt otočenie obrázku vtáčika a aplikujeme ho len na tie obázky, ktoré potrebujeme otočiť. Využijem na to CSS 
vlastnosť `transform`, ktorá pomocou funkcie `scaleX()` dokáže otočiť obrázok v požadovanej osi:  

```css
.flip {
    transform: scaleX(-1);
}


```

A opäť triedu pripojíme len k elementom, ktoré potrebujeme otočiť. Výsledný HTML kód použitých potom bude:

```html
		<div class="slnko"></div>
		<div class="bird top left"></div>
		<div class="bird top flip right"></div>
		<div class="bird bottom left"></div>
		<div class="bird bottom flip right"></div>
```

Týmto krokom sme sa dostali až ku koncu riešenia a výsledok je presne taký, ako bol požadovaný v zadaní.

