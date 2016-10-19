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
 * Class Collection
 *
 * @package Valkyrja\Support
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
     * @return bool|mixed
     */
    public function get($key, $default = false)
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
    public function has($key)
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
    public function exists($key)
    {
        return array_key_exists($key, $this->collection);
    }

    /**
     * Set a new item into the collection.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    public function set($key, $value)
    {
        $this->collection[$key] = $value;

        return $this;
    }

    /**
     * Remove an item from the collection.
     *
     * @param string $key
     *
     * @return $this
     */
    public function remove($key)
    {
        if (!$this->has($key)) {
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
    public function all()
    {
        return $this->collection;
    }

    /**
     * Set the collection.
     *
     * @param array $collection
     *
     * @return $this
     */
    public function setAll(array $collection)
    {
        $this->collection = $collection;

        return $this;
    }

    /**
     * Get all the keys in the collection.
     *
     * @return array
     */
    public function keys()
    {
        return array_keys($this->collection);
    }

    /**
     * Get the total count of items in the collection.
     *
     * @return int
     */
    public function count()
    {
        return count($this->collection);
    }

    /**
     * Get a single item from the collection.
     *
     * @param string $key
     *
     * @return bool|mixed
     */
    public function __get($key)
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
    public function __isset($key)
    {
        return $this->has($key);
    }

    /**
     * Set a new item into the collection.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return \Valkyrja\Support\Collection
     */
    public function __set($key, $value)
    {
        return $this->set($key, $value);
    }

    /**
     * Remove an item from the collection.
     *
     * @param string $key
     *
     * @return \Valkyrja\Support\Collection
     */
    public function __unset($key)
    {
        return $this->remove($key);
    }

    /**
     * Convert the collection to a string.
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->collection);
    }
}
