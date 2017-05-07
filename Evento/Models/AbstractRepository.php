<?php
namespace Evento\Models;

abstract class AbstractRepository
{
    private static $instance;
    protected $pdo;

    private function __construct()
    {
        $this->pdo = DatabaseContext::getContext();
    }

    /**
     * Creates a new instance of this classor returns an existing 
     * instance if this method has already been invoked once before.
     */
    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        
        return static::$instance;
    }
}