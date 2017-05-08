<?php
namespace Evento\Models;

use PDO;
use PDOException;
use Evento\Models\Validator;
use Evento\Models\UserRepository;
use Respect\Validation\Exceptions\NestedValidationException;

class Authentication
{
    /**
     * Perform sign in of an user by taking the parameters from 
     * the incoming request and attempts to validate the data 
     * model, fetch the user from the database and compare the 
     * incoming password against the password fetched from the 
     * database.
     *
     * @return boolean
     */
    public static function performSignIn(array $params = [])
    {
        //Return true if already signed in
        if (static::isVerified()) {
            return true;
        }

        $pdo = DatabaseContext::getContext();

        //Validate the incoming data
        try {
            Validator::signIn($params);
        } catch (NestedValidationException $e) {
            //Validation of data fails
            return false;
        }

        //Try to get the user by email from the database
        try {
            $statement = $pdo
                ->prepare('SELECT * FROM `users` WHERE `email` = ?');

            $statement->bindParam(1, $params['email']);
            $statement->execute();
        } catch (PDOException $e) {
            var_dump($e);
            //Error performing data query
            return false;
        }

        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if ($user === false) {
            //Could not fetch user
            return false;
        }

        $password = base64_encode(
            hash('sha256', $params['password'], true)
        );

        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['username'],
                'email' => $user['email'],
                'role' => $user['role']
            ];

            //Sign in succeded
            return true;
        }

        //Wrong password or email
        return false;
    }

    /**
     * Removes data about the user from the current session 
     * to sign out.
     */
    public static function performSignOut()
    {
        //Unset the user array of data
        unset($_SESSION['user']);
    }

    public static function isVerified()
    {
        return isset($_SESSION['user']);
    }

    /**
     * Checks if the user has one of the roles supplied after 
     * verifying that the user is already signed in.
     */
    public static function hasRole(...$roles)
    {
        if (static::isVerified()) {
            foreach ($role as $roles) {
                if (isset($_SESSION['user'][$role])) {
                    return true;
                }
            }
        }

        return false;
    }
}
