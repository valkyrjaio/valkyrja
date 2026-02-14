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
 * @template K of non-empty-string|int
 * @template T of scalar|ParamCollectionContract|null
 */
interface ParamCollectionContract
{
    /**
     * Determine if a param exists.
     *
     * @param K $key The param name
     */
    public function has(string|int $key): bool;

    /**
     * Get a param.
     *
     * @param K $key The param name
     *
     * @return T|null
     */
    public function get(string|int $key): self|float|bool|int|string|null;

    /**
     * Get all the params.
     *
     * @return array<K, T>
     */
    public function getAll(): array;

    /**
     * Get only the specified params.
     *
     * @param K ...$keys The param keys
     *
     * @return array<K, T>
     */
    public function getOnly(string|int ...$keys): array;

    /**
     * Get all the params except the specified ones.
     *
     * @param K ...$keys The param names
     *
     * @return array<K, T>
     */
    public function getAllExcept(string|int ...$keys): array;

    /**
     * Get a new instance with the specified params.
     *
     * @param array<K, T> $params The params
     */
    public function with(array $params): static;

    /**
     * Get a new instance with the added params.
     *
     * @param array<K, T> $params The params
     */
    public function withAdded(array $params): static;
}
