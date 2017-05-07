<?php
namespace Evento\Middleware;

use Slim\Container;

abstract class AbstractMiddleware
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }
}
