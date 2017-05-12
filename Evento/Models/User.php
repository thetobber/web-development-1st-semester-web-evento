<?php
namespace Evento\Models;

class User
{
    public $id;
    public $role;
    public $username;
    public $email;
    public $email_confirmed;
    public $password;

    public function __construct(array $user)
    {
        $this->id = '';
    }
}