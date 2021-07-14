<?php
session_start();
// aplikacia pouziva session, preto ju musime najprv inicializovat


// potrebne pripojenie suborov s pouzivanimi triedami
require "php/Message.php";
require "php/User.php";
require "php/Db.php";
require "php/MessageStorage.php";
require "php/UserStorage.php";

function sendResponse($httpCode, $httpStatus, $body = ""){
    header($_SERVER["SERVER_PROTOCOL"] . " {$httpCode} {$httpStatus}");
    echo $body;
}

try {
    switch (@$_GET['method']) {

        case 'get-messages':
            $messageStorage = new MessageStorage();
            $messages = array_reverse($messageStorage->getMessages(@$_SESSION['user']));
            echo json_encode($messages);
            break;

        case 'post-message':
            if (empty($_SESSION['user'])){
                throw new Exception("Must be logged to post messages.", 400);
            }
            if (!empty($_POST['message'])) {
                $m = new Message();
                $m->user = $_SESSION['user'];
                $m->message = $_POST['message'];
                $m->private_for = $_POST['private'] ?? null;
                $m->created = date('Y-m-d H:i:s');
                $messageStorage = new MessageStorage();
                $messageStorage->storeMessage($m);
                //No content
                http_response_code(204);
            } else {
                throw new Exception("Invalid API call", 400);
            }
            break;

        case 'users' :
            $out = [];
            if (!empty($_SESSION['user'])) {
                $userStorage = new UserStorage();
                $out = array_filter($userStorage->getUsers(), function (User $user) {
                    return $user->name != $_SESSION['user'];
                });
            }
            echo json_encode($out);
            break;

        case 'login':
            if (!empty($_POST['name'])){
                if (!empty($_SESSION['user'])) {
                    throw new Exception("User already logged", 400);
                }
                $userStorage = new UserStorage();
                if ($userStorage->isLogged($_POST['name'])) {
                    throw new Exception("User already exists", 455);
                };
                $userStorage->addUser($_POST['name']);
                $_SESSION['user'] = $_POST['name'];
                echo json_encode($_SESSION['user']);
            } else {
                throw new Exception("Invalid API call", 400);
            }
            break;

        case 'logout' :
            if (!empty($_SESSION['user'])){
                $userStorage = new UserStorage();
                $userStorage->removeUser($_SESSION['user']);
                session_destroy();
                sendResponse(204, 'No Content');
            } else {
                throw new Exception("Invalid API call", 400);
            }
            break;

        case 'is-logged' :
            echo json_encode(empty($_SESSION['user']) ? false : $_SESSION['user']);
            break;

        default:
            throw new Exception("Invalid API call", 400);

    }
} catch (Exception $exception) {
    sendResponse(
        $exception->getCode(),
        $exception->getMessage(),
        json_encode([
            "error-code" => $exception->getCode(),
            "error-message" => $exception->getMessage()
        ])
    );
}