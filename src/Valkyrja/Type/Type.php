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
use JsonSerializable;

/**
 * Interface Type.
 *
 * @author Melech Mizrachi
 *
 * @template T
 */
interface Type extends JsonSerializable
{
    /**
     * Get a new Type given a value.
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
     * @param Closure(T): T $closure The closure
     */
    public function modify(Closure $closure): static;
}
