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

namespace Valkyrja\Session\Provider;

use Override;
use Valkyrja\Application\Env\Env;
use Valkyrja\Cache\Manager\Contract\CacheContract;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Provider;
use Valkyrja\Crypt\Manager\Contract\CryptContract;
use Valkyrja\Http\Message\Constant\HeaderName;
use Valkyrja\Http\Message\Enum\SameSite;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Jwt\Manager\Contract\JwtContract;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Session\Data\CookieParams;
use Valkyrja\Session\Manager\CacheSession;
use Valkyrja\Session\Manager\Contract\SessionContract;
use Valkyrja\Session\Manager\Cookie\CookieSession;
use Valkyrja\Session\Manager\Cookie\EncryptedCookieSession;
use Valkyrja\Session\Manager\Jwt\EncryptedJwtSession;
use Valkyrja\Session\Manager\Jwt\JwtSession;
use Valkyrja\Session\Manager\LogSession;
use Valkyrja\Session\Manager\NullSession;
use Valkyrja\Session\Manager\PhpSession;
use Valkyrja\Session\Manager\Token\EncryptedTokenSession;
use Valkyrja\Session\Manager\Token\TokenSession;

final class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function publishers(): array
    {
        return [
            SessionContract::class        => [self::class, 'publishSession'],
            PhpSession::class             => [self::class, 'publishPhpSession'],
            NullSession::class            => [self::class, 'publishNullSession'],
            CacheSession::class           => [self::class, 'publishCacheSession'],
            CookieSession::class          => [self::class, 'publishCookieSession'],
            EncryptedCookieSession::class => [self::class, 'publishEncryptedCookieSession'],
            JwtSession::class             => [self::class, 'publishJwtSession'],
            EncryptedJwtSession::class    => [self::class, 'publishEncryptedJwtSession'],
            TokenSession::class           => [self::class, 'publishTokenSession'],
            EncryptedTokenSession::class  => [self::class, 'publishEncryptedTokenSession'],
            LogSession::class             => [self::class, 'publishLogSession'],
            CookieParams::class           => [self::class, 'publishCookieParams'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            SessionContract::class,
            PhpSession::class,
            NullSession::class,
            CacheSession::class,
            CookieSession::class,
            EncryptedCookieSession::class,
            JwtSession::class,
            EncryptedJwtSession::class,
            TokenSession::class,
            EncryptedTokenSession::class,
            LogSession::class,
            CookieParams::class,
        ];
    }

    /**
     * Publish the cookie params service.
     */
    public static function publishCookieParams(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var string $path */
        $path = $env::SESSION_COOKIE_PARAM_PATH;
        /** @var string|null $domain */
        $domain = $env::SESSION_COOKIE_PARAM_DOMAIN;
        /** @var int $lifetime */
        $lifetime = $env::SESSION_COOKIE_PARAM_LIFETIME;
        /** @var bool $secure */
        $secure = $env::SESSION_COOKIE_PARAM_SECURE;
        /** @var bool $httpOnly */
        $httpOnly = $env::SESSION_COOKIE_PARAM_HTTP_ONLY;
        /** @var SameSite $sameSite */
        $sameSite = $env::SESSION_COOKIE_PARAM_SAME_SITE;

        $container->setSingleton(
            CookieParams::class,
            new CookieParams(
                path: $path,
                domain: $domain,
                lifetime: $lifetime,
                secure: $secure,
                httpOnly: $httpOnly,
                sameSite: $sameSite,
            )
        );
    }

    /**
     * Publish the session service.
     */
    public static function publishSession(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<SessionContract> $default */
        $default = $env::SESSION_DEFAULT;

        $container->setSingleton(
            SessionContract::class,
            $container->getSingleton($default),
        );
    }

    /**
     * Publish the php session service.
     */
    public static function publishPhpSession(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var string|null $sessionId */
        $sessionId = $env::SESSION_PHP_ID;
        /** @var string|null $sessionName */
        $sessionName = $env::SESSION_PHP_NAME;

        $container->setSingleton(
            PhpSession::class,
            new PhpSession(
                cookieParams: $container->getSingleton(CookieParams::class),
                sessionId: $sessionId,
                sessionName: $sessionName,
            ),
        );
    }

    /**
     * Publish the null session service.
     */
    public static function publishNullSession(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var string|null $sessionId */
        $sessionId = $env::SESSION_PHP_ID;
        /** @var string|null $sessionName */
        $sessionName = $env::SESSION_PHP_NAME;

        $container->setSingleton(
            NullSession::class,
            new NullSession(
                sessionId: $sessionId,
                sessionName: $sessionName,
            ),
        );
    }

    /**
     * Publish the cache session service.
     */
    public static function publishCacheSession(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var string|null $sessionId */
        $sessionId = $env::SESSION_PHP_ID;
        /** @var string|null $sessionName */
        $sessionName = $env::SESSION_PHP_NAME;

        $container->setSingleton(
            CacheSession::class,
            new CacheSession(
                cache: $container->getSingleton(CacheContract::class),
                sessionId: $sessionId,
                sessionName: $sessionName,
            ),
        );
    }

    /**
     * Publish the cookie session service.
     */
    public static function publishCookieSession(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var string|null $sessionId */
        $sessionId = $env::SESSION_PHP_ID;
        /** @var string|null $sessionName */
        $sessionName = $env::SESSION_PHP_NAME;

        $container->setSingleton(
            CookieSession::class,
            new CookieSession(
                request: $container->getSingleton(ServerRequestContract::class),
                sessionId: $sessionId,
                sessionName: $sessionName,
            ),
        );
    }

    /**
     * Publish the encrypted cookie session service.
     */
    public static function publishEncryptedCookieSession(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var string|null $sessionId */
        $sessionId = $env::SESSION_PHP_ID;
        /** @var string|null $sessionName */
        $sessionName = $env::SESSION_PHP_NAME;

        $container->setSingleton(
            EncryptedCookieSession::class,
            new EncryptedCookieSession(
                crypt: $container->getSingleton(CryptContract::class),
                request: $container->getSingleton(ServerRequestContract::class),
                sessionId: $sessionId,
                sessionName: $sessionName,
            ),
        );
    }

    /**
     * Publish the jwt session service.
     */
    public static function publishJwtSession(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var string|null $sessionId */
        $sessionId = $env::SESSION_PHP_ID;
        /** @var string|null $sessionName */
        $sessionName = $env::SESSION_PHP_NAME;
        /** @var string|null $headerName */
        $headerName = $env::SESSION_JWT_HEADER_NAME;

        $container->setSingleton(
            JwtSession::class,
            new JwtSession(
                jwt: $container->getSingleton(JwtContract::class),
                request: $container->getSingleton(ServerRequestContract::class),
                sessionId: $sessionId,
                sessionName: $sessionName,
                headerName: $headerName ?? HeaderName::AUTHORIZATION,
            ),
        );
    }

    /**
     * Publish the encrypted jwt session service.
     */
    public static function publishEncryptedJwtSession(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var string|null $sessionId */
        $sessionId = $env::SESSION_PHP_ID;
        /** @var string|null $sessionName */
        $sessionName = $env::SESSION_PHP_NAME;
        /** @var string|null $headerName */
        $headerName = $env::SESSION_JWT_HEADER_NAME;

        $container->setSingleton(
            EncryptedJwtSession::class,
            new EncryptedJwtSession(
                crypt: $container->getSingleton(CryptContract::class),
                jwt: $container->getSingleton(JwtContract::class),
                request: $container->getSingleton(ServerRequestContract::class),
                sessionId: $sessionId,
                sessionName: $sessionName,
                headerName: $headerName ?? HeaderName::AUTHORIZATION,
            ),
        );
    }

    /**
     * Publish the token session service.
     */
    public static function publishTokenSession(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var string|null $sessionId */
        $sessionId = $env::SESSION_PHP_ID;
        /** @var string|null $sessionName */
        $sessionName = $env::SESSION_PHP_NAME;
        /** @var string|null $headerName */
        $headerName = $env::SESSION_JWT_HEADER_NAME;

        $container->setSingleton(
            TokenSession::class,
            new TokenSession(
                request: $container->getSingleton(ServerRequestContract::class),
                sessionId: $sessionId,
                sessionName: $sessionName,
                headerName: $headerName ?? HeaderName::AUTHORIZATION,
            ),
        );
    }

    /**
     * Publish the encrypted token session service.
     */
    public static function publishEncryptedTokenSession(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var string|null $sessionId */
        $sessionId = $env::SESSION_PHP_ID;
        /** @var string|null $sessionName */
        $sessionName = $env::SESSION_PHP_NAME;
        /** @var string|null $headerName */
        $headerName = $env::SESSION_JWT_HEADER_NAME;

        $container->setSingleton(
            EncryptedTokenSession::class,
            new EncryptedTokenSession(
                crypt: $container->getSingleton(CryptContract::class),
                request: $container->getSingleton(ServerRequestContract::class),
                sessionId: $sessionId,
                sessionName: $sessionName,
                headerName: $headerName ?? HeaderName::AUTHORIZATION,
            ),
        );
    }

    /**
     * Publish the log session service.
     */
    public static function publishLogSession(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var string|null $sessionId */
        $sessionId = $env::SESSION_PHP_ID;
        /** @var string|null $sessionName */
        $sessionName = $env::SESSION_PHP_NAME;

        $container->setSingleton(
            LogSession::class,
            new LogSession(
                logger: $container->getSingleton(LoggerContract::class),
                sessionId: $sessionId,
                sessionName: $sessionName,
            ),
        );
    }
}
