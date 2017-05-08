<?php
namespace Evento\Middleware;

use Evento\Models\Authentication;

class AuthMiddleware extends AbstractMiddleware
{
    /**
     * PHP magic method which enables an object to invoked as 
     * a closure.
     */
    public function __invoke($request, $response, $next)
    {
        if ($this->container->authHandler->isVerified()) {
            return $next($request, $response);
        }

        /*$token = $request->getCookieParam('3dtnx6xd');

        if ($token !== null) {
            //Attempt sign in by cookie
        }*/

        return $response->withRedirect(
            $this->container->router
                ->pathFor('Auth.SignIn')
        );
    }
}