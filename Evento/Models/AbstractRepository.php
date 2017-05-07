<?php
namespace Evento\Models;

abstract class AbstractRepository
{
    protected $pdo;

    public function __construct()
    {
        $this->pdo = DatabaseContext::getContext();
    }
}