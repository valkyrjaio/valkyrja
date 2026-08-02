<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cache\Manager\Contract;

use Valkyrja\Cache\Tagger\Contract\TaggerContract;

interface CacheContract
{
    /**
     * Determine if an item exists in the cache.
     */
    public function has(string $key): bool;

    /**
     * Retrieve an item from the cache by key.
     */
    public function get(string $key): string;

    /**
     * Retrieve multiple items from the cache by key.
     *
     * Items not found in the cache will have a null value.
     *
     * @return string[]
     */
    public function many(string ...$keys): array;

    /**
     * Store an item in the cache for a given number of seconds.
     */
    public function put(string $key, string $value, int $seconds): void;

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
     * @param array<string, string> $values The values as a key/value pair
     */
    public function putMany(array $values, int $seconds): void;

    /**
     * Increment the value of an item in the cache.
     */
    public function increment(string $key, int $value = 1): int;

    /**
     * Decrement the value of an item in the cache.
     */
    public function decrement(string $key, int $value = 1): int;

    /**
     * Store an item in the cache indefinitely.
     */
    public function forever(string $key, string $value): void;

    /**
     * Remove an item from the cache.
     */
    public function forget(string $key): bool;

    /**
     * Remove all items from the cache.
     */
    public function flush(): bool;

    /**
     * Get the cache key prefix.
     */
    public function getPrefix(): string;

    /**
     * Get the tagger.
     */
    public function getTagger(string ...$tags): TaggerContract;
}
