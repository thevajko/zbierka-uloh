<?php

try {
    $pdo = new PDO('mysql:host=db:3306;dbname=dbtable', "db_user", "db_user_pass");
}  catch (PDOException $e) {
    die("Error!: " . $e->getMessage());
}
try {
    $sql = 'SELECT * FROM users';
    $result = $pdo->query($sql);

    $users = $result->fetchAll(PDO::FETCH_ASSOC);

    if ($users) {
        echo "<ul>";
        foreach ($users as $user) {
            echo "<li>{$user['name']}</li>";
        }
        echo "</ul>";
    }
} catch (\PDOException $e) {
    die($e->getMessage());
}