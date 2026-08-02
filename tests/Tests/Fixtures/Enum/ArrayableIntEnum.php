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

use Valkyrja\Type\Enum\Contract\ArrayableContract;
use Valkyrja\Type\Enum\Contract\JsonSerializableContract;
use Valkyrja\Type\Enum\Trait\Arrayable;
use Valkyrja\Type\Enum\Trait\JsonSerializable;

/**
 * Enum class to use to test Arrayable Int Backed Enum.
 */
enum ArrayableIntEnum: int implements ArrayableContract, JsonSerializableContract
{
    use Arrayable;
    use JsonSerializable;

    case first  = 1;
    case second = 2;
}
