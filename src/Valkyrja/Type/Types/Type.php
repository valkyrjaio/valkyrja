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

namespace Valkyrja\Type\Types;

use Closure;
use Valkyrja\Type\Type as Contract;

/**
 * Class Type.
 *
 * @author Melech Mizrachi
 *
 * @implements Contract<mixed>
 * @template T
 */
abstract class Type implements Contract
{
    /**
     * @param T $subject
     */
    public function __construct(
        protected mixed $subject,
    ) {
    }

    /**
     * @inheritDoc
     */
    public static function fromValue(mixed $value): static
    {
        return new static($value);
    }

    /**
     * @inheritDoc
     *
     * @return T
     */
    public function asValue(): mixed
    {
        return $this->subject;
    }

    /**
     * @inheritDoc
     */
    abstract public function asFlatValue(): string|int|float|bool|null;

    /**
     * @inheritDoc
     */
    public function modify(Closure $closure): static
    {
        return static::fromValue($closure($this->subject));
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        return $this->asValue();
    }
}
