<?php
namespace Evento\Controllers;

use Evento\Repositories\UserRepository;
use Evento\Models\Role;

class MainController extends AbstractController
{
    public function getUserList($request, $response)
    {
        if (!$this->authHandler->hasRole('admin')) {
            return $this->redirect($response, 'Auth.SignIn');
        }

        $repo = new UserRepository();

        $users = $repo->readAll();

        if ($users->hasContent()) {
            return $this->view($response, 'Main/UserList.html', [
                'users' => $users->getContent(),
                'role' => Role::NAME
            ]);
        }

        return $this->view($response, 'Static/500.html');
    }

    public function deleteUser($request, $response, $args)
    {
        if (!$this->authHandler->hasRole('admin')) {
            return $this->redirect($response, 'Auth.SignIn');
        }

        $repo = new UserRepository();

        $result = $repo->delete($args['name']);

        if ($result->hasSuccess()) {
            return $this->redirect($response, 'Main.UserList');
        }

        return $this->view($response, 'Static/500.html');
    }

    public function promoteUser($request, $response, $args)
    {
        if (!$this->authHandler->hasRole('admin')) {
            return $this->redirect($response, 'Auth.SignIn');
        }

        $repo = new UserRepository();

        $result = $repo->setRole($args['name'], Role::ADMIN);

        if ($result->hasSuccess()) {
            return $this->redirect($response, 'Main.UserList');
        }

        return $this->view($response, 'Static/500.html');
    }

    public function demoteUser($request, $response, $args)
    {
        if (!$this->authHandler->hasRole('admin')) {
            return $this->redirect($response, 'Auth.SignIn');
        }

        $repo = new UserRepository();

        $result = $repo->setRole($args['name'], Role::MEMBER);

        if ($result->hasSuccess()) {
            return $this->redirect($response, 'Main.UserList');
        }

        return $this->view($response, 'Static/500.html');
    }
}
