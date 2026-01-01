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

namespace Valkyrja\Type\Collection;

use Override;
use Valkyrja\Type\BuiltIn\Support\Arr;
use Valkyrja\Type\Collection\Contract\CollectionContract as Contract;

use function array_keys;
use function count;
use function in_array;

/**
 * Class Collection.
 *
 * @template K of array-key
 * @template T of string|int|float|bool|array|object|null
 *
 * @implements Contract<K, T>
 */
class Collection implements Contract
{
    /**
     * The collection of items.
     *
     * @var array<K, T>
     */
    protected array $collection = [];

    /**
     * Collection constructor.
     *
     * @param array<K, T> $collection
     */
    public function __construct(array $collection = [])
    {
        $this->setAll($collection);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setAll(array $collection): static
    {
        $this->collection = $collection;

        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function exists($value): bool
    {
        return in_array($value, $this->collection, true);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function all(): array
    {
        return $this->collection;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function keys(): array
    {
        return array_keys($this->collection);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function count(): int
    {
        return count($this->collection);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function isEmpty(): bool
    {
        return empty($this->collection);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function __get(string|int $key): string|int|float|bool|array|object|null
    {
        return $this->get($key);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function __set(string|int $key, string|int|float|bool|array|object|null $value): void
    {
        $this->set($key, $value);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function get(string|int $key, string|int|float|bool|array|object|null $default = null): string|int|float|bool|array|object|null
    {
        return $this->has($key) ? $this->collection[$key] : $default;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function has(string|int $key): bool
    {
        return isset($this->collection[$key]);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function set(string|int $key, $value): static
    {
        $this->collection[$key] = $value;

        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function __isset(string|int $key): bool
    {
        return $this->has($key);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function __unset(string|int $key): void
    {
        $this->remove($key);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function remove(string|int $key): static
    {
        if (! $this->has($key)) {
            return $this;
        }

        unset($this->collection[$key]);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return Arr::toString($this->collection);
    }
}
