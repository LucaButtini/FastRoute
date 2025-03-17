<?php
require 'functions.php';

class Dbconnection
{
    private static Pdo $db;

    public static function getDb(array $config):PDO{
        if(!isset(self::$db)) {
            try{
                self::$db = new PDO($config['dsn'], $config['username'], $config['password'], $config['options']);
            }catch (PDOException $e){
                echo $e->getMessage();
                logError($e);
            }
        }
        return self::$db;
    }
}