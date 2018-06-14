<?php

# ======================================================================================================= #

namespace App\M;

use \PDO;

class Database
{
    private static $connection;
    private static $settings = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_EMULATE_PREPARES => false,
    );

    /**
     * Connect to DB
     *
     */
    public static function connect($host, $user, $password, $database) {
        if (!isset(self::$connection)) {
            try {
                self::$connection = @new \PDO("mysql:host=$host;dbname=$database", $user, $password, self::$settings);
            } catch (PDOException $e) {
                debug($e->getMessage());
                die();
            }
        }
    }

    /**
     * Close connection to DB
     *
     */
    public static function close() {
        if (isset(self::$connection)) {
            self::$connection = null;
        }
    }

    /**
     * Select few rows
     *
     */
    public static function getArray($query, $parms = array()) {
        $result = self::$connection->prepare($query);
        $result->execute($parms);

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Select one row
     *
     */
    public static function getRow($query, $parms = array(), $method = PDO::FETCH_ASSOC) {
        $result = self::$connection->prepare($query);
        $result->execute($parms);

        return $result->fetch($method);
    }

    /**
     * Select one value from one column
     *
     */
    public static function getOne($query, $parms = array()) {
        $result = self::getRow($query, $parms, PDO::FETCH_NUM);
        return $result ? $result[0] : false;
    }

    /**
     * Executes an SQL statement
     *
     */
    public static function query($query, $parms = array()) {
        $result = self::$connection->prepare($query);
        $result->execute($parms);

        return $result->rowCount();
    }

    /**
     * Quotes a string for use in a query
     *
     */
     public static function quote($string) {
         return self::$connection->quote($string);
     }
}
