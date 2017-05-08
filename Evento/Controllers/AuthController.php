<?php
namespace Evento\Controllers;

use PDO;
use PDOException;
use DateTime;
use Evento\Models\Validator;
use Evento\Models\Authentication;
use Evento\Models\UserRepository;
use Respect\Validation\Exceptions\NestedValidationException;

/**
 * Defines a layer of security with the purpose of authentication
 * users, checking their roles and deciding what they can access.
 */
class AuthController extends AbstractController
{
    /**
     * Return sign in view on GET request.
     */
    public function getSignIn($request, $response)
    {
        if (Authentication::isVerified()) {
            return $this->redirect($response, 'Auth.Profile');
        }

        return $this->view($response, 'Auth/SignIn.html');
    }

    /**
     * Attempt to sign in user on POST request.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function postSignIn($request, $response)
    {
        $params = $request->getParams();

        if (Authentication::performSignIn($params)) {
            //Set cookie if remember_me
            if (isset($params['remember_me'])) {
                //set Secure
                /*$response = $response
                    ->withHeader('Set-Cookie', 'test=value; HttpOnly; Path=/; SameSite=Strict');*/
            }

            return $this->redirect($response, 'Auth.Profile');
        }

        return $this->view($response, 'Auth/SignIn.html', [
            'params' => $params,
            'error' => Validator::ERRORS['signIn']
        ]);
    }

    /**
     * Return sign up view on GET request.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function getSignUp($request, $response)
    {
        if (Authentication::isVerified()) {
            return $this->redirect($response, 'Auth.Profile');
        }

        return $this->view($response, 'Auth/SignUp.html');
    }

    /**
     * Attempt to sign up and create a new user on POST request.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function postSignUp($request, $response)
    {
        if (Authentication::isVerified()) {
            return $this->redirect($response, 'Auth.Profile');
        }

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

        $repository = UserRepository::getInstance();

        try {
            $repository->insertUser($params);
        } catch (PDOException $e) {
            return $this->view($response, 'Auth/SignUp.html', [
                'params' => $params,
                'errors' => [
                    'database' => 'Failed to create account.'
                ]
            ]);
        }

        return $this->redirect($response, 'Auth.SignIn');
    }

    /**
     * Sign out an user
     */
    public function getSignOut($request, $response)
    {
        Authentication::performSignOut();
        return $this->redirect($response, 'Main');
    }

    /**
     * View user profile
     */
    public function getProfile($request, $response)
    {
        if (!Authentication::isVerified()) {
            return $this->redirect($response, 'Auth.SignIn');
        }

        return $this->view($response, 'Auth/Profile.html');
    }

    /**
     * Update user profile
     */
    public function putProfile($request, $response)
    {
        if (!Authentication::isVerified()) {
            return $this->redirect($response, 'Auth.SignIn');
        }

        return $this->view($response, 'Auth/Profile.html');
    }
}
