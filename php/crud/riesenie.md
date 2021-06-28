<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/php/crud), [Riešenie](/../../tree/solution/php/crud).
> - [Zobraziť zadanie](zadanie.md)

# CRUD (PHP)

</div>

## Riešenie

<div class="hidden">

> Toto riešenie obsahuje všetky potrebné služby v `docker-compose.yml`. Po ich spustení sa vytvorí:
> - webový server, ktory do __document root__ namapuje adresár tejto úlohy s modulom __PDO__. Port __80__ a bude dostupný na adrese [http://localhost/](http://localhost/). Server má pridaný modul pre ladenie [__Xdebug 3__](https://xdebug.org/) nastavený na port __9000__.
> - databázový server s vytvorenou _databázov_ a tabuľkou `users` s dátami na porte __3306__ a bude dostupný na `localhost:3306`. Prihlasovacie údaje sú:
>   - MYSQL_ROOT_PASSWORD: db_user_pass
>   - MYSQL_DATABASE: crud
>   - MYSQL_USER: db_user
>   - MYSQL_PASSWORD: db_user_pass
> - phpmyadmin server, ktorý sa automatický nastevený na databázový server na porte __8080__ a bude dostupný na adrese [http://localhost:8080/](http://localhost:8080/)

</div>

Samotné riešenie si rozdelíme na niekoľko častí.

### Príprava databázovej schémy a dát

Na úvod si pripravíme databázovú entitu, nad ktorou budeme robiť zadané operácie. Pripravíme si tabuľku `users`, ktorá
bude obsahovať atribúty `name`, `surname`, `mail` a `country`.

![Schéma DB tabuľky users](images_crud/users-table.png)

DDL na definovanie tejto tabuľky je nasledovné:

```sql
CREATE TABLE `users`
(
    `id`      mediumint(8) unsigned NOT NULL auto_increment,
    `name`    varchar(255) default NULL,
    `surname` varchar(255) default NULL,
    `mail`    varchar(255) default NULL,
    `country` varchar(100) default NULL,
    PRIMARY KEY (`id`)
) AUTO_INCREMENT=1;
```

Do databázy pre testovacie účely vložíme niekoľko záznamov.
![Ukážka záznamov v tabuľke `users`](images_crud/users-data.png)


### Pripojenie k databáze
Pre čítanie dát z databázy existuje v jazyku PHP niekoľko prístupov. Každý DB systém môže mať vlastnú sadu tried (napr. [`mysqli`](https://www.php.net/manual/en/book.mysqli.php) pre MySQL / MariaDB alebo [`pgsql`](https://www.php.net/manual/en/book.pgsql.php) pre PostgreSQL). Okrem toho v PHP existuje unifikované rozhranie PHP Data Objects ([`PDO`](https://www.php.net/manual/en/book.pdo.php)), ktoré sa používa ako unifikovaná nadstavba nad rôznymi DBS.

V našom príklade si ukážeme prístup cez PDO, ktoré je v súčastnosti odporúčané využívať, pretože na rozdiel od ostatných prístupov plne podporuje objektový prístup.

Hlavným prístupovým bodom k databáze je trieda [`PDO`](https://www.php.net/manual/en/class.pdo.php). Táto trieda umožní vytvoriť pripojenie k databáze a následne vykonávanie SQL príkazov. Pre vytvorenie jej inštancie potrebujeme zadať parametre [konštrukora](https://www.php.net/manual/en/pdo.construct.php).

1. __connection string__ - textový reťazec, ktorý obsahuje informácie o tom, kde sa nachádza databázový server, typ servera a názov použitej databázovej schémy.
2. __meno__ - textový reťazec obsahujúci názov používateľského konta pre databázu.
3. __heslo__ - textový reťazec obsahujúci heslo používateľského konta pre databázu.

```php
try {
    $pdo = new PDO('mysql:host=db:3306;dbname=crud', "db_user", "db_user_pass");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    exit("Error!: " . $e->getMessage());
}
```

Takto vytvorená inštancia PDO nás pripojí na `mysql` databázový server na servery `db` bežiacom na porte `3306` s prihlasovacím menom "db_user" a heslom "db_user_pass". V prípade, že sa pripojenie podarí (nesprávne meno heslo, nedostupný db server) ukončíme bez celého skriptu pomocou funkcie  [`exit()`](https://www.php.net/manual/en/function.exit.php).

Pre pohodlnejšiu prácu ešte nastavíme správanie PDO tak, že pri chybe dostaneme výnimku. Od PHP 8 je toto správanie predvolené, takže na PHP8 už `$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);` nieje potrebné.

V aplikácii často pracujeme s rôznymi entitami ale pripájame sa na rovnakú databázu. Dobrou praxou je pre to oddelenie pripojenia k databáze do vlastnej triedy, ktorú môžme implementovať ako singleton, aby sme mali len jedno spoločné pripojenie k databáze. Spravíme si pre to triedu `Db`, ktorá bude zaobaľovať túto funkcionalitu.

```php
class Db {
    private const DB_HOST = "db:3306";
    private const DB_NAME = "crud";
    private const DB_USER = "db_user";
    private const DB_PASS = "db_user_pass";

    private static ?PDO $connection = null;

    public static function conn(): PDO
    {
        if (Db::$connection == null) {
            self::connect();
        }
        return Db::$connection;
    }

    private static function connect() {
        try {
            Db::$connection = new PDO("mysql:host=".self::DB_HOST.";dbname=".self::DB_NAME, self::DB_USER, self::DB_PASS);
            Db::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Databáza nedostupná: " . $e->getMessage());
        }
    }
}
```

Trieda obsahuje jeden statický atribút `$connection` typu `PDO`, v ktorom sa uchováva inštancia pripojenia k databáze. Pripojenie k databáze z predchádzajúceho príkladu sme umiestnili do statickej metódy `Db::connect()`.

Pre prístup k pripojeniu využijeme statickú metódu `Db::conn()`, ktorá vráti (prípadne vytvorí) inštanciu `PDO`.

### Návrh objektovej štruktúry
Pre lepšiu organizáciu kódu si vytvoríme triedu na prácu s databázou (`UserStorage`) a entitnú triedu (`User`). Trieda `User` bude kopírovať dáta v databáze:
```php
class User
{
    public int $id = 0;
    public string $name = "";
    public string $surname = "";
    public string $mail = "";
    public string $country = "";
}
```

Trieda `UserStorage` bude mať metódy na:
- Získanie zoznamu používateľov
- Uloženie používateľa
- Odstránenie používateľa

Vzťahy medzi jednotlivými triedami budú vyzerať nasledovne:
![UML diagram UserStorage](http://www.plantuml.com/plantuml/proxy?cache=no&src=https://raw.githubusercontent.com/thevajko/zbierka-uloh/solution/php/crud/diagram.puml)

### Implementácia UserStorage
Začneme implementáciou metódy `UserStorage::getAllUsers()`. Pre získanie dát z databázy môžeme použiť metódu [`PDO::query()`](https://www.php.net/manual/en/pdo.query.php), ktorá dostane ako parameter SQL príkaz, ktorý vykoná. V našom prípade chceme získať všetky dáta tabuľky `users`, preto môžme použiť jednoduchý select: `SELECT * FROM users`.

Metóda `PDO::query()` vracia výsledok operácie z databázy v podobe inštancie
triedy [`PDOStatement`](https://www.php.net/manual/en/class.pdostatement.php), v prípade ak databáza nájde výsledok alebo `false` ak nenájde nič.

Ak chceme získať dáta v iterovaťelnej podobe, musíme použiť metódu [`PDOStatement::fetchAll()`](https://www.php.net/manual/en/pdostatement.fetchall.php). Tá má vstupný parameter,
ktorý upresňuje spôsob akým su jednotlivé riadky tabuľky transformované. PDO podporuje rôzne módy, napríklad štandardne používaný `PDO::FETCH_ASSOC` vráti dáta v asociatívnom poli, kde kľúčom bude názov stĺpa a hodnotou príslušná hodnota v danom riadku. V našom prípade ale môžme využiť to, že máme k dispozícii entitnú triedu, a prinútiť PDO aby nám dáta vrátilo v týchto entitných triedach použitím módu `PDO::FETCH_CLASS` a uvedením príslušnej triedy `User::class`.

Výsledná metóda na získanie všetkých používateľov bude vyzerať nasledovne:
```php
/**
* @return User[]
*/
public function getAllUsers(): array
{
    return Db::conn()
        ->query("SELECT * FROM users")
        ->fetchAll(PDO::FETCH_CLASS, User::class);
}
```

