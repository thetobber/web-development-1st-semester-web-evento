<?php
session_start();

require(__DIR__.'/../Vendor/autoload.php');

use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use Evento\Middleware\AuthenticationMiddleware;
use Evento\Controllers\AuthController;
use Evento\Controllers\MainController;

$app = new App([
    'settings' => [
        'displayErrorDetails' => true,
        'addContentLengthHeader' => true
    ]
]);

$container = $app->getContainer();

$container['view'] = function ($container) {
    $view = new Twig(__DIR__.'/Views', [
        'debug ' => true,
        'cache' => false //__DIR__.'/cache'
    ]);

    $view->addExtension(new TwigExtension(
        $container->router,
        $container->request->getUri()
    ));

    return $view;
};

$app->add(new AuthenticationMiddleware($container));

$container['AuthController'] = function ($container) {
    return new AuthController($container);
};

$container['MainController'] = function ($container) {
    return new MainController($container);
};

// Auth routes
$app->get('/signin', 'AuthController:getSignIn')
    ->setName('Auth.SignIn');

$app->post('/signin', 'AuthController:postSignIn');

$app->get('/signup', 'AuthController:getSignUp')
    ->setName('Auth.SignUp');

$app->post('/signup', 'AuthController:postSignUp');

$app->get('/signout', 'AuthController:getSignOut')
    ->setName('Auth.SignOut');

$app->get('/profile', 'AuthController:getProfile')
    ->setName('Auth.Profile');

$app->put('/profile', 'AuthController:putProfile');

// Main routes
$app->get('/', 'MainController:getIndex')
    ->setName('Main');

$app->run();