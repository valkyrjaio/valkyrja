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

use Valkyrja\Type\BuiltIn\Support\Arr;
use Valkyrja\Type\Collection\Contract\Collection as Contract;

use function array_keys;
use function count;
use function in_array;

/**
 * Class Collection.
 *
 * @author   Melech Mizrachi
 *
 * @template T
 *
 * @implements Contract<T>
 */
class Collection implements Contract
{
    /**
     * The collection of items.
     *
     * @var array<string|int, T>
     */
    protected array $collection = [];

    /**
     * Collection constructor.
     *
     * @param array<string|int, T> $collection
     */
    public function __construct(array $collection = [])
    {
        $this->setAll($collection);
    }

    /**
     * @inheritDoc
     */
    public function setAll(array $collection): static
    {
        $this->collection = $collection;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function exists($value): bool
    {
        return in_array($value, $this->collection, true);
    }

    /**
     * @inheritDoc
     */
    public function all(): array
    {
        return $this->collection;
    }

    /**
     * @inheritDoc
     */
    public function keys(): array
    {
        return array_keys($this->collection);
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return count($this->collection);
    }

    /**
     * @inheritDoc
     */
    public function isEmpty(): bool
    {
        return empty($this->collection);
    }

    /**
     * @inheritDoc
     */
    public function __get(string|int $key) // : mixed
    {
        return $this->get($key);
    }

    /**
     * @inheritDoc
     */
    public function __set(string|int $key, $value)
    {
        return $this->set($key, $value);
    }

    /**
     * @inheritDoc
     */
    public function get(string|int $key, mixed $default = null): mixed
    {
        return $this->has($key) ? $this->collection[$key] : $default;
    }

    /**
     * @inheritDoc
     */
    public function has(string|int $key): bool
    {
        return isset($this->collection[$key]);
    }

    /**
     * @inheritDoc
     */
    public function set(string|int $key, $value): static
    {
        $this->collection[$key] = $value;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function __isset(string|int $key): bool
    {
        return $this->has($key);
    }

    /**
     * @inheritDoc
     */
    public function __unset(string|int $key): void
    {
        $this->remove($key);
    }

    /**
     * @inheritDoc
     */
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
