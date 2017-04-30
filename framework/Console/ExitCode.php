<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Console;

use Valkyrja\Enum\Enum;

/**
 * Class ExitCode
 *
 * @package Valkyrja\Console
 *
 * @author  Melech Mizrachi
 */
final class ExitCode extends Enum
{
    public const SUCCESS   = 1;
    public const FAILURE   = 0;
    public const AUTO_EXIT = 255;

    protected const VALUES = [
        self::SUCCESS,
        self::FAILURE,
        self::AUTO_EXIT,
    ];
}
