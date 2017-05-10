<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Based off work by Fabien Potencier for symfony/http-foundation/Request.php
 */

namespace Valkyrja\Support;

use Valkyrja\Contracts\Support\Collection as CollectionContract;

/**
 * Class Collection.
 *
 *
 * @author  Melech Mizrachi
 */
class Collection implements CollectionContract
{
    /**
     * The collection of items.
     *
     * @var array
     */
    protected $collection;

    /**
     * Collection constructor.
     *
     * @param array $collection
     */
    public function __construct(array $collection = [])
    {
        $this->setAll($collection);
    }

    /**
     * Get a single item from the collection.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get(string $key, $default = false) // : mixed
    {
        return $this->has($key)
            ? $this->collection[$key]
            : $default;
    }

    /**
     * Determine if an item is in the collection.
     *
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($this->collection[$key]);
    }

    /**
     * Determine if an item exists in the collection.
     *
     * @param string $key
     *
     * @return bool
     */
    public function exists(string $key): bool
    {
        return array_key_exists($key, $this->collection);
    }

    /**
     * Set a new item into the collection.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return CollectionContract
     */
    public function set(string $key, $value): CollectionContract
    {
        $this->collection[$key] = $value;

        return $this;
    }

    /**
     * Remove an item from the collection.
     *
     * @param string $key
     *
     * @return CollectionContract
     */
    public function remove(string $key): CollectionContract
    {
        if (! $this->has($key)) {
            return $this;
        }

        unset($this->collection[$key]);

        return $this;
    }

    /**
     * Get all the items in the collection.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->collection;
    }

    /**
     * Set the collection.
     *
     * @param array $collection
     *
     * @return CollectionContract
     */
    public function setAll(array $collection): CollectionContract
    {
        $this->collection = $collection;

        return $this;
    }

    /**
     * Get all the keys in the collection.
     *
     * @return array
     */
    public function keys(): array
    {
        return array_keys($this->collection);
    }

    /**
     * Get the total count of items in the collection.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->collection);
    }

    /**
     * Determine if the collection is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->collection);
    }

    /**
     * Get a single item from the collection.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get(string $key) // : mixed
    {
        return $this->get($key);
    }

    /**
     * Determine if an item is in the collection.
     *
     * @param string $key
     *
     * @return bool
     */
    public function __isset(string $key): bool
    {
        return $this->has($key);
    }

    /**
     * Set a new item into the collection.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return CollectionContract
     */
    public function __set(string $key, $value): CollectionContract
    {
        return $this->set($key, $value);
    }

    /**
     * Remove an item from the collection.
     *
     * @param string $key
     *
     * @return CollectionContract
     */
    public function __unset(string $key): CollectionContract
    {
        return $this->remove($key);
    }

    /**
     * Convert the collection to a string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return json_encode($this->collection);
    }
}
