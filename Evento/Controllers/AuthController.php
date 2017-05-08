<?php
namespace Evento\Controllers;

use PDO;
use PDOException;
use Evento\Models\DatabaseContext;
use Evento\Models\Validator;
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
        if ($this->authHandler->isVerified()) {
            return $this->redirect($response, 'Auth.Profile');
        }

        return $this->view($response, 'Auth/SignIn.html');
    }

    /**
     * Attempt to sign in user on POST request and does also
     * perform sign in of an user by taking the parameters from
     * the incoming request and attempts to validate the data
     * model, fetch the user from the database and compare the
     * incoming password against the password fetched from the
     * database.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function postSignIn($request, $response)
    {
        $params = $request->getParams();

        //Validate the incoming data
        try {
            Validator::signIn($params);
        } catch (NestedValidationException $e) {
            //Validation of data fails
        }
        
        $pdo = DatabaseContext::getContext();

        //Try to get the user by email from the database
        try {
            $statement = $pdo
                ->prepare('CALL getUserByEmail(?)');

            $statement->bindParam(1, $params['email']);
            $statement->execute();
        } catch (PDOException $e) {
            //Error performing data query
        }

        $user = $statement->fetch(PDO::FETCH_ASSOC);

        //Fetch returns false on error or not found
        if ($user !== false) {
            $password = base64_encode(
                hash('sha256', $params['password'], true)
            );

            if (password_verify($password, $user['password'])) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['username'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                    $user['role'] => true
                ];

                /*if (isset($params['remember_me'])) {
                    $expires = date('D, d M Y H:i:s e', strtotime('+1 months'));

                    $response = $response
                            ->withHeader('Set-Cookie', "3dtnx6xd=; HttpOnly; Path=/; SameSite=Strict; Expires=$expires");

                    var_dump($response->getHeaders());
                }*/

                //Sign in succeded
                return $this->redirect($response, 'Auth.Profile');
            }
        }

        //Wrong password, email or database error
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

        try {
            Validator::signUp($params);
        } catch (NestedValidationException $e) {
            $errors = $e->findMessages(Validator::ERRORS['signUp']);

            return $this->view($response, 'Auth/SignUp.html', [
                'params' => $params,
                'errors' => $errors
            ]);
        }

        $pdo = DatabaseContext::getContext();

        try {
            $statement = $pdo
                ->prepare('CALL insertUser(?, ?, ?)');

            $password = password_hash(
                base64_encode(
                    hash('sha256', $params['password'], true)
                ),
                PASSWORD_BCRYPT
            );

            $statement->bindParam(1, $params['username']);
            $statement->bindParam(2, $params['email']);
            $statement->bindParam(3, $password);

            $statement->execute();
        } catch (PDOException $e) {
            return $this->view($response, 'Auth/SignUp.html', [
                'params' => $params
            ]);
        }

        return $this->redirect($response, 'Auth.SignIn');
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

        if ($request->getCookieParam('3dtnx6xd') !== null) {
            //Remove cookie for remember me
            //Expiration format Mon, 01 Jan 1970 00:00:00 UTC or seconds
            $response = $response
                ->withHeader('Set-Cookie', '3dtnx6xd=; HttpOnly; Path=/; SameSite=Strict; Max-Age=0');
        }

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
    public function putProfile($request, $response)
    {
        $params = $request->getParams();
        $data = [
            'params' => $params,
            'errors' => []
        ];

        try {
            Validator::updateUser($params);
        } catch (NestedValidationException $e) {
            $data['errors'] = $e->findMessages(Validator::ERRORS['updateUser']);

            return $this->view($response, 'Auth/Profile.html', $data);
        }

        $pdo = DatabaseContext::getContext();

        //Try to get the user by id from the database
        try {
            $statement = $pdo
                ->prepare('CALL getUserById(?)');

            $statement->bindParam(1, $params['id']);
            $statement->execute();
        } catch (PDOException $e) {
            //Error performing data query
            $data['errors']['database'] = 'An unknown error occured.';
        }

        $user = $statement->fetch(PDO::FETCH_ASSOC);

        //Fetch returns false on error or not found
        if ($user !== false) {
            $password_old = base64_encode(
                hash('sha256', $params['password_old'], true)
            );

            if (password_verify($password_old, $user['password_old'])) {
                $_SESSION['user']['username'] = $params['username'];

                //return $this->redirect($response, 'Auth.Profile');
            } else {
                $data['errors']['password_old'] = 'Invalid password.';
            }
        } else {
            $data['errors']['notfound'] = 'Could not find user.';
        }

        try {
            $statement = $pdo
                ->prepare('CALL updateUser(?, ?, ?)');

            $password = password_hash(
                base64_encode(
                    hash('sha256', $params['password'], true)
                ),
                PASSWORD_BCRYPT
            );

            $statement->bindParam(1, $params['id']);
            $statement->bindParam(2, $params['username']);
            $statement->bindParam(3, $password);

            $statement->execute();
        } catch (PDOException $e) {
            return $this->view($response, 'Auth/SignUp.html', [
                'params' => $params
            ]);
        }

        return $this->redirect($response, 'Auth.SignIn');





        return $this->view($response, 'Auth/Profile.html');
    }
}
