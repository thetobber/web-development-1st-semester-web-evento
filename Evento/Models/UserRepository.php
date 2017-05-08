<?php
namespace Evento\Models;

use PDO;
use PDOException;

class UserRepository
{
    private static $instance;
    private $pdo;

    private function __construct()
    {
        $this->pdo = DatabaseContext::getContext();
    }

    /**
     * Creates a new instance of this class or returns an 
     * existing instance if this method has already been 
     * invoked once before.
     */
    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        
        return static::$instance;
    }

    /**
     *
     */
    public function insertUser(array $data)
    {
        $statement = $this->pdo
            ->prepare('CALL insertUser(?, ?, ?)');

        $password = password_hash(
            base64_encode(
                hash('sha256', $data['password'], true)
            ),
            PASSWORD_BCRYPT
        );

        $statement->bindParam(1, $data['username'], PDO::PARAM_STR, 255);
        $statement->bindParam(2, $data['email'], PDO::PARAM_STR, 255);
        $statement->bindParam(3, $password);

        $statement->execute();
        //return $this->pdo->lastInsertId();
    }

    public function get($email)
    {
        try {
            $statement = $this->pdo
                ->prepare('SELECT * FROM `users` WHERE `email` = ?');

            $statement->bindParam(1, $email, PDO::PARAM_STR, 255);
            $statement->execute();
        } catch (PDOException $e) {
        }

        if (($user = $statement->fetch(PDO::FETCH_ASSOC)) !== false) {
            return $user;
        }

        return null;
    }
}
