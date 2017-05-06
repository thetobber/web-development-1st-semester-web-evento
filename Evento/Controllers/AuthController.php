<?php
namespace Evento\Controllers;

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

        return $this->redirect($response, 'Auth.SignIn');
    }

    /**
     * Sign up user view
     */
    public function getSignUp($request, $response)
    {
        return $this->view($response, 'Auth/SignIn.html');
    }

    /**
     * Sign up an user
     */
    public function postSignUp($request, $response)
    {

        return $this->redirect($response, 'Auth.SignIn');
    }
}
