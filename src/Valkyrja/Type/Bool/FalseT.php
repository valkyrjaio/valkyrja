<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Type\Bool;

use Override;
use Valkyrja\Type\Abstract\Type;
use Valkyrja\Type\Bool\Contract\FalseContract;

/**
 * @extends Type<false>
 */
class FalseT extends Type implements FalseContract
{
    public function __construct()
    {
        $this->subject = false;
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
    public function asValue(): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function asFlatValue(): bool
    {
        return false;
    }
}
