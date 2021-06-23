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
    private string $dbName = "dbtable";
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

    public function UsersCount($filter = "") : int
    {
        return $this->pdo->query("SELECT count(*) FROM users" . $this->getFilter($filter))->fetchColumn();
    }

    private function getFilter($filter = ""){

        if ($filter){
            $filter = str_replace("*","%", $filter);
            $searchableColumns = ["name", "surname", "mail"];
            $search = [];
            foreach ($searchableColumns as $columnName) {
                $search[] = " {$columnName} LIKE '%{$filter}%' ";
            }
            return " WHERE ". implode(" OR ", $search). " ";
        }
        return "";
    }

    /**
     * @return User[]
     */
    public function getAllUsers($sortedBy = "", $sortDirection = "", $page = 0, $pageSize = 10, $filter = "") : array
    {
        $sql = "SELECT * FROM users";

        $sql .= $this->getFilter($filter);

        if ($sortedBy) {
            $direc = $sortDirection == "DESC" ? "DESC" : "ASC";
            $sql = $sql . " ORDER BY {$sortedBy} {$direc}" ;
        }

        $page *= $pageSize;
        $sql .= " LIMIT {$pageSize} OFFSET {$page}";

        try {
            return $this->pdo
                ->query($sql)
                ->fetchAll(PDO::FETCH_CLASS, User::class);
        }  catch (\PDOException $e) {
            die($e->getMessage());
        }
    }

}
