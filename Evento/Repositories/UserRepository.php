<?php
namespace Evento\Repositories;

use PDO;
use PDOException;
use Evento\Models\DatabaseContext;

/**
 * Represents a repository layer for CRUD actions on the users. 
 * These actions are initiated with prepared statements and then 
 * executed with a stored procedure by the database system.
 */
class UserRepository extends AbstractRepository
{
    /**
     * Create a single user and insert it into the database.
     *
     * @param array $user
     * @return null|PDOException
     */
    public function create(array $user)
    {
        try {
            $statement = $this->handle->prepare('CALL createUser(?, ?, ?)');

            $password = password_hash(
                base64_encode(hash('sha256', $user['password'], true)),
                PASSWORD_BCRYPT
            );

            $statement->bindValue(1, $user['username'], PDO::PARAM_STR);
            $statement->bindValue(2, $user['email'], PDO::PARAM_STR);
            $statement->bindValue(3, $password);

            $statement->execute();
        } catch (PDOException $exeption) {
            return $exeption;
        }

        $statement->closeCursor();
        return null;
    }

    /**
     * Read a single user record from the database by email.
     *
     * @param string $email
     * @return array|null|PDOException
     */
    public function read($email)
    {
        try {
            $statement = $this->handle->prepare('CALL readUser(?)');
            $statement->bindValue(1, $email, PDO::PARAM_STR);

            $statement->execute();
        } catch (PDOException $exeption) {
            return $exeption;
        }

        $user = $statement->fetch(PDO::FETCH_ASSOC);
        $statement->closeCursor();

        if ($user !== false) {
            return $user;
        }

        return null;
    }

    /**
     * Read multiple user records from the database filtered by a 
     * limit and an offset.
     *
     * @param int $limit
     * @param int $offset
     * @return array|PDOException
     */
    public function readAll($limit = 20, $offset = 0)
    {
        try {
            $statement = $this->handle->prepare('CALL readUsers(?, ?)');
            $statement->bindValue(1, $limit, PDO::PARAM_INT);
            $statement->bindValue(2, $offset, PDO::PARAM_INT);

            $statement->execute();
        } catch (PDOException $exeption) {
            return $exeption;
        }

        $users = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();

        if ($users !== false) {
            return $users;
        }

        return null;
    }

    /**
     * Update a single user record from the database by email.
     *
     * @param array $user
     * @return null|PDOException
     */
    public function update(array $user)
    {
        try {
            $statement = $this->handle->prepare('CALL updateUser(?, ?, ?)');

            $password = password_hash(
                base64_encode(hash('sha256', $user['password'], true)),
                PASSWORD_BCRYPT
            );

            $statement->bindValue(1, $user['email'], PDO::PARAM_STR);
            $statement->bindValue(2, $user['username'], PDO::PARAM_STR);
            $statement->bindValue(3, $password);

            $statement->execute();
        } catch (PDOException $exeption) {
            return $exeption;
        }

        $statement->closeCursor();
        return null;
    }

    /**
     * Delete a single user record from the database by email.
     *
     * @param string $email
     * @return null|PDOException
     */
    public function delete($email)
    {
        try {
            $statement = $this->handle->prepare('CALL deleteUser(?)');
            $statement->bindValue(1, $email, PDO::PARAM_STR);

            $statement->execute();
        } catch (PDOException $exeption) {
            return $exeption;
        }

        $statement->closeCursor();
        return null;
    }
}
