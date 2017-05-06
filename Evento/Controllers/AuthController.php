<?php
namespace Evento\Controllers;

use Evento\Validation\Validator;
use Respect\Validation\Exceptions\NestedValidationException;

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
     * Sign in an user by validating the request parameters.
     */
    public function postSignIn($request, $response)
    {
        $params = $request->getParams();

        try {
            Validator::signIn($params);
        } catch (NestedValidationException $e) {
            $errors = $e->findMessages(Validator::ERRORS['signIn']);

            return $this->view($response, 'Auth/SignIn.html', [
                'params' => $params,
                'errors' => $errors
            ]);
        }

        return $this->redirect($response, 'Main');
    }

    /**
     * Sign up user view
     */
    public function getSignUp($request, $response)
    {
        return $this->view($response, 'Auth/SignUp.html');
    }

    /**
     * Sign up an user
     */
    public function postSignUp($request, $response)
    {
        $params = $request->getParams();

        try {
            Validator::signUp($params);
        } catch (NestedValidationException $e) {
            $errors = $e->findMessages(Validator::ERRORS['signUp']);

            return $this->view($response, 'Auth/SignUp.html', [
                'params' => $params,
                'errors' => $errors
            ]);
        }

        return $this->redirect($response, 'Main');
    }
}
