<?php

class UserStorage
{

    /**
     * @return User[]
     * @throws Exception
     */
    public static function getUsers() : array
    {
        try {
            return Db::i()->getPDO()
                ->query("SELECT * FROM users")
                ->fetchAll(PDO::FETCH_CLASS, User::class);
        }  catch (\PDOException $e) {
            throw new Exception($e->getMessage(), 500);
        }
    }

    public static function addUser($name)
    {
        try {
            $sql = "INSERT INTO users (name) VALUES (?)";
            Db::i()->getPDO()->prepare($sql)->execute([$name]);
        } catch (\PDOException $e) {
            throw new Exception($e->getMessage(), 500);
        }
    }

    public static function removeUser($name)
    {
        try {
            $sql = "DELETE FROM users WHERE name = ?";
            Db::i()->getPDO()->prepare($sql)->execute([$name]);
        }  catch (\PDOException $e) {
            throw new Exception($e->getMessage(), 500);
        }
    }
}