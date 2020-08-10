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

namespace Valkyrja\Cache\Drivers;

use Valkyrja\Cache\Adapter;
use Valkyrja\Cache\Driver as Contract;
use Valkyrja\Cache\Tagger;

/**
 * Class Driver.
 *
 * @author Melech Mizrachi
 */
class Driver implements Contract
{
    /**
     * The adapter.
     *
     * @var Adapter
     */
    protected Adapter $adapter;

    /**
     * Driver constructor.
     *
     * @param Adapter $adapter The adapter
     */
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
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
        return $this->adapter->has($key);
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
        return $this->adapter->get($key);
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
        return $this->adapter->many(...$keys);
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
        $this->adapter->put($key, $value, $minutes);
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
        $this->adapter->putMany($values, $minutes);
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
        return $this->adapter->increment($key, $value);
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
        return $this->adapter->decrement($key, $value);
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
        $this->adapter->forever($key, $value);
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
        return $this->adapter->forget($key);
    }

    /**
     * Remove all items from the cache.
     *
     * @return bool
     */
    public function flush(): bool
    {
        return $this->adapter->flush();
    }

    /**
     * Get the cache key prefix.
     *
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->adapter->getPrefix();
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
        return $this->adapter->getTagger(...$tags);
    }
}
