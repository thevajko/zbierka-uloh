# CSS position

Konečné umiestnenie vykreslenia HTML elementov sa dá ovplyvniť CSS vlastnosťou `position`. Pre korektne pochopenie chovania je dôležité vedieť, čo je [viewport](https://developer.mozilla.org/en-US/docs/Web/CSS/Viewport_concepts). Tá môže obsahovať nasledovné hodnoty:

- `static` - je predvolené zobrazenie. Element sa zobrazuje podľa toho ako je zadefinovaný v _DOM štruktúre_.
- `relative` - element "zaberá" svoje pôvodné miesto ale je možné ho z jeho miesta "posunúť".
- `fixed` - element svoje miesto nezaberá a je "zobrazený vo viewporte". Čo znamená, že sa umiestňuje v ploche "viewportu" a pôsobí ako keby bol nan "prilepený", nakoľko jeho umiestnenie neovplyvňuje scrolovanie.
- `absolute` - element nezaberá miesto a umiestňuje sa v priestore najbližšieho predka, ktorý ma nastavenú vlastnosť `position` na `relative` alebo `absolute`. 
- `sticky` - Tento element sa "prilepí" na najbližšieho predka, ktorý sa dá scrolovať. Dopĺňa tak logiku menu, ktoré "sleduje" scrolloanie používateľa. Hlavný rozdiel medzi `sticky` a `fixed` spočíva v tom, že `sticky` "zaberá miesto" až kým ho scrolovanie nedonúti sa prilepiť. Kiež to `fixed` nezaberá žiadne miesto.

## Nastavenie pozície

Samotné umiestňovanie alebo posúvanie potom prebieha pomocou vlastností, ktoré hovoria ako chceme daný element posunúť od východzej pozície:

- `left` - vzdialenosť od ľavej strany východzej pozície 
- `right` - vzdialenosť od pravej strany východzej pozície
- `top` - vzdialenosť od vrchu východzej pozície
- `bottom` - vzdialenosť od vrchu východzej pozície

Samozrejme je možné použiť aj percentuálne hodnoty. Ako ale zistiť, čo predstavuje východziu pozíciu? 

## Východzia pozícia `relative`

V prípade `relative` posúvate zobrazenie daného prvku relatívne od jeho normálneho miesta, pričom miesto, ktoré zaberá v zobrazení zostane obsadené.

Zmena chovania ale nastáva pokiaľ chcete element posunúť o hodnotu s `%`. Vtedy sa za základ berie rozmer rodičovského elementu. __Tu ale pozor__ aby bolo aby bolo možné používať percentuálne hodnoty pre `top` a `bottom` musí mať rodič zadefinovanú výšku, inač to nebude mať žiaden efekt. 

## Východzia pozícia `absolute`

Element, ktorý ma nastavenú hodnotu `absolute` pozíciujeme vzhľadom na najbližšieho hierarchicky najbližšieho rodiča, ktorý ma nastavenú pozíciu inú ako hodnotu `static`. Miesto, ktoré element zaberá nie je obsadene a zobrazuje sa ako keby "plával" nad obsahom.

V prípade, že sa nenájde vhodný predok, berie sa za základ _viewport_, ale na rozdiel od `fixed` sa prvok nebude hýbať pri scrollovaní.

## Východzia pozícia `fixed`

Východziu pozíciu predstavuje _viewport_, a tak isto sa hodnoty `%` určujú z jeho veľkosti.

## Východzia pozícia `sticky`

V prípade `sticky` je za základ určený rodičovský element, ktorý v sebe obsahuje sticky element a obsah, ktorý je tak veľký, že je ho nutné scrolovať. Pozícia sa dá určiť definovať použitím `left`, `right` a `top`. Hodnota `bottom` spôsobí, že sa element "zmizne". 

Percentuálne hodnoty sa odvíjajú od veľkosti rodiča. Pri hodnotách `left` a `right`, ktoré by mali prvok umiestni mimo rodiča prvok ostane vždy v jeho vnútri.

Nevhodná hodnota pre `top` spôsobí, že sa sticky prvok zobrazí nepoužiteľné.

## Z-index
To v akom poradí sa prekrývajúce prvky zobrazia predvolene určuje ich poradie v akom su uvedené v `HTML` kóde. Pokiaľ chceme nejaký prvok zobraziť nad ostatnými, stačí pokiaľ mu zmeníme hodnotu `CSS` vlastnosti `z-index` na takú aby sa zobrazil nad ostatnými.

Ako hodnotu nastavujeme celé číslo, ktoré môže byť aj záporne. Predvolene majú všetky zobrazené elementy túto hodnotu nastavenú na `0`.

Viac informácií o z-index nájdete [napríklad tu](https://www.w3schools.com/cssref/pr_pos_z-index.asp).


> ## Odkazy
>
> Pre lepšiu predstavu si prejdite nasledovné stránky s príkladmi:
>
>1. [https://www.w3schools.com/css/css_positioning.asp](https://www.w3schools.com/css/css_positioning.asp)
>2. [https://developer.mozilla.org/en-US/docs/Web/CSS/position](https://developer.mozilla.org/en-US/docs/Web/CSS/position)
>3. [https://css-tricks.com/almanac/properties/p/position/](https://css-tricks.com/almanac/properties/p/position/)