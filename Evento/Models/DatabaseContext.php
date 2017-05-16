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
    public static function getInstance()
    {
        //PDO::MYSQL_ATTR_INIT_COMMAND

        if (static::$pdo === null) {
            try {
                static::$pdo = new PDO(
                    'mysql:host=localhost;dbname=evento;charset=utf8',
                    'evento',
                    'nhQrQQzf7C6mTybsm47Hy4ae',
                    [
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = 'Europe/Copenhagen';",
                        PDO::ATTR_PERSISTENT => true,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_EMULATE_PREPARES => true
                    ]
                );
            } catch (PDOException $exception) {
                /*
                Implement something which does not make the 
                application die here.
                */

                //return $exception;
                var_dump($exception);

                header('HTTP/1.1 500 Internal Server Error', true);
                die('Could not connect to database.');
            }
        }
        
        return static::$pdo;
    }

    public static function closeConnection()
    {
        $this->pdo = null;
    }
}
