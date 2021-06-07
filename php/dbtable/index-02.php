<?php

require "User.php";
require "Db.php";


$users = Db::i()->getAllUsers();

if ($users) {
    echo "<ul>";
    foreach ($users as $user) {
        echo "<li>{$user->name}</li>";
    }
    echo "</ul>";
}
