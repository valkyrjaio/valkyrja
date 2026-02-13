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

namespace Valkyrja\Http\Message\Param\Abstract;

use InvalidArgumentException;
use Override;
use Valkyrja\Http\Message\Param\Contract\ParamCollectionContract;

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

            static::validateParam($param);

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
     * Validate a param.
     *
     * @psalm-assert T $param
     *
     * @phpstan-assert T $param
     */
    protected static function validateParam(mixed $param): void
    {
        if (! static::isValidParam($param)) {
            throw new InvalidArgumentException('Param must be scalar, null, or a ParamCollectionContract instance');
        }
    }

    /**
     * Determine if a param is valid.
     */
    protected static function isValidParam(mixed $param): bool
    {
        return is_scalar($param) || $param instanceof static || $param === null;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function hasParam(int|string $name): bool
    {
        return isset($this->params[$name]);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getParam(int|string $name): ParamCollectionContract|float|bool|int|string|null
    {
        return $this->params[$name]
            ?? null;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function onlyParams(string|int ...$names): array
    {
        return array_filter(
            $this->params,
            static fn (string|int $name): bool => in_array($name, $names, true),
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function exceptParams(string|int ...$names): array
    {
        return array_filter(
            $this->params,
            static fn (string|int $name): bool => ! in_array($name, $names, true),
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withParams(array $params): static
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
    public function withAddedParams(array $params): static
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
            static::validateParam($param);
        }
    }
}
