<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/php/data-table), [Riešenie](/../../tree/solution/php/data-table)
> - [Zobraziť zadanie](zadanie.md)

# Dátová tabuľka (PHP)

</div>

## Riešenie

<div class="hidden">

Predpokladáme, že databázový server je spustený a obsahuje tabuľku s dátami, ktoré sú v súbore `data.sql`.

> Toto riešenie obsahuje všetky potrebné služby v `docker-compose.yml`. Po ich spustení sa vytvorí:
> - webový server, ktorý do __document root__ namapuje adresár tejto úlohy s modulom __PDO__. Port __80__ a bude dostupný na adrese [http://localhost/](http://localhost/). Server má pridaný modul pre ladenie [__Xdebug 3__](https://xdebug.org/) nastavený na port __9000__.
> - databázový server s vytvorenou _databázou_ a tabuľkou `users` s dátami na porte __3306__ a bude dostupný na `localhost:3306`. Prihlasovacie údaje sú:
>   - MYSQL_ROOT_PASSWORD: heslo
>   - MYSQL_DATABASE: dbtable
>   - MYSQL_USER: db_user
>   - MYSQL_PASSWORD: db_user_pass
> - phpmyadmin server, ktorý sa automatický nastevený na databázový server na porte __8080__ a bude dostupný na adrese [http://localhost:8080/](http://localhost:8080/)

</div>

Samotné riešenie je rozdelené do niekoľkých častí.

### Pripojenie k databáze

Úlohou tohto príkladu je zobrazenie dát z databázy. Na pripojenie k databáze využijeme modul PDO. Vytvoríme si triedu `Db`, ktorá bude sprostredkovať pripojenie na databázu. Táto trieda bude mať statickú metódu, ktorá vráti inštanciu `PDO`. Účelom tejto triedy je iba sprostredkovať tú istú inštanciu `PDO` pre komunikáciu s databázou, nič iné. 

<div style="page-break-after: always;"></div>

Trieda bude vyzerať nasledujúco:

```php
class Db {
    private const DB_HOST = "db:3306";
    private const DB_NAME = "dbtable";
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

V ďalšom kroku si skúsime vytvoriť jednoduchú entitu, ktorú budeme ukladať do databázy:

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

V našom prípade ide o triedu `User`, ktorá reprezentuje osobu. Pre túto entitu si v DB vytvoríme tabuľku takto:

```sql
CREATE TABLE `users`
(
    `id`      mediumint(8) unsigned NOT NULL auto_increment,
    `name`    varchar(255) default NULL,
    `surname` varchar(255) default NULL,
    `mail`    varchar(255) default NULL,
    `country` varchar(100) default NULL,
    PRIMARY KEY (`id`)
);
```

Dáta do našej DB tabuľky si môžeme pripraviť sami, alebo môžeme využiť niektorý z on-line generátorov. Napríklad generátor [*filldb.info*](http://filldb.info/) umožňuje po vložení schémy automaticky vygenerovať dáta pre našu tabuľku `users`.

Pre prístup k dátam v databáze si vytvoríme triedu `UserStorage`, ktorá bude obsahovať metódu `getAll()`, ktorej úlohou bude vybrať všetky záznamy z tabuľky `users` a vrátiť ich v poli, kde každý riadok bude predstavovať jednu inštanciu triedy `User`.

<div class="end">

```php
class UserStorage {
    /**
     * @return User[]
     */
    public function getAll(): array
    {
        try {
            return Db::conn()
                ->query("SELECT * FROM users")
                ->fetchAll(PDO::FETCH_CLASS, User::class);
        }  catch (\PDOException $e) {
            die($e->getMessage());
        }
    }
}
```
</div>

### Jednoduchý výpis dát

V ďalšom kroku upravíme súbor `index.php`. Ako prvé potrebujeme vložiť skripty `User.php`, `Db.php` a `UserStorage.php`, ktoré obsahujú definície našich novovytvorených tried. Následne si od triedy `UserStorage` vypýtame pole všetkých používateľov a vypíšeme ich pomocou cyklu. Kód v `index.php` bude nasledujúci:

```php
<?php

require "Db.php";
require "User.php";
require "UserStorage.php";

$storage = new UserStorage();
$users = $storage->getAll();

if ($users) {
    echo "<ul>";
    foreach ($users as $user) {
        echo "<li>{$user->name}</li>";
    }
    echo "</ul>";
}
```

### Výpis dát do tabuľky

Aby sme dodržali rozdelenie aplikačnej logiky po logických celkoch, vytvoríme novú triedu `Table`, ktorej úlohou bude výpis dát DB tabuľky z databázy do HTML tabuľky a doplnenie podpornej logiky na zoraďovanie, stránkovanie a správu jednotlivých záznamov.

Ako prvé vytvoríme zobrazenie všetkých dát vo forme HTML tabuľky. Na to budeme potrebovať získať názvy stĺpcov tabuľky v databáze. My však na mapovanie dát používame triedu `User`, stačí nám preto získať zoznam atribútov tejto triedy.

PHP má funkciu [`get_object_vars()`](https://www.php.net/manual/en/function.get-object-vars.php), ktorá vie získať tieto údaje vo forme poľa. Index výsledku je názov verejných inicializovaných atribútov a hodnota je hodnota daného atribútu aktuálnej inštancie. Musíme preto upraviť triedu `User` a doplniť predvolené hodnoty:

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

Do triedy `Table` pridáme privátnu metódu `renderHead()`, ktorej účelom bude vytvoriť čisto iba hlavičku HTML tabuľky. Ako prvé získame pole atribútov z inštancie triedy `User`. Následne vytvoríme a inicializujeme premennú `$header`, ktorá slúži ako "zberač" generovaného výstupu.

Následne v cykle `foreach` prechádzame pole atribútov a index vkladáme ako obsah `th` elementu. Výsledok pred vrátením zabalíme do elementu `tr`. Kód metódy `renderHead()` bude nasledujúci:

```php
class Table
{
    private function renderHead() : string {
        
        $attribs = get_object_vars(new User());

        $header = "";

        foreach ($attribs as $attribName => $value) {
            $header .= "<th>{$attribName}</th>";
        }

        return "<tr>{$header}</tr>";
    }
}
```

Pridáme ďalšiu verejnú metódu `render()`, ktorá zostaví HTML tabuľku vo forme textového reťazca. Aktuálne iba vloží výsledok metódy `renderHead()` do elementu `table`. 

Kód bude vyzerať nasledujúco:

```php
class Table
{
    public function render() : string
    {
        return "<table border=\"1\">{$this->renderHead()}</table>";
    }

    private function renderHead() : string {
        // ... 
    }
}
```

Teraz upravíme skript `index.php`, pridáme načítanie skriptu `Table.php` a upravíme kód takto:

```php
<?php

require "Db.php";
require "User.php";
require "UserStorage.php";
require "Table.php";

$usersTable = new Table();

echo $usersTable->render();
```

Výsledkom skriptu je HTML tabuľka zatiaľ iba s hlavičkou. Do triedy `Table` pridáme privátnu metódu `renderBody()`, ktorá bude generovať samotné riadky s dátami, opäť vo forme reťazca pre jej výstup.

Ako prvé opäť potrebujeme získať zoznam atribútov. Kód ich získania vyberieme a vložíme do samostatnej metódy `getColumnAttributes()`, nakoľko ho budeme používať na dvoch miestach. Túto metódu budeme volať často a jej výstup bude vždy rovnaký, preto si pri jej prvom zavolaní uložíme výsledok do privátneho atribútu `$columnAttribs` a ak bude mať hodnotu, vrátime tú. 

<div style="page-break-after: always;"></div>

Kód bude vyzerať takto:

```php
class Table 
{
 
     // ...
 
    private ?array $columnAttribs = null;
    private function getColumnAttributes() :  array
    {
        if ($this->columnAttribs == null) {
            $this->columnAttribs = get_object_vars(new User());
        }
        return $this->columnAttribs;
    }
    
    // ...
}
```

Teraz musíme upraviť metódu `renderHead()` tak, aby používala novovytvorenú metódu `getColumnAttributes()` nasledujúco:

```php
class Table 
{
    // ...
    
    private function renderHead() : string {
        $header = "";
        foreach ($this->getColumnAttributes() as $attribName => $value) {
            $header .= "<th>{$attribName}</th>";
        }
        return "<tr>{$header}</tr>";
    }

    // ...
}
```

V metóde `renderBody()` najprv inicializujeme lokálnu premennú `$body`, do ktorej budeme postupne zbierať jednotlivé riadky tabuľky. V ďalšom kroku vyberieme všetky dáta z&nbsp;tabuľky `users` vo forme poľa do premennej `$users`, ktoré budeme prechádzať v cykle.

Na začiatku každej iterácie priradíme do premennej `$tr` prázdny reťazec. Následne prechádzame pole s atribútmi vrátenými z `$this->getColumnAttributes()`. 

V nasledujúcom cykle sa ukladá pri iterácii do premennej `$attribName` hodnota indexu, ktorý predstavuje názov parametra. V PHP je možné použiť hodnotu v premennej pri odkazovaní sa na atribút objektu. Jednoduchá ukážka:

```php
class Test { 
    public int $hodnota = 5;
}

$o = new Test();
$a = "hodnota";
echo $o->$a; // 5
```

Tento princíp použijeme pri vypisovaní dát z objektov, ktoré dostaneme z databázy. Takto pridáme všetky hodnoty ako textový reťazec do premennej `$tr`. Po prechode všetkými atribútmi umiestnime obsah premennej `$tr` do `$body`. Po spracovaní všetkých dát z&nbsp;databázy vrátime obsah `$body` ako výsledok metódy.

<div class="end">

```php
class Table
{
    // ...
    private function renderBody() : string
    {
        $userStorage = new UserStorage();
        $body = "";
        $users = $userStorage->getAll();
        foreach ($users as $user) {
            $tr = "";
            foreach ($this->getColumnAttributes() as $attribName => $value) {
                $tr .= "<td>{$user->$attribName}</td>";
            }
            $body .= "<tr>$tr</tr>";
        }
        return $body;
    }
    // ...
}
```
</div>

Následne pridáme do metódy `render()` metódu `renderBody()`:

```php
class Table
{
    // ...
    public function render() : string
    {
        return "<table border=\"1\">{$this->renderHead()}{$this->renderBody()}</table>";
    }
    // ...
}
```

### Implementácia zoraďovania dát

Aby sme mohli tabuľku zoraďovať, musíme vedieť, podľa ktorého stĺpca máme tabuľku zoradiť. Túto informáciu obsahujú elementy `a` v podobe parametrov GET, ktoré sú pridané sa na konci URL adresy. 

**GET parametre** sa oddeľujú od zvyšku adresy znakom `?`. Ak máme napríklad URL adresu `http://localhost/?order=country`, tak tá obsahuje parameter `order` s hodnotou `country`. V prípade viacerých parametrov ich oddeľujeme znakom `&` napríklad `http://localhost/?order=country&direction=desc`.

Ešte by sme chceli poznamenať, že maximálna dĺžka URL adresy je 2,048 znakov vrátane HTTP GET parametrov. Rozhodne neodporúčame posielať veľké množstvo dát práve cez GET parametre. Na takéto zasielanie slúži odoslanie cez HTTP POST metódu. 

Na prenos informácie o tom, podľa ktorého stĺpca budeme zoraďovať, budeme používať GET parameter `order`. Musíme preto upraviť metódu `renderHead()`, kde upravíme zostavovanie jednotlivých elementov `th` tak, že samotný názov hlavičky umiestnime do elementu `a`. Tomu do atribútu `href` pridáme GET parameter `order`, ktorého hodnota bude jeho názov. Upravený kód je:

```php
class Table
{
    // ...
    private function renderHead() : string {
        $header = "";
        foreach ($this->getColumnAttributes() as $attribName => $value) {
            $header .= "<th><a href=\"?order={$attribName}\">{$attribName}</a></th>";
        }
        return "<tr>{$header}</tr>";
    }
    // ...
}
```

Tabuľka sa zobrazí s "klikateľnými" názvami stĺpcov v hlavičke. Teraz musíme doplniť logiku na strane servera na zoraďovanie. Predtým ale potrebujeme získať odoslané parametre. Spracovanie GET parametrov umiestnime do triedy `Table`, nakoľko sa parametre týkajú výlučne tabuľky samotnej a tá preto potrebné dáta musí získať sama. Na úroveň databázy ich potom budeme predávať pomocou parametrov metód. Umiestnením do konštruktora docielime nastavenie parametrov ešte pred spustením získavania dát.

Informácia o tom, ako sa má tabuľka zoradiť, bude uložená v privátnom atribúte `$orderBy`, ktorý inicializujeme hodnotou prázdneho textového reťazca. Táto hodnota bude znamenať, že tabuľka nie je nijako zoradená.

Parametre GET jazyk PHP automaticky ukladá do *superglobálnej* premennej [$_GET](https://www.php.net/manual/en/reserved.variables.get.php). Tú tvorí asociatívne pole, kde index je názov parametra a hodnota je jeho hodnota. My očakávame, že v tomto poli bude prítomný index `order`, ktorý tam ale byť nemusí. Z&nbsp;tohto dôvodu použijeme operátor typu [*Null coalescing operator*](https://www.php.net/manual/en/migration70.new-features.php), ktorý vracia prvý parameter, ak porovnávaná hodnota existuje a druhý, ak nie.

Úprava triedy `Table` bude takáto:

<div class="end">

```php
class Table
{
    private string $orderBy = "";
    public function __construct()
    {
        $this->orderBy = ($_GET['order'] ?? "");
    }
    
    // ...
}
```
</div>

Teraz musíme upraviť metódu `UserStorage::getAll()` a doplniť do nej vstupný parameter `$sortedBy`, ktorý bude mať predvolenú hodnotu opäť nastavenú ako prázdny reťazec. Vyberáme všetky dáta pomocou SQL dopytu `SELECT * FROM users` a ak chceme pridať zoradenie, musíme pridať klauzulu `ORDER BY` s názvom stĺpca a smerom, akým chceme dáta zoradiť.

Názov stĺpca budeme mať vo vstupnej premennej `$sortedBy` a zoraďovať budeme zatiaľ iba jedným smerom `ASC`. Zoradenie sa pridáva na koniec pôvodného SQL a musíme overiť, či sa zoraďovať vôbec má. 

Preto najprv skontrolujeme, či vstupná premenná `$sortedBy` obsahuje hodnotu a zoradenie do SQL pridáme iba v tom prípade, ak ju má. Upravený kód bude takýto:

```php
class UserStorage
{
    /**
     * @return User[]
     */
    public function getAll($sortedBy = ""): array
    {
        $sql = "SELECT * FROM users";

        if ($sortedBy) {
            $sql = $sql . " ORDER BY {$sortedBy} ASC" ;
        }

        try {
            return Db::conn()
                ->query($sql)
                ->fetchAll(PDO::FETCH_CLASS, User::class);
        }  catch (\PDOException $e) {
            die($e->getMessage());
        }
    }
}
```

Touto úpravou však vnášame zraniteľnosť tým, že do SQL dopytu vkladáme priamo hodnotu s *GET parametra* `order`. Naša aplikácia je náchylná na útoky typu [*SQL injection*](https://www.w3schools.com/sql/sql_injection.asp).

Pokiaľ vkladáme hodnoty, vieme hodnoty zabezpečiť proti tomuto útoku pomocou použitia [*PDO prepared statements*](https://code.tutsplus.com/tutorials/why-you-should-be-using-phps-pdo-for-database-access--net-12059). To sa však týka iba hodnôt a nie je možné ich použiť na pridávanie názvov tabuliek alebo názvov stĺpcov. To si budeme musieť ošetriť sami.

Najjednoduchším spôsobom bude preto overiť, či hodnota z GET parametra `order` zodpovedá jednému z názvov stĺpcov, ktoré nám vie vrátiť metóda `Table::getColumnAttributes()`. Pridáme preto do triedy `Table` novú privátnu metódu `isColumnNameValid()`, ktorá bude overovať správnosť hodnoty. Jej kód bude nasledujúci:

```php
class Table
{
    // ...

    private function isColumnNameValid($name) : bool {
        return array_key_exists($name, $this->getColumnAttributes());
    }
    
    // ...
}
````

Potom pridáme overenie do konštruktora triedy `Table` tak, že v prípade nesprávnej hodnoty sa na zoradenie použije prázdny textový reťazec:

```php
class Table
{
    public function __construct()
    {
        $this->orderBy = ($this->isColumnNameValid(@$_GET['order']) ? $_GET['order'] : "");
    }
    // ...
}
````

Teraz potrebujeme upraviť metódu `Table::renderBody()` tak, aby sa pri volaní metódy `UserStorage::getAll()` do nej vkladal parameter `$this->orderBy`. Po úprave bude jej kód nasledujúci:

```php
class Table
{
    // ...
    
    private function renderBody() : string
    {
        $body = "";
        $userStorage = new UserStorage();
        $users = $userStorage->getAll($this->orderBy);

        foreach ($users as $user) {
            $tr = "";
            foreach ($this->getColumnAttributes() as $attribName => $value) {
                $tr .= "<td>{$user->$attribName}</td>";
            }
            $body .= "<tr>$tr</tr>";
        }
        return $body;
    }
}
```

Zoraďovanie tabuľky by malo fungovať takto:

![Zoraďovanie stĺpca tabuľky po kliknutí na názov atribútu](images_data-table/dbtable-01.gif)

### Obojstranné zoraďovanie

Obojstranné zoraďovanie bude fungovať tak, že prvým kliknutím na hlavičku stĺpca sa najprv zoradí jedným smerom a následne, keď naň klikneme opäť, zoradí sa v opačnom poradí. Budeme musieť preto pridať nový GET parameter `direction`, ktorý:

1. V prípade, že nebude prítomný alebo bude obsahovať inú hodnotu ako `DESC`, zoradí tabuľku podľa daného stĺpca vzostupne.
2. Ak bude prítomný a bude obsahovať hodnotu `DESC`, zoradí danú tabuľku zostupne.

Do triedy `Table` pridáme nový privátny atribút `$direction` a v konštruktore budeme zisťovať jeho prítomnosť v `$_GET`:

```php
class Table
{
     private string $orderBy = "";
     private string $direction = "";

    public function __construct()
    {
        $this->orderBy = ($this->isColumnNameValid(@$_GET['order']) ? $_GET['order'] : "");
        $this->direction = $_GET['direction'] ?? "";
    }
   
    // ... 
}
```

Teraz pridáme do metódy `UserStorage::getAll()` nový vstupný parameter `$sortDirection` a nastavíme mu predvolenú hodnotu vstupu na prázdny textový reťazec. Následne doplníme kontrolu, či vstupný parameter `$sortDirection` obsahuje hodnotu `DESC` a až vtedy do lokálnej premennej `$direc` pridáme hodnotu `DESC` a opačnom prípade do nej priradíme `ASC` (zabránime tak možnému zneužitiu hodnoty GET parametra `direction`). Upravený kód tejto metódy bude vyzerať nasledujúco:

```php
class UserStorage
{
    /**
     * @return User[]
     */
    public function getAll($sortedBy = "", $sortDirection = ""): array
    {
        $sql = "SELECT * FROM users";

        if ($sortedBy) {
            $direc = $sortDirection == "DESC" ? "DESC" : "ASC";
            $sql = $sql . " ORDER BY {$sortedBy} {$direc}" ;
        }

        try {
            return Db::conn()
                ->query($sql)
                ->fetchAll(PDO::FETCH_CLASS, User::class);
        }  catch (\PDOException $e) {
            die($e->getMessage());
        }
    }
}
```

Do metódy `Table::renderBody()` doplníme parameter na zoraďovanie:

```php
class Table
{
    // ...
    private function renderBody() : string
    {
        $body = "";
        $userStorage = new UserStorage();
        $users = $userStorage->getAll($this->orderBy, $this->direction);

        foreach ($users as $user) {
            $tr = "";
            foreach ($this->getColumnAttributes() as $attribName => $value) {
                $tr .= "<td>{$user->$attribName}</td>";
            }
            $body .= "<tr>$tr</tr>";
        }
        return $body;
    }
   
    // ... 
}
```

Poslednú úpravu vykonáme v metóde `Table::renderHead()`, kde musíme nastaviť hodnotu GET parametra `direction` na `DESC` iba v prípade, ak bol daný stĺpec už zoradený, inak nastavíme hodnotu tohto parametra na prázdny textový reťazec. Úprava bude takáto:

```php
class Table
{
    // ...
    private function renderHead() : string {
        $header = "";
        foreach ($this->getColumnAttributes() as $attribName => $value) {
            $direction = $this->orderBy == $attribName && $this->direction == "DESC" ? "" : "DESC";
            $header .= "<th><a href=\"?order={$attribName}&direction={$direction}\">{$attribName}</a></th>";
        }
        return "<tr>{$header}</tr>";
    }
  
    // ... 
}
```

Tabuľka sa bude zoraďovať takto:

![Obojsmerné zoraďovanie stĺpca tabuľky](images_data-table/dbtable-02.gif)

### Stránkovanie výsledkov

Stránkovanie môžeme implementovať jednoducho pomocou klauzuly [*SQL limit*](https://www.w3schools.com/php/php_mysql_select_limit.asp). Na zostavenie potrebujeme vedieť:

1. Koľko záznamov sa má zobraziť na jednej stránke.
2. Ktorá stránka sa aktuálne zobrazuje.

Budeme preto používať ďalší GET parameter `page`, ktorého hodnota bude predstavovať `offset` hodnotu pre `limit` v SQL dopyte. Vzhľadom na zväčšujúci sa počet parametrov, bude najlepšie vytvoriť metódu v triede `Table`, ktorá nám uľahčí generovanie URL pre `a` elementy.

Vytvorime si preto v triede `Table` novú privátnu metódu `prepareUrl()`. Táto metóda bude mať vstupný parameter, ktorý bude typu pole. Index tohto poľa bude predstavovať názov GET parametra a jeho hodnota bude obsahovať hodnotu parametra. Toto pole bude predstavovať parametre, ktorých hodnota sa má upraviť alebo pridať, ak nebudú existovať.

V prvom kroku si vytvoríme kópiu superglobálnej premennej `$_GET` do lokálnej premennej `$temp`, nakoľko toto pole budeme pravdepodobne modifikovať. Následne prechádzame vstupnú premennú `$params`, kde v cykle `foreach` používame ako index, tak aj hodnotu. Ak má táto premenná nejaké hodnoty, priradíme ich do lokálnej premennej `$a`.

Samotný reťazec GET parametrov zostavíme zavolaním funkcie [http_build_query()](https://www.php.net/manual/en/function.http-build-query.php) a doplníme ešte oddelenie GET parametrov v URL adrese pomocou znaku `?`. Kód metódy bude nasledujúci:

```php
class Table
{
    // ...

    private function prepareUrl($params = []): string
    {
        $temp = $_GET;
        if ($params){
            foreach ($params as $paramName => $paramValue){
                $temp[$paramName] = $paramValue;
            }
        }
        return "?".http_build_query($temp);
    }
  
    // ... 
}
```

Teraz upravíme generovanie hlavičky v metóde `Table::renderHead()`. V cykle najprv inicializujeme pole `$hrefParams` a doplníme do neho parameter `order` aj s hodnotou. Potom budeme kontrolovať, či už je tabuľka zoradená podľa aktuálneho stĺpca. Ak áno, pridáme do poľa index `direction` s hodnotou `DESC`, inak mu nastavíme prázdny textový reťazec.

Upravíme ešte generovanie `href` parametra pre element `a` tak, aby používal metódu `Table::prepareUrl()`. Úprava bude takáto:

<div class="end">

```php
class Table
{
    // ...

    private function renderHead() : string {
        $header = "";
        foreach ($this->getColumnAttributes() as $attribName => $value) {

            $hrefParams = ['order' => $attribName];

            if ($this->orderBy == $attribName && $this->direction == ""){
                $hrefParams['direction'] = "DESC";
            } else {
                $hrefParams['direction'] = "";
            }

            $header .= "<th><a href=\"{$this->prepareUrl($hrefParams)}\">{$attribName}</a></th>";
        }
        return "<tr>{$header}</tr>";
    }
  
    // ... 
}
```

</div>

Môžeme pokračovať pridaním stránkovania. Do triedy `Table` pridáme privátne atribúty:

1. `$pageSize` - definuje, koľko záznamov sa bude zobrazovať na jednej stránke.
2. `$page` - určuje, na ktorej stránke sa aktuálne nachádzame, predvolená hodnota bude 0 - na prvej.
3. `$itemsCount` - obsahuje počet záznamov tabuľky.
4. `$totalPages` - je celkový počet stránok vzhľadom na počet záznamov.

Ako prvé získame dáta z GET parametra `page`. Na získanie hodnoty vytvoríme novú privátnu metódu `getPageNumber()`, v ktorej budeme hodnotu tohto parametra aj validovať. Kontrolovať budeme tieto podmienky:

1. Je hodnota typu `int`? Ak nie, vrátime hodnotu `0`.
2. Je hodnota menšia ako `0`? Ak áno, vrátime hodnotu `0`.
3. Je hodnota väčšia ako *maximálny počet stránok*? Ak áno, vrátime hodnotu `0`.

Pred získaním a overovaním stránkovania musíme doplniť do triedy `UserStorage` metódu `getCount()`, ktorá vráti celkový počet záznamov v databázovej tabuľke `users`. To zrealizujeme dopytom `SELECT count(*) FROM users` nasledujúco:

```php
class UserStorage
{
    // ...
    public function getCount() : int
    {
        return DB::conn()->query("SELECT count(*) FROM users")->fetchColumn();
    }
    // ... 
}
```

Nasleduje získanie aktuálnej stránky:

```php
class Table
{
    // ...

    private int $pageSize = 10;
    private int $page = 0;
    private int $itemsCount = 0;
    private int $totalPages = 0;
    // ...
    
    public function __construct()
    {
        $this->orderBy = ($this->isColumnNameValid(@$_GET['order']) ? $_GET['order'] : "");
        $this->direction = $_GET['direction'] ?? "";

        $this->page = $this->getPageNumber();
    }
    
    private function getPageNumber(): int
    {
        $userStorage = new UserStorage();
        $this->itemsCount = $userStorage->getCount();
        $page =  intval($_GET['page'] ?? 0);
        $this->totalPages = ceil($this->itemsCount / $this->pageSize);
        if (($page < 0) || $page > $this->totalPages){
            return 0;
        }
        return $page;
    }
  
    // ... 
}
```

Ďalším krokom je vytvorenie metódy, ktorá generuje HTML kód s odkazmi na zmenu zobrazenej stránky na inú. V triede `Table` vytvoríme novú privátnu metódu `renderPaginator()`. V nej vytvoríme pre každú stránku pomocou cyklu element `a` s&nbsp;patričnou hodnotou GET parametra `page`:

```php
class Table
{
    // ...
    private function renderPaginator() : string {
        $r = "";
        for ($i = 0; $i < $this->totalPages; $i++){
            $href = $this->prepareUrl(['page' => $i]);
            $r .= "<a href=\"{$href}\">{$i}</a>";
        }

        return "<div>$r</div>";
    }
    // ... 
}
```

Zobrazíme stránkovanie naspodku tabuľky:

```php
class Table
{
    // ...
    public function render() : string
    {
        return "<table border=\"1\">{$this->renderHead()}{$this->renderBody()}</table>". $this->renderPaginator();
    }
    // ... 
}
```

Teraz upravíme metódu `UserStorage::getAll()` tak, aby bolo do nej možné vložiť parametre definujúce, z ktorej stránky sa majú záznamy zobraziť. Pridáme dva vstupné parametre `$page` a `$pageSize` s predvolenými hodnotami `0` a `10`. Následne rozšírime SQL dopyt o klauzuly [`LIMIT` a `OFFSET`](https://www.sqltutorial.org/sql-limit/).  *Offest* definuje, koľko záznamov sa má preskočiť a ich počet získame vynásobením `$page` a `$pageSize`. Upravený kód bude:

```php
class UserStorage
{
    // ...
    /**
     * @return User[]
     */
    public function getAll($sortedBy = "", $sortDirection = "", $page = 0, $pageSize = 10): array
    {
        $sql = "SELECT * FROM users";

        if ($sortedBy) {
            $direc = $sortDirection == "DESC" ? "DESC" : "ASC";
            $sql = $sql . " ORDER BY {$sortedBy} {$direc}" ;
        }

        $page *= $pageSize;
        $sql .= " LIMIT {$pageSize} OFFSET {$page}";

        try {
            return DB::conn()
                ->query($sql)
                ->fetchAll(PDO::FETCH_CLASS, User::class);
        }  catch (\PDOException $e) {
            die($e->getMessage());
        }
    }
}
```

Teraz môžeme doplniť odovzdanie parametrov o stránke pre zobrazenie do `Table::renderBody()` nasledujúco:

```php
class Table
{
    // ...
    private function renderBody() : string
    {
        $body = "";
        $userStorage = new UserStorage();
        $users = $userStorage->getAll($this->orderBy, $this->direction, $this->page, $this->pageSize);

        foreach ($users as $user) {
            $tr = "";
            foreach ($this->getColumnAttributes() as $attribName => $value) {
                $tr .= "<td>{$user->$attribName}</td>";
            }
            $body .= "<tr>$tr</tr>";
        }
        return $body;
    }
    // ... 
}
```

Pri zmene zoradenia je dobré nastaviť zobrazenú stránku na prvú. To urobíme jednoducho tak, že v metóde `Table::renderHead()` pridáme do lokálnej premennej `$hrefParams` index `page` s hodnotou `0`:

```php
class Table
{
    // ...

    private function renderHead() : string {
        $header = "";
        foreach ($this->getColumnAttributes() as $attribName => $value) {

            $hrefParams = [
                'order' => $attribName,
                'page' => 0
            ];

            if ($this->orderBy == $attribName && $this->direction == ""){
                $hrefParams['direction'] = "DESC";
            } else {
                $hrefParams['direction'] = "";
            }

            $header .= "<th><a href=\"{$this->prepareUrl($hrefParams)}\">{$attribName}</a></th>";
        }
        return "<tr>{$header}</tr>";
    }
    // ... 
}
```

Ako posledné pridáme štýlovanie k tlačidlám stránkovača, aby sme vedeli používateľovi zobraziť, ktorú stránku má aktuálne zobrazenú. Upravíme preto metódu `Table::renderPaginator()` tak, aby elementu `a` aktuálne zobrazenej stránky pridal do atribútu `class` triedu `active`:

```php
class Table
{
    // ...
    private function renderPaginator() : string {

        $r = "";
        for ($i = 0; $i < $this->totalPages; $i++){
            $href = $this->prepareUrl(['page' => $i]);
            $active = $this->page == $i ? "active" : "";
            $r .= "<a href=\"{$href}\" class=\"{$active}\">{$i}</a>";
        }

        return "<div>$r</div>";
    }
}
```

Upravíme ešte súbor `index.php` tak, aby sme mohli doplniť CSS pre stránkovač:

```php
<?php
$usersTable = new Table();
?><html>
<head>
    <style>
        div a {
            display: inline-block;
            margin: 4px;
            padding: 4px;
            border: 1px solid black;
        }
        a.active {
            background-color: #949494;
        }
    </style>
</head>
    <body>
        <?php echo $usersTable->render(); ?>
    </body>
</html>
```
<div style="page-break-after: always;"></div>

Tabuľka sa bude zobrazovať takto:

![Stránkovanie dát v tabuľke](images_data-table/dbtable-03.gif)

### Filtrovanie

Ako prvé pridáme možnosť filtrovania do triedy `UserStorage`. Filtrovanie do SQL dopytu pridáva podmienky s operátorom `LIKE` do klauzuly `WHERE`. Musíme určiť, čo chceme vyhľadávať a v ktorom stĺpci. Taktiež rovnakú filtráciu musíme pridať pri získavaní celkového počtu záznamov, aby sa zobrazoval správny počet strán v stránkovači.

Z tohto dôvodu ako prvé vytvoríme v triede `UserStorage` novú privátnu metódu `getFilter()`, ktorej úlohou bude vytvorenie podmienky na filtrovanie a tú následne pridáme ako do získania celkového počtu záznamov v metóde `UserStorage::getCount()`, tak aj do získavania samotných dát v metóde `UserStorage::getAll()`.

Metóda `getFilter()` bude obsahovať jeden vstupný parameter, a to hodnotu, podľa ktorej chceme výsledky filtrovať. Samotnú podmienku na filtrovanie metóda zostaví iba, ak nebude táto vstupná premenná prázdna.

Nakoľko musíme pre každý stĺpec, v ktorom chceme vyhľadávať, uviesť samostatnú podmienku, môžeme si vytvoriť pole, v ktorom budú názvy stĺpcov pre vyhľadávanie a následne v cykle postupne podmienku zostavovať.

Zoznam stĺpcov je umiestnený v premennej `$searchableColumns` a jednotlivé podmienky budeme ukladať ako pole reťazcov do premennej `$search`. To pospájame do jedného textového reťazca pomocou PHP funkcie [`implode()`](https://www.php.net/manual/en/function.implode.php). Doplníme klauzulu `WHERE` a z každej strany reťazca doplníme medzeru, aby sme sa vyhli syntaktickej chybe vo výslednom SQL dopyte.

SQL dopyt umožňuje za znakom `%` definovať ľubovoľnú postupnosť znakov medzi pevne stanovenými znakmi v hľadanom výraze. Používatelia sú ale zvyknutí skôr použiť znak `*`. Z tohto dôvodu môžeme v premennej `$filter` vymeniť všetky znaky `*` za znak `%` pomocou PHP funkcie [`str_replace()`](https://www.php.net/manual/en/function.str-replace.php).

Kód metódy bude nasledujúci:

```php
class UserStorage
{
    // ...
    private function getFilter($filter = "")
    {
        if ($filter) {
            $filter = str_replace("*","%", $filter);
            $searchableColumns = ["name", "surname", "mail"];
            $search = [];
            foreach ($searchableColumns as $columnName) {
                $search[] = " {$columnName} LIKE '%{$filter}%' ";
            }
            return " WHERE ". implode(" OR ", $search). " ";
        }
        return "";
    }
    // ...
}
```

Najprv filtráciu doplníme do metódy `UserStorage::getCount()` a doplníme vstupnú premennú kvôli filtrovaniu:

```php
class UserStorage
{
    // ...
    public function getCount($filter = "") : int
    {
        return Db::conn()
            ->query("SELECT count(*) FROM users" . $this->getFilter($filter))
            ->fetchColumn();
    }
    // ...
}
```

Teraz modifikujeme metódu `UserStorage::getAll()` a doplníme ju na správne miesto tak, ako to určuje predpis na zostavenie SQL dopytu:

```php
class UserStorage
{
    public function getAll($sortedBy = "", $sortDirection = "", $page = 0, $pageSize = 10, $filter = ""): array
    {
        $sql = "SELECT * FROM users";
        $sql .= $this->getFilter($filter);

        if ($sortedBy) {
            $direc = $sortDirection == "DESC" ? "DESC" : "ASC";
            $sql = $sql . " ORDER BY {$sortedBy} {$direc}" ;
        }
        $page *= $pageSize;

        $sql .= " LIMIT {$pageSize} OFFSET {$page}";

        try {
            return DB::conn()
                ->query($sql)
                ->fetchAll(PDO::FETCH_CLASS, User::class);
        }  catch (\PDOException $e) {
            die($e->getMessage());
        }
    }
}
```

Ešte potrebujeme upraviť triedu `Table` tak, že jej pridáme nový privátny atribút `$filter` a do jej konštruktora pridáme získanie hodnoty GET parametra `filter`.

Aby sme predišli možnému útoku typu *SQL injection* bude stačiť, ak v hodnote GET parametra `filter` vymažeme všetky znaky `'`, nakoľko hľadaný výraz je zadávaný ako reťazec medzi znakmi `'` v SQL dopyte. Upravený konštruktor bude:

```php
class Table
{
    // ...
    private string $filter = "";
    
    public function __construct()
    {
        $this->orderBy = ($this->isColumnNameValid(@$_GET['order']) ? $_GET['order'] : "");
        $this->direction = $_GET['direction'] ?? "";
        $this->filter =  str_replace( "'", "",$_GET['filter'] ?? "");

        $this->page = $this->getPageNumber();
    }
    //...
}
```

Ako prvú upravíme metódu `Table::getPageNumber()`. Jediné, čo v nej vykonáme, je pridanie parametra, podľa ktorého budeme filtrovať, do volania metódy `UserStorage::getCount()`:

<div class="end">

```php
class Table
{
    // ...
    private function getPageNumber(): int
    {
        $userStorage = new UserStorage();
        $this->itemsCount = $userStorage->getCount($this->filter);
        $page =  intval($_GET['page'] ?? 0);
        $this->totalPages = ceil($this->itemsCount / $this->pageSize);
        if (($page < 0) || $page > $this->totalPages){
            return 0;
        }
        return $page;
    }
    //...
}
```
</div>

To istú úpravu vykonáme v metóde `Table::renderBody()`:

```php
class Table
{
    // ...
    private function renderBody() : string
    {
        $body = "";
        $userStorage = new UserStorage();
        $users = $userStorage->getAll($this->orderBy, $this->direction, $this->page, $this->pageSize, $this->filter);

        foreach ($users as $user) {
            $tr = "";
            foreach ($this->getColumnAttributes() as $attribName => $value) {
                $tr .= "<td>{$user->$attribName}</td>";
            }
            $body .= "<tr>$tr</tr>";
        }
        return $body;
    }
    //...
}
```

Tým pádom máme pripravený kód na filtrovanie. Teraz vytvoríme novú privátnu metódu `Table->renderFilter()`, ktorá vráti HTML formulár pre zadanie filtrovaného výrazu.

Do formulára nedopĺňame žiadne extra atribúty ani nastavenia GET parametrov, nakoľko chceme, aby sa tabuľka po odoslaní filtrovaného výrazu zobrazila na prvej stránke a bola zoradená. Jediné, čo je potrebné doplniť, je hodnota atribútu `value` elementu `input`, aby používateľ vedel, podľa čoho sa filtrujú výsledky. Metóda bude vyzerať:

```php
class Table
{
    //...
    private function renderFilter() : string{
        return '<form>
        <input name="filter" type="text" value="'.$this->filter.'">
        <button type="submit">Filtrovať</button>
        </form>';
    }
    //...
}
```

Formulár sa má zobraziť nad tabuľkou, preto ho doplníme do metódy `Table->render()`:

```php
class Table
{
    //...
    public function render() : string
    {
        return $this->renderFilter()
        ."<table border=\"1\">{$this->renderHead()}{$this->renderBody()}</table>"
        . $this->renderPaginator();
    }
    //...
}
```

Dáta v tabuľke sa budú dať filtrovať:

![Filtrovanie dát v tabuľke](images_data-table/dbtable-04.gif)

### Vlastné stĺpce

Aktuálna verzia tabuľky automaticky vygeneruje zoznam stĺpcov na základe triedy `User`. V reálnych aplikáciách je ale bežné, že v tabuľke nechceme zobraziť všetky stĺpce (napr. stĺpec `id` sa nezvykne zobrazovať) alebo chceme pomenovať stĺpce inak, ako sa volajú v&nbsp;databáze. Ďalšou bežnou požiadavkou je zobrazenie stĺpca, ktorý bude obsahovať tlačidlá (odkazy) na modifikáciu záznamov. Na tento účel si upravíme tabuľku tak, aby bolo možné programovo definovať stĺpce, ktoré chceme zobrazovať.

Na reprezentáciu stĺpca si vytvoríme triedu `Column`, ktorá bude obsahovať titulok (to, čo sa má zobraziť v hlavičke tabuľky), názov atribútu a funkciu, ktorá vypíše obsah konkrétnej bunky v danom stĺpci.

```php
class Column
{
    private string $field;
    private string $title;
    private ?Closure $renderer;
    
    public function __construct(string $field, string $title, Closure $renderer)
    {
        $this->field = $field;
        $this->title = $title;
        $this->renderer = $renderer;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function render($row): string
    {
        $renderer = $this->renderer;
        return $renderer($row);
    }
}
```

Trieda obsahuje *get* metódy na názov stĺpca a názov atribútu. Okrem toho obsahuje metódu `render()`, ktorá má ako parameter celý záznam (riadok tabuľky) a má za úlohu vypísať daný stĺpec pomocou definovanej metódy `render()`. Ako si môžeme všimnúť, v&nbsp;metóde `render()` sme si renderovaciu funkciu, ktorá je uložená v atribúte, museli najskôr uložiť do lokálnej premennej a až potom zavolať. Je to z toho dôvodu, že zápis `$this->renderer($row)` by nevykonal funkciu uloženú v atribúte `$renderer`, ale snažil by sa nájsť metódu `renderer()` v triede `Column`.

Keď máme pripravenú triedu reprezentujúcu stĺpec tabuľky, pristúpime k jej implementácii do triedy `Table`. V prvom rade si pripravíme atribút `$columns`, ktorý bude obsahovať definíciu stĺpcov tabuľky. Ďalej si pridáme metódu, pomocou ktorej budeme môcť definovať jednotlivé stĺpce tabuľky.

```php
class Table
{
    //...
    private array $columns = [];
    
    public function addColumn(string $field, string $title, ?Closure $renderer = null): self {
        if ($renderer == null) {
            $renderer = fn($row) => htmlentities($row->$field);
        }
        $this->columns[] = new Column($field, $title, $renderer);
        return $this;
    }
    //...
}
```

Metóda obsahuje rovnaké vstupné parametre ako trieda `Column`. Táto metóda v princípe len vytvorí novú inštanciu triedy `Column` a zaradí ho do zoznamu. Aby sme nemuseli vždy špecifikovať spôsob výpisu každej hodnoty, tak sme nastavili parameter `$renderer` ako voliteľný. Ak ho nevyplníme, tak implementujeme východzí výpis tak, že vypíšeme len hodnotu daného atribútu a ošetríme ju pomocou PHP funkcie `htmlentities()`.

V tomto príklade sme na vytvorenie objektu typu `Closure` využili *lambda* funkciu, ktorá sa v PHP definuje pomocou kľúčového slova `fn`.

Táto metóda má návratovú hodnotu typu `self` a vráti `$this`. Tento prístup sa často používa pri metódach, ktoré slúžia na nastavenie určitých vlastností objektu. Vďaka tomu ich môžeme volať zreťazene:

```php
$table->addColumn(...)
    ->addColumn(...)
    ->addColumn(...);
```

Ďalej musíme upraviť metódy na generovanie hlavičky a tela tabuľky tak, aby využívali takto definované stĺpce. Metóda `renderHead()` bude vyzerať takto:

```php
class Table
{
    //...
    private function renderHead() : string 
    {
        $header = "";
        foreach ($this->columns as $column) {
            if (empty($column->getField())) {
                $header .= "<th>{$column->getTitle()}</th>";
            }
            else {
                $hrefParams = [
                    'order' => $column->getField(),
                    'page' => 0
                ];
                    if ($this->orderBy == $column->getField() && $this->direction == "") {
                    $hrefParams['direction'] = "DESC";
                } else {
                    $hrefParams['direction'] = "";
                }
                $header .= "<th><a href=\"{$this->prepareUrl($hrefParams)}\">{$column->getTitle()}</a></th>";
            }
        }
        return "<tr>{$header}</tr>";
    }
    //...
}
```

Namiesto `retColumnAttributes()` teraz prechádzame zoznamom stĺpcov definovaných v&nbsp;atribúte `$columns`. Okrem toho si na tomto kóde môžeme všimnúť ešte jednu zmenu. Na začiatok sme pridali podmienku `empty($column->getField())`. Touto podmienkou zabezpečíme, že stĺpce, ktoré nemajú ekvivalent v databáze, sa nebudú dať triediť.

Ďalšou metódou, ktorú musíme upraviť je metóda `renderBody()`:

```php
class Table
{
    //...
    private function renderBody() : string
    {
        $body = "";
        $userStorage = new UserStorage();
        $users = $userStorage->getAll($this->orderBy, $this->direction, $this->page, $this->pageSize, $this->filter);
    
        foreach ($users as $user) {
            $tr = "";
            foreach ($this->columns as $column) {
                $tr .= "<td>{$column->render($user)}</td>";
            }
            $body .= "<tr>$tr</tr>";
        }
        return $body;
    }
    //...
}
```

Tu je to podobne ako v predchádzajúcom prípade, zmena je len v tom, že namiesto `$this->getColumnAttributes()` iterujeme cez `$columns` a výstup generujeme cez `$column->render($user)`. Metóda `getColumnAttributes()` bola ešte používaná pri validácii parametra pri zoraďovaní stĺpcov. 

<div style="page-break-after: always;"></div>

Upravíme preto ešte metódu `isColumnNameValid()`:

```php
class Table
{
    //...
    private function isColumnNameValid($name) : bool {
        return !empty($name) && in_array($name, array_map(fn(Column $c) => $c->getField(), $this->columns));
    }
    //...
}
```

Tentoraz kontrolujeme, či názov stĺpca, podľa ktorého zoraďujeme, nie je prázdny (pretože reálne stĺpce tabuľky môžu obsahovať napríklad pole s akciami, t. j. neodkazujú sa na DB atribút a zoraďovanie podľa tohto poľa nie je dovolené). Okrem toho sme pomocou funkcie `array_map()` transformovali pole objektov typu `Column` na pole reťazcov, v&nbsp;ktorom následne vyhľadávame. V tomto momente môžeme odstrániť metódu `getColumnAttributes()` a atribút `$columnAttribs`.

Poslednou úpravou je presun kontroly atribútu `$orderBy` z konštruktora do metódy `renderBody()`, pretože v konštruktore ešte nemáme k dispozícii zoznam definovaných stĺpcov. Konštruktor upravíme:

```php
$this->orderBy = $_GET['order'] ?? "";
```

Na začiatok metódy `renderBody()` pridáme:

<div class="end">

```php
class Table
{
    //...
    private function renderBody() : string
    {
        $this->orderBy = $this->isColumnNameValid($this->orderBy) ? $this->orderBy : "";
        //...
    }
    //...
}
```
</div>

Pre zobrazenie pôvodnej tabuľky upravíme `index.php`:

```php
$usersTable = new Table();
$usersTable->addColumn("id", "ID")
    ->addColumn("name", "Meno")
    ->addColumn("surname", "Priezvisko")
    ->addColumn("mail", "Emailová adresa")
    ->addColumn("country", "Krajina");
```

Pokiaľ by sme chceli pridať pole s tlačidlami do každého riadku, môžeme tak urobiť nasledujúco:

```php
$usersTable->addColumn("", "Akcie", function (User $user) {
    return '<button onclick="alert(' . $user->id . ')">Tlačidlo</button>';
});
```

V tomto príklade uvádzame prázdny názov atribútu, na ktorý sa daný stĺpec viaže, potom sa nebude dať podľa tohto stĺpca zoraďovať. Ako tretí parameter definujeme anonymnú funkciu, ktorá ako parameter dostane entitu používateľa a zobrazí jednoduché tlačidlo, ktoré po stlačení vypíše ID daného používateľa.

![Funkčné akčné tlačidlo a zobrazenie správy používateľovi](images_data-table/dbtable-05.gif)

### Univerzálny zdroj dát

Naša tabuľka aktuálne zobrazuje dáta len z tabuľky `users`. Na zovšeobecnenie nášho návrhu si pridáme interface `ITableSource`, ktorý bude definovať metódy na získanie dát.

```php
interface ITableSource
{
    public function getCount(?string $filter = null): int;

    public function getAll(?string $sortedBy = "", ?string $sortDirection = "", int $page = 0, int $pageSize = 10, ?string $filter = ""): array;
}
```

Rovnaké metódy už implementuje naša trieda `UserStorage` takže, aby ju bolo možné použiť ako zdroj dát pre triedu `Table`, musíme pridať do hlavičky triedy kľúčové slovo `implements`.

```php
class UserStorage implements ITableSource
{
    //...
}
```

Triedu `Table` upravíme tak, že ako parameter konštruktora získa inštanciu typu `ITableSource`, ktorú si uloží do atribútu `$dataSource`:

<div class="end">

```php
class Table
{
    //...
    private ITableSource $dataSource;
   
    public function __construct(ITableSource $dataSource)
    {
        $this->dataSource = $dataSource;
        //...
    }
    //...
}
```
</div>

Následne upravíme metódy `renderBody()` a `getPageNumber()` tak, aby si nevyrábali vždy nový `UserStorage`, ale využívali atribút `$dataSource`.

```php
class Table
{
    //...
    private function getPageNumber(): int
    {
        $this->itemsCount = $this->dataSource->getCount($this->filter);
        $page =  intval($_GET['page'] ?? 0);
        $this->totalPages = ceil($this->itemsCount / $this->pageSize);
        if (($page < 0) || $page > $this->totalPages){
            return 0;
        }
        return $page;
    }
    
    private function renderBody() : string
    {
        $body = "";
        $this->orderBy = $this->isColumnNameValid($this->orderBy) ? $this->orderBy : "";
        $rows = $this->dataSource->getAll($this->orderBy, $this->direction, $this->page, $this->pageSize, $this->filter);

        foreach ($rows as $row) {
            $tr = "";
            foreach ($this->columns as $column) {
                $tr .= "<td>{$column->render($row)}</td>";
            }
            $body .= "<tr>$tr</tr>";
        }
        return $body;
    }
}
```

<div style="page-break-after: always;"></div>

V súbore `index.php` upravíme deklaráciu tabuľky tak, že v konštruktore jej nastavíme inštanciu `UserStorage`:

```php
$userStorage = new UserStorage();
$usersTable = new Table($userStorage);
$usersTable->addColumn("name", "Meno")
    ->addColumn("surname", "Priezvisko")
    ->addColumn("mail", "Emailová adresa")
    ->addColumn("country", "Krajina")
    ->addColumn("", "Akcie", function (User $user) {
        return '<button onclick="alert(' . $user->id . ')">Tlačidlo</button>';
    });
```

V prípade, že by sme chceli v tabuľke zobraziť inú dátovú entitu, stačí implementovať príslušný `Storage` a nastaviť požadované stĺpce.