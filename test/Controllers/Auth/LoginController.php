<?php
declare(strict_types=1);

namespace Controllers\Auth;

use Controllers\Controller;
use Laminas\Diactoros\Response;
use League\Route\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Session\Flash;
use Views\View;

class LoginController extends Controller
{
    public function __construct(
        protected View       $view,
        protected \Auth\Auth $auth,
        protected Router     $router,
        protected Flash      $flash
    )
    {
    }

    public function index(): ResponseInterface
    {
        return $this->view->render(new Response, 'auth/login.twig');
    }

    public function store(ServerRequestInterface $request): ResponseInterface
    {
        $data = $this->validate($request, [
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        $attempt = $this->auth->attempt($data['email'], $data['password'], isset($data['remember']));

        if (!$attempt) {
            $this->flash->now('error', 'Could not sign you in with those details');
            return redirect($request->getUri()->getPath());
        }
        return redirect($this->router->getNamedRoute('home')->getPath());
    }
}