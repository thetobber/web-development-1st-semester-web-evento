<?php
session_start();

require(__DIR__.'/../Vendor/autoload.php');

use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use Evento\Models\DbContext;
use Evento\Controllers\AuthController;
use Evento\Controllers\MainController;
use Evento\Middleware\RequestForgeryMiddleware;

$app = new App([
    'settings' => [
        'displayErrorDetails' => true,
        'addContentLengthHeader' => true
    ]
]);

$app->add(new RequestForgeryMiddleware());


$container = $app->getContainer();

$container['view'] = function ($container) {
    $view = new Twig(__DIR__.'/Views', [
        'debug ' => true,
        'cache' => false //__DIR__.'/cache'
    ]);

    $view->addExtension(new TwigExtension(
        $container['router'],
        $container->request->getUri()
    ));

    return $view;
};

$container['AuthController'] = function ($container) {
    return new AuthController($container);
};

$container['MainController'] = function ($container) {
    return new MainController($container);
};

// Auth routes
$app->get('/signin', 'AuthController:getSignIn')->setName('Auth.SignIn');
$app->post('/signin', 'AuthController:postSignIn');

$app->get('/signup', 'AuthController:getSignUp')->setName('Auth.SignUp');
$app->post('/signup', 'AuthController:postSignUp');

$app->get('/signout', 'AuthController:signOut')->setName('Auth.SingOut');

// Main routes
$app->get('/', 'MainController:getIndex')->setName('Main');

$app->run();