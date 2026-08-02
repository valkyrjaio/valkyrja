<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Type\String\Factory;

use Override;

use function mb_convert_case;
use function mb_strtolower;
use function mb_strtoupper;
use function mb_substr;

use const MB_CASE_TITLE;

class MbStringFactory extends StringFactory
{
    /**
     * The charset to use.
     *
     * @var string
     */
    protected static string $charset = 'UTF-8';

    /**
     * Get a substring from start position with a certain length.
     */
    #[Override]
    public static function substr(string $subject, int $start, int|null $length = null): string
    {
        return mb_substr($subject, $start, $length, static::$charset);
    }

    /**
     * Convert a string to title case.
     */
    public static function toTitleCase(string $subject): string
    {
        return mb_convert_case($subject, MB_CASE_TITLE, static::$charset);
    }

    /**
     * Convert a string to lower case.
     */
    public static function toLowerCase(string $subject): string
    {
        return mb_strtolower($subject, static::$charset);
    }

    /**
     * Convert a string to upper case.
     */
    public static function toUpperCase(string $subject): string
    {
        return mb_strtoupper($subject, static::$charset);
    }
}
