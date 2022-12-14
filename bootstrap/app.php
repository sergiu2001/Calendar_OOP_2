<?php

declare(strict_types=1);

use Config\Config;
use Dotenv\Exception\InvalidPathException;
use Exceptions\Handler;
use League\{Container\Container, Container\ReflectionContainer, Route\Router};
use Providers\ConfigServiceProvider;
use Session\SessionStore;
use Views\View;

require_once dirname(__DIR__).'/vendor/autoload.php';

session_start();

/* Load environment variables */
try {
    $dotenv = Dotenv\Dotenv::createUnsafeImmutable(base_path())->load();
} catch (InvalidPathException $exception) {
    die($exception->getMessage());
}

/* Container setup, Autowire & Service Providers */
$container = (new Container)
    ->delegate(new ReflectionContainer)
    ->addServiceProvider(new ConfigServiceProvider());
foreach ($container->get(Config::class)->get('app.providers') as $provider) {
    $container->addServiceProvider(new $provider);
}

/* Router & Middleware setup */
$router = $container->get(Router::class);
foreach ($container->get(Config::class)->get('app.middleware') as $middleware) {
    $router->middleware($container->get($middleware));
}

require_once base_path('routes/web.php');

/* Handle response */
try {
    $response = $router->dispatch($container->get('request'));
} catch (Exception $exception) {
    $response = (new Handler(
        $exception,
        $container->get(SessionStore::class),
        $container->get(View::class),
    ))->respond();
}
$container->get('emitter')->emit($response);


//use App\Kernel;
//
//require_once dirname(__DIR__) . '/vendor/autoload_runtime.php';
//
//return function (array $context) {
//    return new Kernel($context['APP_ENV'], (bool)$context['APP_DEBUG']);
//};
