<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/php/dbtable), [Riešenie](/../../tree/solution/php/dbtable).
> - [Zobraziť riešenie](riesenie.md)
</div>

# Faktoriál (PHP)

## Zadanie

Vytvorte PHP triedu, ktoré bude schopná zobraziť obsah ľubovolnej tabuľky a bude vedieť:

- načíta a zobrazí všetky dáta vo forme HTML tabuľky
- Bude vedieť zoradiť dáta vzostupne a zostupne kliknutím na hlavičku 
- Zobrazené dáta bude vedieť stránkovať

# Riešenie

Predpokladáme, že databázový server je spustený a obsahuje tabuľku s dátami, ktoré sú v súbore `data.sql`.

<div class="hidden">

> Toto riešenie obsahuje všetky potrebné služby v `docker-compose.yml`. Po ich spustení sa vytvorí:
> - webový server, ktory do __document root__ namapuje adresár tejto úlohy s modulom __PDO__. Port __80__ a bude dostupný na adrese [http://localhost/](http://localhost/). Server má pridaný modul pre ladenie [__Xdebug 3__](https://xdebug.org/) nastavený na port __9000__.
> - databázový server s vytvorenou _databázov_ a tabuľkou `users` s dátami na porte __3306__ a bude dostupný na `localhost:3306`. Prihlasovacie údaje sú:
>   - MYSQL_ROOT_PASSWORD: heslo
>   - MYSQL_DATABASE: dbtable
>   - MYSQL_USER: db_user
>   - MYSQL_PASSWORD: db_user_pass
> - phpmyadmin server, ktorý sa automatický nastevený na databázový server na porte __8080__ a bude dostupný na adrese [http://localhost:8080/](http://localhost:8080/)

</div>

Samotné riešenie je rozdelené do niekoľkých častí. 

### Jednoduché pripojenie a čítanie dát z databázy

Ako prvé je potrebné mať v __PHP__ zapnutý modul [__PDO__](https://www.php.net/manual/en/pdo.installation.php). Ten doĺňa do _PHP_ funkcionalitu pre prácu s _relačnou databázou_. Pokiaľ chceme komunikovať s _databázou_ musíme najprv vytvoriť inštanciu triedy `PDO`, ktorá bude následne predstavovať jej prístupový bod.

Pre jej vytvorenie potrebujeme zadať nutné parametre jej [konštrukora](https://www.php.net/manual/en/pdo.construct.php), ktoré sú:

1. __connection string__ - textový reťazec, ktorý obsahuje informácie o tom, kde sa nachádza databázový server
2. __meno__ - textový reťazec obsahujúci názov používateľského konta pre databázu
3. __heslo__ - textový reťazec obsahujúci heslo používateľského konta pre databázu

Pripojenie v našom prípade bude vytvorenie inštancie `PDO` vyzerať nasledovne:

```php
try {
    $pdo = new PDO('mysql:host=db:3306;dbname=dbtable', "db_user", "db_user_pass");
} catch (PDOException $e) {
    die("Error!: " . $e->getMessage());
}
```

Funkcia  [`die()`](https://www.php.net/manual/en/function.die.php) ukončí okamžite beh skriptu a ako výstup vráti vloženú hodnotu. Jej ekvivalentom a správnejším pre použite je [`exit()`](https://www.php.net/manual/en/function.exit.php). Jediným rozdielom je strata "poetickosti" kódu pri použití `exit()`.

Použitie `try-catch` bloku je potrebné pre prípadne odchytenie chyby, ktorá môže nastať pri vytváraní pripojenia na _databázu_. V prípade ak nastane, tak sa výnimka odchytí a používateľovi sa vypíše chybová hláška.

Naša databáza obsahuje tabuľku `users` v ktorej je pridaných _100_ záznamov. Najjednoduchším spôsobom ako ich získať pre použitie v našom _PHP_ skripte bude použiť metódu [`PDO::query()`](https://www.php.net/manual/en/pdo.query.php), ktorá vyžaduje vstupný parameter ktorý predstavuje __SQL dotaz__ v podobe textového reťazca. V našom prípade chceme získať naraz všetky dáta tabuľky `users`. Naš _SQL dotaz_ bude preto veľmi jednoduchý: `SELECT * FROM users`.

Metóda `PDO::query()` vracia výsledok operácie z _databázy_ v podobe inštancie triedy [`PDOStatement`](https://www.php.net/manual/en/class.pdostatement.php) ak databáza nájde výsledok alebo `false` ak nenájde nič. Taktiež je potrebné použiť `try-catch` blok v prípade, že by nastala nejaká chybová situácia.

Ak chceme získať dáta v iterovaťelnej podobe, musíme zavolať metodu [`PDOStatement::fetchAll()`](https://www.php.net/manual/en/pdostatement.fetchall.php). Tá má vstupný parameter, ktorý upresňuje spôsob akým su jednotlivé riadky tabuľky transformované na dáta použiteľné v _PHP_. Na začiatok použijeme hodnotu `PDO::FETCH_ASSOC`, ktorá vráti riadky v podobe asociatívnych polí. 

Následne stačí iba overiť či nám `PDOStatement::fetchAll()` nevrátila hodnotu `false`. A nie, výsledkom je pole polí z celým obsahom tabuľky `users`, ktoré vieme v _PHP_ prechádzať pomocou cyklu [`foreach`](https://www.php.net/manual/en/control-structures.foreach.php). Jednotlivé riadky sú predstavané polom, kde index v danom poli má prestne taký istý názov ako v _databáze_. Ukažka obsahu jedného riadku:

```
Array
(
    [id] => 1
    [name] => Samuel
    [surname] => Hamilton
    [mail] => ornare@sitametante.co.uk
    [country] => Bahrain
)
```

Kód pre jednoduché vypísanie obsahu bude vyzerať nasledovne:

```php
try {
    $pdo = new PDO('mysql:host=db:3306;dbname=dbtable', "db_user", "db_user_pass");
} catch (PDOException $e) {
    die("Error!: " . $e->getMessage());
}

try {
    $sql = 'SELECT * FROM users';
    $result = $pdo->query($sql);

    $users = $result->fetchAll(PDO::FETCH_ASSOC);

    if ($users) {
        echo "<ul>";
        foreach ($users as $user) {
            echo "<li>{$user['name']}</li>";
        }
        echo "</ul>";
    }
} catch (\PDOException $e) {
    die($e->getMessage());
}
```

### Pokročilejšia implementácia

Skúsme teraz navrhnúť lepšiu štruktúru riešenia. Vytvoríme preto ako prvé objekt, ktorý bude predstavovať prístupový bod čisto pre komunikáciu s databázou. Preto v samostatnom súbor vytvoríme triedu `Db`. K tejto triede budeme chcieť pristupovať z rôznych častí kódu a budeme chcieť aby bola pre celu našu aplikáciu vytvorená a používaná jedna jediná jej inštancia. 

Implementujeme do nej návrhový vzor __signleton__. Nakoľko nepoužívame dômyselnejší framework alebo knižnice, implementujeme __singleton__ pomocou statických metód. To z dôvodu, že _PHP_ nepodporuje statický konštruktor. 

Naša trieda `Db` bude obsahovať _privátny statický atribút_ `$db`, ktorý nie je možné inicializovat pri jeho definícií. Musíme preto vytvoriť statickú metódu, ktorá bude slúžiť ako jeho  _getter_.  Pred vrátením hodnoty statického atribútu `Db::$db` métoda najpr overí či existuje a ak nie tak vytvorí novú inštanciu a priradí ju doň. Implementácia _singleton_ bude v _PHP_ vyzerať nasledovne:

```php 
class Db {
    private static Db $db;
    public static function i()
    {
        if (Db::$db == null) {
            Db::$db = new Db();
        }
        return Db::$db;
    }
}
```
