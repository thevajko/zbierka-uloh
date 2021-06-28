<?php
$userStorage = new UserStorage();
$user = new User();
if (isset($_GET["id"])) {
    $user = $userStorage->getUser($_GET["id"]);
}

if ($user == null) {
    echo "Užívateľ nenájdený.<br><a href='?'>Späť</a>";
    return;
}

if (isset($_POST['save'])) {
    $user->name = $_POST['name'];
    $user->surname = $_POST['surname'];
    $user->mail = $_POST['mail'];
    $user->country = $_POST['country'];
    $userStorage->storeUser($user);
    echo "Užívateľ ".htmlentities($user->getFullname())." bol uložený.<br><a href='?'>Späť</a>";
    return;
}

?>

<form method="post">
    <label>Meno</label><br>
    <input type="text" name="name" value="<?=htmlentities($user->name, ENT_QUOTES)?>"><br>

    <label>Priezvisko</label><br>
    <input type="text" name="surname" value="<?=htmlentities($user->surname, ENT_QUOTES)?>"><br>

    <label>Email</label><br>
    <input type="email" name="mail" value="<?=htmlentities($user->mail, ENT_QUOTES)?>"><br>

    <label>Krajina</label><br>
    <input type="text" name="country" value="<?=htmlentities($user->country, ENT_QUOTES)?>">

    <br><br>
    <input type="submit" name="save" value="Odoslať">
</form>
