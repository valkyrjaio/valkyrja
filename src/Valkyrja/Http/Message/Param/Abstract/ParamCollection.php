<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Message\Param\Abstract;

use InvalidArgumentException;
use Override;
use Valkyrja\Http\Message\Param\Contract\ParamCollectionContract;

use function array_filter;
use function in_array;
use function is_array;
use function is_scalar;

use const ARRAY_FILTER_USE_KEY;

/**
 * @template K of non-empty-string|int
 * @template T of scalar|ParamCollectionContract|null
 *
 * @implements ParamCollectionContract<K, T>
 */
abstract class ParamCollection implements ParamCollectionContract
{
    /** @var array<K, T> */
    protected array $params = [];

    /**
     * @param array<K, T> $params The params
     */
    public function __construct(array $params = [])
    {
        $this->validateParams($params);

        $this->params = $params;
    }

    /**
     * Create a new instance from an array.
     *
     * @param array<array-key, mixed> $data The data to create from
     */
    public static function fromArray(array $data): static
    {
        $params = [];

        /**
         * @var array-key                                           $name
         * @var scalar|object|array<array-key, mixed>|resource|null $param
         */
        foreach ($data as $name => $param) {
            if (is_array($param)) {
                $param = static::fromArray($param);
            }

            $params[$name] = $param;
        }

        /**
         * @var array<K, scalar|ParamCollectionContract> $params
         *
         * @phpstan-ignore-next-line
         */
        return new static($params);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function has(int|string $key): bool
    {
        return isset($this->params[$key]);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function get(int|string $key): ParamCollectionContract|float|bool|int|string|null
    {
        return $this->params[$key]
            ?? null;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getAll(): array
    {
        return $this->params;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getOnly(string|int ...$keys): array
    {
        return array_filter(
            $this->params,
            static fn (string|int $name): bool => in_array($name, $keys, true),
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getAllExcept(string|int ...$keys): array
    {
        return array_filter(
            $this->params,
            static fn (string|int $name): bool => ! in_array($name, $keys, true),
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function with(array $params): static
    {
        $this->validateParams($params);

        $new = clone $this;

        $new->params = $params;

        return $new;
    }

    /**
     * @inheritDoc
     *
     * @param array<K, T> $params The params
     */
    #[Override]
    public function withAdded(array $params): static
    {
        $this->validateParams($params);

        $new = clone $this;

        // Do not use array_merge as it would rewrite int keys when mixed with string keys
        foreach ($params as $name => $param) {
            $new->params[$name] = $param;
        }

        return $new;
    }

    /**
     * Validate params.
     *
     * @param array<K, mixed> $params The params to validate
     *
     * @psalm-assert array<K, T> $params
     *
     * @phpstan-assert array<K, T> $params
     */
    protected function validateParams(array $params): void
    {
        /**
         * @var scalar|object|array<array-key, mixed>|resource|null $param
         */
        foreach ($params as $param) {
            $this->validateParam($param);
        }
    }

    /**
     * Validate a param.
     *
     * @psalm-assert T $param
     *
     * @phpstan-assert T $param
     */
    protected function validateParam(mixed $param): void
    {
        if (! $this->isValidParam($param)) {
            throw new InvalidArgumentException('Param must be scalar, null, or a ParamCollectionContract instance');
        }
    }

    /**
     * Determine if a param is valid.
     */
    protected function isValidParam(mixed $param): bool
    {
        return is_scalar($param) || $param instanceof static || $param === null;
    }
}
