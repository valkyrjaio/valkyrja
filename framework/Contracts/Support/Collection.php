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
     * @return mixed
     */
    public function get($key, $default = false); // : mixed;

    /**
     * Determine if an item is in the collection.
     *
     * @param string $key
     *
     * @return bool
     */
    public function has($key) : bool;

    /**
     * Determine if an item exists in the collection.
     *
     * @param string $key
     *
     * @return bool
     */
    public function exists($key) : bool;

    /**
     * Set a new item into the collection.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return Collection
     */
    public function set($key, $value) : Collection;

    /**
     * Remove an item from the collection.
     *
     * @param string $key
     *
     * @return Collection
     */
    public function remove($key) : Collection;

    /**
     * Get all the items in the collection.
     *
     * @return array
     */
    public function all() : array;

    /**
     * Set the collection.
     *
     * @param array $collection
     *
     * @return Collection
     */
    public function setAll(array $collection) : Collection;

    /**
     * Get all the keys in the collection.
     *
     * @return array
     */
    public function keys() : array;

    /**
     * Get the total count of items in the collection.
     *
     * @return int
     */
    public function count() : int;

    /**
     * Get a single item from the collection.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key); // : mixed;

    /**
     * Determine if an item is in the collection.
     *
     * @param string $key
     *
     * @return bool
     */
    public function __isset($key) : bool;

    /**
     * Set a new item into the collection.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return Collection
     */
    public function __set($key, $value) : Collection;

    /**
     * Remove an item from the collection.
     *
     * @param string $key
     *
     * @return Collection
     */
    public function __unset($key) : Collection;

    /**
     * Convert the collection to a string.
     *
     * @return string
     */
    public function __toString() : string;
}
