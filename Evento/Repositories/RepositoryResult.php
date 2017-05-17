<?php
namespace Evento\Repositories;

class RepositoryResult
{
    protected $result;
    protected $errorCode;
    protected $errorMessage;

    const DEFAULT = 0;
    const SUCCESS = 1;
    const DUPLICATE = 2;

    public function __construct($result, $errorCode, $errorMessage)
    {
        $this->result = $result;
        $this->errorCode = $errorCode;
        $this->errorMessage = $errorMessage;
    }

    public function hasSuccess()
    {

    }

    public function getErrorCode()
    {

    }

    public function getErrorMessage()
    {

    }
}