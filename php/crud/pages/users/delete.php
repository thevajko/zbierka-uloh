<?php
$userStorage = new UserStorage();
$user = null;
if (isset($_GET["id"])) {
    $user = $userStorage->get($_GET["id"]);
}

if ($user == null) {
    echo "Záznam používateľa nenájdený.<br><a href='?'>Späť</a>";
    return;
}

if (isset($_POST['delete'])) {
    $userStorage->delete($user);
    echo "Záznam používateľa {$user->getFullname()} bol odstránený.<br><a href='?'>Späť</a>";
    return;
}
?>

Skutočne chcete odstrániť záznam používateľa <?=$user->getFullname()?>?
<form method="post">
    <input type="submit" name="delete" value="Áno">
</form>
<a href="?">Späť</a>
