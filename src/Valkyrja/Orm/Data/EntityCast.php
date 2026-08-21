<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Orm\Data;

use Valkyrja\Orm\Entity\Contract\EntityContract;
use Valkyrja\Type\Data\Cast;
use Valkyrja\Type\Enum\CastType;

class EntityCast extends Cast
{
    /**
     * @param CastType|class-string<EntityContract> $type The type
     */
    public function __construct(
        CastType|string $type,
        public string|null $column = null,
        bool $convert = true,
        bool $isArray = false
    ) {
        parent::__construct($type, $convert, $isArray);
    }
}
