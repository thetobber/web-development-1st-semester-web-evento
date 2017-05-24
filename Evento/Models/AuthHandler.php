<?php
namespace Evento\Models;

use PDO;
use PDOException;
use Evento\Models\Validator;
use Respect\Validation\Exceptions\NestedValidationException;

/**
 * Defines an handler for checking if an user is signed in 
 * or has a role.
 */
class AuthHandler
{
    /**
     * Removes data about the user from the current session 
     * to sign out.
     */
    public function unsetUserSession()
    {
        //Unset the user array of data
        unset($_SESSION['user']);
    }

    public function setUserSession(array $user)
    {
        $_SESSION['user'] = [
            'username' => $user['username'],
            'email' => $user['email'],
            'role' => $user['role'],
            Role::NAME[$user['role']] => true,
        ];
    }

    public function setUserSessionKey($key, $value)
    {
        $_SESSION['user'][$key] = $value;
    }

    public function isVerified()
    {
        return isset($_SESSION['user']);
    }

    /**
     * Checks if the user has one of the roles supplied after
     * verifying that the user is already signed in.
     */
    public function hasRole($role)
    {
        return isset($_SESSION['user'][$role]) || isset($_SESSION['user']) && $_SESSION['user']['role'] === Role::ADMIN;
    }
}
