<?php

require "table/Table.php";
require "model/User.php";
require "model/Db.php";
require "model/UserStorage.php";

$userStorage = new UserStorage();
$usersTable = new Table($userStorage);
$usersTable->addColumn("name", "Meno")
    ->addColumn("surname", "Priezvisko")
    ->addColumn("mail", "Emailová adresa")
    ->addColumn("country", "Krajina")
    ->addColumn("", "Akcie", function (User $user) {
        return '<button onclick="alert(' . $user->id . ')">Tlačidlo</button>';
    });
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
        <?php echo $usersTable->Render(); ?>
    </body>
</html>

