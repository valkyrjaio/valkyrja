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

namespace Valkyrja\Cache\Adapters;

use Valkyrja\Cache\Adapter;
use Valkyrja\Cache\Tagger;
use Valkyrja\Cache\Taggers\Tagger as TagClass;

/**
 * Class NullAdapter.
 *
 * @author Melech Mizrachi
 */
class NullAdapter implements Adapter
{
    /**
     * The prefix to use for all keys.
     *
     * @var string
     */
    protected string $prefix;

    /**
     * NullAdapter constructor.
     *
     * @param string|null $prefix [optional] The prefix
     */
    public function __construct(string $prefix = null)
    {
        $this->prefix = $prefix ?? '';
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
        return true;
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
        return '';
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
        return [];
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
     * @param string[] $values
     * @param int      $minutes
     *
     * @return void
     */
    public function putMany(array $values, int $minutes): void
    {
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
        return $value;
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
        return $value;
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
        return true;
    }

    /**
     * Remove all items from the cache.
     *
     * @return bool
     */
    public function flush(): bool
    {
        return true;
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
     * Get tagger.
     *
     * @param string ...$tags
     *
     * @return Tagger
     */
    public function getTagger(string ...$tags): Tagger
    {
        return TagClass::make($this, ...$tags);
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
