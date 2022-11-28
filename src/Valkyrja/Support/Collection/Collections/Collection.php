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

namespace Valkyrja\Support\Collection\Collections;

use Valkyrja\Support\Collection\Collection as Contract;
use Valkyrja\Support\Type\Arr;

use function array_keys;
use function count;
use function in_array;

/**
 * Class Collection.
 *
 * @author   Melech Mizrachi
 * @template T
 */
class Collection implements Contract
{
    /**
     * The collection of items.
     *
     * @var array<int, T>
     */
    protected array $collection = [];

    /**
     * Collection constructor.
     *
     * @param array<int, T> $collection
     */
    public function __construct(array $collection = [])
    {
        $this->setAll($collection);
    }

    /**
     * @inheritDoc
     */
    public function setAll(array $collection): self
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
    public function __get(string $key) // : mixed
    {
        return $this->get($key);
    }

    /**
     * @inheritDoc
     */
    public function __set(string $key, $value)
    {
        return $this->set($key, $value);
    }

    /**
     * @inheritDoc
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->has($key) ? $this->collection[$key] : $default;
    }

    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        return isset($this->collection[$key]);
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, $value): self
    {
        $this->collection[$key] = $value;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function __isset(string $key): bool
    {
        return $this->has($key);
    }

    /**
     * @inheritDoc
     */
    public function __unset(string $key): void
    {
        $this->remove($key);
    }

    /**
     * @inheritDoc
     */
    public function remove(string $key): self
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
