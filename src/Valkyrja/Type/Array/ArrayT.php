<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Type\Array;

use JsonException;
use Override;
use Valkyrja\Type\Abstract\Type;
use Valkyrja\Type\Array\Contract\ArrayContract;
use Valkyrja\Type\Array\Factory\ArrayFactory;

/**
 * @extends Type<array<array-key, mixed>>
 */
class ArrayT extends Type implements ArrayContract
{
    /**
     * @param array<array-key, mixed> $subject The array
     */
    public function __construct(array $subject)
    {
        $this->subject = $subject;
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public static function fromValue(mixed $value): static
    {
        return new static(ArrayFactory::fromMixed($value));
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function asValue(): array
    {
        return $this->subject;
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public function asFlatValue(): string
    {
        return ArrayFactory::toString($this->subject);
    }
}
