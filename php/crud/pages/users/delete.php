<?php
$user = null;
if (isset($_GET["id"])) {
    $user = Db::i()->getUser($_GET["id"]);
}

if ($user == null) {
    echo "Užívateľ nenájdený.<br><a href='?'>Späť</a>";
    return;
}

Db::i()->deleteUser($user);
echo "Uživateľ {$user->name} {$user->surname} ostránený.<br><a href='?'>Späť</a>";

?>