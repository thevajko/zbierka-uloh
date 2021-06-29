<?php

require "User.php";
require "Db.php";
require "UserStorage.php";
require "Table.php";

$usersTable = new Table();
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

