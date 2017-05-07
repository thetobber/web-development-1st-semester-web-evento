<?php
namespace Evento\Middleware;

//use Slim\Http\Stream;
//use Slim\Csrf\Guard;

class RequestForgeryMiddleware extends AbstractMiddleware
{
    /**
     * PHP magic method which enables an object to called as 
     * a closure.
     */
    public function __invoke($request, $response, $next)
    {
        $response = $next($request, $response);
        return $response;
    }
}


//X-CSRF-Token