<?php

class DbStorage implements IStorage
{

    /**
     * @var PDO
     */
    private $conn;

    public function __construct()
    {
        try {
            $this->conn = new PDO("mysql:host=db;dbname=dbinterface", "db_user", "db_user_pass");
            // set the PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch(PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function GetAll()
    {
        $sth = $this->conn->prepare("SELECT * FROM blogs");
        $sth->execute();
        $result = $sth->fetchAll( PDO::FETCH_CLASS, "Blog");

        return empty($result) ? [] : $result;
    }

    public function Remove($id)
    {
        $this->conn->prepare("DELETE FROM blogs WHERE id=?")->execute([$id]);
    }

    public function Store(Blog $blog)
    {
        if ($blog->getId() == null) {
            // do insert
            $stmt = $this->conn->prepare("INSERT INTO blogs (title, body) VALUES (?,?)");
            $stmt->execute([$blog->getTitle(), $blog->getBody()]);
        } else {
            // do update
            $stmt = $this->conn->prepare("UPDATE blogs SET title = ? , body = ? WHERE id= ?");
            $stmt->execute([$blog->getTitle(), $blog->getBody(), $blog->getId()]);
        }

    }

    public function GetById($id)
    {
        $st = $this->conn->prepare("SELECT * FROM blogs WHERE id=?");
        $st->execute([intval($id)]);
        $r = $st->fetchAll( PDO::FETCH_CLASS, "Blog");
        return $r[0];
    }
}