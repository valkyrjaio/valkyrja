<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Type\Null;

use Override;
use Valkyrja\Type\Abstract\Type;
use Valkyrja\Type\Null\Contract\NullContract;

/**
 * @extends Type<null>
 */
class NullT extends Type implements NullContract
{
    public function __construct()
    {
        $this->subject = null;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function fromValue(mixed $value): static
    {
        return new static();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function asValue(): mixed
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function asFlatValue(): string|int|float|bool|null
    {
        return null;
    }
}
