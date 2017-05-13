<?php
namespace Evento\Controllers;

use PDO;
use PDOException;
use Evento\Models\DatabaseContext;
use Evento\Repositories\UserRepository;
use Evento\Models\Validator;
use Evento\Models\AuthHandler;
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
        $this->repository = new UserRepository();
    }

    /**
     * Return sign in view on GET request.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function getSignIn($request, $response)
    {
        return $this->view($response, 'Auth/SignIn.html');
    }

    /**
     * Attempt to sign in user.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function postSignIn($request, $response)
    {
        $params = $request->getParams();
        $data = ['params' => $params];

        try {
            Validator::signIn($params);
        } catch (NestedValidationException $e) {
            //Validation error
            $data['validator'] = Validator::ERRORS['signIn'];
            return $this->view($response, 'Auth/SignIn.html', $data);
        }

        $result = $this->repository->read($params['email']);

        if ($result instanceof PDOException) {
            //Database error
            $data['database'] = 'An unexpected error occurred.';
            return $this->view($response, 'Auth/SignIn.html', $data);
        }

        if ($result === null) {
            //Not found
            $data['validator'] = Validator::ERRORS['signIn'];
            return $this->view($response, 'Auth/SignIn.html', $data);
        }

        $password = base64_encode(
            hash('sha256', $params['password'], true)
        );

        if (password_verify($password, $result['password'])) {
            $_SESSION['user'] = [
                'id' => $result['id'],
                'name' => $result['username'],
                'email' => $result['email'],
                'role' => $result['role'],
                $result['role'] => true
            ];

            return $this->redirect($response, 'Auth.Profile');
        }

        $data['validator'] = Validator::ERRORS['signIn'];
        return $this->view($response, 'Auth/SignIn.html', $data);
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
        $params = $request->getParams();
        $data = ['params' => $params];

        try {
            Validator::signUp($params);
        } catch (NestedValidationException $e) {
            $data['validator'] = $e->findMessages(Validator::ERRORS['signUp']);

            return $this->view($response, 'Auth/SignUp.html', $data);
        }

        $result = $this->repository->create($params);

        if ($result === null) {
            return $this->redirect($response, 'Auth.SignIn');
        }

        if ($result instanceof PDOException) {
            if ($result->getCode() === '23000') {
                $data['database'] = 'Please use another e-mail address.';
            } else {
                $data['database'] = 'An unexpected error occurred.';
            }
        }

        return $this->view($response, 'Auth/SignUp.html', $data);
    }

    /**
     * Sign out by removing data about the user from the current
     * session and destroying the "remember me" cookie if it has
     * been set on a sign in.
     */
    public function getSignOut($request, $response)
    {
        //Unset the user array of data
        unset($_SESSION['user']);
        return $this->redirect($response, 'Main');
    }

    /**
     * View user profile
     */
    public function getProfile($request, $response)
    {
        return $this->view($response, 'Auth/Profile.html');
    }

    /**
     * Update user profile
     */
    public function postProfile($request, $response)
    {
        $params = $request->getParams();
        $params['email'] = $_SESSION['user']['email'];

        $data = ['params' => $params];

        try {
            Validator::updateUser($params);
        } catch (NestedValidationException $e) {
            $data['validator'] = $e->findMessages(Validator::ERRORS['updateUser']);

            return $this->view($response, 'Auth/Profile.html', $data);
        }

        $result = $this->repository->update($params);

        if ($result === null) {
            $_SESSION['user']['name'] = $params['username'];
            return $this->redirect($response, 'Auth.Profile');
        }

        if ($result instanceof PDOException) {
            $data['database'] = $result->getMessage();
        }

        return $this->view($response, 'Auth/Profile.html', $data);
    }
}

/*
//Check for cookie with auth token

if ($request->getCookieParam('3dtnx6xd') !== null) {
    //Remove cookie for remember me
    //Expiration format Mon, 01 Jan 1970 00:00:00 UTC or seconds
    $response = $response
        ->withHeader('Set-Cookie', '3dtnx6xd=; HttpOnly; Path=/; SameSite=Strict; Max-Age=0');
}
*/

/*
//Set cookie with auth token

if (isset($params['remember_me'])) {
    $expires = date('D, d M Y H:i:s e', strtotime('+1 months'));
    $response = $response>withHeader('Set-Cookie', "3dtnx6xd=; HttpOnly; Path=/; SameSite=Strict; Expires=$expires");
}
*/