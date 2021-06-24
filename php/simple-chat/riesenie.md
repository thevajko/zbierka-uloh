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
            $messages = Db::i()->getMessages();
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

Základ pre serverovú časť máme hotovú. Klient bude komunikovať zo serverom čisto asynchrónne a celá jeho logika bude napísaná v javascripte. Základom klienta je súbor _index.html_. Ten bude načítavať súbor _main.js_, ako javascrit modul, a bude obsahovať inicializačnú logiku. Taktiež do elementu `<body>` element `<div>`, ktorému pridáme atribút `id` s hodnotou `messages`. Tento element bude slúžiť pre zobrazovanie získaných správ. Súbor `index.html` bude obsahovať:

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

Odosielanie a spracovanie asynchrónnych dopytov budeme realizovať pomocou [`fetch()`](https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API). `Fetch API` používa pre spracovanie asynchrónnych volaní [`Promise`](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Promise/then). My namiesto vytvarania reťazenia pomocou callbackov použijeme [`async/await`](https://developer.mozilla.org/en-US/docs/Learn/JavaScript/Asynchronous/Async_await). To značne zjednoduší a sprehľadní kód.

Teraz vytvoríme súbor `chat.js` v ktorom vytvoríme triedu `Chat`. Tejto triede pridáme metódu `GetMessages()`, ktorá pomocou `fetch()` získavať pole posledných 50 správ zo servera. Nakoľko budeme používať `async/await` musíme tú metódu označiť `async`. Fetch však odchytáva výnimky, ktoré nastanú iba v prípade problémov pri komunikácií so serverom. Pokiaľ teda klient obdrží odpoveď s ľubovolným _HTTP kódom_ `catch()` sa nevykoná. Samotné vyhodnotenie kódu odpovede budeme musieť manuálne. Z tohto dôvodu bude celá logika získavania a spracovania umiestnená do `try-catch` bloku. 


V prvom rade zavoláme `fetch()`, kde ako parameter doplníme _url_ `api.php?method=get-messages` a výsledok umiestnime do lokálnej premennej `response`. Nakoľko sa jedná o asynchrónne spustenú logiku vložíme pre `fetch()` slovo `await`.

Následne overíme, či má odpoveď _HTTP kód_ `200` pokiaľ nie, vytvoríme a vyhodíme chybu. 
Správy budeme vypisovať do elementu `<div>` s `id` `messages`. Tento element nebude slúžiť na nič iné. Jednotlivé elementy správ budeme zostavovať pomocou textového reťazca.

Každá správa bude samostatne zabalená do elementu `<div class="message">` a dátum vytvorenia s textom bude v samostatnom `<span>` elemente. Každému `<span>` elementu pridáme vlastnú _CSS triedu_ aby ich bolo možné neskôr naštýlovať.

HTML kód každej správy sa pridáva do lokálnej premennej `messagesHTML`, ktorú po prejdení všetkých správ priamo pridáme do `innerHTML` elementu. Zobrazené správy sa tak prekreslia. 

Kód v bloku `catch()` vloží do elementu `<div class="message">` text o chybe s jej detailom. Výsledná metóda bude obsahovať nasledovný kód:

```javascript
class Chat {
 
    async getMessages() {
        try {

            let response = await fetch("api.php?method=get-messages");

            if (response.status != 200) {
                throw new Error("ERROR:"  + response.status + " " + response.statusText);
            }
            
            let messages = await response.json();
            let messagesHTML = "";
            messages.forEach(message => {
                messagesHTML += `
                        <div class="message">
                            <span class="date">${message.created}</span>
                            <span class="user">${message.user} &gt; </span>
                            <span>${message.message}</span>
                        </div>`;
            })
            document.getElementById("messages").innerHTML = messagesHTML;
        } catch (e) {
            document.getElementById("messages").innerHTML = `<h2>Nastala chyba na strane servera.</h2><p>${e.message}</p>`;
        }
    }
}
export default Chat;
```

Súbor `main.js` vytvorí po plnej inicializácií stránky v prehliadači inštanciu triedy `Chat` a pridá ju do `window.chat`. Klient má každú sekundu získavať správy, čo implementujeme vytvorením periodického časovača pomocou [`setInterval()`](https://developer.mozilla.org/en-US/docs/Web/API/WindowOrWorkerGlobalScope/setInterval) ktoré bude volať metódu `Chat.getMessages()`. Časovač sa však prvý krát nespustí ihneď ale až po uplynutí 1 sekundy. Aby používateľ nečakal na spustenie zavoláme po nastavení intervalu metódu `getMessages()`. Asynchrónnu logiku nemôžeme umiestniť do konštruktora, nakoľko konštruktor vracia novú inštanciu danej triedy a nie `promise`. Preto vytvoríme novú asynchrónnu metódu `Chat.run()`, ktorá bude obsahovať:


```javascript
class Chat {

    async run(){
        setInterval(this.getMessages, 1000);
        await this.getMessages()
    }
    
    // ...
}
export default Chat;
```

Logika v skripte `main.js`, teda najprv vytvorí inštanciu triedy `Chat` a následne zavolá jej metódu `Chat.init()`:

```javascript
import Chat from "./chat.js";

window.onload = async function (){
    window.chat = new Chat();
    await window.chat.init();
}
```
V tomto momente bude _chat_ zobrazovať iba dáta, ktoré sú v databáze. Aby sme dáta mohli na server odosielať musíme upraviť `index.html`. Najprv pridáme pod element `<div id=messages>` nový element `<div>` do ktorého umiestníme element `<input>`, ktorý zobrazí textové pole a element `<button>` pre odoslanie napísanej správy.  Súbor `index.html` bude po úprave vyzerať nasledovne:

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

Teraz v do javascript triedy `Chat` pridáme metódu `postMessage()` ktorej zavolaním odošleme dáta novej správy na server. Zasielanie parametrov pomocou _HTTP POST_ je trošičku komplikovanejšie ako pomocou _HTTP GET_, nakoľko je potrebné pridať zopár doplňujúcich informácií. Tie pridáme metóde `fetch()` ako druhý parameter:

1. Aby `fetch()` poslal dopyt ako _HTTP POST_ musíme doplniť nastavenie `method: "POST"`. 
2. Doplníme hlavičku, kde povieme, že telo _HTTP požiadavky_ bude obsahovať dáta pomocou `'Content-Type': 'application/x-www-form-urlencoded'`. 
3.   Nakoniec do tela pridáme _POST parameter_ `body` a naplníme ho hodnotou z nášho `<input>` elementu.

Teraz skontrolujeme, _HTTP kód_ odpovede. Ako posledný krok nastavíme obsah elementu `<input id="message>` ako prázdny. Takto bude používateľ môcť ihneď po odoslaní správy začať písať novú. Metóda `postMessage()` bude obsahovať nasledujúcu logiku:

```javascript
class Chat {
    // ...
    async postMessage(){
        try {
            let response = await fetch(
                "api.php?method=post-message",
                {
                    headers : {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    method: "POST",
                    body: "message=" +  document.getElementById("message").value
                });

            if (response.status != 200) {
                throw new Error("ERROR:"  + response.status + " " + response.statusText);
            }
            
            document.getElementById("message").value = "";
        } catch (err) {
            console.log('Request Failed', err);
        }
    }
    // ...
}
export default Chat;
```

Používateľ bude môcť odoslať správu dvoma spôsobmi:

1. kliknutím na `<button>` element. Zavoláme metódu `Chat.postMessage()` v udalosti `onclick`.
2. stlačením klávesy `enter`. V udalosti `<input>` elementu `onkeyup` najprv skontrolujeme, či bola stlačená klávesa enter pomocou `event.code === "Enter"` a ak áno spúšťame opäť metódu `Chat.postMessage()`.

Nastavenie reagovania na popísane udalosti elementov nastavíme nasledovne v konštruktore triedy `Chat` nasledovne:

```javascript
class Chat {
    
    constructor() {
        
        document.getElementById("send-button").onclick = () => this.PostMessage();
        
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

### Ajax progress

Pri odoslaní správy na server, naša aplikácia používateľovi nijako neoznamuje, že sa na pozadí vykonáva nejaká logika. Bude preto dobré pridať toto oznámenie do kódu našej aplikácie. Ako prvé vytvoríme _CSS_ štýlovanie, ktoré vyobrazovať [_spinner_](https://projects.lukehaas.me/css-loaders/). Jedná sa vizuálny animovaný prvok, ktorý používateľovi hovorí, že ním spusténá akcia sa vykonáva na pozadí.

Pridáme preto do našej aplikácie nasledovné _CSS_ ([zdroj](https://www.w3schools.com/howto/howto_css_loader.asp)):

```css
.loader {
    border: 4px solid #f3f3f3; /* Light grey */
    border-top: 4px solid #3498db; /* Blue */
    border-radius: 50%;
    width: 12px;
    height: 12px;
    animation: spin 2s linear infinite;
    display: inline-block;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
```

Toto _CSS_ vytvorí kruhový šedý rámik, kde jedna jeho štvrtina je modrá.  Je k nemu pridaná animácia, ktorá ho za dve sekundy otočí okolo svojej osi o 360 stupňov.

Informáciu o prebiehajúcom procese na pozadí momentálne zobrazíme pri odoslaní správy. Aktivizovaním metódy `Chat.postMessage()` musíme zablokovať prvok `<input id="message>` a `<button>`. Tým pádom nebude možné túto metódu spustit znovu iba ak už spustená logika skončí. Taktiež zmeníme text elementu `<button>` z `Odoslať` na `Posielam...`.

Na začiatok metódy `Chat.postMessage()` preto umiestnime zmenu jeho vnútorného _HTML_ `<button>` a následne nastavíme elementom `<input>` a `<button>` atribút `disabled` na hodnotu `true`. Znemožníme tak používateľovi zmeniť správu a kliknúť na `<button>`. Do `try-catch` pridáme blok `finally`, ktorého logika sa spustí keď _ajaxové_ volanie skončí. V ňom opäť zmeníme _HTML_ obsah elementu `<button>` a následne nastavíme elementom `<input>` a `<button>` atribút `disabled` na hodnotu `false`. Kód metódy `Chat.postMessage()` bude po úprave nasledovný:

```javascript
class Chat {
    async postMessage(){
        document.getElementById("send-button").innerHTML = `<span class="loader"></span> Posielam...`;
        document.getElementById("send-button").disabled = true;
        document.getElementById("message").disabled = true;

        try {
            let response =  await fetch(
                "api.php?method=post-message",
                {
                    headers : {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    method: "POST",
                    body: "message=" +  document.getElementById("message").value
                });

            if (response.status != 200) {
                throw new Error("ERROR:"  + response.status + " " + response.statusText);
            }

            document.getElementById("message").value = "";

        } catch (err) {
            console.log('Request Failed', err);
        } finally {
            document.getElementById("send-button").innerHTML = `Odoslať`;
            document.getElementById("send-button").disabled = false;
            document.getElementById("message").disabled = false;
        }

    }
    // ...
}
export default Chat;
```

### Podmienenie chatovania prihlásením

Teraz upravíme posielanie správ tak, aby sa používateľ musel "prihlásiť" pre ich odosielanie. Ináč ich bude môcť iba čítať. Prihlasovanie bude spočívať v tom, že bude musieť zadať meno pod ktorým bude v chate vystupovať. 

Ďalej nebude možné aby chatovali dvaja používatelia s rovnakým menom. Z tohto dôvodu vytvoríme v databáze novú tabuľku `users`, ktorá bude obsahovať iba meno aktuálne prihlásených používateľov. _DDL_ pre tabuľu je nasledovné:

```sql
create table users
(
	id int auto_increment
		primary key,
	name varchar(100) not null
);
```

Táto tabuľka bude obsahovať zoznam aktuálne chatujúcich použivateľov. Do existujúcej tabuľky `messages` pridáme stĺpec `user`, ktorý bude obsahovať meno používateľa, ktorý správu odoslal. Nepoužijeme tu _FK_, a to z dôvodu zachovania jednoduchosti riešenia. _DDL_ upravenej tabuľky `messages` je nasledovné:

```sql
create table messages
(
	id int auto_increment
		primary key,
	message text not null,
	created datetime default current_timestamp() null,
	user varchar(100) null
);
```
Do _PHP_ triedy `Message` doplníme atribút `$user`, tak aby reflektovala upravenie databázovej tabuľky `messages` nasledovne:

```php
class Message
{
    public int $id;
    public string $message;
    public string $created;
    public string $user;
}
```

Vytvoríme novú _PHP_ triedu `User` a doplníme do nej atribúty, tak aby zodpovedala jej databázovej verzií:

```php
class User
{
    public int $id;
    public string $name;
}
```

Do _PHP_ triedy `Db` doplníme metódy pre získanie všetkých používateľov a pridanie a vymazanie používateľa. Získanie všetkých používateľov je rovnaké ako pri získavaní správ a bude vyzerať nasledovne:

```php
class Db {

    // ...
    /**
     * @return User[]
     * @throws Exception
     */
    public function getUsers() : array
    {
        try {
            return $this->pdo
                ->query("SELECT * FROM users")
                ->fetchAll(PDO::FETCH_CLASS, User::class);
        }  catch (\PDOException $e) {
            throw new Exception($e->getMessage(), 500);
        }
    }
}
```

Pridávanie používateľa ma tak isto rovnakú logiku ako pridávanie správy a vyzerá nasledovne:

```php
class Db {

    // ...
    public function addUser($name)
    {
        try {
            $sql = "INSERT INTO users (name) VALUES (?)";
            $this->pdo->prepare($sql)->execute([$name]);
        } catch (\PDOException $e) {
            throw new Exception($e->getMessage(), 500);
        }
    }
}
```

A ako posledné pridáme metódu, ktorou budeme na základe mena mazať používateľov:

```php
class Db {

    // ...
    public function removeUser($name)
    {
        try {
            $sql = "DELETE FROM users WHERE name = ?";
            $this->pdo->prepare($sql)->execute([$name]);
        }  catch (\PDOException $e) {
            throw new Exception($e->getMessage(), 500);
        }
    }
}
```






Teraz rozšírime skript `api.php` tak aby umožňoval prihlásenie používateľa. Aby server vedel, ktoré meno používatelia je platné pre aktuálne sedenie budeme ukladať do [`session`](https://www.php.net/manual/en/book.session.php). Dáta pre dané sedenie _PHP_ umožňuje uložiť do špeciálnej super-globálnej premennej [`$_SESSION`](https://www.php.net/manual/en/reserved.variables.session.php). Aby sme ho mohli použiť potrebujeme _PHP_ povedať, že túto súčasť naša aplikácia bude používať. Preto ako prvý riadok v skripte `api.php` bude volanie funkcie [`session_start()`](https://www.php.net/manual/en/function.session-start.php).

`$_SESSION` je pole, kde si pod index `user` budeme ukladať informáciu o mene aktuálne "prihláseného" používateľa pre dané sedenie. Pokiaľ tento index nebude existovať alebo bude obsahovať prázdnu hodnotu (`null` alebo prázdny textovy reťazec) bude logika vedieť, že používateľ sa "neprihlásil". Do tohto príkladu nebudeme pridávať používateľské prihlasovanie pomocou hesla aby sme jeho implementáciu udržali čo najjednoduchšiu.

Samotné prihlásenie bude prebiehať tak, že pošleme _HTTP POST_ požiadavku na adresu `api.php?method=login`, kde meno používateľa pošleme v jej tele ako _POST parameter_. Nesmieme zabudnúť, že pokiaľ už je používateľ prihlásený, exituje hodnota v `$_SESSION['user']`, nesmieme v procese prihlasovania pokračovať. Následne skontrolujeme, či tabuľka `users` neobsahuje dané meno. Ak ho bude obsahovať server vráti odpoveď s chybou, že je používateľ s rovnakým menom už chatuje. V tomto prípade si prehlasujúci používateľ bude musieť zvoliť iné meno. Ak nie, tak sa meno používateľa uloží do databázy a v `$_SESSION` vytvoríme index `user` kde túto hodnotu uložíme tiež. Následne v odpovedi s _HTTP kódom_ `200` vrátime túto hodnotu.

Do súboru `api.php` v bloku `switch` pridáme nový `case` pre hodnotu `login`, ktorého kód bude nasledovný:

```php
// ...
switch (@$_GET['method']) {

        // ...

        case 'login':

            if (!empty($_POST['name'])){

                if (!empty($_SESSION['user'])) {
                    throw new Exception("User already logged", 400);
                }

                $users = DB::i()->getUsers();
                $foundUser = array_filter($users, function (User $user){
                    return $user->name == $_POST['name'];
                });

                if (!empty($foundUser)) {
                    throw new Exception("User already exists", 455);
                };

                DB::i()->addUser($_POST['name']);

                $_SESSION['user'] = $_POST['name'];

                echo json_encode($_SESSION['user']);

            } else {
                throw new Exception("Invalid API call", 400);
            }
            break;
        // ...
}
```

Kontrolu, či je používateľ prihlásený pridáme aj do časti, ktorá je zodpovedná za pridávanie správ:

```php
// ...
switch (@$_GET['method']) {

        // ...
   case 'post-message':

            if (empty($_SESSION['user'])){
                throw new Exception("Must be logged to post messages.", 400);
            }
        //...
            break;

        // ...
}
```
