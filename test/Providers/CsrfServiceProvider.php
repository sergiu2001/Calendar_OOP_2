<?php

declare(strict_types=1);

namespace Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Security\Csrf;
use Session\SessionStore;

class CsrfServiceProvider extends AbstractServiceProvider
{
    public function provides(string $id): bool
    {
        $services = [Csrf::class];

        return in_array($id, $services);
    }

    public function register(): void
    {
        $container = $this->getContainer();

        $container->addShared(Csrf::class, function () use ($container) {
            return new Csrf(
                $container->get(SessionStore::class)
            );
        });
    }
}