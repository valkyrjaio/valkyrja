<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Type\Abstract;

use Override;
use Valkyrja\Type\Contract\TypeContract;

/**
 * @template T of scalar|object|array<array-key, mixed>|null
 *
 * @implements TypeContract<T>
 */
abstract class Type implements TypeContract
{
    /**
     * @var T
     */
    protected mixed $subject;

    /**
     * @inheritDoc
     */
    #[Override]
    abstract public static function fromValue(mixed $value): static;

    /**
     * @inheritDoc
     */
    #[Override]
    public function asValue(): mixed
    {
        return $this->subject;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function modify(callable $closure): static
    {
        return static::fromValue($closure($this->subject));
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function jsonSerialize(): mixed
    {
        return $this->asValue();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    abstract public function asFlatValue(): string|int|float|bool|null;
}
