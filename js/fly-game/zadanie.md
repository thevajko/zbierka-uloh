<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/js/fly-game), [Riešenie](/../../tree/solution/js/fly-game).
> - [Zobraziť riešenie](riesenie.md)

</div>

# Hra Mucha
<div class="info"> 

**Hlavný jazyk príkladu**: JS

**Ostatné použité jazyky**: HTML, CSS

**Obtiažnosť**: 4/5

**Obsah príkladu**: Objektovo-orientované programovanie v JavaScript, moduly, použitie časovačov, dynamická manipulácia s atribútmi elementu, *arrow* funkcie 
</div>

## Zadanie

Vytvorte hru, kde bude úlohou hráča triafať muchy meniace svoju pozíciu na obrazovke. Hra bude mať časový limit a skóre hráča bude určené počtom múch, ktoré trafil. Aplikácia by mala byť vytvorená v jazyku JavaScript.

1. Hra je určená pre jedného hráča. 
1. Čas jednej hry je vopred stanovený, napr. jedna hra môže trvať 30 sekúnd.
1. Na hracej ploche bude súčasne niekoľko múch, napr. 5.
1. Muchy budú v pravidelných intervaloch meniť svoju polohu na obrazovke.
1. Ak hráč muchu zasiahne, ostane po nej fľak, ktorý za nejaký čas (napr. 1s) zmizne. 
1. Skóre sa skladá z dvoch hodnôt:
    - počet trafených múch
    - celkový počet pokusov
1. Po skončení hry, môže hráč začať novú hru. Skóre sa mu vynuluje.    