<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/php/crud), [Riešenie](/../../tree/solution/php/crud).
> - [Zobraziť zadanie](zadanie.md)

# CRUD (PHP)
</div>

## Riešenie

Predpokladáme, že databázový server je spustený a obsahuje tabuľku s dátami, ktoré sú v súbore `data.sql`.

<div class="hidden">

> Toto riešenie obsahuje všetky potrebné služby v `docker-compose.yml`. Po ich spustení sa vytvorí:
> - webový server, ktory do __document root__ namapuje adresár tejto úlohy s modulom __PDO__. Port __80__ a bude dostupný na adrese [http://localhost/](http://localhost/). Server má pridaný modul pre ladenie [__Xdebug 3__](https://xdebug.org/) nastavený na port __9000__.
> - databázový server s vytvorenou _databázov_ a tabuľkou `users` s dátami na porte __3306__ a bude dostupný na `localhost:3306`. Prihlasovacie údaje sú:
    >   - MYSQL_ROOT_PASSWORD: heslo
>   - MYSQL_DATABASE: crud
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

Použitie [`try-catch`](https://www.php.net/manual/en/language.exceptions.php) bloku je potrebné pre prípadne odchytenie chyby, ktorá môže nastať pri vytváraní pripojenia na _databázu_. V prípade ak nastane, tak sa výnimka odchytí a používateľovi sa vypíše chybová hláška.

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

Naša trieda `Db` bude obsahovať _privátny statický atribút_ `$db`, ktorý nie je možné inicializovat pri jeho definícií. Musíme preto vytvoriť statickú metódu, ktorá bude slúžiť ako jeho  _getter_.  Pred vrátením hodnoty statického atribútu `Db::$db` métoda najpr overí či existuje a ak nie tak vytvorí novú inštanciu a priradí ju doň.

Je ale potrebné všetky ešte statický atribút inicializovať priradením hodnoty `null` a označit jeho typ ako [`nullable`](https://www.php.net/manual/en/migration71.new-features.php).

Implementácia _singleton_ bude v _PHP_ vyzerať nasledovne:

```php
class Db {
    private static ?Db $db = null;
    public static function i()
    {
        if (Db::$db == null) {
            Db::$db = new Db();
        }
        return Db::$db;
    }
}
```

Teraz vytvoríme konštruktor triedy pridaním metódy `__construct()` do triedy `Db`. V konštruktore budeme vytvárať novú inštanciu triedy `PDO`, ktorú priradíme do privátneho atribútu `$pdo`. Taktiež z _connection stringu_ vyberieme dáta pre pripojenie do databázy a umiestnime ich, tiež, do privátnych atribútov.

Úprava triedy `Db`  bude vyzerať nasledovne:

```php
class Db {
    
    // ... 
    
    private PDO $pdo;

    private string $dbHost = "db:3306";
    private string $dbName = "dbtable";
    private string $dbUser = "db_user";
    private string $dbPass = "db_user_pass";

    public function __construct()
    {
        try {
            $this->pdo = new PDO("mysql:host={$this->dbHost};dbname={$this->dbName}", $this->dbUser, $this->dbPass);
        } catch (PDOException $e) {
            die("Error!: " . $e->getMessage());
        }
    }
}
```

Teraz vytvoríme triedu `User` (v samostatnom súbore), ktorá bude reprezentovať jednotlivé dátové riadky a následne ju budeme používať pri práci s databázou. Táto trieda bude obsahovať iba verejne atribúty pomenované rovnako ako sú stĺpce tabuľky `users` v databáze. Trieda bude nasledovná:

```php
class User
{
    public int $id;
    public string $name;
    public string $surname;
    public string $mail;
    public string $country;
}
```

Do triedy `Db` teraz pridáme metódu `Db->getAllUsers()`, ktorej úlohou bude vybrať všetky záznamy z tabuľky `users` a vrátiť ich v poli, kde každý riadok bude predstavovať jedna inštancia triedy `User`.

To docielime tým, že metódu `PDOStatement::fetchAll()`, zavoláme s dvoma parametrami a to s prvou hodnotou `PDO::FETCH_CLASS` a následne s názvom triedy, na ktorú sa budú mapovať dáta jednotlivých riadkov (preto sa musia atribúty triedy volať rovnako ako stĺpce riadkov).

Tu doporučujeme vkladať názov pomocou atribútu [`::class`](https://www.php.net/manual/en/language.oop5.basic.php), ktorý sa bude meniť podla toho ako budeme presúvať trie bu v mennom priestore alebo ju premenujeme. Kód metódy môžeme zapísať nasledovne:

```php
class Db {
    
    // ... 
    
    /**
     * @return User[]
     */
    public function getAllUsers(): array
    {
        try {
            return $this->pdo
                ->query("SELECT * FROM users")
                ->fetchAll(PDO::FETCH_CLASS, User::class);
        }  catch (\PDOException $e) {
            die($e->getMessage());
        }
    }
}
```

Problém pri _PHP_ a iných dynamicko-typovaných jazykoch sa skrýva v komplikovanom získavaní toho, čo nam čo vracia a čo za typ parametrov funkcie alebo metódy potrebujú. PHP postupne túto medzeru vypĺňa ale nie úplne dobre. Naša metóda `getAllUsers()` síce hovorí, že jej výstup je pole ale nemôžeme už zadefinovať čo konkrétne je v poli (aj keď v php podporuje nehomogénne polia...).

Na pomoc nám tu prichádza [_PHPDoc_](https://www.phpdoc.org/), ktorý sa definuje v komentáre metódy a ten hovorí, že metóda vracia pole inštancií typu `User`. Aj keď je tento zápis zdĺhavejší, poskytuje ohromnú výhodu v tom, že vaše _IDE_ vie následné použiť tiet informácie pri automatickom dopĺňaní atribútom a hlavne bude poriadne fungovať _refaktoring_ a to za tu trošku námahy rozhodne stojí.


Následne potrebujeme upraviť náš `index.php`. Ako prvé potrebujeme pridať skrtipty `user.php` a `db.php`, ktoré obsahujú definície našich novo vytvorených tried. Následne si od našej triedy `Db` vypítame pole všetkých používateľov a vypíšeme ich tak ako predtým, akurať už k jednotlivým záznamom budeme pristupivať ako k objektom typu `User`. Kód v `index.php` bude nasledovný: