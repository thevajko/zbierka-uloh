<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/js-a-css/fly), [Riešenie](/../../tree/solution/js-a-css/fly).
> - [Zobraziť riešenie](riesenie.md)

# Mucha (JS, CSS)

</div>

## Riešenie

#### HTML súbor

Najprv si vytvoríme hraciu plochu. Tento súbor bude veľmi jednoduchý, nebudeme tu implemdewntovať žiadnu aplikačnú logiku, ani dizajn. V súbore sa budú
nachádzať len elementy, ktoré budú služiť na výpis skóre, zostávajúceho času a tlačidlo *Štart hry*. Všetky elementy, ku ktorým budeme v aplikačnej logike
pristupovať, umiestnime do kontajnerov (elementy `div`) a označíme ich atribútom `id`. HTML kód bude vyzerať nasledovne:

```html

<div class="playground">
    <div id="menu">
        <div>
            Score: <span id="score">0/0</span>
        </div>
        <div>
            Time left: <span id="timer">0</span>
        </div>
        <div id="start">ŠTART HRY</div>
    </div>
</div>
```

#### CSS štýl

Najprv nastavíme vnútorné a vonkajšie okraje na 0, aby hracia plocha bola celé klientske okno prehliadača a všetky elementy mali tieto hodnoty nastavené na 0.

```css
* {
    padding: 0;
    margin: 0;
}
```

Pomocou CSS štýlu si vytvoríme aj hraciu plochu, ktorú roztiahneme na celú šírku klientskeho okna prehliadača. Použijeme na to jednotky `vw` a `vh`, ktoré sa
používajú na určenie relatívnej veľkosti elementu voči oknu prehlidača (*viewport*). 100% šírky okna je `100vw` a 100% výšky je `100vh`.

```css
.playground {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
}
```

Ďalej si v súbore so štýlom si naštýlujeme element, ktorý bude obsahovať obrázok s muchou. Obrázky do CSS vložíme ako obrázok pozadia pomocou
vlastnosti `background-image`. Formát obrázku sme zvolili typu gif, najmä z dôvodu, že obrázok je animovaný a vytvára dojem, že mucha sa mierne hýbe. Veľkosť
obrázku nastavíme na 50 x 50 px a roztiahneme ho na celú šírku elementu (vlastnosť `background-size`). Dôležité je nastaviť vlastnosť `position` na fixed, aby
sme vedeli pomocou Javascriptu muchu zobrazovať na rôznych miestach obrazovky. Štýl pre muchu bude vyzerať takto:

```css
.fly {
    background-image: url("../img/fly.gif");
    background-size: contain;
    width: 50px;
    height: 50px;
    position: fixed;
}
```

Zaujímavou vecou je zmena kurzora na náš vlastný obrázok. Chceli by sme, aby pri hre sa používal obrázok muchlapky:

```css
.fly_killer {
    cursor: url("../img/flykiller.png") 20 20, pointer;
}
```

Ostatné použité štýly len formátujú zobrazenie skóre a času hry. Umiestnime ich na vrch stránky do stredu. A pravidlo `.fly_killer` sa bude používať pri zásahu
muchy a zobrazí krvavú škrvnu.

```css
.fly_killer {
    cursor: url("../img/flykiller.png") 20 20, pointer;
}

#menu {
    background-color: silver;
    border: 1px solid black;
    padding: 10px;
    position: fixed;
    top: 0;
    left: 50%;
}

#menu div {
    display: inline-block;
    border: 1px solid white;
    padding: 4px;
}

#start {
    background-color: red;
    cursor: pointer;
}
```

### Logika hry

Na tomto príklade si ukážeme použitie niektorých techník objektovo orientovaného programovania v jazyku Javascript. Celú hru si rozdelíme na triedy a každá
trieda bude implementovať tú časť aplikačnej logiky, pre ktorú bude určená. Zmena pozície muchy bude definovaná zmenou jej polohy pomocou vlastností `left`
a `top`. Tieto vlastnosti budú určovať polohu vďaka tomu, že element má nastavenú pozíciu na `fixed`. Poloha muchy na obrazovke sa musí pravidelne meniť, aby
hra mala zmysel. Na to bude potrebovať, aby v pravidelných intervalom svoju polohu zmenila. Preto použijeme časovač, ktorý v pravidelných intervaloch zavolá
metódu na zmenu polohy muchy.

