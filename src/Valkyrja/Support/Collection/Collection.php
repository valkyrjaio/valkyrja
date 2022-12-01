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

namespace Valkyrja\Support\Collection;

use JsonException;

/**
 * Interface Collection.
 *
 * @author   Melech Mizrachi
 * @template T
 */
interface Collection
{
    /**
     * Set the collection.
     *
     * @param array $collection The collection
     *
     * @return self
     */
    public function setAll(array $collection): self;

    /**
     * Determine if a value exists in the collection.
     *
     * @param mixed $value The value
     *
     * @return bool
     */
    public function exists(mixed $value): bool;

    /**
     * Get all the items in the collection.
     *
     * @return array<string|int, T>
     */
    public function all(): array;

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
     * @param string|int $key The key
     *
     * @return mixed
     */
    public function __get(string|int $key);

    /**
     * Set a new item into the collection.
     *
     * @param string|int $key   The key
     * @param mixed      $value The value
     *
     * @return mixed
     */
    public function __set(string|int $key, mixed $value);

    /**
     * Get a single item from the collection.
     *
     * @param string|int $key     The key to get
     * @param mixed      $default [optional] The default value
     *
     * @return mixed
     */
    public function get(string|int $key, mixed $default = null): mixed;

    /**
     * Determine if an item is in the collection.
     *
     * @param string|int $key The key
     *
     * @return bool
     */
    public function has(string|int $key): bool;

    /**
     * Set a new item into the collection.
     *
     * @param string|int $key   The key
     * @param mixed      $value The value
     *
     * @return self
     */
    public function set(string|int $key, mixed $value): self;

    /**
     * Determine if an item is in the collection.
     *
     * @param string|int $key The key
     *
     * @return bool
     */
    public function __isset(string|int $key): bool;

    /**
     * Remove an item from the collection.
     *
     * @param string|int $key The key
     *
     * @return void
     */
    public function __unset(string|int $key): void;

    /**
     * Remove an item from the collection.
     *
     * @param string|int $key The key
     *
     * @return self
     */
    public function remove(string|int $key): self;

    /**
     * Convert the collection to a string.
     *
     * @throws JsonException
     *
     * @return string
     */
    public function __toString(): string;
}
