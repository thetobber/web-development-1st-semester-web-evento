<?php
namespace Evento\Controllers;

class AuthController extends Controller
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
