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

use JsonException;
use Valkyrja\Cache\Adapter;
use Valkyrja\Cache\Tagger as Contract;
use Valkyrja\Support\Type\Arr;

use function json_decode;

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
     * @var Adapter
     */
    protected Adapter $adapter;

    /**
     * The tags.
     *
     * @var array
     */
    protected array $tags;

    /**
     * Tag constructor.
     *
     * @param Adapter $store
     * @param string  ...$tags
     */
    public function __construct(Adapter $store, string ...$tags)
    {
        $this->adapter = $store;
        $this->tags    = $tags;
    }

    /**
     * Make a new Tag Store.
     *
     * @param Adapter $store
     * @param string  ...$tags
     *
     * @return static
     */
    public static function make(Adapter $store, string ...$tags): self
    {
        return new static($store, ...$tags);
    }

    /**
     * Determine if an item exists in the cache.
     *
     * @param string $key
     *
     * @throws JsonException
     *
     * @return mixed
     */
    public function has(string $key): bool
    {
        foreach ($this->tags as $tag) {
            if (isset($this->getKeys($tag)[$key]) && $this->adapter->has($key)) {
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
     * @throws JsonException
     *
     * @return string|null
     */
    public function get(string $key): ?string
    {
        foreach ($this->tags as $tag) {
            if (isset($this->getKeys($tag)[$key])) {
                return $this->adapter->get($key);
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
     * @throws JsonException
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
                    $items[] = $this->adapter->get($key);
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
     * @throws JsonException
     *
     * @return void
     */
    public function put(string $key, string $value, int $minutes): void
    {
        $this->tag($key);

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
     * @throws JsonException
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
     * @throws JsonException
     *
     * @return int
     */
    public function increment(string $key, int $value = 1): int
    {
        $this->tag($key);

        return $this->adapter->increment($key, $value);
    }

    /**
     * Decrement the value of an item in the cache.
     *
     * @param string $key
     * @param int    $value
     *
     * @throws JsonException
     *
     * @return int
     */
    public function decrement(string $key, int $value = 1): int
    {
        $this->tag($key);

        return $this->adapter->decrement($key, $value);
    }

    /**
     * Store an item in the cache indefinitely.
     *
     * @param string $key
     * @param string $value
     *
     * @throws JsonException
     *
     * @return void
     */
    public function forever(string $key, string $value): void
    {
        $this->tag($key);

        $this->adapter->forever($key, $value);
    }

    /**
     * Remove an item from the cache.
     *
     * @param string $key
     *
     * @throws JsonException
     *
     * @return bool
     */
    public function forget(string $key): bool
    {
        $this->untag($key);

        return $this->adapter->forget($key);
    }

    /**
     * Remove all items from the cache.
     *
     * @throws JsonException
     *
     * @return bool
     */
    public function flush(): bool
    {
        foreach ($this->tags as $tag) {
            foreach ($this->getKeys($tag) as $key) {
                $this->adapter->forget($key);
            }
        }

        return true;
    }

    /**
     * Tag a key.
     *
     * @param string $key
     *
     * @throws JsonException
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
     * @throws JsonException
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
     * @throws JsonException
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
     * @throws JsonException
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
     * @throws JsonException
     *
     * @return array
     */
    protected function getKeys(string $tag): array
    {
        $keys = $this->adapter->get($tag);

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
     * @throws JsonException
     *
     * @return void
     */
    protected function putKeys(string $tag, array $keys): void
    {
        $this->adapter->forever($tag, Arr::toString($keys));
    }
}
