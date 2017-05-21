<?php
namespace Evento\Models;

class Role
{
    private function __contrsuct()
    {
    }

    const ADMIN = 1;
    const EDITOR = 2;
    const MEMBER = 3;

    const NAME = [
        self::ADMIN => 'admin',
        self::EDITOR => 'editor',
        self::MEMBER => 'member'
    ];
}
