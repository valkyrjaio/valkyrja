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
use Valkyrja\Type\Id\Contract\IdContract;
use Valkyrja\Type\Id\Throwable\Exception\IdInvalidFromValueException;

use function is_float;
use function is_int;
use function is_string;

/**
 * @extends Type<string|int>
 */
class Id extends Type implements IdContract
{
    public function __construct(string|int $subject)
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
            is_string($value), is_int($value) => new static($value),
            is_float($value)                  => new static((string) $value),
            default                           => throw new IdInvalidFromValueException('Unsupported value provided'),
        };
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function asValue(): string|int
    {
        return $this->subject;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function asFlatValue(): string|int
    {
        return $this->asValue();
    }
}
