<?php
namespace Evento\Controllers;

abstract class AbstractController
{
    private $view;
    private $router;

    public function __construct($container)
    {
        $this->view = $container->view;
        $this->router = $container->router;
    }

    protected function redirect($response, $path)
    {
        return $response->withRedirect(
            $this->router
                ->pathFor($path)
        );
    }

    protected function view($response, $template, $data = [])
    {
        return $this->view
            ->render($response, $template, $data);
    }
}
