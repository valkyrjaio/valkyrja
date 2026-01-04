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
 * @template K of array-key
 * @template T of string|int|float|bool|array|object|null
 */
interface CollectionContract extends Stringable
{
    /**
     * Set the collection.
     *
     * @param array<K, T> $collection The collection
     */
    public function setAll(array $collection): static;

    /**
     * Determine if a value exists in the collection.
     *
     * @param mixed $value The value
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
     */
    public function count(): int;

    /**
     * Determine if the collection is empty.
     */
    public function isEmpty(): bool;

    /**
     * Get a single item from the collection.
     *
     * @param K $key The key
     *
     * @return T
     */
    public function __get(string|int $key): string|int|float|bool|array|object|null;

    /**
     * Set a new item into the collection.
     *
     * @param K $key   The key
     * @param T $value The value
     */
    public function __set(string|int $key, string|int|float|bool|array|object|null $value): void;

    /**
     * Get a single item from the collection.
     *
     * @param K $key     The key to get
     * @param T $default [optional] The default value
     *
     * @return T
     */
    public function get(string|int $key, string|int|float|bool|array|object|null $default = null): string|int|float|bool|array|object|null;

    /**
     * Determine if an item is in the collection.
     *
     * @param K $key The key
     */
    public function has(string|int $key): bool;

    /**
     * Set a new item into the collection.
     *
     * @param K $key   The key
     * @param T $value The value
     */
    public function set(string|int $key, string|int|float|bool|array|object|null $value): static;

    /**
     * Determine if an item is in the collection.
     *
     * @param K $key The key
     */
    public function __isset(string|int $key): bool;

    /**
     * Remove an item from the collection.
     *
     * @param K $key The key
     */
    public function __unset(string|int $key): void;

    /**
     * Remove an item from the collection.
     *
     * @param K $key The key
     */
    public function remove(string|int $key): static;

    /**
     * Convert the collection to a string.
     *
     * @throws JsonException
     */
    public function __toString(): string;
}
