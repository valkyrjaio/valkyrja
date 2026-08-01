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
use Valkyrja\Application\Data\Contract\ConfigContract;
use Valkyrja\Cache\Data\CacheConfig;
use Valkyrja\Cache\Data\Contract\CacheConfigContract;
use Valkyrja\Cache\Manager\Contract\CacheContract;
use Valkyrja\Cache\Manager\LogCache;
use Valkyrja\Cache\Manager\NullCache;
use Valkyrja\Cache\Manager\RedisCache;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;

class CacheServiceProvider implements ServiceProviderContract
{
    /**
     * Publish the cache config service.
     */
    public static function publishConfig(ContainerContract $container): void
    {
        $config = $container->getSingleton(ConfigContract::class);

        if ($config instanceof CacheConfigContract) {
            $container->setSingleton(CacheConfigContract::class, $config);

            return;
        }

        $container->setSingleton(
            CacheConfigContract::class,
            new CacheConfig()
        );
    }

    /**
     * Publish the cache service.
     */
    public static function publishCache(ContainerContract $container): void
    {
        $config = $container->getSingleton(CacheConfigContract::class);

        $container->setSingleton(
            CacheContract::class,
            $container->getSingleton($config->defaultCache)
        );
    }

    /**
     * Publish the redis cache service.
     */
    public static function publishRedisCache(ContainerContract $container): void
    {
        $config = $container->getSingleton(CacheConfigContract::class);

        $container->setSingleton(
            RedisCache::class,
            new RedisCache(
                client: $container->getSingleton(Client::class),
                prefix: $config->redisCache->prefix
            )
        );
    }

    /**
     * Publish the redis client service.
     */
    public static function publishRedisClient(ContainerContract $container): void
    {
        $config = $container->getSingleton(CacheConfigContract::class);

        $container->setSingleton(
            Client::class,
            new Client(
                parameters: [
                    'host' => $config->redisCache->host,
                    'port' => $config->redisCache->port,
                ]
            )
        );
    }

    /**
     * Publish the log cache service.
     */
    public static function publishLogCache(ContainerContract $container): void
    {
        $config = $container->getSingleton(CacheConfigContract::class);

        $container->setSingleton(
            LogCache::class,
            new LogCache(
                logger: $container->getSingleton($config->logCache->logger),
                prefix: $config->logCache->prefix
            )
        );
    }

    /**
     * Publish the null cache service.
     */
    public static function publishNullCache(ContainerContract $container): void
    {
        $config = $container->getSingleton(CacheConfigContract::class);

        $container->setSingleton(
            NullCache::class,
            new NullCache(
                prefix: $config->nullCache->prefix
            )
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function publishers(): array
    {
        return [
            CacheConfigContract::class => [self::class, 'publishConfig'],
            CacheContract::class       => [self::class, 'publishCache'],
            RedisCache::class          => [self::class, 'publishRedisCache'],
            Client::class              => [self::class, 'publishRedisClient'],
            LogCache::class            => [self::class, 'publishLogCache'],
            NullCache::class           => [self::class, 'publishNullCache'],
        ];
    }
}
