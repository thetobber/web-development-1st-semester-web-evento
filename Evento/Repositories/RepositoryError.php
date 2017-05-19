<?php
namespace Evento\Repositories;

class RepositoryError
{
    private function __construct()
    {
    }

    const DEFAULT = 0;
    const SUCCESS = 1;
    const DUPLICATE = 2;

    const REASON = [
        self::DEFAULT => 'An unhandled error occurred.',
        self::SUCCES => 'Successfully completed.',
        self::DUPLICATE => 'Duplicate key on unique index constraint.'
    ];
}