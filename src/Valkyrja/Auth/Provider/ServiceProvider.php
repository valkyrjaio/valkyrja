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
use Valkyrja\Application\Env;
use Valkyrja\Auth\Contract\Authenticator;
use Valkyrja\Auth\EncryptedJwtAuthenticator;
use Valkyrja\Auth\EncryptedTokenAuthenticator;
use Valkyrja\Auth\Entity\Contract\User;
use Valkyrja\Auth\Hasher\Contract\PasswordHasher;
use Valkyrja\Auth\JwtAuthenticator;
use Valkyrja\Auth\SessionAuthenticator;
use Valkyrja\Auth\Store\Contract\Store;
use Valkyrja\Auth\Store\InMemoryStore;
use Valkyrja\Auth\Store\NullStore;
use Valkyrja\Auth\Store\OrmStore;
use Valkyrja\Auth\TokenAuthenticator;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Crypt\Contract\Crypt;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Jwt\Contract\Jwt;
use Valkyrja\Orm\Contract\Manager;
use Valkyrja\Session\Contract\Session;

/**
 * Class ServiceProvider.
 *
 * @author Melech Mizrachi
 */
final class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function publishers(): array
    {
        return [
            Authenticator::class               => [self::class, 'publishAuthenticator'],
            EncryptedJwtAuthenticator::class   => [self::class, 'publishEncryptedJwtAuthenticator'],
            EncryptedTokenAuthenticator::class => [self::class, 'publishEncryptedTokenAuthenticator'],
            JwtAuthenticator::class            => [self::class, 'publishJwtAuthenticator'],
            SessionAuthenticator::class        => [self::class, 'publishSessionAuthenticator'],
            TokenAuthenticator::class          => [self::class, 'publishTokenAuthenticator'],
            Store::class                       => [self::class, 'publishStore'],
            OrmStore::class                    => [self::class, 'publishOrmStore'],
            InMemoryStore::class               => [self::class, 'publishInMemoryStore'],
            NullStore::class                   => [self::class, 'publishNullStore'],
            PasswordHasher::class              => [self::class, 'publishPasswordHasher'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            Authenticator::class,
            Store::class,
            OrmStore::class,
            InMemoryStore::class,
            NullStore::class,
            PasswordHasher::class,
        ];
    }

    /**
     * Publish the authenticator service.
     */
    public static function publishAuthenticator(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<Authenticator> $default */
        $default = $env::AUTH_DEFAULT_AUTHENTICATOR;

        $container->setSingleton(
            Authenticator::class,
            $container->getSingleton($default),
        );
    }

    /**
     * Publish the encrypted jwt authenticator service.
     */
    public static function publishEncryptedJwtAuthenticator(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<User> $entity */
        $entity = $env::AUTH_DEFAULT_USER_ENTITY;
        /** @var non-empty-string $headerName */
        $headerName = $env::AUTH_DEFAULT_AUTHORIZATION_HEADER;

        $container->setSingleton(
            EncryptedJwtAuthenticator::class,
            new EncryptedJwtAuthenticator(
                crypt: $container->getSingleton(Crypt::class),
                jwt: $container->getSingleton(Jwt::class),
                request: $container->getSingleton(ServerRequest::class),
                store: $container->getSingleton(Store::class),
                hasher: $container->getSingleton(PasswordHasher::class),
                entity: $entity,
                headerName: $headerName,
            ),
        );
    }

    /**
     * Publish the encrypted token authenticator service.
     */
    public static function publishEncryptedTokenAuthenticator(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<User> $entity */
        $entity = $env::AUTH_DEFAULT_USER_ENTITY;
        /** @var non-empty-string $headerName */
        $headerName = $env::AUTH_DEFAULT_AUTHORIZATION_HEADER;

        $container->setSingleton(
            EncryptedTokenAuthenticator::class,
            new EncryptedTokenAuthenticator(
                crypt: $container->getSingleton(Crypt::class),
                request: $container->getSingleton(ServerRequest::class),
                store: $container->getSingleton(Store::class),
                hasher: $container->getSingleton(PasswordHasher::class),
                entity: $entity,
                headerName: $headerName,
            ),
        );
    }

    /**
     * Publish the jwt authenticator service.
     */
    public static function publishJwtAuthenticator(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<User> $entity */
        $entity = $env::AUTH_DEFAULT_USER_ENTITY;
        /** @var non-empty-string $headerName */
        $headerName = $env::AUTH_DEFAULT_AUTHORIZATION_HEADER;

        $container->setSingleton(
            JwtAuthenticator::class,
            new JwtAuthenticator(
                jwt: $container->getSingleton(Jwt::class),
                request: $container->getSingleton(ServerRequest::class),
                store: $container->getSingleton(Store::class),
                hasher: $container->getSingleton(PasswordHasher::class),
                entity: $entity,
                headerName: $headerName,
            ),
        );
    }

    /**
     * Publish the session authenticator service.
     */
    public static function publishSessionAuthenticator(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<User> $entity */
        $entity = $env::AUTH_DEFAULT_USER_ENTITY;
        /** @var non-empty-string $sessionId */
        $sessionId = $env::AUTH_DEFAULT_SESSION_ID;

        $container->setSingleton(
            SessionAuthenticator::class,
            new SessionAuthenticator(
                session: $container->getSingleton(Session::class),
                store: $container->getSingleton(Store::class),
                hasher: $container->getSingleton(PasswordHasher::class),
                entity: $entity,
                sessionId: $sessionId,
            ),
        );
    }

    /**
     * Publish the token authenticator service.
     */
    public static function publishTokenAuthenticator(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<User> $entity */
        $entity = $env::AUTH_DEFAULT_USER_ENTITY;
        /** @var non-empty-string $headerName */
        $headerName = $env::AUTH_DEFAULT_AUTHORIZATION_HEADER;

        $container->setSingleton(
            TokenAuthenticator::class,
            new TokenAuthenticator(
                request: $container->getSingleton(ServerRequest::class),
                store: $container->getSingleton(Store::class),
                hasher: $container->getSingleton(PasswordHasher::class),
                entity: $entity,
                headerName: $headerName,
            ),
        );
    }

    /**
     * Publish the store service.
     */
    public static function publishStore(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<Store> $default */
        $default = $env::AUTH_DEFAULT_STORE;

        $container->setSingleton(
            Store::class,
            $container->getSingleton($default),
        );
    }

    /**
     * Publish the orm store service.
     */
    public static function publishOrmStore(Container $container): void
    {
        $container->setSingleton(
            OrmStore::class,
            new OrmStore(
                orm: $container->getSingleton(Manager::class)
            ),
        );
    }

    /**
     * Publish the in memory store service.
     */
    public static function publishInMemoryStore(Container $container): void
    {
        $container->setSingleton(
            InMemoryStore::class,
            new InMemoryStore(),
        );
    }

    /**
     * Publish the null store service.
     */
    public static function publishNullStore(Container $container): void
    {
        $container->setSingleton(
            NullStore::class,
            new NullStore(),
        );
    }

    /**
     * Publish the password hasher service.
     */
    public static function publishPasswordHasher(Container $container): void
    {
        $container->setSingleton(
            PasswordHasher::class,
            new \Valkyrja\Auth\Hasher\PasswordHasher()
        );
    }
}
