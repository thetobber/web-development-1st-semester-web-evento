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

//Group only accessible when NOT signed in
$app->group('', function () {
    $this->get('/signin', 'authCtrl:getSignIn')
        ->setName('Auth.SignIn');

    $this->post('/signin', 'authCtrl:postSignIn');

    $this->get('/signup', 'authCtrl:getSignUp')
        ->setName('Auth.SignUp');

    $this->post('/signup', 'authCtrl:postSignUp');
})
->add(new GuestMiddleware($container));

//Group which is ONLY accessible when signed in
$app->group('', function () {
    $this->get('/signout', 'authCtrl:getSignOut')
        ->setName('Auth.SignOut');

    $this->get('/profile', 'authCtrl:getProfile')
        ->setName('Auth.Profile');

    $this->post('/profile', 'authCtrl:postProfile');
})
->add(new AuthMiddleware($container));

// Main routes
$app->get('/event/create', 'eventCtrl:getCreate')
    ->setName('Event.Create');

// Main routes
$app->get('/', 'mainCtrl:getIndex')
    ->setName('Main');

$app->run();