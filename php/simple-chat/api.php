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

        case 'is-logged' :
            echo json_encode(empty($_SESSION['user']) ? false : $_SESSION['user']);
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

        case 'login':

            if (!empty($_POST['name'])){

                if (!empty($_SESSION['user'])) {
                    throw new Exception("User already logged", 400);
                }

                $userStorage = new UserStorage();
                $users = $userStorage->getUsers();
                $foundUser = array_filter($users, function (User $user){
                    return $user->name == $_POST['name'];
                });

                if (!empty($foundUser)) {
                    throw new Exception("User already exists", 455);
                };

                $userStorage->addUser($_POST['name']);

                $_SESSION['user'] = $_POST['name'];

                echo json_encode($_SESSION['user']);

            } else {
                throw new Exception("Invalid API call", 400);
            }
            break;

        case 'get-messages':
            $messageStorage = new MessageStorage();
            $messages = $messageStorage->getMessages(@$_SESSION['user']);
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
                $m->private_for = @$_POST['private'];
                $m->created = date('Y-m-d H:i:s');
                $messageStorage = new MessageStorage();
                $messageStorage->storeMessage($m);
                sendResponse(204, 'No Content');
            } else {
                throw new Exception("Invalid API call", 400);
            }
            break;

        case 'users' :
            if (empty($_SESSION['user'])){
                throw new Exception("Must be logged to get active users list", 400);
            }
            $userStorage = new UserStorage();
            $users = array_filter($userStorage->getUsers(), function (User $user) {
                return $user->name != $_SESSION['user'];
            });
            echo json_encode(array_values($users));
            break;

        default:
            throw new Exception("Invalid API call", 400);
            break;
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