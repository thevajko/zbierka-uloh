<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/css/dropdownmenu), [Riešenie](/../../tree/solution/css/dropdownmenu)
> - [Zobraziť riešenie](riesenie.md)
</div>

# Roletové menu
<div class="info"> 

**Hlavný jazyk príkladu**: CSS

**Ostatné použité jazyky**: HTML

**Obťažnosť**: 3/5

**Obsah príkladu**: Tvorba menu, použitie CSS *flexbox*, CSS vlastnosti `display`, `position`, pokročilé CSS selektory, pseudotrieda `:hover`, pseudoelementy  `::before` a `::after`.
</div>

## Zadanie

Cieľom úlohy je vytvoriť roletové menu, ktoré bežne používajú desktopové aplikácie. Menu bude možné rozbaliť smerom dolu (*drop-down menu*) alebo smerom hore (*drop-up menu*). Fungovanie menu ukazuje nasledujúci obrázok:

![Ukážka práce s menu](images_dropdownmenu/menu-fung-00.gif)

Menu musí spĺňať nasledujúce podmienky:

1. Prvá úroveň menu je vždy zobrazená na vrchu stránky.
2. Ďalšie úrovne menu sú viditeľné iba, ak ich používateľ aktivuje kurzorom.
3. Vizuálne treba indikovať, či dané menu obsahuje podmenu.
4. Zvýraznite, ktoré položky menu sú aktivované.
5. Jednotlivé podmenu zobrazte s odlišnou farbou pozadia, napr. budú tmavšie.
6. Drop-down menu sa musí dať jednoduchou úpravou zmeniť na drop-up menu.

Počiatočný HTML dokument obsahuje menu definované pomocou štruktúry elementov a vyzerá nasledujúco:

```html

<div id="menu">
    <ul>
        <li>
            <span>Súbor</span>
            <ul>
                <li>
                    <span>Vytvoriť nový</span>
                    <ul>
                        <li><span>PDF</span></li>
                        <li><span>PPT</span></li>
                        <li><span>TXT</span></li>
                        <li><span>HTML</span></li>
                    </ul>
                </li>
                <li><span>Uložiť</span></li>
                <li>
                    <span>Exportovať</span>
                    <ul>
                        <li>
                            <span>Web</span>
                            ...
```

Všimnite si však, že samotné elementy `ul` a `li` definujú iba štruktúru menu. Textový obsah položky menu je definovaný ako obsah elementu `span`. Vnorenie jednotlivých elementov `ul` v `li` definuje hierarchiu menu.

Na riešenie použite výlučne iba jazyk CSS.
