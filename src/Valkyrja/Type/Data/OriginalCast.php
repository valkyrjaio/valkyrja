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

use Valkyrja\Type\Contract\TypeContract;
use Valkyrja\Type\Enum\CastType;

class OriginalCast extends Cast
{
    /**
     * @param CastType|class-string<TypeContract> $type The type
     */
    public function __construct(CastType|string $type, public bool $isArray = false)
    {
        parent::__construct($type, false, $isArray);
    }
}
