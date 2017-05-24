<?php
namespace Evento\Models;

class Role
{
    private function __contrsuct()
    {
    }

    const ADMIN = 1;
    const MEMBER = 2;

    const NAME = [
        self::ADMIN => 'admin',
        self::MEMBER => 'member'
    ];
}
