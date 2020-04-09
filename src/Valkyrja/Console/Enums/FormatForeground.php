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

namespace Valkyrja\Console\Enums;

use Valkyrja\Support\Enum\Enum;

/**
 * Enum FormatForeground.
 *
 * @author Melech Mizrachi
 */
final class FormatForeground extends Enum
{
    public const BLACK         = 30;
    public const RED           = 31;
    public const GREEN         = 32;
    public const YELLOW        = 33;
    public const BLUE          = 34;
    public const MAGENTA       = 35;
    public const CYAN          = 36;
    public const WHITE         = 37;
    public const DARK_GRAY     = 90;
    public const LIGHT_RED     = 91;
    public const LIGHT_GREEN   = 92;
    public const LIGHT_YELLOW  = 93;
    public const LIGHT_BLUE    = 94;
    public const LIGHT_MAGENTA = 95;
    public const LIGHT_CYAN    = 96;
    public const LIGHT_WHITE   = 97;
    public const DEFAULT       = 39;

    protected static ?array $VALUES = [
        self::BLACK         => self::BLACK,
        self::RED           => self::RED,
        self::GREEN         => self::GREEN,
        self::YELLOW        => self::YELLOW,
        self::BLUE          => self::BLUE,
        self::MAGENTA       => self::MAGENTA,
        self::CYAN          => self::CYAN,
        self::WHITE         => self::WHITE,
        self::DARK_GRAY     => self::DARK_GRAY,
        self::LIGHT_RED     => self::LIGHT_RED,
        self::LIGHT_GREEN   => self::LIGHT_GREEN,
        self::LIGHT_YELLOW  => self::LIGHT_YELLOW,
        self::LIGHT_BLUE    => self::LIGHT_BLUE,
        self::LIGHT_MAGENTA => self::LIGHT_MAGENTA,
        self::LIGHT_CYAN    => self::LIGHT_CYAN,
        self::LIGHT_WHITE   => self::LIGHT_WHITE,
        self::DEFAULT       => self::DEFAULT,
    ];
}
