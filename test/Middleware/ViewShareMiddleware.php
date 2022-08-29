<?php
declare(strict_types=1);
namespace Middleware;
use Auth\Auth;
use Config\Config;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Security\Csrf;
use Session\Flash;
use Views\View;

class ViewShareMiddleware implements MiddlewareInterface
{
    public function __construct(
        protected View $view,
        protected Config $config,
        protected Auth $auth,
        protected Flash $flash,
        protected Csrf $csrf
    ) {
    }
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->view->share([
            'config' => $this->config,
            'auth' => $this->auth,
            'flash' => $this->flash,
            'csrf' => $this->csrf,
        ]);

        return $handler->handle($request);
    }
}