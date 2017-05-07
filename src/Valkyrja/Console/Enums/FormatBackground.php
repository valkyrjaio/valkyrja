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
    public const BLACK         = '40';
    public const RED           = '41';
    public const GREEN         = '42';
    public const YELLOW        = '43';
    public const BLUE          = '44';
    public const MAGENTA       = '45';
    public const CYAN          = '46';
    public const WHITE         = '47';
    public const DARK_GRAY     = '100';
    public const LIGHT_RED     = '101';
    public const LIGHT_GREEN   = '102';
    public const LIGHT_YELLOW  = '103';
    public const LIGHT_BLUE    = '104';
    public const LIGHT_MAGENTA = '105';
    public const LIGHT_CYAN    = '106';
    public const LIGHT_WHITE   = '107';
    public const DEFAULT       = '49';

    protected const VALUES = [
        self::BLACK,
        self::RED,
        self::GREEN,
        self::YELLOW,
        self::BLUE,
        self::MAGENTA,
        self::CYAN,
        self::WHITE,
        self::DARK_GRAY,
        self::LIGHT_RED,
        self::LIGHT_GREEN,
        self::LIGHT_YELLOW,
        self::LIGHT_BLUE,
        self::LIGHT_MAGENTA,
        self::LIGHT_CYAN,
        self::LIGHT_WHITE,
        self::DEFAULT,
    ];
}
