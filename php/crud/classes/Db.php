<?php

class Db {

    private static ?Db $db = null;
    public static function i()
    {
        if (Db::$db == null) {
            Db::$db = new Db();
        }
        return Db::$db;
    }

    private PDO $pdo;

    private string $dbHost = "db:3306";
    private string $dbName = "crud";
    private string $dbUser = "db_user";
    private string $dbPass = "db_user_pass";

    public function __construct()
    {
        try {
            $this->pdo = new PDO("mysql:host={$this->dbHost};dbname={$this->dbName}", $this->dbUser, $this->dbPass);
        } catch (PDOException $e) {
            die("Error!: " . $e->getMessage());
        }
    }

    /**
     * @return User[]
     */
    public function getAllUsers(): array
    {
        $sql = "SELECT * FROM users";

        try {
            return $this->pdo
                ->query($sql)
                ->fetchAll(PDO::FETCH_CLASS, User::class);
        }  catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function getUser($id): ?User {
        try {
            $statement =  $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
            $statement->setFetchMode(PDO::FETCH_CLASS, User::class);
            $statement->execute([$id]);
            $user = $statement->fetch();
            if ($user === false) {
                return null;
            }
            else {
                return $user;
            }
        }  catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function storeUser(User $user) {
        try {
            //Insert
            if ($user->id == 0) {
                $sql = "INSERT INTO users (name, surname, mail, country) VALUES (?, ?, ?, ?)";
                $this->pdo->prepare($sql)->execute([$user->name, $user->surname, $user->mail, $user->country]);
            }
            //Update
            else {
                $sql = "UPDATE users SET name = ?, surname = ?, mail = ?, country = ? WHERE id = ?";
                $this->pdo->prepare($sql)->execute([$user->name, $user->surname, $user->mail, $user->country, $user->id]);
            }
        }  catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function deleteUser(User $user) {
        try {
            $sql = "DELETE FROM users WHERE id = ?";
            $this->pdo->prepare($sql)->execute([$user->id]);
        }  catch (PDOException $e) {
            die($e->getMessage());
        }
    }
}
