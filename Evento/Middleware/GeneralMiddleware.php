<?php
namespace Evento\Middleware;

class GeneralMiddleware extends AbstractMiddleware
{
    /**
     * PHP magic method which enables an object to invoked as 
     * a closure.
     */
    public function __invoke($request, $response, $next)
    {
        if ($this->container->authHandler->isVerified()) {
            $this->container->view
                ->getEnvironment()
                ->addGlobal('user', $_SESSION['user']);
        }

        return $next($request, $response);
    }
}