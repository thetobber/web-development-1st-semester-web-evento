<?php
namespace Evento\Controllers;

use Evento\Validation\Validator;
use Respect\Validation\Exceptions\ValidationException;

/**
 * Defines a layer of security with the purpose of authentication
 * users, checking their roles and deciding what they can access.
 */
class AuthController extends AbstractController
{
    /**
     * Sign in user view
     */
    public function getSignIn($request, $response)
    {
        return $this->view($response, 'Auth/SignIn.html');
    }

    /**
     * Sign in an user
     */
    public function postSignIn($request, $response)
    {
        $signIn = Validator::signIn();
        $params = $request->getParams();

        if ($signIn->validate($params)) {
            return $this->redirect($response, 'Main');
        }

        return $this->view($response, 'Auth/SignIn.html', $params);
    }

    /**
     * Sign up user view
     */
    public function getSignUp($request, $response)
    {
        /*$signIn = Validator::signIn();
        $signUp = Validator::signUp();

        try {
            $signUpCheck = $signUp->check([
                'username' => 'tobias',
                'email' => 'mail@tobymw.dk',
                'password' => '12345678',
                'password_confirmation' => '12345678'
            ]);
        } catch (ValidationException $e) {
            var_dump($e->getMessage());
        }*/

        return $this->view($response, 'Auth/SignUp.html');
    }

    /**
     * Sign up an user
     */
    public function postSignUp($request, $response)
    {
        $signUp = Validator::signUp();
        $params = $request->getParams();

        if ($signUp->validate($params)) {
            return $this->redirect($response, 'Main');
        }

        return $this->view($response, 'Auth/SignUp.html', $params);
    }
}
