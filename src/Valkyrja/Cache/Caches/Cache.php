<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Cache\Caches;

use InvalidArgumentException;
use Predis\Client;
use Valkyrja\Application\Application;
use Valkyrja\Cache\Cache as CacheContract;
use Valkyrja\Cache\Store;
use Valkyrja\Cache\Stores\RedisStore;
use Valkyrja\Support\Providers\Provides;

/**
 * Class Cache.
 *
 * @author Melech Mizrachi
 */
class Cache implements CacheContract
{
    use Provides;

    /**
     * The application.
     *
     * @var Application
     */
    protected Application $app;

    /**
     * Stores.
     *
     * @var Store[]
     */
    protected array $stores = [];

    /**
     * NativeCache constructor.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            CacheContract::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Application $app The application
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $app->container()->singleton(
            CacheContract::class,
            new static($app)
        );
    }

    /**
     * Get a store by name.
     *
     * @param string|null $name
     *
     * @throws InvalidArgumentException If the name doesn't exist
     *
     * @return Store
     */
    public function store(string $name = null): Store
    {
        return $this->getStore($name);
    }

    /**
     * Get the appropriate store.
     *
     * @param string|null $name
     *
     * @throws InvalidArgumentException
     *
     * @return Store
     */
    protected function getStore(string $name = null): Store
    {
        $name = $name ?? $this->app->config()['cache']['default'];

        if (isset($this->stores[$name])) {
            return $this->stores[$name];
        }

        $config = $this->getStoreConfig($name);

        return $this->getStoreFromConfig($config);
    }

    /**
     * Get config given a name.
     *
     * @param string $name
     *
     * @throws InvalidArgumentException
     *
     * @return array
     */
    protected function getStoreConfig(string $name): array
    {
        $config = $this->app->config('cache.stores.' . $name);

        if (null === $config) {
            throw new InvalidArgumentException('Invalid store name specified: ' . $name);
        }

        if (empty($config)) {
            throw new InvalidArgumentException('Invalid store config specified: ' . $name);
        }

        return $config;
    }

    /**
     * Get a store from config.
     *
     * @param array $config
     *
     * @throws InvalidArgumentException
     *
     * @return Store
     */
    protected function getStoreFromConfig(array $config): Store
    {
        $storeMethod = 'get' . ucfirst($config['driver']) . 'Store';

        if (method_exists($this, $storeMethod)) {
            return $this->{$storeMethod}($config);
        }

        throw new InvalidArgumentException('Invalid store driver specified: ' . $config['driver']);
    }

    /**
     * Get the redis store.
     *
     * @param array $config
     *
     * @return Store
     */
    protected function getRedisStore(array $config): Store
    {
        $predis = new Client($config['connection']);

        return new RedisStore($predis, $this->app->config('config.prefix'));
    }
}