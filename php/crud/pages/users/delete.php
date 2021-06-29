<?php
$userStorage = new UserStorage();
$user = null;
if (isset($_GET["id"])) {
    $user = $userStorage->get($_GET["id"]);
}

if ($user == null) {
    echo "Užívateľ nenájdený.<br><a href='?'>Späť</a>";
    return;
}

if (isset($_POST['delete'])) {
    $userStorage->delete($user);
    echo "Uživateľ {$user->getFullname()} ostránený.<br><a href='?'>Späť</a>";
    return;
}
?>

Skutočne chcete odstrániť používateľa <?=$user->getFullname()?>?
<form method="post">
    <input type="submit" name="delete" value="Áno">
</form>
<a href="?">Späť</a>
