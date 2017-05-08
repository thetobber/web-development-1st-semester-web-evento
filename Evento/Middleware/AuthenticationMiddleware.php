<?php
namespace Evento\Middleware;

use Evento\Models\Authentication;

class AuthenticationMiddleware extends AbstractMiddleware
{
    /**
     * PHP magic method which enables an object to invoked as 
     * a closure.
     */
    public function __invoke($request, $response, $next)
    {
        if (Authentication::isVerified()) {
            $this->container->view
                ->getEnvironment()
                ->addGlobal('user', $_SESSION['user']);
        }

        return $next($request, $response);
    }
}