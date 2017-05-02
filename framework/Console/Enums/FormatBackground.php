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
 * Enum FormatBackground
 *
 * @package Valkyrja\Console\Enums
 *
 * @author  Melech Mizrachi
 */
final class FormatBackground extends Enum
{
    public const BLACK   = '40';
    public const RED     = '41';
    public const GREEN   = '42';
    public const YELLOW  = '43';
    public const BLUE    = '44';
    public const MAGENTA = '45';
    public const CYAN    = '46';
    public const WHITE   = '47';
    public const DEFAULT = '49';

    protected const VALUES = [
        self::BLACK,
        self::RED,
        self::GREEN,
        self::YELLOW,
        self::BLUE,
        self::MAGENTA,
        self::CYAN,
        self::WHITE,
        self::DEFAULT,
    ];
}
