<?php

declare(strict_types = 1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\ORM\Enums;

use Valkyrja\Enum\Enum;

/**
 * Enum WhereType.
 */
final class WhereType extends Enum
{
    public const EQUALS             = '=';
    public const NOT_EQUAL          = '!=';
    public const IN                 = 'IN';
    public const NOT_IN             = 'NOT_IN';
    public const LIKE               = 'LIKE';
    public const NOT_LIKE           = 'NOT LIKE';
    public const IS                 = 'IS';
    public const IS_NOT             = 'IS NOT';
    public const GREATER_THAN       = '>';
    public const GREATER_THAN_EQUAL = '>=';
    public const LESS_THAN          = '<';
    public const LESS_THAN_EQUAL    = '<=';
    public const REGEXP             = 'REGEXP';
    public const NOT_REGEXP         = 'NOT REGEXP';

    protected const VALUES = [
        self::EQUALS             => self::EQUALS,
        self::NOT_EQUAL          => self::NOT_EQUAL,
        self::IN                 => self::IN,
        self::NOT_IN             => self::NOT_IN,
        self::LIKE               => self::LIKE,
        self::NOT_LIKE           => self::NOT_LIKE,
        self::IS                 => self::IS,
        self::IS_NOT             => self::IS_NOT,
        self::GREATER_THAN       => self::GREATER_THAN,
        self::GREATER_THAN_EQUAL => self::GREATER_THAN_EQUAL,
        self::LESS_THAN          => self::LESS_THAN,
        self::LESS_THAN_EQUAL    => self::LESS_THAN_EQUAL,
        self::REGEXP             => self::REGEXP,
        self::NOT_REGEXP         => self::NOT_REGEXP,
    ];
}
