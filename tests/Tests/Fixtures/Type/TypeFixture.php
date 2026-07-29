<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Type;

use Override;
use Valkyrja\Type\Abstract\Type;

/**
 * Type class to use to test abstract type.
 */
final class TypeFixture extends Type
{
    public function __construct(mixed $subject)
    {
        $this->subject = $subject;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function fromValue(mixed $value): static
    {
        return new static($value);
    }

    #[Override]
    public function asFlatValue(): string|int|float|bool|null
    {
        return $this->asValue();
    }
}
