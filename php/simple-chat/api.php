<?php
session_start();

require "php/Message.php";
require "php/User.php";
require "php/Db.php";

try {
    switch (@$_GET['method']) {

        case 'is-logged' :
            echo json_encode(empty($_SESSION['user']) ? false : $_SESSION['user']);
            break;

        case 'logout' :
                if (!empty($_SESSION['user'])){
                    DB::i()->RemoveUser($_SESSION['user']);
                    session_destroy();
                } else {
                    throw new Exception("Invalid API call", 400);
                }
            break;

        case 'login':

            if (!empty($_POST['name'])){

                if (!empty($_SESSION['user'])) {
                    throw new Exception("User already logged", 400);
                }

                $users = DB::i()->GetUsers();
                $foundUser = array_filter($users, function (User $user){
                    return $user->name == $_POST['name'];
                });

                if (!empty($foundUser)) {
                    throw new Exception("User already exists", 455);
                };

                DB::i()->AddUser($_POST['name']);

                $_SESSION['user'] = $_POST['name'];

                echo json_encode($_SESSION['user']);

            } else {
                throw new Exception("Invalid API call", 400);
            }
            break;

        case 'get-messages':
            $messages = Db::i()->GetMessages();
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
                $m->created = date('Y-m-d H:i:s');
                Db::i()->StoreMessage($m);
            } else {
                throw new Exception("Invalid API call", 400);
            }
            break;

        default:
            throw new Exception("Invalid API call", 400);
            break;
    }
} catch (Exception $exception) {
    header($_SERVER["SERVER_PROTOCOL"] . " {$exception->getCode()} {$exception->getMessage()}");
    echo json_encode([
        "error-code" => $exception->getCode(),
        "error-message" => $exception->getMessage()
    ]);
}