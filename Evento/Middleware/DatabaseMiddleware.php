<?php
namespace Evento\Middleware;

/**
 * Defines middleware which hooks up a singleton instance of 
 * the PDO class to server as a context for the database.
 */
class DatabaseMiddleware
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