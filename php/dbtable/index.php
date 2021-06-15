<?php

require "User.php";
require "Db.php";
require "Table.php";

$usersTable = new Table();

echo $usersTable->Render();
