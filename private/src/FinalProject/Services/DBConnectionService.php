<?php

namespace FinalProject\Services;

use PDO;
use Teacher\GivenCode\Exceptions\RuntimeException;

class DBConnectionService {
    private const DB_NAME = "420dw3_finalproject_aprb";
    private static ?PDO $connection;
    
    /**
     * @return PDO
     */
    public static function getConnection() : PDO {
        try {
            self::$connection ??= new PDO("mysql:dbname=" . self::DB_NAME . ";host=" . $_SERVER["HTTP_HOST"], "root", "");
            return self::$connection;
        } catch (\PDOException $exception) {
            throw new RuntimeException("Failure to connect to the database: " . $exception->getMessage());
        }
    }
}