<?php

class Db {

    private static Db $db = new Db();

    public static function i()
    {
        if (Db::$db == null) {
            Db::$db = new Db();
        }
        return Db::$db;
    }
}
