<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Type\Id;

use Override;
use Valkyrja\Type\Abstract\Type;
use Valkyrja\Type\Id\Contract\IntIdContract;
use Valkyrja\Type\Id\Throwable\Exception\IdInvalidFromValueException;

use function is_bool;
use function is_float;
use function is_int;
use function is_string;

/**
 * @extends Type<int>
 */
class IntId extends Type implements IntIdContract
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
            default                                              => throw new IdInvalidFromValueException('Unsupported value provided'),
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
