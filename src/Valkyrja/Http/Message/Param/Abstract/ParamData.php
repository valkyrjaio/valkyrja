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
use Valkyrja\Http\Message\Param\Contract\ParamDataContract;

use function in_array;
use function is_array;
use function is_scalar;

use const ARRAY_FILTER_USE_KEY;

/**
 * @template T of scalar|self
 *
 * @implements ParamDataContract<T>
 */
abstract class ParamData implements ParamDataContract
{
    /** @var array<array-key, T> */
    protected array $params = [];

    /**
     * The position during iteration.
     *
     * @var int
     */
    protected int $position = 0;

    /**
     * @param T ...$params The params
     */
    public function __construct(ParamDataContract|float|bool|int|string ...$params)
    {
        $this->validateParams($params);

        $this->params = $params;
    }

    /**
     * Create a new instance from an array.
     *
     * @param array<array-key, mixed> $data The data to create from
     */
    public function fromArray(array $data): static
    {
        $params = [];

        /**
         * @var array-key $name
         * @var mixed     $param
         */
        foreach ($data as $name => $param) {
            if (is_array($param)) {
                $param = static::fromArray($param);
            }

            $this->validateParam($param);

            $params[$name] = $param;
        }

        /**
         * @var array<array-key, scalar|self<scalar|self>> $params
         *
         * @phpstan-ignore-next-line
         */
        return new static(...$params);
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
    public function getParam(int|string $name): ParamDataContract|float|bool|int|string|null
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
     * @param T ...$params The params
     */
    #[Override]
    public function withAddedParams(ParamDataContract|float|bool|int|string ...$params): static
    {
        $this->validateParams($params);

        $new = clone $this;

        $new->params = array_merge($new->params, $params);

        return $new;
    }

    /**
     * Validate params.
     *
     * @param array<array-key, mixed> $params The params to validate
     *
     * @psalm-assert array<array-key, T> $params
     *
     * @phpstan-assert array<array-key, T> $params
     */
    protected function validateParams(array $params): void
    {
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
            throw new InvalidArgumentException('Param must be scalar');
        }
    }

    /**
     * Determine if a param is valid.
     */
    protected function isValidParam(mixed $param): bool
    {
        return is_scalar($param) || $param instanceof static;
    }
}
