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
use Valkyrja\Type\Bool\Contract\BoolContract;

/**
 * @extends Type<bool>
 */
class BoolT extends Type implements BoolContract
{
    public function __construct(bool $subject)
    {
        $this->subject = $subject;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function fromValue(mixed $value): static
    {
        return new static((bool) $value);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function asValue(): bool
    {
        return $this->subject;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function asFlatValue(): bool
    {
        return $this->asValue();
    }
}
