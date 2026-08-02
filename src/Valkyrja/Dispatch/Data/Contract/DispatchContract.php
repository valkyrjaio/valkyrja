<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Dispatch\Data\Contract;

use JsonSerializable;
use Override;
use Stringable;

interface DispatchContract extends JsonSerializable, Stringable
{
    /**
     * Get the Dispatch as a string.
     *
     * @return non-empty-string
     */
    public function __toString(): string;

    /**
     * Serialize properties for json_encode.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function jsonSerialize(): array;
}
