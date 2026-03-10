<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
