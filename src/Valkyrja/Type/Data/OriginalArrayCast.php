<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Type\Data;

use Valkyrja\Type\Enum\CastType;

class OriginalArrayCast extends Cast
{
    /**
     * @param CastType|class-string $type The type
     */
    public function __construct(CastType|string $type)
    {
        parent::__construct($type, false, true);
    }
}
