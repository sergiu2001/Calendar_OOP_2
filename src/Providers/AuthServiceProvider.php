<?php
declare(strict_types=1);

namespace App\Providers;

use Auth\Auth;
use Auth\Hashing\Hasher;
use Auth\Recaller;
use Cookie\CookieJar;
use Doctrine\ORM\EntityManager;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Session\SessionStore;

class AuthServiceProvider extends AbstractServiceProvider
{
    public function provides(string $id): bool
    {
        $services = [Auth::class];
        return in_array($id, $services);
    }

    public function register(): void
    {
        $container = $this->getContainer();

        $container->addShared(Auth::class, function () use ($container) {
            return new Auth(
                db: $container->get(EntityManager::class),
                hasher: $container->get(Hasher::class),
                session: $container->get(SessionStore::class),
                cookieJar: $container->get(CookieJar::class),
                recaller: new Recaller(),
            );
        });
    }
}