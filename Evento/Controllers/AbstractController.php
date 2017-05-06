<?php
namespace Evento\Controllers;

abstract class AbstractController
{
    private $view;

    public function __construct($container)
    {
        $this->view = $container->view;
    }

    protected function redirect($response, $path)
    {
        return $response->withRedidirect($this->router->pathFor($path));
    }

    protected function view($response, $template, $data = [])
    {
        return $this->view->render($response, $template, $data);
    }
}
