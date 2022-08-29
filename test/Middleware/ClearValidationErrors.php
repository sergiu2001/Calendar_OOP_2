<?php

declare(strict_types=1);

namespace Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Session\SessionStore;

class ClearValidationErrors implements MiddlewareInterface
{
    public function __construct(
        protected SessionStore $session
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $next = $handler->handle($request);

        $this->session->clear('errors', 'old');

        return $next;
    }
}