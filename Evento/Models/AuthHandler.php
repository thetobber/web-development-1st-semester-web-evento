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
    public function performSignOut()
    {
        //Unset the user array of data
        unset($_SESSION['user']);
    }

    public function unsetUserSession()
    {
        unset($_SESSION['user']);
    }

    public function setUserSession(array $user)
    {
        $user[Role::NAME[$user['role']]] = true;
        $_SESSION['user'] = $user;
    }

    public function isVerified()
    {
        return isset($_SESSION['user']);
    }

    /**
     * Checks if the user has one of the roles supplied after 
     * verifying that the user is already signed in.
     */
    public function hasRole(...$roles)
    {
        if ($this->isVerified()) {
            foreach ($role as $roles) {
                if (isset($_SESSION['user'][$role])) {
                    return true;
                }
            }
        }

        return false;
    }
}
