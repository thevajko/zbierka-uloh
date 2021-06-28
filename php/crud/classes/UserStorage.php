<?php
require_once 'Db.php';

class UserStorage {

    /**
     * @return User[]
     */
    public static function getAllUsers(): array
    {
        return Db::conn()
            ->query("SELECT * FROM users")
            ->fetchAll(PDO::FETCH_CLASS, User::class);
    }

    public static function getUser($id): ?User {
        $statement =  Db::conn()->prepare("SELECT * FROM users WHERE id = ?");
        $statement->execute([$id]);
        $statement->setFetchMode(PDO::FETCH_CLASS, User::class);
        $user = $statement->fetch();
        if ($user === false) {
            return null;
        }
        return $user;
    }

    public static function storeUser(User $user) {
        //Insert
        if ($user->id == 0) {
            $sql = "INSERT INTO users (name, surname, mail, country) VALUES (?, ?, ?, ?)";
            Db::conn()->prepare($sql)->execute([$user->name, $user->surname, $user->mail, $user->country]);
        }
        //Update
        else {
            $sql = "UPDATE users SET name = ?, surname = ?, mail = ?, country = ? WHERE id = ?";
            Db::conn()->prepare($sql)->execute([$user->name, $user->surname, $user->mail, $user->country, $user->id]);
        }
    }

    public static function deleteUser(User $user) {
        $sql = "DELETE FROM users WHERE id = ?";
        Db::conn()->prepare($sql)->execute([$user->id]);
    }
}
