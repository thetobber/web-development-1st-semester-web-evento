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
     */
    public function getSignIn($request, $response)
    {
        //If is signed in
        if ($this->authHandler->isVerified()) {
            return $this->redirect($response, 'Auth.Profile');
        }

        return $this->view($response, 'Auth/SignIn.html');
    }

    /**
     * Attempt to sign in user.
     */
    public function postSignIn($request, $response)
    {
        //If is signed in
        if ($this->authHandler->isVerified()) {
            return $this->redirect($response, 'Auth.Profile');
        }

        $params = $request->getParams();

        $result = $this->repository->read($params);

        if ($result->hasContent()) {
            $isVerified = password_verify(
                $params['password'],
                $result->getContent()['password']
            );

            if ($isVerified) {
                $this->authHandler
                    ->setUserSession($result->getContent());

                return $this->redirect($response, 'Auth.Profile');
            } else {
                return $this->view($response, 'Auth/SignIn.html', [
                    'params' => $params,
                    'errors' => ['credentials' => 'Wrong username or password.']
                ]);
            }
        }

        return $this->view($response, 'Auth/SignIn.html', [
            'params' => $params,
            'errors' => $result->getErrorMessages()
        ]);
    }

    /**
     * Return sign up view on GET request.
     */
    public function getSignUp($request, $response)
    {
        //If is signed in
        if ($this->authHandler->isVerified()) {
            return $this->redirect($response, 'Auth.Profile');
        }

        return $this->view($response, 'Auth/SignUp.html');
    }

    /**
     * Attempt to sign up and create a new user on POST request.
     */
    public function postSignUp($request, $response)
    {
        //If is signed in
        if ($this->authHandler->isVerified()) {
            return $this->redirect($response, 'Auth.Profile');
        }

        $params = $request->getParams();

        $result = $this->repository->create($params);

        if ($result->hasSuccess()) {
            return $this->redirect($response, 'Auth.SignIn');
        }

        return $this->view($response, 'Auth/SignUp.html', [
            'params' => $params,
            'errors' => $result->getErrorMessages()
        ]);
    }

    /**
     * Sign out by removing data about the user from the current
     * session and destroying the "remember me" cookie if it has
     * been set on a sign in.
     */
    public function getSignOut($request, $response)
    {
        //If not signed in
        if (!$this->authHandler->isVerified()) {
            return $this->redirect($response, 'Auth.SignIn');
        }

        //Unset the user array of data
        $this->authHandler->unsetUserSession();
        return $this->redirect($response, 'Auth.Profile');
    }

    /**
     * View user profile
     */
    public function getProfile($request, $response)
    {
        //If not signed in
        if (!$this->authHandler->isVerified()) {
            return $this->redirect($response, 'Auth.SignIn');
        }

        return $this->view($response, 'Auth/Profile.html');
    }

    /**
     * Update user profile
     */
    public function postProfile($request, $response)
    {
        //If not signed in
        if (!$this->authHandler->isVerified()) {
            return $this->redirect($response, 'Auth.SignIn');
        }

        $params = $request->getParams();
        $params['username'] = $_SESSION['user']['username'];

        $result = $this->repository->update($params);

        if ($result->hasSuccess()) {
            $this->authHandler->setUserSessionKey('email', $params['email']);
            return $this->redirect($response, 'Auth.Profile');
        }

        return $this->view($response, 'Auth/Profile.html', [
            'params' => $params,
            'errors' => $result->getErrorMessages()
        ]);
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
