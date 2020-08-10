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

namespace Valkyrja\Cache;

/**
 * Interface Adapter.
 *
 * @author Melech Mizrachi
 */
interface Adapter
{
    /**
     * Determine if an item exists in the cache.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function has(string $key): bool;

    /**
     * Retrieve an item from the cache by key.
     *
     * @param string $key
     *
     * @return string|null
     */
    public function get(string $key): ?string;

    /**
     * Retrieve multiple items from the cache by key.
     *
     * Items not found in the cache will have a null value.
     *
     * @param string ...$keys
     *
     * @return array
     */
    public function many(string ...$keys): array;

    /**
     * Store an item in the cache for a given number of minutes.
     *
     * @param string $key
     * @param string $value
     * @param int    $minutes
     *
     * @return void
     */
    public function put(string $key, string $value, int $minutes): void;

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
    public function putMany(array $values, int $minutes): void;

    /**
     * Increment the value of an item in the cache.
     *
     * @param string $key
     * @param int    $value
     *
     * @return int
     */
    public function increment(string $key, int $value = 1): int;

    /**
     * Decrement the value of an item in the cache.
     *
     * @param string $key
     * @param int    $value
     *
     * @return int
     */
    public function decrement(string $key, int $value = 1): int;

    /**
     * Store an item in the cache indefinitely.
     *
     * @param string $key
     * @param string $value
     *
     * @return void
     */
    public function forever(string $key, string $value): void;

    /**
     * Remove an item from the cache.
     *
     * @param string $key
     *
     * @return bool
     */
    public function forget(string $key): bool;

    /**
     * Remove all items from the cache.
     *
     * @return bool
     */
    public function flush(): bool;

    /**
     * Get the cache key prefix.
     *
     * @return string
     */
    public function getPrefix(): string;

    /**
     * Get the tagger.
     *
     * @param string ...$tags
     *
     * @return Tagger
     */
    public function getTagger(string ...$tags): Tagger;
}