Zmyslom hry je trafiť mnuchu, preto musíme nejako vyriešiť, či kliknutie myši bolo na mieste, kde sa mucha nachádza, alebo mimo nej. Jedno riešenie by sa
ponúkalo a to také, že na základe súredníc kliknutia zístíme, či sme klikni do obdĺžnika, kde sa nachádza mucha. To by si však vyžadovalo niekoľko výpočtov a
porovnaní. Jednoduchším riešením je nechať rozhodnutie, či sme klikli na muchu na prehlidač. Stačí, aby sme definovali obsluhu udalosti `onclick` na elemente,
kde sa mucha nachádza. Ak sme klikli na muchu, obsluha udalosti sa sptustí a my možeme hráčovi pripočítať bod a zároveň zobrazíme fľak. Akby fľak nezmizol hneď,
použijeme časovač na pozdržanie jeho vymazania z obrazovky. Ak klikol mimo elemetu s muchov, pripočítame mu o jeden pokus viac.

Celá hra bude limitovaná časom, preto pri začiatku hry spustíme časovač, ktorý začne odpočítavať čas hry. Na konci hry všetky muchy skryjeme a hra môže začať
odznovu.

Implementáciu hry si rozložíme do troch tried: `Timer`, `Fly`, `Game`. Najprv vytvoríme triedu `Timer` ktorá sa bude starať o časovače potrebné pri rôznych
situáciách v hre. Ďalej si vytvoríme triedu `Fly` ktorá bude mať na starosti správanie sa muchy počas hry. A celú hru bude riadiť trieda `Game`, ktorej
zodpovednosťou bude spúšťanie a ukončovanie hry ako aj rátanie bodov hráča. Všetky tieto triedy uložíme do jedného súboru skript.js.

### Trieda Timer

Začneme pomocou triedou Timer, ktorá bude mať na starosti spúštanie časovačov v hre. Časovač budeme potrebovať v hre na viacerých miestach. Ako už bolo
spomínané bude použitý pri presune muchy na iné miesto na obrazovke, na pozdržanie zmazania fľaku po trafenej muche, ako aj na odpočítavanie času hry. Triedu v
Javascripte vytvoríme kľúčovým slovom `class`.

```javascript
class Timer {

}
```

Najskôr si nastavíme všetky atribúty, ktoré bude trieda využívať. Atribúty sa zapisujú do vnútra definície triedy. Pre túto treidu budeme potrebovať
atribút `interval`, čo bude čas v milisekundách definujúci, ako často časovač spúštať funkciu (metódu), ktorú mu nastavíme. Atribút `timerId` bude identifikátor
časovača, ktorý budeme používať na identifikáciu časovača pri jeho rušení, kedže nám v hre bude bežať viac časovačov. Nakoniec atribút `_callback`
bude obsahovať funkciu (alebo metódu triedy), ktorú bude časovač spúšťat. Všimnite si znak _ pre začiatkom atribútu. Keďže k atribútu budeme vytvárať `set`
metódu (angl. *setter*), nemôže sa atribút voľako rovnako aj bude názov metódu. Impementáciu set metódy si ukážeme nejskôr. Všetky atribúty sa zapisujú bez
kľúčového slova `let` alebo `var`. Atribútom je možné priradiť aj nejaké hodnoty, v našom prípade iba `null` pri atribútoch `timerId` a `_callback`.

```javascript
    interval;
timerId = null;
_callback = null;
```

Všetky metódy tejto triedy (sú to bežné funkcie v Javascripte) musíme pridať do vnútra triedy. Každá trieda by mala mať svoj konštruktor, čo je metóda, ktorá za
zavolá pri vzniku inštancie danej triedy a vykoná nastavenie tejto inštancie. V našom prípade len nastavíme interval v milisekundách. Na zápis konštruktora v
Javascripte sa používa kľúčové slovo `constructor` a ako parameter mu pri volaní nastavíme hodnotu intervalu.

