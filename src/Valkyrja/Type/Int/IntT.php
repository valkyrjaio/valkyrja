<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Type\Int;

use Override;
use Valkyrja\Type\Abstract\Type;
use Valkyrja\Type\Int\Contract\IntContract;
use Valkyrja\Type\Int\Throwable\Exception\IntInvalidFromValueException;

use function is_array;
use function is_bool;
use function is_float;
use function is_int;
use function is_string;

/**
 * @extends Type<int>
 */
class IntT extends Type implements IntContract
{
    public function __construct(int $subject)
    {
        $this->subject = $subject;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function fromValue(mixed $value): static
    {
        return match (true) {
            is_int($value)                                       => new static($value),
            is_string($value), is_float($value), is_bool($value) => new static((int) $value),
            is_array($value)                                     => new static($value !== [] ? 1 : 0),
            default                                              => throw new IntInvalidFromValueException('Unsupported value provided'),
        };
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function asValue(): int
    {
        return $this->subject;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function asFlatValue(): int
    {
        return $this->asValue();
    }
}
