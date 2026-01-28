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
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Routing\Constant\OptionName;
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
use Valkyrja\Session\Manager\Jwt\Cli\EncryptedOptionJwtSession;
use Valkyrja\Session\Manager\Jwt\Cli\OptionJwtSession;
use Valkyrja\Session\Manager\Jwt\Http\EncryptedHeaderJwtSession;
use Valkyrja\Session\Manager\Jwt\Http\HeaderJwtSession;
use Valkyrja\Session\Manager\LogSession;
use Valkyrja\Session\Manager\NullSession;
use Valkyrja\Session\Manager\PhpSession;
use Valkyrja\Session\Manager\Token\Cli\EncryptedOptionTokenSession;
use Valkyrja\Session\Manager\Token\Cli\OptionTokenSession;
use Valkyrja\Session\Manager\Token\Http\EncryptedHeaderTokenSession;
use Valkyrja\Session\Manager\Token\Http\HeaderTokenSession;

final class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function publishers(): array
    {
        return [
            SessionContract::class             => [self::class, 'publishSession'],
            PhpSession::class                  => [self::class, 'publishPhpSession'],
            NullSession::class                 => [self::class, 'publishNullSession'],
            CacheSession::class                => [self::class, 'publishCacheSession'],
            CookieSession::class               => [self::class, 'publishCookieSession'],
            EncryptedCookieSession::class      => [self::class, 'publishEncryptedCookieSession'],
            OptionJwtSession::class            => [self::class, 'publishOptionJwtSession'],
            EncryptedOptionJwtSession::class   => [self::class, 'publishEncryptedOptionJwtSession'],
            HeaderJwtSession::class            => [self::class, 'publishHeaderJwtSession'],
            EncryptedHeaderJwtSession::class   => [self::class, 'publishEncryptedHeaderJwtSession'],
            OptionTokenSession::class          => [self::class, 'publishOptionTokenSession'],
            EncryptedOptionTokenSession::class => [self::class, 'publishEncryptedOptionTokenSession'],
            HeaderTokenSession::class          => [self::class, 'publishHeaderTokenSession'],
            EncryptedHeaderTokenSession::class => [self::class, 'publishEncryptedHeaderTokenSession'],
            LogSession::class                  => [self::class, 'publishLogSession'],
            CookieParams::class                => [self::class, 'publishCookieParams'],
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
            OptionJwtSession::class,
            EncryptedOptionJwtSession::class,
            HeaderJwtSession::class,
            EncryptedHeaderJwtSession::class,
            OptionTokenSession::class,
            EncryptedOptionTokenSession::class,
            HeaderTokenSession::class,
            EncryptedHeaderTokenSession::class,
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
        /** @var non-empty-string|null $sessionId */
        $sessionId = $env::SESSION_PHP_ID;
        /** @var non-empty-string|null $sessionName */
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
        /** @var non-empty-string|null $sessionId */
        $sessionId = $env::SESSION_PHP_ID;
        /** @var non-empty-string|null $sessionName */
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
        /** @var non-empty-string|null $sessionId */
        $sessionId = $env::SESSION_PHP_ID;
        /** @var non-empty-string|null $sessionName */
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
        /** @var non-empty-string|null $sessionId */
        $sessionId = $env::SESSION_PHP_ID;
        /** @var non-empty-string|null $sessionName */
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
        /** @var non-empty-string|null $sessionId */
        $sessionId = $env::SESSION_PHP_ID;
        /** @var non-empty-string|null $sessionName */
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
     * Publish the option jwt session service.
     */
    public static function publishOptionJwtSession(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var non-empty-string|null $sessionId */
        $sessionId = $env::SESSION_PHP_ID;
        /** @var non-empty-string|null $sessionName */
        $sessionName = $env::SESSION_PHP_NAME;
        /** @var non-empty-string|null $optionName */
        $optionName = $env::SESSION_JWT_OPTION_NAME;

        $container->setSingleton(
            OptionJwtSession::class,
            new OptionJwtSession(
                jwt: $container->getSingleton(JwtContract::class),
                input: $container->getSingleton(InputContract::class),
                sessionId: $sessionId,
                sessionName: $sessionName,
                optionName: $optionName ?? OptionName::TOKEN,
            ),
        );
    }

    /**
     * Publish the encrypted option jwt session service.
     */
    public static function publishEncryptedOptionJwtSession(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var non-empty-string|null $sessionId */
        $sessionId = $env::SESSION_PHP_ID;
        /** @var non-empty-string|null $sessionName */
        $sessionName = $env::SESSION_PHP_NAME;
        /** @var non-empty-string|null $optionName */
        $optionName = $env::SESSION_JWT_OPTION_NAME;

        $container->setSingleton(
            EncryptedOptionJwtSession::class,
            new EncryptedOptionJwtSession(
                crypt: $container->getSingleton(CryptContract::class),
                jwt: $container->getSingleton(JwtContract::class),
                input: $container->getSingleton(InputContract::class),
                sessionId: $sessionId,
                sessionName: $sessionName,
                optionName: $optionName ?? OptionName::TOKEN,
            ),
        );
    }

    /**
     * Publish the header jwt session service.
     */
    public static function publishHeaderJwtSession(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var non-empty-string|null $sessionId */
        $sessionId = $env::SESSION_PHP_ID;
        /** @var non-empty-string|null $sessionName */
        $sessionName = $env::SESSION_PHP_NAME;
        /** @var non-empty-string|null $headerName */
        $headerName = $env::SESSION_JWT_HEADER_NAME;

        $container->setSingleton(
            HeaderJwtSession::class,
            new HeaderJwtSession(
                jwt: $container->getSingleton(JwtContract::class),
                request: $container->getSingleton(ServerRequestContract::class),
                sessionId: $sessionId,
                sessionName: $sessionName,
                headerName: $headerName ?? HeaderName::AUTHORIZATION,
            ),
        );
    }

    /**
     * Publish the encrypted header jwt session service.
     */
    public static function publishEncryptedHeaderJwtSession(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var non-empty-string|null $sessionId */
        $sessionId = $env::SESSION_PHP_ID;
        /** @var non-empty-string|null $sessionName */
        $sessionName = $env::SESSION_PHP_NAME;
        /** @var non-empty-string|null $headerName */
        $headerName = $env::SESSION_JWT_HEADER_NAME;

        $container->setSingleton(
            EncryptedHeaderJwtSession::class,
            new EncryptedHeaderJwtSession(
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
     * Publish the option token session service.
     */
    public static function publishOptionTokenSession(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var non-empty-string|null $sessionId */
        $sessionId = $env::SESSION_PHP_ID;
        /** @var non-empty-string|null $sessionName */
        $sessionName = $env::SESSION_PHP_NAME;
        /** @var non-empty-string|null $optionName */
        $optionName = $env::SESSION_JWT_OPTION_NAME;

        $container->setSingleton(
            OptionTokenSession::class,
            new OptionTokenSession(
                input: $container->getSingleton(InputContract::class),
                sessionId: $sessionId,
                sessionName: $sessionName,
                optionName: $optionName ?? OptionName::TOKEN,
            ),
        );
    }

    /**
     * Publish the encrypted option token session service.
     */
    public static function publishEncryptedOptionTokenSession(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var non-empty-string|null $sessionId */
        $sessionId = $env::SESSION_PHP_ID;
        /** @var non-empty-string|null $sessionName */
        $sessionName = $env::SESSION_PHP_NAME;
        /** @var non-empty-string|null $optionName */
        $optionName = $env::SESSION_JWT_OPTION_NAME;

        $container->setSingleton(
            EncryptedOptionTokenSession::class,
            new EncryptedOptionTokenSession(
                crypt: $container->getSingleton(CryptContract::class),
                input: $container->getSingleton(InputContract::class),
                sessionId: $sessionId,
                sessionName: $sessionName,
                optionName: $optionName ?? OptionName::TOKEN,
            ),
        );
    }

    /**
     * Publish the header token session service.
     */
    public static function publishHeaderTokenSession(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var non-empty-string|null $sessionId */
        $sessionId = $env::SESSION_PHP_ID;
        /** @var non-empty-string|null $sessionName */
        $sessionName = $env::SESSION_PHP_NAME;
        /** @var non-empty-string|null $headerName */
        $headerName = $env::SESSION_JWT_HEADER_NAME;

        $container->setSingleton(
            HeaderTokenSession::class,
            new HeaderTokenSession(
                request: $container->getSingleton(ServerRequestContract::class),
                sessionId: $sessionId,
                sessionName: $sessionName,
                headerName: $headerName ?? HeaderName::AUTHORIZATION,
            ),
        );
    }

    /**
     * Publish the encrypted header token session service.
     */
    public static function publishEncryptedHeaderTokenSession(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var non-empty-string|null $sessionId */
        $sessionId = $env::SESSION_PHP_ID;
        /** @var non-empty-string|null $sessionName */
        $sessionName = $env::SESSION_PHP_NAME;
        /** @var non-empty-string|null $headerName */
        $headerName = $env::SESSION_JWT_HEADER_NAME;

        $container->setSingleton(
            EncryptedHeaderTokenSession::class,
            new EncryptedHeaderTokenSession(
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
        /** @var non-empty-string|null $sessionId */
        $sessionId = $env::SESSION_PHP_ID;
        /** @var non-empty-string|null $sessionName */
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
