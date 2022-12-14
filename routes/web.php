<?php
/** @var Router $router */
global $router;
/** @var Container $container */
global $container;

use Controllers\{HomeController};
use Controllers\Auth\LoginController;
use Controllers\Auth\LogoutController;
use Controllers\Auth\RegisterController;
use Controllers\ReservationController;
use League\{Container\Container, Route\RouteGroup, Route\Router};
use Middleware\{Guest};
use Middleware\Authenticated;

// Routes that need authentication in order to access
$router->group('', function (RouteGroup $router) {
    $router->get('/home', [HomeController::class, 'index'])->setName('home');

    $router->get('/calendar', [HomeController::class, 'getData'])->setName('home.events');

    $router->post('/logout', [LogoutController::class, 'logout'])->setName('logout');

    $router->get('/reservation', [ReservationController::class, 'index'])->setName('reservation');

    $router->post('/reservation', [ReservationController::class, 'store'])->setName('reservation.store');

})->middleware($container->get(Authenticated::class));
// Routes that can be accessed only if the user is NOT authenticated
$router->group('', function (RouteGroup $router) {

    $router->get('/login', [LoginController::class, 'index'])->setName('login');

    $router->post('/login', [LoginController::class, 'store'])->setName('login.store');

    $router->get('/register', [RegisterController::class, 'index'])->setName('register');

    $router->post('/register', [RegisterController::class, 'store'])->setName('register.store');

})->middleware($container->get(Guest::class));