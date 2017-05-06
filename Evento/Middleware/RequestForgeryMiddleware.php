<?php
namespace Evento\Middleware;

//use Slim\Http\Stream;
//use Slim\Csrf\Guard;

class RequestForgeryMiddleware
{
    public function __invoke($request, $response, $next)
    {
        $response = $next($request, $response);
        return $response;
    }
}


//X-CSRF-Token