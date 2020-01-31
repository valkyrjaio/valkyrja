<?php

declare(strict_types = 1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Console\Enums;

use Valkyrja\Enum\Enum;

/**
 * Enum OutputStyle.
 *
 * @author Melech Mizrachi
 */
final class OutputStyle extends Enum
{
    public const NORMAL = 'NORMAL';
    public const PLAIN  = 'PLAIN';
    public const RAW    = 'RAW';

    protected const VALUES = [
        self::NORMAL => self::NORMAL,
        self::PLAIN  => self::PLAIN,
        self::RAW    => self::RAW,
    ];
}
