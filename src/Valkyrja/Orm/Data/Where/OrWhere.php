<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Orm\Data\Where;

use Valkyrja\Orm\Data\Value;
use Valkyrja\Orm\Data\Where;
use Valkyrja\Orm\Enum\Comparison;
use Valkyrja\Orm\Enum\WhereType;

readonly class OrWhere extends Where
{
    public function __construct(
        Value $value,
        Comparison $comparison = Comparison::EQUALS,
    ) {
        parent::__construct(
            value: $value,
            comparison: $comparison,
            type: WhereType::OR,
        );
    }
}
