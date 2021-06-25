<?php

require "User.php";
require "Db.php";

$users = Db::i()->getAllUsers();
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
        <table border="1">
            <tr>
                <th>Meno</th>
                <th>Priezvisko</th>
                <th>Email</th>
                <th>Krajina</th>
                <th>Akcie</th>
            </tr>
            <?php foreach ($users as $user) { ?>
                <tr>
                    <td><?=$user->name?></td>
                    <td><?=$user->surname?></td>
                    <td><?=$user->mail?></td>
                    <td><?=$user->country?></td>
                    <td></td>
                </tr>
            <?php } ?>
        </table>
    </body>
</html>

