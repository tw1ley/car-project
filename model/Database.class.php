<?php

# ======================================================================================================= #

namespace App\Model;

use \PDO;

class Database
{
    private static $connection;
    private static $settings = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_EMULATE_PREPARES => false,
    );

    public static function connect($host, $user, $password, $database) {
        if (!isset(self::$connection)) {
            self::$connection = @new \PDO("mysql:host=$host;dbname=$database", $user, $password, self::$settings);
        }
    }

    // Return array
    public static function getArray($query, $parms = array()) {
        $result = self::$connection->prepare($query);
        $result->execute($parms);

        return $result->fetchAll();
    }

    // Return one row from array
    public static function getRow($query, $parms = array()) {
        $result = self::$connection->prepare($query);
        $result->execute($parms);

        return $result->fetch();
    }

    // Return only one value from selected column
    public static function getOne($query, $parms = array()) {
        $result = self::getRow($query, $parms);
        return $result ? $result[0] : false;
    }

    // Insert, Update, Delete
    public static function query($query, $parms = array()) {
        return self::$connection->prepare($query)->execute($parms)->rowCount();
    }

}
