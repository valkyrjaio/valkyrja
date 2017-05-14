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
 * Interface Collection.
 *
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
     * @param string $key     The key to get
     * @param mixed  $default [optional] The default value
     *
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * Determine if an item is in the collection.
     *
     * @param string $key The key
     *
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * Determine if a value exists in the collection.
     *
     * @param mixed $value The value
     *
     * @return bool
     */
    public function exists($value): bool;

    /**
     * Set a new item into the collection.
     *
     * @param string $key   The key
     * @param mixed  $value The value
     *
     * @return Collection
     */
    public function set(string $key, $value): self;

    /**
     * Remove an item from the collection.
     *
     * @param string $key The key
     *
     * @return Collection
     */
    public function remove(string $key): self;

    /**
     * Get all the items in the collection.
     *
     * @return array
     */
    public function all(): array;

    /**
     * Set the collection.
     *
     * @param array $collection The collection
     *
     * @return Collection
     */
    public function setAll(array $collection): self;

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
     * @param string $key The key
     *
     * @return mixed
     */
    public function __get(string $key);

    /**
     * Determine if an item is in the collection.
     *
     * @param string $key The key
     *
     * @return bool
     */
    public function __isset(string $key): bool;

    /**
     * Set a new item into the collection.
     *
     * @param string $key   The key
     * @param mixed  $value The value
     *
     * @return mixed
     */
    public function __set(string $key, $value);

    /**
     * Remove an item from the collection.
     *
     * @param string $key The key
     *
     * @return void
     */
    public function __unset(string $key): void;

    /**
     * Convert the collection to a string.
     *
     * @return string
     */
    public function __toString(): string;
}
