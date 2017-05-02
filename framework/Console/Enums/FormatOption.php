<?php

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
 * Enum FormatOption
 *
 * @package Valkyrja\Console\Enums
 *
 * @author  Melech Mizrachi
 */
final class FormatOption extends Enum
{
    public const BOLD       = '1';
    public const UNDERSCORE = '4';
    public const BLINK      = '5';
    public const REVERSE    = '7';
    public const CONCEAL    = '8';

    protected const VALUES = [
        self::BOLD,
        self::UNDERSCORE,
        self::BLINK,
        self::REVERSE,
        self::CONCEAL,
    ];
}
