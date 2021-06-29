<?php

class UserStorage
{

    /**
     * @return User[]
     * @throws Exception
     */
    public function getUsers(): array
    {
        try {
            return Db::conn()
                ->query("SELECT * FROM users")
                ->fetchAll(PDO::FETCH_CLASS, User::class);
        }  catch (\PDOException $e) {
            throw new Exception($e->getMessage(), 500);
        }
    }

    public function addUser($name): void
    {
        try {
            $sql = "INSERT INTO users (name) VALUES (?)";
            Db::conn()->prepare($sql)->execute([$name]);
        } catch (\PDOException $e) {
            throw new Exception($e->getMessage(), 500);
        }
    }

    public function removeUser($name): void
    {
        try {
            $sql = "DELETE FROM users WHERE name = ?";
            Db::conn()->prepare($sql)->execute([$name]);
        }  catch (\PDOException $e) {
            throw new Exception($e->getMessage(), 500);
        }
    }
}