```javascript
    constructor(interval = 1000)
{
    this.interval = interval;
}
```

V tejto triede budeme potrebovať dve metódy. Jednu na spustenie časovača a druhú na jeho zastavenie. Spustenie časovača je jednoduchá operácia, ktorá zavolá
metódu `setInterval()` (je to metóda triedy `window`) s parametrami `handler`, čo je buď názov metódy, alebo funkcie, ktorá sa má zavolať, ale v princípe to
može byť ľubovolný Javascript kód a čas v milisekundách, v akom časovom intervale sa pravidelne bude tento kód spúšťať. Pred tým však ešte časovač vypneme, aby
sme eliminovali viacnázobné spustenie toho istého časovača. Do atribútu `timerId` si uložíme vytvorený časovač na neskoršie použitie. Pri tomto zápise si môžete
všimnúť, že na definíciu metód v Javascripte sa nepoužíva kľúčové slovo `function`.

```javascript
    start()
{
    this.stop();
    this.timerId = window.setInterval(this._callback, this.interval);
}
```

Metóda `stop()` bude mať za úlohu zastavenie časovača, aby sa prestal spúštať. Obsahom metódy je len kontrola, či časovač beží (vtedy nemá atribút `timerId`
nastavenú hpodnotu na `null`) a ak beží, tak ho volaním metódy `clearInterval()` zastavíme a atribút `timerId` nastavíme na `null`.

```javascript
    stop()
{
    if (this.timerId != null) {
        window.clearInterval(this.timerId);
    }
    this.timerId = null;
}
```

Poslednou metódou triedy `Timer` je metóda `callback`, ktorá slúži na nastavenie metódy, ktorú bude daný časovač spúšťať. Je to `set` metóda, čo je zrejmé z
použitia kľúčového slova `set`. Metóda v svojom tele len nastaví parameter. Prázdne zátvorky znamenajú, že priraďovaná metóda nemá žiadne parametre a telom
priraďovanej funkcie bude volanie metódy. Spôsob použitia tejto metódy si ukážeme neskôr v príklade.

```javascript
    set
callback(callback)
{
    this._callback = callback;
}
```

### Trieda Fly

Táto trieda bude predstavovať jednu muchu v hre. Na obrazovke bude súčasne zobrazených niekoľko múch a každá z nich bude jedna inštancia triedy mucha. Trieda
bude obsahovať jeden atribút a to `element`, ktorý bude odkaz na DOM element, ktorý zodpovedá muche v HTML dokumente.

```javascript
    element = null;
```

Konštruktor v tejto triede má za úlohu vytvoriť muchu a nastaviť jej, aby v definovanom čase menila svoju pozíciu. Parameter `interval` definuje, ako často sa
zmena polohy bude vykonávať. Na to potrebujeme vytvoriť novú inštanciu triedy Timer, vytvoriť DOM element (pozor toto nie je rovnaká metóda
ako `document.createElement()`) a nastaviť časovaču, že pravidelne definovanom intervale má volať metódu `changePosition()` tejto inštancie muchy. Tu je vidieť
použitie `set` metódy, ktoré sa líši od volania bežnej metódy v tom, že je realizovaná ako priradenie. Na priradenie metódy, ktorá sa bude volať, použijeme **
arrow funkciu**, ktorá celý zápis zjednoduší a spehľadní, navyše nám vo vnutri volanie sprístupní odkaz `this`, inak by sme nemali prístup k inštancii triedy
mucha. KVýsledná implememtácia konštruktora bude vyzerať nasledovne:

```javascript
    constructor(interval = 1000)
{
    this.timer = new Timer(interval);
    this.createElement();
    this.timer.callback = () => this.changePosition();
}
```

