<?php
namespace Evento\Models;

use PDO;
use PDOException;

class UserRepository extends AbstractRepository
{
    /**
     *
     */
    public function create(array $data)
    {
        try {
            $statement = $this->pdo
                ->prepare('INSERT INTO `users` (`username`, `email`, `password`) VALUES (?, ?, ?)');

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
        } catch (PDOException $e) {
            return false;
        }

        return true;
    }

    public function get($email)
    {
        try {
            $statement = $this->pdo
                ->prepare('SELECT * FROM `users` WHERE `email` = ?');

            $statement->bindParam(1, $email, PDO::PARAM_STR, 255);
            $statement->execute();
        } catch (PDOException $e) {
            return null;
        }

        if (($user = $statement->fetch(PDO::FETCH_ASSOC)) !== false) {
            return $user;
        }

        return null;
    }
}
