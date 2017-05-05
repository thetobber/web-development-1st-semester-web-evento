<?php
require(__DIR__.'/../Vendor/autoload.php');

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
    $view = new Twig(__DIR__.'/Views', [
        'cache' => false //__DIR__.'/cache'
    ]);

    $view->getLoader()
        ->addPath(__DIR__.'/Views/Auth');

    $view->addExtension(new TwigExtension(
        $container['router'],
        $container->request->getUri()
    ));

    return $view;
};

$container['AuthController'] = function ($container) {
    return new AuthController($container['view']);
};

$app->get('/signin', 'AuthController:getSignIn')->setName('Auth.SignIn');
$app->post('/signin', 'AuthController:postSignIn');

$app->get('/signup', 'AuthController:getSignUp')->setName('Auth.SignUp');
$app->post('/signup', 'AuthController:postSignUp');

$app->get('/signout', 'AuthController:signOut')->setName('Auth.SingOut');

$app->run();