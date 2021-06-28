<?php

class Db {

    private static ?Db $db = null;

    public static function conn(): PDO
    {
        if (Db::$db == null) {
            Db::$db = new Db();
        }
        return Db::$db->pdo;
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
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Databáza nedostupná: " . $e->getMessage());
        }
    }

}