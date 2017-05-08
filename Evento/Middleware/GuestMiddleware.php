<?php
namespace Evento\Middleware;

use Evento\Models\Authentication;

class GuestMiddleware extends AbstractMiddleware
{
    /**
     * PHP magic method which enables an object to invoked as 
     * a closure.
     */
    public function __invoke($request, $response, $next)
    {
        if ($this->container->authHandler->isVerified()) {
            return $response->withRedirect(
                $this->container->router
                    ->pathFor('Auth.Profile')
            );
        }

        return $next($request, $response);
    }
}