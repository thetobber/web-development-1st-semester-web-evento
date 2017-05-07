<?php
namespace Evento\Models;

use Evento\Models\Validator;
use Evento\Models\UserRepository;

class Auth
{
    public static function signIn(array $params)
    {
        $repository = UserRepository::getInstance();

        try {
            Validator::signIn($params);
            
            $user = $repository
                ->get($params['email']);
            
            $password = base64_encode(
                hash('sha256', $params['password'], true)
            );

            if (password_verify($password, $user['password'])) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['username'],
                    'email' => $user['email']
                ];

                return true;
            }
        } catch (NestedValidationException $e) {
        }

        return false;
    }

    public static function signOut()
    {
        
    }
}