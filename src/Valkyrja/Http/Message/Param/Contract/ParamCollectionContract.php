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

namespace Valkyrja\Http\Message\Param\Contract;

/**
 * @template T of scalar|self
 */
interface ParamCollectionContract
{
    /**
     * Determine if a param exists.
     *
     * @param non-empty-string|int $name The param name
     */
    public function hasParam(string|int $name): bool;

    /**
     * Get a param.
     *
     * @param non-empty-string|int $name The param name
     *
     * @return T|null
     */
    public function getParam(string|int $name): self|float|bool|int|string|null;

    /**
     * Get all the params.
     *
     * @return array<array-key, T>
     */
    public function getParams(): array;

    /**
     * Get only the specified params.
     *
     * @param non-empty-string|int ...$names The param names
     *
     * @return array<array-key, T>
     */
    public function onlyParams(string|int ...$names): array;

    /**
     * Get all the params except the specified ones.
     *
     * @param non-empty-string|int ...$names The param names
     *
     * @return array<array-key, T>
     */
    public function exceptParams(string|int ...$names): array;

    /**
     * Get a new instance with the specified params.
     *
     * @param array<array-key, T> $params The params
     */
    public function withParams(array $params): static;

    /**
     * Get a new instance with the added params.
     *
     * @param T ...$params The params
     */
    public function withAddedParams(self|float|bool|int|string ...$params): static;
}
