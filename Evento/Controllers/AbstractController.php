<?php
namespace Evento\Controllers;

use Slim\Exception\ContainerValueNotFoundException;

abstract class AbstractController
{
    private $view;
    private $router;
    private $authHandler;

    public function __construct($container)
    {
        $this->view = $container->view;
        $this->router = $container->router;
        $this->authHandler = $container->authHandler;
        $this->container = $container;
    }

    protected function redirect($response, $path)
    {
        return $response->withRedirect(
            $this->router->pathFor($path)
        );
    }

    protected function view($response, $template, array $data = [])
    {
        return $this->view
            ->render($response, $template, $data);
    }

    public function __get($key)
    {
        try {
            return $this->container->get($key);
        } catch (ContainerValueNotFoundException $e) {
            return null;
        }
    }
}
