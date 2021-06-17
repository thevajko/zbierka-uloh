<div class="hidden">

> ## Rozcestník
> - [Späť na úvod](../../README.md)
> - Repo: [Štartér](/../../tree/main/php/simple-chat), [Riešenie](/../../tree/solution/php/simple-chat).
> - [Zobraziť zadanie](zadanie.md)

# Jednoduchý chat  (DB, PHP, JS, AJAX, CSS)
</div>

## Riešenie

Na začiatok si ujasníme architektúru celého riešenia. V tomto prípade rozdelíme budúci výsledok na dve časti:

1. _Klientská časť_ - bude mať logiku vytvorenú pomocou _javascriptu_ a bude komunikovať asynchrónne so serverovou časťou.
2. _Serverová časť_ - jej vstupný bod bude predstavovať jediný _php_ súbor a bude poskitovať jednoduché _API_ pre chat.


### Vytvorenie anonymného chatu

V prvom kroku vytvoríme verziu "anonymného" chatu, kde môže pridať príspevok hocikto. Ako prvé musíme vytvoriť perzistentné úložisku pre správy. To bude predstavovať databázová tabuľka `messages`. _DDL_ pre jej vytvorenie bude:

```sql
create table messages
(
    id int auto_increment,
    message text not null,
    created datetime not null,
    constraint messages_pk
        primary key (id)
);
```

Teraz si načrtneme serverovú časť, ktorú bude predstavovať _PHP súbor_ `api.php`. Nakoľko sa jedná o _API_ musíme komunikáciu vypracovať zodpovedne. To znamená, že všetky chyby, ktoré v našej serverovej časti nastanú musíme preposlať na klienta v správnom tvare. Ináč povedané, klient musí dostať chybu v uvedené pomocou zodpovedajúceho stavu, ktorý je v prostredí webových aplikácií predávaný pomocou [_HTTP kódu_](https://developer.mozilla.org/en-US/docs/Web/HTTP/Status). Tento kód je súčasť hlavičky odosielanej serverom v _HTTP odpovedi_. V jej tele by sme mali posielať ako kód tak aj chybovú hlášku, ktorú spracuje naša aplikácia. 

Prečo to implementovať? Koly klientovi. Správne implementovaná schéma HTTP kódov uľahčuje programovanie klienta v javasctipte. Pri posielaní asynchrónnzch dopytov, vie klient následne správne vyhodnotiť a vykonať adekvátnu reakciu bez nutnosti odpovede ručne kontorlovať.

Z tohto dôvodu bude celá logika nášho _API_ v súbore `api.php` obalená v `try-catch` bloku. Veľmi dôležité je generovanie chyby do hlavičky v `catch` sekcií. 

Túto informácie vkladáme do hlavičky _HTTP odpovede_ pomocou _PHP_ funkcie [`header()`](https://www.php.net/manual/en/function.header.php). Pripájame do nej ako _HTTP kód_ kód z výnimky tak aj jej text. Následne pridávame do tela odpovede kód aj text chyby v podobe pola, ktoré serializujeme do formátu [_JSON_](https://developer.mozilla.org/en-US/docs/Learn/JavaScript/Objects/JSON) pomocou _PHP funkcie_ [`json_encode()`](https://www.php.net/manual/en/function.json-encode.php), čo je praktické pri jej spracovaní na klientovi.

Pre jednoduchosť bude naše _API_ určovať metódu pre generovanie výstupu na základe _GET parametra_ `method`. Vetvenie prevedieme pomocou bloku `switch`, kde pri zadaní nepoužívanej hodnoty vrátime _HTTP kód_ [`400 Bad Request`](https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/400).

Kód v súbore `api.php` bude vyzerať nasledovne:

```php
try {
    switch (@$_GET['method']) {

        default:  
            throw new Exception("Invalid API call", 400);            
    }
} catch (Exception $exception) {
    header($_SERVER["SERVER_PROTOCOL"] . " {$exception->getCode()} {$exception->getMessage()}");
    echo json_encode([
        "error-code" => $exception->getCode(),
        "error-message" => $exception->getMessage()
    ]);
}
```


Ako ďalšie si vytvoríme dve _PHP triedy_. Prvá bude predstavovať dátový objekt reprezentujúci jeden riadok v databáze. Nazveme ju `Message` a bude vyzerať nasledovne:

```php
class Message
{
    public int $id;
    public string $message;
    public string $created;
}
```

Trieda pre pripojenie sa bude volať `Db`, bude implementovať _singleton_ a v jej konštruktore inicializujeme spojenie s databázov pomocou `PDO`. Trieda bude vyzerať nasledovne:

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

    private PDO $pdo;

    private string $dbHost = "db:3306";
    private string $dbName = "dbchat";
    private string $dbUser = "db_user";
    private string $dbPass = "db_user_pass";

    public function __construct()
    {
        try {
            $this->pdo = new PDO("mysql:host={$this->dbHost};dbname={$this->dbName}", $this->dbUser, $this->dbPass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
           throw new Exception($e->getMessage(), 500);
        }
    }
}
```