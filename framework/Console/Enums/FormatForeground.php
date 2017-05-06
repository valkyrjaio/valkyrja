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
 * Enum FormatForeground
 *
 * @package Valkyrja\Console\Enums
 *
 * @author  Melech Mizrachi
 */
final class FormatForeground extends Enum
{
    public const BLACK         = '30';
    public const RED           = '31';
    public const GREEN         = '32';
    public const YELLOW        = '33';
    public const BLUE          = '34';
    public const MAGENTA       = '35';
    public const CYAN          = '36';
    public const WHITE         = '37';
    public const DARK_GRAY     = '90';
    public const LIGHT_RED     = '91';
    public const LIGHT_GREEN   = '92';
    public const LIGHT_YELLOW  = '93';
    public const LIGHT_BLUE    = '94';
    public const LIGHT_MAGENTA = '95';
    public const LIGHT_CYAN    = '96';
    public const LIGHT_WHITE   = '97';
    public const DEFAULT       = '39';

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
