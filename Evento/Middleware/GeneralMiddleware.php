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

        $response = $response
            ->withHeader('Content-Security-Policy', "script-src 'self'")
            ->withHeader('X-Content-Type-Options', 'nosniff')
            ->withHeader('X-Frame-Options', 'SAMEORIGIN')
            ->withHeader('X-XSS-Protection', '1; mode=block');

        return $next($request, $response);
    }
}