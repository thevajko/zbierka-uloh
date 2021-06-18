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

Trieda pre pripojenie sa bude volať `Db`, bude implementovať _singleton_ a v jej konštruktore inicializujeme spojenie s databázou pomocou `PDO`. 

Vzhľadom na to, že chybové výnimky musí odchytávať súbor `api.php` upravíme chovanie `PDO` tak, aby pri nastaní chyby s databázou bola vyhodená výnimka. Čo urobíme ihneď po vytvorení jej inštancie nastavením `  $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);` (toto [nastavenie je predvolené](https://www.php.net/manual/en/pdo.error-handling.php#:~:text=PDO%3A%3AERRMODE_EXCEPTION&text=0%2C%20this%20is%20the%20default,error%20code%20and%20error%20information.) až od verzie PHP 8.0).

Následne si ešte musíme upraviť chybový kód, tak aby zodpovedal _HTTP kódom_. Preto po odchytení výnimky vytvoríme novú výnimku, nastavíme jej rovnakú správu a upravíme jej kód na [`500 Internal Server Error`](https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/500).

Trieda bude vyzerať nasledovne:

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

Teraz do triedy `Db` pridáme verejnú metódu, ktorej výstup bude posledných 50 záznamov z databázovej tabuľky `messages` v podobe pola inštancií triedy `Message`.

```php
class Db {

    // ...
    /**
     * @return Message[]
     * @throws Exception
     */
    public function GetMessages(): array
    {
        try {
            return $this->pdo
                ->query("SELECT * FROM messages ORDER by created ASC LIMIT 50")
                ->fetchAll(PDO::FETCH_CLASS, Message::class);
        }  catch (\PDOException $e) {
            throw new Exception($e->getMessage(), 500);
        }
    }
}
```

Teraz pridáme v súbore `api.php` do bloku `switch` reakciu na hodnotu `get-messages` v _HTTP parametre_ `method`. V nej získame pole správ zavolaním ` Db::i()->GetMessages()` a následne ho serializujeme do formátu _JSON_ a vypíšeme do tela odpovede. Nesmieme zabudnúť doplniť pomocou `require` naše definície tried `Message` a `Db`. Pridanie logiky bude vyzerať následovne:

```php

require "php/Message.php";
require "php/Db.php";

try {
    switch (@$_GET['method']) {

        // ...

        case 'get-messages':
            $messages = Db::i()->GetMessages();
            echo json_encode($messages);
            break;
            
       // ...     
    }
} catch (Exception $exception) {
    // ...    
}
```

Ak teraz navštívime naše _API_ a nezadáme žiadne parametre, dostaneme chybovú spolu aj rozpoznaním _HTTP kódu_.

![](images_simplechat/api-01.png)

Ak však pridáme _GET parameter_  `method=get-messages` dostaneme normálnu dopoveď aj keď momentálne v podobe prázdneho pola, nakoľko v databáze nemáme žiadne záznamy.

![](images_simplechat/api-02.png)

Teraz vytvoríme logiku, ktorá bude vedieť zobraziť získane dáta. Ako prvé si vytvoríme súbor `index.html` a do elementu `<body>` umiestnime element `<div>`, ktorému pridáme atribút `id` s hodnotou `messages`.

Pre lepšiu prehladnosť budeme pre písanie javascript kódu klienta používať _moduly_. Vytvoríme dva súbory. Prvý bude `main.js`, ktorý bude slúžiť ako zostavovač logiky a `chat.js` do ktorého vytvoríme triedu `Chat`.

Súbor `index.html` bude obsahovať:

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Jednoduchý chat</title>
    <script type="module" src="js/main.js"></script>
</head>
<body>
    <div id="messages">
    </div>
</body>
</html>
````

Súbor `main.js` vytvorí po plnej inicializácií stránky v prehliadači inštanciu triedy `Chat` a pridá ju do `window.chat`. Obsah súboru `main.js` bude nasledovný:

```javascript
import Chat from "./chat.js";

window.onload = function (){
    window.chat = new Chat();
}
```

Trieda `Chat` bude obsahovať momentálne iba jednú metódu `GetMessages()`, ktorá pomocou [_FETCH API_](https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API) získavať pole správ zo servera. Ako url doplníme `api.php?method=get-messages`. 

Pre jednoduchosť budeme používať callbackové spracovanie asynchrónnych volaní pomocou metódy `fetch()`. 

Pri prvom volaní vrátime z odpovede deserializované pole správ zo servera. Taktiež tu kontorolujeme, či sa kód HTTP statusu rovná inej hodnote ako `200`, pokiaľ áno, vytvoríme a vyhodíme novú chybovú výnimku. Tú odchytí `catch()`, ktorú zadefinujeme ako poslednú. Tá ju momentálne iba vypíše do konzolového výstupu.

Správy budeme vypisovať do elementu `<div>` s `id` `messages`. Tento element nebude slúžiť na nič iné. Jednotlivé elementy správ budeme zostavovať pomocou textového reťazca. 

Každá správa bude samostatne zabalená do elementu `<div class="message">` a dátum vytvorenia s textom bude v samostatnom `<span>` elemente. Každému `<span>` elementu pridáme vlastnú _CSS triedu_ aby ich bolo možné naštýlovať.

HTML kód každej správy sa pridáva do lokálnej premennej `messagesHTML`, ktorú po prejdení všetkých správ priamo pridáme do `innerHTML` elementu. Zobrazené správu sa tak prekreslia.

```javascript
class Chat {
    GetMessages(){
        fetch("api.php?method=get-messages")
            .then(response => {
                response.json()
                if (response.status != 200) {
                    throw new Error("ERROR:"  + response.status + " " + response.statusText);
                }
            })
            .then(messages => {
                let messagesHTML = "";
                messages.forEach(message => {
                    messagesHTML += `
                    <div class="message">
                        <span class="date">${message.created}</span>
                        <span class="text">${message.message}</span>
                    </div>`;
                })
                document.getElementById("messages").innerHTML = messagesHTML;
            })
            .catch(err => console.log('Request Failed', err));
        ;
    }
}
export default Chat;
```

Do triedy `Chat` môžeme pridať konštruktor, ktorým nastavíme prvotné a následne aj periodické volanie metódy `Chat.GetMessages()`. Konštruktor bude vyzerať nasledovne:


```javascript
class Chat {
    
    constructor() {
        setInterval(this.GetMessages, 1000);
        this.GetMessages();
    }
    
    // ...
}
export default Chat;
```

Teraz do `index.html` pridáme pod element `<div id=messages>` nový element `<div>` do ktorého umiestníme element `<input>`, ktorý zobrzí textové pole a element `<button>` pre odoslanie napísanej správy.  Súbor `index.html` bude po úprave vyzerať nasledovne:

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Jednoduchý chat</title>
    <script type="module" src="js/main.js"></script>
</head>
<body>
    <div id="messages">
    </div>
    <div>
        <input type="text" id="message">
        <button id="send-button">Odoslať</button>
    </div>
</body>
</html>
```

Teraz v do javascript triedy `Chat` pridáme metódu `PostMessage()` ktorej zavolaním odošleme dáta novej správy na server. Aby sme požiadavku zaslali asynchrónne pomocou `fetch()` doplníme druhý parameter, ktorý nám umožnuje lepšie špecifikovať HTTP dopyt.

V prvom rade doplníme informáciu o metóde _HTTP požiadavky_ na post pomocou `method: "POST"`. Doplníme hlavičku, kde povieme, že telo _HTTP požiadavky_ bude obsahovať dáta pomocou `'Content-Type': 'application/x-www-form-urlencoded'`. Nakoniec do tela pridáme _POST parameter_ `body` a naplníme ho hodnotou z nášho `<input>` elementu.

V prvom `then()` skontrolujeme kód _HTTP odpovede_. Ak nasne chyba vyhodíme a spracujeme výnimku podobne ako v metóde `GetMessages()`. Pokiaľ je všetko v poriadku druhým `then()` vymažeme vpísanú hodnotu. Kód metódy je nasledovný:

```javascript
class Chat {
    // ...
    PostMessage(){
        fetch(
            "api.php?method=post-message",
            {
                headers : {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                method: "POST",
                body: "message=" +  document.getElementById("message").value
            })
            .then(response => {
                if (response.status != 200) {
                    throw new Error("ERROR:"  + response.status + " " + response.statusText);
                }
            })
            .then( () => {
                document.getElementById("message").value = "";
            })
            .catch(err => console.log('Request Failed', err));
    }
    
    // ...
}
export default Chat;
```

Teraz musíme logiku aktivovať. Upravíme

```javascript
class Chat {
    constructor() {
        setInterval(this.GetMessages, 1000);
        this.GetMessages();

        document.getElementById("send-button").onclick = () => {
            this.PostMessage();
        }

        document.getElementById("message").onkeyup = (event) => {
            if (event.code === "Enter") {
                this.PostMessage();
            }
        }
    }
    
    // ...
}
export default Chat;
```
