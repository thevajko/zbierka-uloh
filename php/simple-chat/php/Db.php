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
    private string $dbName = "dbchat";
    private string $dbUser = "db_user";
    private string $dbPass = "db_user_pass";

    public function __construct()
    {
        try {
            $this->pdo = new PDO("mysql:host={$this->dbHost};dbname={$this->dbName}", $this->dbUser, $this->dbPass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
           throw new Exception($e->getMessage(), 500);
        }
    }

    /**
     * @return Message[]
     * @throws Exception
     */
    public function getMessages($userName = ""): array
    {
        try {
            if (empty($userName)){
                return $this->pdo
                ->query("SELECT * FROM messages WHERE private_for IS null ORDER by created ASC LIMIT 50")
                ->fetchAll(PDO::FETCH_CLASS, Message::class);
            } else {
                $stat = $this->pdo
                    ->prepare("SELECT * FROM messages  WHERE private_for IS null OR private_for LIKE ? ORDER by created ASC LIMIT 50");
                $stat->execute([$userName]);
                    return $stat->fetchAll(PDO::FETCH_CLASS, Message::class);
            }
        }  catch (\PDOException $e) {
            throw new Exception($e->getMessage(), 500);
        }
    }

    public function storeMessage(Message $message){
        try {
            if (empty($message->private_for)) {
                $sql = "INSERT INTO messages (message, created, user) VALUES (?, ?, ?)";
                $this->pdo->prepare($sql)->execute([$message->message, $message->created, $message->user]);
            } else {
                $sql = "INSERT INTO messages (message, created, user, private_for) VALUES (?, ?, ?, ?)";
                $this->pdo->prepare($sql)->execute([$message->message, $message->created, $message->user, $message->private_for]);
            }

        }  catch (\PDOException $e) {
            throw new Exception($e->getMessage(), 500);
        }
    }

    /**
     * @return User[]
     * @throws Exception
     */
    public function getUsers() : array
    {
        try {
            return $this->pdo
                ->query("SELECT * FROM users")
                ->fetchAll(PDO::FETCH_CLASS, User::class);
        }  catch (\PDOException $e) {
            throw new Exception($e->getMessage(), 500);
        }
    }

    public function addUser($name)
    {
        try {
            $sql = "INSERT INTO users (name) VALUES (?)";
            $this->pdo->prepare($sql)->execute([$name]);
        } catch (\PDOException $e) {
            throw new Exception($e->getMessage(), 500);
        }
    }

    public function removeUser($name)
    {
        try {
            $sql = "DELETE FROM users WHERE name = ?";
            $this->pdo->prepare($sql)->execute([$name]);
        }  catch (\PDOException $e) {
            throw new Exception($e->getMessage(), 500);
        }
    }

}
