<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\HttpMessage;

use Valkyrja\Enum\Enum;

/**
 * Enum DefaultPort.
 *
 * @author Melech Mizrachi
 */
final class DefaultPort extends Enum
{
    public const HTTP  = 80;
    public const HTTPS = 443;

    protected const VALUES = [
        self::HTTP  => self::HTTP,
        self::HTTPS => self::HTTPS,
    ];
}
