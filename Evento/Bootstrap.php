<?php
//Restrict the session cookie as much as possible
ini_set('session.name', 'vf56p3x0');
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0);
ini_set('session.cookie_lifetime', 0);

session_start();
date_default_timezone_set('Europe/Copenhagen');

/*
Insert timezones in MySQL database
https://dev.mysql.com/downloads/timezones.html

DATE format
MySQL: YYYY-MM-DD
PHP:   Y-m-d

DATETIME format
MySQL: YYYY-MM-DD HH:MM:SS
PHP:   Y-m-d H:i:s

Possible shown like this:
d-m-Y H:i:s \U\T\C P
*/

require(__DIR__.'/../Vendor/autoload.php');

use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use Evento\Models\AuthHandler;
use Evento\Middleware\GeneralMiddleware;
use Evento\Middleware\GuestMiddleware;
use Evento\Middleware\AuthMiddleware;
use Evento\Controllers\AuthController;
use Evento\Controllers\MainController;
use Evento\Controllers\EventController;

$app = new App([
    'settings' => [
        'displayErrorDetails' => true,
        'addContentLengthHeader' => true
    ]
]);

$container = $app->getContainer();

$container['authHandler'] = function ($c) {
    return new AuthHandler($c);
};

$container['view'] = function ($c) {
    $view = new Twig(__DIR__.'/Views', [
        'debug ' => true,
        'cache' => false //__DIR__.'/cache'
    ]);

    $view->addExtension(new TwigExtension(
        $c->router,
        $c->request->getUri()
    ));

    return $view;
};

$container['authCtrl'] = function ($c) {
    return new AuthController($c);
};

$container['mainCtrl'] = function ($c) {
    return new MainController($c);
};

$container['eventCtrl'] = function ($c) {
    return new EventController($c);
};

$app->add(new GeneralMiddleware($container));

// Auth routes

$app->get('/signin', 'authCtrl:getSignIn')
    ->setName('Auth.SignIn');

$app->post('/signin', 'authCtrl:postSignIn');

$app->get('/signup', 'authCtrl:getSignUp')
    ->setName('Auth.SignUp');

$app->post('/signup', 'authCtrl:postSignUp');

$app->get('/signout', 'authCtrl:getSignOut')
    ->setName('Auth.SignOut');

$app->get('/profile', 'authCtrl:getProfile')
    ->setName('Auth.Profile');

$app->post('/profile', 'authCtrl:postProfile');

// Event routes
$app->get('/events/create', 'eventCtrl:getCreate')
    ->setName('Event.Create');

/*$app->get('/events/update', 'eventCtrl:getUpdate')
    ->setName('Events.Create');

$app->get('/events/[]', 'eventCtrl:getList')
    ->setName('Events.List');

$app->get('/events/single/{id:[0-9]+}', 'eventCtrl:getSingle')
    ->setName('Events.Single');*/



// Main routes
$app->get('/', 'mainCtrl:getIndex')
    ->setName('Main');

$app->run();