<?php
namespace Evento\Repositories;

abstract class AbstractRepository
{
    /**
     * Static instance of PDO.
     *
     * @var PDO
     */
    protected $handle;

    /**
     * Get static instance of PDO to communicate with the database.
     */
    public function __contruct()
    {
        $this->handle = DatabaseContext::getInstance();
    }
}