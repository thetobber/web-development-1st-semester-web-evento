<?php
namespace Evento\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class AuthMiddleware extends AbstractMiddleware
{
    /**
     * PHP magic method which enables an object to called as 
     * a closure.
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        if (isset($_SESSION['user'])) {
            $this->container->view
                ->getEnvironment()
                ->addGlobal('user', $_SESSION['user']);
        }

        return $next($request, $response);
    }
}