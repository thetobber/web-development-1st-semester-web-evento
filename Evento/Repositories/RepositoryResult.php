<?php
namespace Evento\Repositories;

use Evento\Repositories\RepositoryError as Error;

class RepositoryResult
{
    protected $result;
    protected $errorCode;
    protected $errorMessage;

    public function __construct($result, $errorCode = Error::DEFAULT, $errorMessage = Error::REASON[Error::DEFAULT])
    {
        $this->result = $result;
        $this->errorCode = $errorCode;
        $this->errorMessage = $errorMessage;
    }

    public function hasSuccess()
    {
        return $this->errorCode === Error::SUCCESS;
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }
}