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

namespace Valkyrja\Support;

use function count;
use function in_array;

/**
 * Class Collection.
 *
 * @author Melech Mizrachi
 */
class Collection
{
    /**
     * The collection of items.
     *
     * @var array
     */
    protected array $collection = [];

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
     * Set the collection.
     *
     * @param array $collection The collection
     *
     * @return self
     */
    public function setAll(array $collection): self
    {
        $this->collection = $collection;

        return $this;
    }

    /**
     * Determine if a value exists in the collection.
     *
     * @param mixed $value The value
     *
     * @return bool
     */
    public function exists($value): bool
    {
        return in_array($value, $this->collection, true);
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
     * @param string $key The key
     *
     * @return mixed
     */
    public function __get(string $key) // : mixed
    {
        return $this->get($key);
    }

    /**
     * Set a new item into the collection.
     *
     * @param string $key   The key
     * @param mixed  $value The value
     *
     * @return mixed
     */
    public function __set(string $key, $value)
    {
        return $this->set($key, $value);
    }

    /**
     * Get a single item from the collection.
     *
     * @param string $key     The key to get
     * @param mixed  $default [optional] The default value
     *
     * @return mixed
     */
    public function get(string $key, $default = null) // : mixed
    {
        return $this->has($key) ? $this->collection[$key] : $default;
    }

    /**
     * Determine if an item is in the collection.
     *
     * @param string $key The key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($this->collection[$key]);
    }

    /**
     * Set a new item into the collection.
     *
     * @param string $key   The key
     * @param mixed  $value The value
     *
     * @return self
     */
    public function set(string $key, $value): self
    {
        $this->collection[$key] = $value;

        return $this;
    }

    /**
     * Determine if an item is in the collection.
     *
     * @param string $key The key
     *
     * @return bool
     */
    public function __isset(string $key): bool
    {
        return $this->has($key);
    }

    /**
     * Remove an item from the collection.
     *
     * @param string $key The key
     *
     * @return void
     */
    public function __unset(string $key): void
    {
        $this->remove($key);
    }

    /**
     * Remove an item from the collection.
     *
     * @param string $key The key
     *
     * @return self
     */
    public function remove(string $key): self
    {
        if (! $this->has($key)) {
            return $this;
        }

        unset($this->collection[$key]);

        return $this;
    }

    /**
     * Convert the collection to a string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return (string) json_encode($this->collection, JSON_THROW_ON_ERROR);
    }
}
