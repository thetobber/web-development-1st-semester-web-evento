<?php
namespace Evento\Controllers;

use Slim\Views\Twig;

class Controller
{
    protected $view;

    public function __construct(Twig $view)
    {
        $this->view = $view;
    }
}
