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
 * Enum FormatOption.
 *
 * @author Melech Mizrachi
 */
final class FormatOption extends Enum
{
    public const BOLD       = '1';
    public const UNDERSCORE = '4';
    public const BLINK      = '5';
    public const INVERSE    = '7';
    public const CONCEAL    = '8';
    public const DEFAULT    = [
        self::BOLD       => 22,
        self::UNDERSCORE => 24,
        self::BLINK      => 25,
        self::INVERSE    => 27,
        self::CONCEAL    => 28,
    ];

    protected const VALUES = [
        self::BOLD       => self::BOLD,
        self::UNDERSCORE => self::UNDERSCORE,
        self::BLINK      => self::BLINK,
        self::INVERSE    => self::INVERSE,
        self::CONCEAL    => self::CONCEAL,
    ];
}
