<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Type\Float;

use Override;
use Valkyrja\Type\Abstract\Type;
use Valkyrja\Type\Float\Contract\FloatContract;
use Valkyrja\Type\Float\Throwable\Exception\FloatInvalidFromValueException;

use function is_array;
use function is_bool;
use function is_float;
use function is_int;
use function is_string;

/**
 * @extends Type<float>
 */
class FloatT extends Type implements FloatContract
{
    public function __construct(float $subject)
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
            is_float($value)                                   => new static($value),
            is_string($value), is_int($value), is_bool($value) => new static((float) $value),
            is_array($value)                                   => new static($value !== [] ? 1.0 : 0.0),
            default                                            => throw new FloatInvalidFromValueException('Unsupported value provided'),
        };
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function asValue(): float
    {
        return $this->subject;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function asFlatValue(): float
    {
        return $this->asValue();
    }
}
