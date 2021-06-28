<?php

require "classes/User.php";
require "classes/UserStorage.php";
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Zoznam osôb</title>
    <style>
        a:visited {
            color: blue;
        }
    </style>
</head>
    <body>
        <?php
        $path = $_GET['p'] ?? "";
        switch ($path) {
            case "users/add":
            case "users/edit":
                require "pages/users/form.php";
                break;
            case "users/delete":
                require "pages/users/delete.php";
                break;
            default:
                require "pages/users/list.php";
        }
        ?>
    </body>
</html>

