<?php
namespace Evento\Repositories;

class RepositoryResult
{
    protected $result;
    protected $code;
    protected $errorMessages;

    const ERROR = 0;
    const SUCCESS = 1;
    const NOT_FOUND = 2;

    public function __construct($result, $code, $errorMessages = [])
    {
        $this->result = $result;
        $this->code = $code;
        $this->errorMessages = $errorMessages;
    }

    public function getContent()
    {
        return $this->result;
    }

    public function hasContent()
    {
        return $this->code === self::SUCCESS && $this->result !== null;
    }

    public function hasSuccess()
    {
        return $this->code === self::SUCCESS;
    }

    public function notFound()
    {
        return $this->code === self::NOT_FOUND;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getErrorMessages()
    {
        return $this->errorMessages;
    }
}