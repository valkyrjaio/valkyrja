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
    public function get(string $key, $default = false); // : mixed;

    /**
     * Determine if an item is in the collection.
     *
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * Determine if an item exists in the collection.
     *
     * @param string $key
     *
     * @return bool
     */
    public function exists(string $key): bool;

    /**
     * Set a new item into the collection.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return Collection
     */
    public function set(string $key, $value): Collection;

    /**
     * Remove an item from the collection.
     *
     * @param string $key
     *
     * @return Collection
     */
    public function remove(string $key): Collection;

    /**
     * Get all the items in the collection.
     *
     * @return array
     */
    public function all(): array;

    /**
     * Set the collection.
     *
     * @param array $collection
     *
     * @return Collection
     */
    public function setAll(array $collection): Collection;

    /**
     * Get all the keys in the collection.
     *
     * @return array
     */
    public function keys(): array;

    /**
     * Get the total count of items in the collection.
     *
     * @return int
     */
    public function count(): int;

    /**
     * Determine if the collection is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool;

    /**
     * Get a single item from the collection.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get(string $key); // : mixed;

    /**
     * Determine if an item is in the collection.
     *
     * @param string $key
     *
     * @return bool
     */
    public function __isset(string $key): bool;

    /**
     * Set a new item into the collection.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return Collection
     */
    public function __set(string $key, $value): Collection;

    /**
     * Remove an item from the collection.
     *
     * @param string $key
     *
     * @return Collection
     */
    public function __unset(string $key): Collection;

    /**
     * Convert the collection to a string.
     *
     * @return string
     */
    public function __toString(): string;
}
