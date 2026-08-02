<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Message\Constant;

final class Port
{
    public const int MIN = 1;
    public const int MAX = 65535;

    public const int HTTP  = 80;
    public const int HTTPS = 443;

    /**
     * Check if a port is valid.
     */
    public static function isValid(int $port): bool
    {
        return $port >= self::MIN && $port <= self::MAX;
    }
}
