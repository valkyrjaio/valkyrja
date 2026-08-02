<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Jwt\Data\Contract;

use Valkyrja\Jwt\Enum\Algorithm;
use Valkyrja\Jwt\Manager\Contract\JwtContract;

interface JwtConfigContract
{
    /** @var class-string<JwtContract> */
    public string $defaultJwt {
        get;
    }

    public Algorithm $algorithm {
        get;
    }
}
