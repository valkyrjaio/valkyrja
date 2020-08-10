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

use InvalidArgumentException;
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
     * Use a store by name.
     *
     * @param string|null $name    [optional] The store name
     * @param string|null $adapter [optional] The adapter
     *
     * @throws InvalidArgumentException If the name doesn't exist
     *
     * @return Driver
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
     * Determine if an item exists in the cache.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function has(string $key): bool
    {
        return $this->useStore()->has($key);
    }

    /**
     * Retrieve an item from the cache by key.
     *
     * @param string $key
     *
     * @return string|null
     */
    public function get(string $key): ?string
    {
        return $this->useStore()->get($key);
    }

    /**
     * Retrieve multiple items from the cache by key.
     *
     * Items not found in the cache will have a null value.
     *
     * @param string ...$keys
     *
     * @return array
     */
    public function many(string ...$keys): array
    {
        return $this->useStore()->many(...$keys);
    }

    /**
     * Store an item in the cache for a given number of minutes.
     *
     * @param string $key
     * @param string $value
     * @param int    $minutes
     *
     * @return void
     */
    public function put(string $key, string $value, int $minutes): void
    {
        $this->useStore()->put($key, $value, $minutes);
    }

    /**
     * Store multiple items in the cache for a given number of minutes.
     *
     * <code>
     *      $store->putMany(
     *          [
     *              'key'  => 'value',
     *              'key2' => 'value2',
     *          ],
     *          5
     *      )
     * </code>
     *
     * @param array $values
     * @param int   $minutes
     *
     * @return void
     */
    public function putMany(array $values, int $minutes): void
    {
        $this->useStore()->putMany($values, $minutes);
    }

    /**
     * Increment the value of an item in the cache.
     *
     * @param string $key
     * @param int    $value
     *
     * @return int
     */
    public function increment(string $key, int $value = 1): int
    {
        return $this->useStore()->increment($key, $value);
    }

    /**
     * Decrement the value of an item in the cache.
     *
     * @param string $key
     * @param int    $value
     *
     * @return int
     */
    public function decrement(string $key, int $value = 1): int
    {
        return $this->useStore()->decrement($key, $value);
    }

    /**
     * Store an item in the cache indefinitely.
     *
     * @param string $key
     * @param string $value
     *
     * @return void
     */
    public function forever(string $key, string $value): void
    {
        $this->useStore()->forever($key, $value);
    }

    /**
     * Remove an item from the cache.
     *
     * @param string $key
     *
     * @return bool
     */
    public function forget(string $key): bool
    {
        return $this->useStore()->forget($key);
    }

    /**
     * Remove all items from the cache.
     *
     * @return bool
     */
    public function flush(): bool
    {
        return $this->useStore()->flush();
    }

    /**
     * Get the cache key prefix.
     *
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->useStore()->getPrefix();
    }

    /**
     * Get the tagger.
     *
     * @param string ...$tags
     *
     * @return Tagger
     */
    public function getTagger(string ...$tags): Tagger
    {
        return $this->useStore()->getTagger(...$tags);
    }
}
