<?php
//Restrict the session cookie as much as possible
ini_set('session.name', 'vf56p3x0');
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0);
ini_set('session.cookie_lifetime', 0);

session_start();

require(__DIR__.'/../Vendor/autoload.php');

use Evento\Config;
use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use Evento\Models\AuthHandler;
use Evento\Middleware\GeneralMiddleware;
use Evento\Controllers\AuthController;
use Evento\Controllers\MainController;
use Evento\Controllers\EventController;

date_default_timezone_set(Config::TIMEZONE);

$app = new App([
    'settings' => [
        'displayErrorDetails' => true,
        'addContentLengthHeader' => true
    ]
]);

$container = $app->getContainer();

$container['notFoundHandler'] = function ($container) {
    return function ($request, $response) use ($container) {
        return $container->view->render($response, 'Static/404.html');
    };
};

$container['authHandler'] = function ($c) {
    return new AuthHandler($c);
};

$container['view'] = function ($c) {
    $view = new Twig(__DIR__.'/Views', [
        'debug ' => Config::DEBUG,
        'cache' => Config::CACHE_DIR
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

$app->get(Config::BASE_PATH.'/signin', 'authCtrl:getSignIn')
    ->setName('Auth.SignIn');

$app->post(Config::BASE_PATH.'/signin', 'authCtrl:postSignIn');

$app->get(Config::BASE_PATH.'/signup', 'authCtrl:getSignUp')
    ->setName('Auth.SignUp');

$app->post(Config::BASE_PATH.'/signup', 'authCtrl:postSignUp');

$app->get(Config::BASE_PATH.'/signout', 'authCtrl:getSignOut')
    ->setName('Auth.SignOut');

$app->get(Config::BASE_PATH.'/profile', 'authCtrl:getProfile')
    ->setName('Auth.Profile');

$app->post(Config::BASE_PATH.'/profile', 'authCtrl:postProfile');

// Event routes
$app->get(Config::BASE_PATH.'/[p/{page:[0-9]+}]', 'eventCtrl:getList')
    ->setName('Event.List');

$app->get(Config::BASE_PATH.'/event/{id:[0-9]+}', 'eventCtrl:getSingle')
    ->setName('Event.Single');

$app->get(Config::BASE_PATH.'/event/create', 'eventCtrl:getCreate')
    ->setName('Event.Create');

$app->post(Config::BASE_PATH.'/event/create', 'eventCtrl:postCreate');

$app->get(Config::BASE_PATH.'/event/update/{id:[0-9]+}', 'eventCtrl:getUpdate')
    ->setName('Event.Update');

$app->post(Config::BASE_PATH.'/event/update/{id:[0-9]+}', 'eventCtrl:postUpdate');

$app->post(Config::BASE_PATH.'/event/delete/{id:[0-9]+}', 'eventCtrl:postDelete')
    ->setName('Event.Delete');

$app->post(Config::BASE_PATH.'/event/participate/{id:[0-9]+}', 'eventCtrl:postParticipate')
    ->setName('Event.Participate');


$app->get(Config::BASE_PATH.'/user', 'mainCtrl:getUserList')
    ->setName('Main.UserList');

$app->post(Config::BASE_PATH.'/user/{name:[a-zA-Z0-9_-]+}', 'mainCtrl:deleteUser')
    ->setName('Main.UserDelete');

$app->post(Config::BASE_PATH.'/user/promote/{name:[a-zA-Z0-9_-]+}', 'mainCtrl:promoteUser')
    ->setName('Main.UserPromote');

$app->post(Config::BASE_PATH.'/user/demote/{name:[a-zA-Z0-9_-]+}', 'mainCtrl:demoteUser')
    ->setName('Main.UserDemote');

$app->run();
