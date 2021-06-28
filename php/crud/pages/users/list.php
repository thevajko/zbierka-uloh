<?php
$userStorage = new UserStorage();
?>

<a href="?p=users/add">Pridaj osobu</a>
<br><br>

<table border="1">
    <tr>
        <th>Meno</th>
        <th>Priezvisko</th>
        <th>Email</th>
        <th>Krajina</th>
        <th>Akcie</th>
    </tr>
    <?php foreach ($userStorage->getAllUsers() as $user) { ?>
        <tr>
            <td><?=htmlentities($user->name)?></td>
            <td><?=htmlentities($user->surname)?></td>
            <td><?=htmlentities($user->mail)?></td>
            <td><?=htmlentities($user->country)?></td>
            <td><a href="?p=users/edit&id=<?=$user->id?>">Edit</a> | <a href="?p=users/delete&id=<?=$user->id?>" onclick="return confirm('Skutočne chcete odstrániť tento záznam?')">Delete</a> </td>
        </tr>
    <?php } ?>
</table>