<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Interaction\Argument\Contract;

interface ArgumentContract
{
    /**
     * Get the value.
     *
     * @return non-empty-string
     */
    public function getValue(): string;

    /**
     * Create a new argument with the specified value.
     *
     * @param non-empty-string $value The value
     */
    public function withValue(string $value): static;
}
