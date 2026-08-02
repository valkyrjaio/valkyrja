<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Enum;

use Valkyrja\Type\Enum\Contract\EnumContract;
use Valkyrja\Type\Enum\Trait\Enumerable;

/**
 * Model class to use to test int BackedEnum.
 */
enum IntEnum: int implements EnumContract
{
    use Enumerable;

    case first  = 1;
    case second = 2;
}
