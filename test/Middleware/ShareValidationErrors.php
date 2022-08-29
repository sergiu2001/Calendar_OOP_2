<?php

declare(strict_types=1);

namespace Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Session\SessionStore;
use Views\View;

class ShareValidationErrors implements MiddlewareInterface
{
    public function __construct(
        protected View $view,
        protected SessionStore $session
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->view->share([
            'errors' => $this->session->get('errors', []),
            'old' => $this->session->get('old', [])
        ]);

        return $handler->handle($request);
    }
}