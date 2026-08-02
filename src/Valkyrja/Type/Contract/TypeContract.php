<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Type\Contract;

use JsonSerializable;

/**
 * @template T of scalar|object|array<array-key, mixed>|null
 */
interface TypeContract extends JsonSerializable
{
    /**
     * Get a new Type given a value.
     *
     * @return static<T>
     */
    public static function fromValue(mixed $value): static;

    /**
     * Get the value.
     *
     * @return T
     */
    public function asValue(): mixed;

    /**
     * Get the flattened value.
     */
    public function asFlatValue(): string|int|float|bool|null;

    /**
     * Modify the subject and return a new instance to maintain immutability.
     *
     * @param callable(T): T $closure The closure
     *
     * @return static<T>
     */
    public function modify(callable $closure): static;
}
