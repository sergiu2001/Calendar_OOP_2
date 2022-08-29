<?php

declare(strict_types=1);

namespace Controllers\Auth;

use Auth\Auth;
use Controllers\Controller;
use League\Route\Router;
use Psr\Http\Message\ResponseInterface;

class LogoutController extends Controller
{
    public function __construct(
        protected Auth $auth,
        protected Router $router,
    ) {
    }

    public function logout(): ResponseInterface
    {
        $this->auth->logout();

        return redirect($this->router->getNamedRoute('home')->getPath());
    }
}