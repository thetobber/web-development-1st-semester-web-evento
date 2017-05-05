<?php
namespace Evento\Models;

use PDO;
use PDOException;

/**
 * Represents a singlton which handles the connection between the application and
 * a database server.
 */
class DbContext
{
    /**
     *
     */
    protected static $pdo;

    /**
     * Constructor with an access modifier of private to prevent instantiation.
     */
    private function __construct()
    {
    }

    /**
     *
     */
    public static function getContext()
    {
        if (static::$pdo === null) {
            try {
                static::$pdo = new PDO(
                    'mysql:host=localhost;dbname=evento;charset=utf8',
                    'evento',
                    '123',
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );
            } catch (PDOException $e) {
                header('HTTP/1.1 500 Internal Server Error', true);
                die('Could not connect to database.');
            }
        }
        
        return static::$pdo;
    }
}
