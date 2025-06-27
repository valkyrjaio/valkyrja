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

namespace Valkyrja\Type\Collection\Contract;

use JsonException;
use Stringable;

/**
 * Interface Collection.
 *
 * @author   Melech Mizrachi
 *
 * @template K of array-key
 * @template T of string|int|float|bool|array|object
 */
interface Collection extends Stringable
{
    /**
     * Set the collection.
     *
     * @param array<K, T> $collection The collection
     *
     * @return static
     */
    public function setAll(array $collection): static;

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
     * @return array<K, T>
     */
    public function all(): array;

    /**
     * Get all the keys in the collection.
     *
     * @return K[]
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
     * @param K $key The key
     *
     * @return T
     */
    public function __get(string|int $key): string|int|float|bool|array|object;

    /**
     * Set a new item into the collection.
     *
     * @param K $key   The key
     * @param T $value The value
     *
     * @return void
     */
    public function __set(string|int $key, string|int|float|bool|array|object $value): void;

    /**
     * Get a single item from the collection.
     *
     * @param K $key     The key to get
     * @param T $default [optional] The default value
     *
     * @return T
     */
    public function get(string|int $key, string|int|float|bool|array|object $default = null): string|int|float|bool|array|object;

    /**
     * Determine if an item is in the collection.
     *
     * @param K $key The key
     *
     * @return bool
     */
    public function has(string|int $key): bool;

    /**
     * Set a new item into the collection.
     *
     * @param K $key   The key
     * @param T $value The value
     *
     * @return static
     */
    public function set(string|int $key, string|int|float|bool|array|object $value): static;

    /**
     * Determine if an item is in the collection.
     *
     * @param K $key The key
     *
     * @return bool
     */
    public function __isset(string|int $key): bool;

    /**
     * Remove an item from the collection.
     *
     * @param K $key The key
     *
     * @return void
     */
    public function __unset(string|int $key): void;

    /**
     * Remove an item from the collection.
     *
     * @param K $key The key
     *
     * @return static
     */
    public function remove(string|int $key): static;

    /**
     * Convert the collection to a string.
     *
     * @throws JsonException
     *
     * @return string
     */
    public function __toString(): string;
}