Keďže budeme vytvárať viacero elementov muchy súčasne, musíme vytvoriž metódu, ktorá bude vykreslovať element muchy na obrazovku. Metóda bude volať DOM
metódu `document.createElement()`, vytvorenému elementu nastaví CSS triedu `fly` a vygeneruje mu náhodnú pozíciu na obrazovke. Túto metódu budeme volať pri
vytváraní a novom spúšťaní hry, preto muchy najskôr skryjeme a zobrazíme ich, až keď sa hra začne (metóda `hideElement()`). Vytvorený element pripojíme do
dokumnetu DOM metodou `document.body.appendChild()`.

```javascript
    createElement()
{
    this.element = document.createElement('div');
    this.element.className = 'fly';
    this.changePosition();
    this.hideElement();
    document.body.appendChild(this.element);
}
```

Pri vytváraní muchy sme spomenuli metódu `changePosition()`, preto sa jej budeme venovať v tomto odstavci. Úlohou metódy bude nastaviť element muchy na náhodnú
pozíciu. Ako sme spomínali, mucha má nastavavenú pozíciu na `fixed`, preto jej môžeme pomocou `top` a `left` predpísať, kde sa má vykresliť. Na výpočet polohy
použije vygenerovanie náhodného čísla, ktoré bude z rozsahu 0 až šírka klientskej okna prehliadača, resp. 0 až jeho výška. Keďže rozmery budú v `px` na konci
ich pripojíme k vygenerovanej hodnote. Ďalej obrázok náhodne otočíme, aby nebola mucha zobrazené stále rovnakým smerom. Nakoniec ešte odstránime CSS
triedu `fly_killed`, ktorá sa tam objaví, keď muchu trafíme, ale to bude predmetom inej metódu. Nakoniec spustíme

```javascript
    changePosition()
{
    this.element.style.left = Math.random() * (window.innerWidth - this.element.offsetWidth) + "px";
    this.element.style.top = Math.random() * (window.innerHeight - this.element.offsetHeight) + "px";
    this.element.style.transform = 'rotate(' + (Math.random() * 360) + 'deg)';
    this.element.classList.remove("fly_killed");
}
```

Metódy `showElement()` a `hideElement()` ako už ich názov napovedá majú za úloh vykresliť resp. skryť element muchy na obrazovky. Pri zobrazení zároveň
naštartujeme časovač, aby mucha začala meniť svoju pozíciu a pri skrytí muchu tento časovač zrušíme. Metóda `showElement()` ešte navyše elementu muchy vymaže
CSS triedu `fly_killed`, ak náhodou mucha bola už trafená a zobrazil by sa fľak. Riadok `this.element.classList.add("fly_killer");` rieši situáciu, keď je
kurzor nad obrázku a zmenil by sa na obyčajnú šípku, preto ho nastavíme opäť na našu mucholapku. Kód metód bude nasledovný:

```javascript
    // Show the fly and start the timer
showElement()
{
    this.timer.start();
    this.element.classList.remove("fly_killed");
    this.element.classList.add("fly_killer");
    this.element.style.display = "block";
}

// Hide the fly and stop the timer
hideElement()
{
    this.timer.stop();
    this.element.classList.remove("fly_killer");
    this.element.style.display = "none";
}
```

Nakoniec nám zostalo implementovať obsluhu udalosti kliknutia na muchu. Ak hráč klikne na element muchy, skontrolujeme, či už na element nebolo kliknuté (
element vtedy bude obsahovať CSS triedu `fly_killed`) a ak nie, obrázok muchy zmeníme na obrázok fľaku pridaním CSS triedy `fly_killed` a rovnako ako v
predchádzajúcom v predchádzajúcej metóde nastavíme kurzor na našu mucholapku. Potom naštartujeme časovač, aby sa začala mucha zobrazovať na inom mieste.
Nakoniec zavoláme callback, ktorý dostaneme ako parameter. Zmysel tohto kroku si objasníme pri vysvetľovaní Metod v triede game. Ako je zrejmé z kódu tejto
metódy, neriešime v nej počítanie bodov hráča, ale túto zodpovednosť prenecháme na inú triedu (trieda `Game`).

