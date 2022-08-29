<?php

use Middleware\AuthenticateFromCookie;
use Middleware\AuthenticateFromSession;
use Middleware\ClearValidationErrors;
use Middleware\CsrfGuard;
use Middleware\ShareValidationErrors;
use Middleware\ViewShareMiddleware;
use Providers\{AuthServiceProvider};
use Providers\AppServiceProvider;
use Providers\CookieServiceProvider;
use Providers\CsrfServiceProvider;
use Providers\DatabaseServiceProvider;
use Providers\FlashServiceProvider;
use Providers\HashServiceProvider;
use Providers\SessionServiceProvider;
use Providers\ValidationServiceProvider;
use Providers\ViewServiceProvider;

return [
    'name' => env('APP_NAME', 'Funky'),
    'debug' => env('APP_DEBUG', false),
    'providers' => [
        AppServiceProvider::class,
        ViewServiceProvider::class,
        DatabaseServiceProvider::class,
        SessionServiceProvider::class,
        HashServiceProvider::class,
        AuthServiceProvider::class,
        FlashServiceProvider::class,
        CsrfServiceProvider::class,
        ValidationServiceProvider::class,
        CookieServiceProvider::class
    ],

    'middleware' => [
        ShareValidationErrors::class,
        ClearValidationErrors::class,
        ViewShareMiddleware::class,
        AuthenticateFromCookie::class,
        AuthenticateFromSession::class,
        CsrfGuard::class
    ]
];