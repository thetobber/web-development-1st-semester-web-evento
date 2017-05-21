<?php
namespace Evento\Repositories;

use PDO;
use PDOException;
use Evento\Models\DatabaseContext;
use Evento\Models\Role;
use Evento\Repositories\RepositoryResult as Result;
use Respect\Validation\Validator as Respect;
use Respect\Validation\Exceptions\NestedValidationException;

/**
 * Represents a repository layer for CRUD actions on the users.
 * These actions are initiated with prepared statements and then
 * executed with a stored procedure by the database system.
 */
class UserRepository extends AbstractRepository
{
    /**
     * Create a single user and insert it into the database.
     */
    public function create(array $user)
    {
        //Create rule set for model
        $ruleSet = Respect::arrayType()
            ->key('username', Respect::noWhiteSpace()->length(1, 250))
            ->key('email', Respect::email())
            ->key('password', Respect::length(12, 4096))
            ->keyValue('password_confirmation', 'equals', 'password');
        
        //Validate model with ruleset
        try {
            $ruleSet->assert($user);
        } catch (NestedValidationException $exception) {
            $errorList = $exception->findMessages([
                'username' => 'Username must be 1-250 characters containing no whitespace.',
                'email' => 'Please enter a valid e-mail address.',
                'password' => 'Password must be 12-4096 characters.',
                'password_confirmation' => 'Confirmation must be equal to password.'
            ]);

            return new Result(null, Result::ERROR, $errorList);
        }

        //Attempt to create the new record
        try {
            $statement = $this->handle->prepare('CALL createUser(?, ?, ?, ?)');

            $password = password_hash($user['password'], PASSWORD_BCRYPT);

            $statement->bindValue(1, Role::MEMBER, PDO::PARAM_INT);
            $statement->bindValue(2, $user['username'], PDO::PARAM_STR);
            $statement->bindValue(3, $user['email'], PDO::PARAM_STR);
            $statement->bindValue(4, $password);

            $statement->execute();
        } catch (PDOException $exception) {
            $errorList = [];

            if ($exception->getCode() == '23000') {
                $errorList['username'] = 'Username already exists.';
            } else {
                $errorList['database'] = 'An unexpected error occurred.';
            }

            return new Result(null, Result::ERROR, $errorList);
        }

        $statement->closeCursor();
        return new Result(null, Result::SUCCESS);
    }

    /**
     * Read a single user record from the database by username.
     */
    public function read($username)
    {
        $errorList = [];

        try {
            $statement = $this->handle->prepare('CALL readUser(?)');
            $statement->bindValue(1, $username, PDO::PARAM_STR);

            $statement->execute();
        } catch (PDOException $exeption) {
            return new Result(null, Result::ERROR, $errorList);
        }

        $user = $statement->fetch(PDO::FETCH_ASSOC);
        $statement->closeCursor();

        if ($user !== false) {
            return new Result($user, Result::SUCCESS);
        }

        return new Result(null, Result::NOT_FOUND);
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

            $password = password_hash($user['password'], PASSWORD_BCRYPT);

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
