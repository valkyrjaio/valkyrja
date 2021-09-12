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

namespace Valkyrja\Cache\Managers;

use Valkyrja\Cache\Cache as Contract;
use Valkyrja\Cache\Driver;
use Valkyrja\Cache\Tagger;
use Valkyrja\Container\Container;

/**
 * Class Cache.
 *
 * @author Melech Mizrachi
 */
class Cache implements Contract
{
    /**
     * The drivers.
     *
     * @var Driver[]
     */
    protected static array $driversCache = [];

    /**
     * The container.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * The config.
     *
     * @var array
     */
    protected array $config;

    /**
     * The adapters.
     *
     * @var array
     */
    protected array $adapters;

    /**
     * The stores.
     *
     * @var array
     */
    protected array $stores;

    /**
     * The drivers config.
     *
     * @var array
     */
    protected array $drivers;

    /**
     * The default store.
     *
     * @var string
     */
    protected string $defaultStore;

    /**
     * Cache constructor.
     *
     * @param Container $container The container
     * @param array     $config    The config
     */
    public function __construct(Container $container, array $config)
    {
        $this->container    = $container;
        $this->config       = $config;
        $this->stores       = $config['stores'];
        $this->adapters     = $config['adapters'];
        $this->drivers      = $config['drivers'];
        $this->defaultStore = $config['default'];
    }

    /**
     * @inheritDoc
     */
    public function useStore(string $name = null, string $adapter = null): Driver
    {
        // The store to use
        $name ??= $this->defaultStore;
        // The store config to use
        $store = $this->stores[$name];
        // The adapter to use
        $adapter ??= $store['adapter'];
        // The cache key to use
        $cacheKey = $name . $adapter;

        return self::$driversCache[$cacheKey]
            ?? self::$driversCache[$cacheKey] = $this->container->get(
                $this->drivers[$store['driver']],
                [
                    $store,
                    $this->adapters[$adapter],
                ]
            );
    }

    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        return $this->useStore()->has($key);
    }

    /**
     * @inheritDoc
     */
    public function get(string $key): ?string
    {
        return $this->useStore()->get($key);
    }

    /**
     * @inheritDoc
     */
    public function many(string ...$keys): array
    {
        return $this->useStore()->many(...$keys);
    }

    /**
     * @inheritDoc
     */
    public function put(string $key, string $value, int $minutes): void
    {
        $this->useStore()->put($key, $value, $minutes);
    }

    /**
     * @inheritDoc
     */
    public function putMany(array $values, int $minutes): void
    {
        $this->useStore()->putMany($values, $minutes);
    }

    /**
     * @inheritDoc
     */
    public function increment(string $key, int $value = 1): int
    {
        return $this->useStore()->increment($key, $value);
    }

    /**
     * @inheritDoc
     */
    public function decrement(string $key, int $value = 1): int
    {
        return $this->useStore()->decrement($key, $value);
    }

    /**
     * @inheritDoc
     */
    public function forever(string $key, string $value): void
    {
        $this->useStore()->forever($key, $value);
    }

    /**
     * @inheritDoc
     */
    public function forget(string $key): bool
    {
        return $this->useStore()->forget($key);
    }

    /**
     * @inheritDoc
     */
    public function flush(): bool
    {
        return $this->useStore()->flush();
    }

    /**
     * @inheritDoc
     */
    public function getPrefix(): string
    {
        return $this->useStore()->getPrefix();
    }

    /**
     * @inheritDoc
     */
    public function getTagger(string ...$tags): Tagger
    {
        return $this->useStore()->getTagger(...$tags);
    }
}
