<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Type\Enum\Contract;

use JsonSerializable;
use Override;
use UnitEnum;

interface JsonSerializableContract extends JsonSerializable, UnitEnum
{
    /**
     * Json serialize.
     */
    #[Override]
    public function jsonSerialize(): string|int;
}
