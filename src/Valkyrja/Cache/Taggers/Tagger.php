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

namespace Valkyrja\Cache\Taggers;

use Valkyrja\Cache\Store;
use Valkyrja\Cache\Tagger as Contract;

use function json_decode;
use function json_encode;

use const JSON_THROW_ON_ERROR;

/**
 * Class Cache.
 *
 * @author Melech Mizrachi
 */
class Tagger implements Contract
{
    /**
     * The cache store.
     *
     * @var Store
     */
    protected Store $store;

    /**
     * The tags.
     *
     * @var array
     */
    protected array $tags;

    /**
     * Tag constructor.
     *
     * @param Store  $store
     * @param string ...$tags
     */
    public function __construct(Store $store, string ...$tags)
    {
        $this->store = $store;
        $this->tags  = $tags;
    }

    /**
     * Make a new Tag Store.
     *
     * @param Store  $store
     * @param string ...$tags
     *
     * @return static
     */
    public static function make(Store $store, string ...$tags): self
    {
        return new static($store, ...$tags);
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
        foreach ($this->tags as $tag) {
            if (isset($this->getKeys($tag)[$key]) && $this->store->has($key)) {
                return true;
            }
        }

        return false;
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
        foreach ($this->tags as $tag) {
            if (isset($this->getKeys($tag)[$key])) {
                return $this->store->get($key);
            }
        }

        return null;
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
        $items = [];

        foreach ($this->tags as $tag) {
            $cachedKeys = $this->getKeys($tag);

            foreach ($keys as $key) {
                if (isset($cachedKeys[$key])) {
                    $items[] = $this->store->get($key);
                }
            }
        }

        return $items;
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
        $this->tag($key);

        $this->store->put($key, $value, $minutes);
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
        foreach ($values as $key => $value) {
            $this->put($key, $value, $minutes);
        }
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
        $this->tag($key);

        return $this->store->increment($key, $value);
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
        $this->tag($key);

        return $this->store->decrement($key, $value);
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
        $this->tag($key);

        $this->store->forever($key, $value);
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
        $this->untag($key);

        return $this->store->forget($key);
    }

    /**
     * Remove all items from the cache.
     *
     * @return bool
     */
    public function flush(): bool
    {
        foreach ($this->tags as $tag) {
            foreach ($this->getKeys($tag) as $key) {
                $this->store->forget($key);
            }
        }

        return true;
    }

    /**
     * Tag a key.
     *
     * @param string $key
     *
     * @return static
     */
    public function tag(string $key): self
    {
        foreach ($this->tags as $tag) {
            $keys = $this->getKeys($tag);

            $keys[$key] = $key;

            $this->putKeys($tag, $keys);
        }

        return $this;
    }

    /**
     * Untag a key.
     *
     * @param string $key
     *
     * @return static
     */
    public function untag(string $key): self
    {
        foreach ($this->tags as $tag) {
            $keys = $this->getKeys($tag);

            unset($keys[$key]);

            $this->putKeys($tag, $keys);
        }

        return $this;
    }

    /**
     * Tag many keys.
     *
     * @param string ...$keys
     *
     * @return static
     */
    public function tagMany(string ...$keys): self
    {
        foreach ($keys as $key) {
            $this->tag($key);
        }

        return $this;
    }

    /**
     * Untag many keys.
     *
     * @param string ...$keys
     *
     * @return static
     */
    public function untagMany(string ...$keys): self
    {
        foreach ($keys as $key) {
            $this->untag($key);
        }

        return $this;
    }

    /**
     * Get a tag.
     *
     * @param string $tag
     *
     * @return array
     */
    protected function getKeys(string $tag): array
    {
        $keys = $this->store->get($tag);

        if ($keys) {
            return json_decode($keys, true, 512, JSON_THROW_ON_ERROR);
        }

        return [];
    }

    /**
     * Put a tag.
     *
     * @param string $tag
     * @param array  $keys
     *
     * @return void
     */
    protected function putKeys(string $tag, array $keys): void
    {
        $this->store->forever($tag, json_encode($keys, JSON_THROW_ON_ERROR));
    }
}
