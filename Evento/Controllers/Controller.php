<?php
namespace Evento\Controllers;

use Slim\Views\Twig;

class Controller
{
    private $view;

    public function __construct(Twig $view)
    {
        $this->view = $view;
    }

    protected function redirect($response, $path)
    {
        return $response->withRedidirect($this->router->pathFor($path));
    }

    protected function view($response, $template)
    {
        return $this->view->render($response, $template);
    }
}
