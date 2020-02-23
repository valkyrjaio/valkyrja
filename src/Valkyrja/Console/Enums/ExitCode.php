<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Console\Enums;

use Valkyrja\Enum\Enums\Enum;

/**
 * Enum ExitCode.
 *
 * @author Melech Mizrachi
 */
final class ExitCode extends Enum
{
    public const SUCCESS   = 0;
    public const FAILURE   = 1;
    public const AUTO_EXIT = 255;

    protected static ?array $VALUES = [
        self::SUCCESS   => self::SUCCESS,
        self::FAILURE   => self::FAILURE,
        self::AUTO_EXIT => self::AUTO_EXIT,
    ];
}
