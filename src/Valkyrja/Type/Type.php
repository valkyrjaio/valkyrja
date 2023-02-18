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

namespace Valkyrja\Type;

use Closure;

/**
 * Interface Type.
 *
 * @author Melech Mizrachi
 */
interface Type
{
    /**
     * Get the value.
     */
    public function get(): mixed;

    /**
     * Modify the subject and return a new instance to maintain immutability.
     */
    public function modify(Closure $closure): static;

    /**
     * Get the type as an array.
     */
    public function asArray(): array;

    /**
     * Get the type as a bool.
     */
    public function asBool(): bool;

    /**
     * Get the type as an int.
     */
    public function asInt(): int;

    /**
     * Get the type as a string.
     */
    public function asString(): string;

    /**
     * Get the type as a string.
     */
    public function __toString(): string;
}
