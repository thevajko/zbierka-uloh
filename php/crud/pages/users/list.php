<a href="/index.php?p=users/add">Pridaj osobu</a>
<br><br>

<table border="1">
    <tr>
        <th>Meno</th>
        <th>Priezvisko</th>
        <th>Email</th>
        <th>Krajina</th>
        <th>Akcie</th>
    </tr>
    <?php foreach (UserStorage::getAllUsers() as $user) { ?>
        <tr>
            <td><?=$user->name?></td>
            <td><?=$user->surname?></td>
            <td><?=$user->mail?></td>
            <td><?=$user->country?></td>
            <td><a href="?p=users/edit&id=<?=$user->id?>">Edit</a> | <a href="?p=users/delete&id=<?=$user->id?>">Delete</a> </td>
        </tr>
    <?php } ?>
</table>