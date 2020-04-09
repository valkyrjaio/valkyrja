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
 * Enum FormatBackground.
 *
 * @author Melech Mizrachi
 */
final class FormatBackground extends Enum
{
    public const BLACK         = 40;
    public const RED           = 41;
    public const GREEN         = 42;
    public const YELLOW        = 43;
    public const BLUE          = 44;
    public const MAGENTA       = 45;
    public const CYAN          = 46;
    public const WHITE         = 47;
    public const DARK_GRAY     = 100;
    public const LIGHT_RED     = 101;
    public const LIGHT_GREEN   = 102;
    public const LIGHT_YELLOW  = 103;
    public const LIGHT_BLUE    = 104;
    public const LIGHT_MAGENTA = 105;
    public const LIGHT_CYAN    = 106;
    public const LIGHT_WHITE   = 107;
    public const DEFAULT       = 49;

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
