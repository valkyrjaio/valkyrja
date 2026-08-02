<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Interaction\Argument;

use Override;
use Valkyrja\Cli\Interaction\Argument\Contract\ArgumentContract;

class Argument implements ArgumentContract
{
    /**
     * @param non-empty-string $value The value
     */
    public function __construct(
        protected string $value,
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withValue(string $value): static
    {
        $new = clone $this;

        $new->value = $value;

        return $new;
    }
}