```javascript
set
onClick(callback)
{
    this.element.onclick = () => {
        if (!this.element.classList.contains('fly_killed')) {
            this.element.classList.add("fly_killer");
            this.element.classList.add("fly_killed");
            this.timer.start();
            callback();
        }
    };
}
```

#### Trieda Game

Trieda game bude zodpovedná za riadenie priebehu hry. Bude sa v nej odohrávať naštartovanie hry, ako aj jej ukončenie. Pri vzniku novej hry vytvorí všetky
muchy, ktoré budeme v hre používať, pri trafení muchy zvýši hráčovi skóre, bude mať na starosti odpočítavanie času a po skončení časového limitu skryje všetky
muchy.

Na začiatku triedy si zadefinujeme všetky atribúty, ktoré budeme v hre používať. Atribút `gameDuration` Je nastavené trvanie hry Na 30 sekúnd túto hodnotu je
možné v prípade potreby zmeniť atribúte bude vždy aktuálny počet sekúnd Ktorý sa na začiatku nastaví na hodnotu `gameDuration` a postupne sa bude odpočítavať
atribút `numOfFlies` Definuje počet múch v jednej hre na obrazovke a atribút skóre obsahuje aktuálne skóre hráča počas hry Atribút `totalAttempts` Bude vždy
obsahovať celkový počet kliknutí hráča aby sme vedeli určiť pomer úspešných a neúspešných pokusov. posledný atribút `flies` predstavuje pole múch kde každý
prvok poľa je jedna mucha. Atribút `timer` predstavuje časovač hry ktorý má na starosti odpočítavanie času celej hry.

```javascript
    gameDuration = 30;
gameSeconds = 0;
numOfFlies = 5;
score = 0;
totalAttempts = 0;
flies = [];
```

Konštruktor triedy má za úlohu inicializáciu celej hry. nazačiatku inicializuje časovač hry, tak aby odpočítavali zostávajúce sekundy do konca hry. Viac sa o
tomto časovači dozvieme v metóde gameTick().

```javascript
    constructor()
{
    this.timer.callback = () => this.gameTick();
```

ďalším krokom v konštruktora je vytvorenie obsluhy udalostí DOMContentLoaded. Táto udalosť nastane vtedy keď je v okne prehliadača už stiahnutý celý webový
dokument príslušnej stránky a teda máme istotu že všetky dom elementy sú už na stránke k dispozícii. V tejto chvíli môžeme zadefinovať obsluhu tlačítka štart
slúžiaceho na spustenie hry. to opäť vykonáme s pomocou arraow funkcie aby sme referencie dis dostali do obsluhy v tejto udalosti. ďalej definujeme obsluhu
udalosti kliknutia na plochu, pričom si najskôr vyhľadáme element ktorý má nastavenú CSS triedu Playground. táto obsluha udalostí je implementovaná inline
spôsobom funkcia je priamo definovaná pri samotnej udalosti. je veľmi jednoduchá a slúži len na to aby sme za počítali kliknutie na stránke okrem kliknutí na
tlačítko štart a takisto musíme brať do úvahy, ak hra už skončila, aby počet pokusov nepribúdal.

```javascript
    document.addEventListener("DOMContentLoaded", (event) => {
    document.getElementById("start").onclick = () => this.start();

    document.querySelector('.playground').onclick = (event) => {

        if (event.target.id === 'start') return;
        if (this.gameSeconds > 0) {
            this.totalAttempts++;
            this.redrawScore();
        }
    }
```

poslednou časťou konštruktora je cyklus ktorý preddefinovaný počet múch každú múku vytvorí v poli flies, pričomkaždej muche nastaví náhodný interval zmeny polohy, aby bola hra zaujímavejšia a okrem toho muche nastaví obsluhu udalosti onclick (na volanie metódy flyHit().

```javascript
    for (let i = 1; i <= this.numOfFlies; i++) {
        this.flies [i] = new Fly(758 + Math.random() * 743);
        this.flies[i].onClick = () => this.flyHit();
}
```