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

namespace Valkyrja\Cache\Provider;

use Override;
use Predis\Client;
use Valkyrja\Application\Env;
use Valkyrja\Cache\Manager\Contract\Cache;
use Valkyrja\Cache\Manager\LogCache;
use Valkyrja\Cache\Manager\NullCache;
use Valkyrja\Cache\Manager\RedisCache;
use Valkyrja\Container\Manager\Contract\Container;
use Valkyrja\Container\Provider\Provider;
use Valkyrja\Log\Logger\Contract\Logger;

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
            Cache::class      => [self::class, 'publishCache'],
            RedisCache::class => [self::class, 'publishRedisCache'],
            Client::class     => [self::class, 'publishRedisClient'],
            LogCache::class   => [self::class, 'publishLogCache'],
            NullCache::class  => [self::class, 'publishNullCache'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            Cache::class,
            RedisCache::class,
            Client::class,
            LogCache::class,
            NullCache::class,
        ];
    }

    /**
     * Publish the cache service.
     */
    public static function publishCache(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<Cache> $default */
        $default = $env::CACHE_DEFAULT;

        $container->setSingleton(
            Cache::class,
            $container->getSingleton($default)
        );
    }

    /**
     * Publish the redis cache service.
     */
    public static function publishRedisCache(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var string $prefix */
        $prefix = $env::CACHE_REDIS_PREFIX;

        $container->setSingleton(
            RedisCache::class,
            new RedisCache(
                client: $container->getSingleton(Client::class),
                prefix: $prefix
            )
        );
    }

    /**
     * Publish the redis client service.
     */
    public static function publishRedisClient(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var non-empty-string $host */
        $host = $env::CACHE_REDIS_HOST;
        /** @var int $port */
        $port = $env::CACHE_REDIS_PORT;

        $container->setSingleton(
            Client::class,
            new Client(
                parameters: [
                    'host' => $host,
                    'port' => $port,
                ]
            )
        );
    }

    /**
     * Publish the log cache service.
     */
    public static function publishLogCache(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var string $prefix */
        $prefix = $env::CACHE_LOG_PREFIX;
        /** @var class-string<Logger> $logger */
        $logger = $env::CACHE_LOG_LOGGER;

        $container->setSingleton(
            LogCache::class,
            new LogCache(
                logger: $container->get($logger),
                prefix: $prefix
            )
        );
    }

    /**
     * Publish the null cache service.
     */
    public static function publishNullCache(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var string $prefix */
        $prefix = $env::CACHE_NULL_PREFIX;

        $container->setSingleton(
            NullCache::class,
            new NullCache(
                prefix: $prefix
            )
        );
    }
}
