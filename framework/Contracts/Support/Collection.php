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

namespace Valkyrja\Contracts\Support;

/**
 * Interface Collection
 *
 * @package Valkyrja\Contracts\Support
 *
 * @author  Melech Mizrachi
 */
interface Collection
{
    /**
     * Collection constructor.
     *
     * @param array $collection
     */
    public function __construct(array $collection = []);

    /**
     * Get a single item from the collection.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return bool|mixed
     */
    public function get($key, $default = false);

    /**
     * Determine if an item is in the collection.
     *
     * @param string $key
     *
     * @return bool
     */
    public function has($key);

    /**
     * Determine if an item exists in the collection.
     *
     * @param string $key
     *
     * @return bool
     */
    public function exists($key);

    /**
     * Set a new item into the collection.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    public function set($key, $value);

    /**
     * Remove an item from the collection.
     *
     * @param string $key
     *
     * @return $this
     */
    public function remove($key);

    /**
     * Get all the items in the collection.
     *
     * @return array
     */
    public function all();

    /**
     * Set the collection.
     *
     * @param array $collection
     *
     * @return $this
     */
    public function setAll(array $collection);

    /**
     * Get all the keys in the collection.
     *
     * @return array
     */
    public function keys();

    /**
     * Get the total count of items in the collection.
     *
     * @return int
     */
    public function count();

    /**
     * Get a single item from the collection.
     *
     * @param string $key
     *
     * @return bool|mixed
     */
    public function __get($key);

    /**
     * Determine if an item is in the collection.
     *
     * @param string $key
     *
     * @return bool
     */
    public function __isset($key);

    /**
     * Set a new item into the collection.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return \Valkyrja\Support\Collection
     */
    public function __set($key, $value);

    /**
     * Remove an item from the collection.
     *
     * @param string $key
     *
     * @return \Valkyrja\Support\Collection
     */
    public function __unset($key);

    /**
     * Convert the collection to a string.
     *
     * @return string
     */
    public function __toString();
}
