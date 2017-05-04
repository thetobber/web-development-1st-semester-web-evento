<?php
require(__DIR__.'/vendor/autoload.php');

use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use Evento\Controllers\AuthController;

$app = new App([
    'settings' => [
        'displayErrorDetails' => true,
        'addContentLengthHeader' => true
    ]
]);

$container = $app->getContainer();

$container['view'] = function ($container) {
    $view = new Twig(__DIR__.'/views', [
        'cache' => false //__DIR__.'/cache'
    ]);

    $view->getLoader()
        ->addPath(__DIR__.'/views/auth');

    $view->addExtension(new TwigExtension(
        $container['router'],
        $container->request->getUri()
    ));

    return $view;
};

$container['AuthController'] = function ($container) {
    return new AuthController($container['view']);
};

$app->get('/', 'AuthController:index')
    ->setName('auth.index');

$app->run();
