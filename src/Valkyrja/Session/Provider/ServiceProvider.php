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
use Valkyrja\Application\Env;
use Valkyrja\Cache\Contract\Cache;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Crypt\Contract\Crypt;
use Valkyrja\Http\Message\Enum\SameSite;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Log\Contract\Logger;
use Valkyrja\Session\CacheSession;
use Valkyrja\Session\Contract\Session;
use Valkyrja\Session\CookieSession;
use Valkyrja\Session\Data\CookieParams;
use Valkyrja\Session\LogSession;
use Valkyrja\Session\NullSession;
use Valkyrja\Session\PhpSession;

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
            Session::class       => [self::class, 'publishSession'],
            PhpSession::class    => [self::class, 'publishPhpSession'],
            NullSession::class   => [self::class, 'publishNullSession'],
            CacheSession::class  => [self::class, 'publishCacheSession'],
            CookieSession::class => [self::class, 'publishCookieSession'],
            LogSession::class    => [self::class, 'publishLogSession'],
            CookieParams::class  => [self::class, 'publishCookieParams'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            Session::class,
            PhpSession::class,
            NullSession::class,
            CacheSession::class,
            CookieSession::class,
            LogSession::class,
            CookieParams::class,
        ];
    }

    /**
     * Publish the cookie params service.
     */
    public static function publishCookieParams(Container $container): void
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
    public static function publishSession(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<Session> $default */
        $default = $env::SESSION_DEFAULT;

        $container->setSingleton(
            Session::class,
            $container->getSingleton($default),
        );
    }

    /**
     * Publish the php session service.
     */
    public static function publishPhpSession(Container $container): void
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
    public static function publishNullSession(Container $container): void
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
    public static function publishCacheSession(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var string|null $sessionId */
        $sessionId = $env::SESSION_PHP_ID;
        /** @var string|null $sessionName */
        $sessionName = $env::SESSION_PHP_NAME;

        $container->setSingleton(
            CacheSession::class,
            new CacheSession(
                cache: $container->getSingleton(Cache::class),
                cookieParams: $container->getSingleton(CookieParams::class),
                sessionId: $sessionId,
                sessionName: $sessionName,
            ),
        );
    }

    /**
     * Publish the cookie session service.
     */
    public static function publishCookieSession(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var string|null $sessionId */
        $sessionId = $env::SESSION_PHP_ID;
        /** @var string|null $sessionName */
        $sessionName = $env::SESSION_PHP_NAME;

        $container->setSingleton(
            CookieSession::class,
            new CookieSession(
                crypt: $container->getSingleton(Crypt::class),
                request: $container->getSingleton(ServerRequest::class),
                cookieParams: $container->getSingleton(CookieParams::class),
                sessionId: $sessionId,
                sessionName: $sessionName,
            ),
        );
    }

    /**
     * Publish the log session service.
     */
    public static function publishLogSession(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var string|null $sessionId */
        $sessionId = $env::SESSION_PHP_ID;
        /** @var string|null $sessionName */
        $sessionName = $env::SESSION_PHP_NAME;

        $container->setSingleton(
            LogSession::class,
            new LogSession(
                logger: $container->getSingleton(Logger::class),
                cookieParams: $container->getSingleton(CookieParams::class),
                sessionId: $sessionId,
                sessionName: $sessionName,
            ),
        );
    }
}
