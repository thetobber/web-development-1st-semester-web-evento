<?php
namespace Evento\Models;

use PDO;
use PDOException;
use Evento\Config;

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
        if (static::$pdo === null) {
            try {
                static::$pdo = new PDO(
                    sprintf(
                        'mysql:host=%s;dbname=%s;charset=%s',
                        Config::DB_HOST,
                        Config::DB_NAME,
                        Config::DB_CHARSET
                    ),
                    Config::DB_USER,
                    Config::DB_PASS,
                    [
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '".Config::TIMEZONE."';",
                        PDO::ATTR_PERSISTENT => true,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_EMULATE_PREPARES => true
                    ]
                );
            } catch (PDOException $exception) {
                header('HTTP/1.1 500 Internal Server Error', true);
                die('Could not connect to the database.');
            }
        }
        
        return static::$pdo;
    }

    public static function closeConnection()
    {
        $this->pdo = null;
    }
}
