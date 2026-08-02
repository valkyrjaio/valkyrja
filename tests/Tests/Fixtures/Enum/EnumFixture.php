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
 * Enum class to use to test enums.
 */
enum EnumFixture implements EnumContract
{
    use Enumerable;

    case spade;
    case heart;
    case diamond;
    case club;
}
