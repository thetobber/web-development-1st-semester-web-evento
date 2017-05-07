<?php
namespace Evento\Controllers;

use Evento\Models\Validator;
use Evento\Models\UserRepository;
use Respect\Validation\Exceptions\NestedValidationException;

/**
 * Defines a layer of security with the purpose of authentication
 * users, checking their roles and deciding what they can access.
 */
class AuthController extends AbstractController
{
    protected $repository;

    public function __construct($container)
    {
        parent::__construct($container);
        $this->repository = UserRepository::getInstance();
    }

    /**
     * Sign in user view
     */
    public function getSignIn($request, $response)
    {
        if (isset($_SESSION['user'])) {
            return $this->redirect($response, 'Auth.Profile');
        }

        return $this->view($response, 'Auth/SignIn.html');
    }

    /**
     * Sign in an user by validating the request parameters.
     */
    public function postSignIn($request, $response)
    {
        if (isset($_SESSION['user'])) {
            return $this->redirect($response, 'Auth.Profile');
        }

        $params = $request->getParams();

        try {
            Validator::signIn($params);
            
            $user = $this->repository
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

                return $this->redirect($response, 'Main');
            }
        } catch (NestedValidationException $e) {
        }

        return $this->view($response, 'Auth/SignIn.html', [
            'params' => $params,
            'error' => Validator::ERRORS['signIn']
        ]);
    }

    /**
     * Sign up user view
     */
    public function getSignUp($request, $response)
    {
        if (isset($_SESSION['user'])) {
            return $this->redirect($response, 'Auth.Profile');
        }

        return $this->view($response, 'Auth/SignUp.html');
    }

    /**
     * Sign up an user
     */
    public function postSignUp($request, $response)
    {
        if (isset($_SESSION['user'])) {
            return $this->redirect($response, 'Main');
        }

        $params = $request->getParams();

        try {
            Validator::signUp($params);
            $this->repository->create($params);
        } catch (NestedValidationException $e) {
            $errors = $e->findMessages(Validator::ERRORS['signUp']);

            return $this->view($response, 'Auth/SignUp.html', [
                'params' => $params,
                'errors' => $errors
            ]);
        }

        return $this->redirect($response, 'Main');
    }

    /**
     * Sign out an user
     */
    public function getSignOut($request, $response)
    {
        unset($_SESSION['user']);
        //session_unset();
        //session_destroy();

        return $this->redirect($response, 'Main');
    }

    /**
     * View user profile
     */
    public function getProfile($request, $response)
    {
        if (!isset($_SESSION['user'])) {
            return $this->redirect($response, 'Main');
        }

        return $this->view($response, 'Auth/Profile.html');
    }

    /**
     * Update user profile
     */
    public function putProfile($request, $response)
    {
        if (!isset($_SESSION['user'])) {
            return $this->redirect($response, 'Main');
        }

        return $this->view($response, 'Auth/Profile.html');
    }
}
