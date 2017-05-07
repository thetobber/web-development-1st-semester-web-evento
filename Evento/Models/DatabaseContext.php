<?php
namespace Evento\Models;

use PDO;
use PDOException;

/**
 * Represents a singlton which handles the connection between 
 * the application and a database server.
 */
class DatabaseContext
{
    /**
     * Static instance for the PDO class.
     */
    protected static $pdo;

    /**
     * Constructor with an access modifier of private to 
     * prevent instantiation.
     */
    private function __construct()
    {
    }

    /**
     * Creates a new instance of the PDO class or returns an 
     * existing instance if this method has already been 
     * invoked once before.
     */
    public static function getContext()
    {
        if (static::$pdo === null) {
            try {
                static::$pdo = new PDO(
                    'mysql:host=localhost;dbname=evento;charset=utf8',
                    'evento',
                    'nhQrQQzf7C6mTybsm47Hy4ae',
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );
            } catch (PDOException $e) {
                /*
                Implement something which does not make the 
                application die here.
                */

                header('HTTP/1.1 500 Internal Server Error', true);
                die('Could not connect to database.');
            }
        }
        
        return static::$pdo;
    }
}
