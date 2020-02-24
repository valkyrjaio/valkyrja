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

namespace Valkyrja\Cache\Stores;

use Predis\ClientInterface as Client;
use Valkyrja\Cache\Store;

/**
 * Class RedisStore.
 *
 * @author Melech Mizrachi
 */
class RedisStore implements Store
{
    /**
     * The prefix to use for all keys.
     *
     * @var string
     */
    protected string $prefix;

    /**
     * The predis client.
     *
     * @var Client
     */
    protected Client $predis;

    /**
     * RedisStore constructor.
     *
     * @param Client      $client
     * @param string|null $prefix
     */
    public function __construct(Client $client, string $prefix = null)
    {
        $this->predis = $client;
        $this->prefix = $prefix;
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
        return (bool) $this->predis->exists($this->getKey($key));
    }

    /**
     * Retrieve an item from the cache by key.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->predis->get($this->getKey($key));
    }

    /**
     * Retrieve multiple items from the cache by key.
     *
     * Items not found in the cache will have a null value.
     *
     * @param array $keys
     *
     * @return array
     */
    public function many(array $keys): array
    {
        $prefixedKeys = [];

        foreach ($keys as $key) {
            $prefixedKeys[] = $this->getKey($key);
        }

        return $this->predis->mget($prefixedKeys);
    }

    /**
     * Store an item in the cache for a given number of minutes.
     *
     * @param string $key
     * @param mixed  $value
     * @param int    $minutes
     *
     * @return void
     */
    public function put(string $key, $value, int $minutes): void
    {
        $this->predis->setex($this->getKey($key), $minutes * 60, $value);
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
        $seconds = $minutes * 60;

        $this->predis->transaction(
            function ($client) use ($values, $seconds) {
                /** @var Client $client */
                foreach ($values as $key => $value) {
                    $client->setex($this->getKey($key), $seconds, $value);
                }
            }
        );
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
        return $this->predis->incrby($this->getKey($key), $value);
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
        return $this->predis->decrby($this->getKey($key), $value);
    }

    /**
     * Store an item in the cache indefinitely.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return void
     */
    public function forever(string $key, $value): void
    {
        $this->predis->set($this->getKey($key), $value);
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
        return (bool) $this->predis->del([$this->getKey($key)]);
    }

    /**
     * Remove all items from the cache.
     *
     * @return bool
     */
    public function flush(): bool
    {
        return (bool) $this->predis->flushdb();
    }

    /**
     * Get the cache key prefix.
     *
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix ?? '';
    }

    /**
     * Get key.
     *
     * @param string $key
     *
     * @return string
     */
    protected function getKey(string $key): string
    {
        return $this->getPrefix() . $key;
    }
}
