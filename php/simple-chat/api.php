<?php

require "php/Message.php";
require "php/Db.php";

try {
    switch (@$_GET['method']) {

        case 'get-messages':
            $messages = Db::i()->GetMessages();
            echo json_encode($messages);
            break;

        case 'post-message':
            if (!empty($_POST['message'])) {
                $m = new Message();
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