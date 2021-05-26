# CSS position

Konečné umiestnenie vykreslenia HTML elementov sa dá ovplyvniť CSS vlastnosťou `position`. Pre korektne pochopenie chovania je dôležité vedieť, čo je [viewport](https://developer.mozilla.org/en-US/docs/Web/CSS/Viewport_concepts). Tá môže obsahovať nasledovné hodnoty:

- `static` - je predvolené zobrazenie. Element sa zobrazuje podľa toho ako je zadefinovaný v _DOM štruktúre_.
- `relative` - element "zaberá" svoje pôvodné miesto ale je možné ho z jeho miesta "posunúť".
- `fixed` - element svoje miesto nezaberá a je "zobrazený vo viewporte". Čo znamená, že sa umiestňuje v ploche "viewportu" a pôsobý akokeby bol nan "prilepený", nakoľko jeho umiestnenie neovlyňuje scrolovanie.
- `absolute` - element nezaberá miesto a umiestnuje sa v priestore najbližieho predka, ktorý ma nastavenú vlastnosť `position` na `relative` alebo `absolute`. 
- `sticky` - Tento element sa "prilepí" na najbližšieho predka, ktorý sa dá scrolovať. Dopĺňa tak logiku menu, ktoré "sleduje" scrolloanie používateľa. Hlavný rozdiel medzi `sticky` a `fixed` spočíva v tom, že `sticky` "zaberá miesto" až kým ho scrolovanie nedonúti sa prilepiť. Kiežto `fixed` nezaberá žiadne miesto.

Samotné umiestňovanie alebo posúvanie potom prebieha pomocou vlastností, ktoré hovoria ako chceme daný element posunúť od východzej pozície:

- `left` - vzdialenosť od ľavej strany východzej pozície 
- `right` - vzdialenosť od pravej strany východzej pozície
- `top` - vzdialenosť od vrchu východzej pozície
- `bottom` - vzdialenosť od vrchu východzej pozície

Samozrejme je možné použiť aj percentuálne hodnoty. Ako ale zistiť, čo predstavuje východziu pozíciu? 

## Umiestnovanie `relative`

V prípade `relative` posúvate zobrazenie daného prvku relatívne od jeho normálneho miesta, pričom miesto, ktoré zaberá v zobrazení zostane obsadené.

Zmena chovania ale nastáva pokiaľ chcete element posunúť o hodnotu s `%`. Vtedy sa za základ berie najbližší rodičovský element, ktorý má position nastavenú inú ako `static`. Pokiaľ sa taký nenájde ako základ sa použije element `<body>`. __Tu ale pozor__ aby bolo aby bolo možné používať percentuálne hodnoty pre `top` a `bottom` musí mať rodič zadefinovanú výšku, inač to nebude mať žiaden efekt. 

## Umiestnovanie `absolute`


## Z-index
To v akom poradí sa prekrývajúce prvky zobrazia predvolene určuje ich poradie v akom su uvedené v `HTML` kóde. Pokiaľ chceme nejaký prvok zobraziť nad ostatnými, stačí pokiaľ mu zmeníme hodnotu CSS vlastnosti `z-index` na takú aby sa zobrazil nad ostatnými.

Ako hodnotu nastavujeme celé číslo, ktoré môže byť aj záporne. Predvolene majú všetky zobrazené elementy túto hodnotu nastavenú na `0`.

Viac informácií o z-index nájdete [napríklad tu](https://www.w3schools.com/cssref/pr_pos_z-index.asp).

___







V prípade nastavenia `position` na `relative` alebo `absolute` sa ako východzia pozícia berie element `<body>`. Umiestnujete teda elementy od jeho ľavej a pravej strany alebo jeho vrchu alebo spodku.

V prípade, že ste v štruktúre nastavili nejakému predkovi alebo predkom `position` na `relative`, `absolute`, `fixed` alebo `sticky` bude sa bra


Pre lepšiu predstavu si prejdite nasledovné stránky s príkladmi:

1. [https://www.w3schools.com/css/css_positioning.asp](https://www.w3schools.com/css/css_positioning.asp)
2. [https://developer.mozilla.org/en-US/docs/Web/CSS/position](https://developer.mozilla.org/en-US/docs/Web/CSS/position)
3. [https://css-tricks.com/almanac/properties/p/position/](https://css-tricks.com/almanac/properties/p/position/)