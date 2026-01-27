<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Auth\Provider;

use Override;
use Valkyrja\Application\Env\Env;
use Valkyrja\Auth\Authenticator\Contract\AuthenticatorContract;
use Valkyrja\Auth\Authenticator\SessionAuthenticator;
use Valkyrja\Auth\Entity\Contract\UserContract;
use Valkyrja\Auth\Hasher\Contract\PasswordHasherContract;
use Valkyrja\Auth\Hasher\PhpPasswordHasher;
use Valkyrja\Auth\Store\Contract\StoreContract;
use Valkyrja\Auth\Store\InMemoryStore;
use Valkyrja\Auth\Store\NullStore;
use Valkyrja\Auth\Store\OrmStore;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Provider;
use Valkyrja\Orm\Manager\Contract\ManagerContract;
use Valkyrja\Session\Manager\Contract\SessionContract;

final class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function publishers(): array
    {
        return [
            AuthenticatorContract::class  => [self::class, 'publishAuthenticator'],
            SessionAuthenticator::class   => [self::class, 'publishSessionAuthenticator'],
            StoreContract::class          => [self::class, 'publishStore'],
            OrmStore::class               => [self::class, 'publishOrmStore'],
            InMemoryStore::class          => [self::class, 'publishInMemoryStore'],
            NullStore::class              => [self::class, 'publishNullStore'],
            PasswordHasherContract::class => [self::class, 'publishPasswordHasher'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            AuthenticatorContract::class,
            SessionAuthenticator::class,
            StoreContract::class,
            OrmStore::class,
            InMemoryStore::class,
            NullStore::class,
            PasswordHasherContract::class,
        ];
    }

    /**
     * Publish the authenticator service.
     */
    public static function publishAuthenticator(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<AuthenticatorContract> $default */
        $default = $env::AUTH_DEFAULT_AUTHENTICATOR;

        $container->setSingleton(
            AuthenticatorContract::class,
            $container->getSingleton($default),
        );
    }

    /**
     * Publish the session authenticator service.
     */
    public static function publishSessionAuthenticator(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<UserContract> $entity */
        $entity = $env::AUTH_DEFAULT_USER_ENTITY;
        /** @var non-empty-string $sessionItemId */
        $sessionItemId = $env::AUTH_SESSION_ITEM_ID;

        $container->setSingleton(
            SessionAuthenticator::class,
            new SessionAuthenticator(
                session: $container->getSingleton(SessionContract::class),
                store: $container->getSingleton(StoreContract::class),
                hasher: $container->getSingleton(PasswordHasherContract::class),
                entity: $entity,
                sessionItemId: $sessionItemId,
            ),
        );
    }

    /**
     * Publish the store service.
     */
    public static function publishStore(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<StoreContract> $default */
        $default = $env::AUTH_DEFAULT_STORE;

        $container->setSingleton(
            StoreContract::class,
            $container->getSingleton($default),
        );
    }

    /**
     * Publish the orm store service.
     */
    public static function publishOrmStore(ContainerContract $container): void
    {
        $container->setSingleton(
            OrmStore::class,
            new OrmStore(
                orm: $container->getSingleton(ManagerContract::class)
            ),
        );
    }

    /**
     * Publish the in memory store service.
     */
    public static function publishInMemoryStore(ContainerContract $container): void
    {
        $container->setSingleton(
            InMemoryStore::class,
            new InMemoryStore(),
        );
    }

    /**
     * Publish the null store service.
     */
    public static function publishNullStore(ContainerContract $container): void
    {
        $container->setSingleton(
            NullStore::class,
            new NullStore(),
        );
    }

    /**
     * Publish the password hasher service.
     */
    public static function publishPasswordHasher(ContainerContract $container): void
    {
        $container->setSingleton(
            PasswordHasherContract::class,
            new PhpPasswordHasher()
        );
    }
}
