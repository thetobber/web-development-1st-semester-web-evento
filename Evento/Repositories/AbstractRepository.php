<?php
namespace Evento\Repositories;

use Evento\Models\DatabaseContext;

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
    public function __construct()
    {
        $this->handle = DatabaseContext::getInstance();
    }
}