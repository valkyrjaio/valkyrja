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

namespace Valkyrja\ORM\Enums;

use Valkyrja\Enum\Enums\Enum;

/**
 * Enum Operator.
 */
final class Operator extends Enum
{
    public const EQUALS             = '=';
    public const NULL_SAFE_EQUALS   = '<=>';
    public const NOT_EQUAL          = '!=';
    public const NOT_EQUAL_ALT      = '<>';
    public const IN                 = 'IN';
    public const NOT_IN             = 'NOT_IN';
    public const LIKE               = 'LIKE';
    public const NOT_LIKE           = 'NOT LIKE';
    public const SOUNDS_LIKE        = 'SOUNDS LIKE';
    public const RLIKE              = 'RLIKE';
    public const IS                 = 'IS';
    public const IS_NOT             = 'IS NOT';
    public const MOD                = '%';
    public const MOD_ALT            = 'MOD';
    public const GREATER_THAN       = '>';
    public const GREATER_THAN_EQUAL = '>=';
    public const LESS_THAN          = '<';
    public const LESS_THAN_EQUAL    = '<=';
    public const RIGHT_SHIFT        = '>>';
    public const LEFT_SHIFT         = '<<';
    public const MEMBER_OF          = 'MEMBER_OF';
    public const REGEXP             = 'REGEXP';
    public const NOT_REGEXP         = 'NOT REGEXP';
    public const BITWISE_XOR        = '^';
    public const LOGICAL_XOR        = 'XOR';
    public const BITWISE_OR         = '|';
    public const BITWISE_INVERSION  = '~';

    protected static ?array $VALUES = [
        self::EQUALS             => self::EQUALS,
        self::NULL_SAFE_EQUALS   => self::NULL_SAFE_EQUALS,
        self::NOT_EQUAL          => self::NOT_EQUAL,
        self::NOT_EQUAL_ALT      => self::NOT_EQUAL_ALT,
        self::IN                 => self::IN,
        self::NOT_IN             => self::NOT_IN,
        self::LIKE               => self::LIKE,
        self::NOT_LIKE           => self::NOT_LIKE,
        self::SOUNDS_LIKE        => self::SOUNDS_LIKE,
        self::RLIKE              => self::RLIKE,
        self::IS                 => self::IS,
        self::IS_NOT             => self::IS_NOT,
        self::MOD                => self::MOD,
        self::MOD_ALT            => self::MOD_ALT,
        self::GREATER_THAN       => self::GREATER_THAN,
        self::GREATER_THAN_EQUAL => self::GREATER_THAN_EQUAL,
        self::LESS_THAN          => self::LESS_THAN,
        self::LESS_THAN_EQUAL    => self::LESS_THAN_EQUAL,
        self::RIGHT_SHIFT        => self::RIGHT_SHIFT,
        self::LEFT_SHIFT         => self::LEFT_SHIFT,
        self::MEMBER_OF          => self::MEMBER_OF,
        self::REGEXP             => self::REGEXP,
        self::NOT_REGEXP         => self::NOT_REGEXP,
        self::BITWISE_XOR        => self::BITWISE_XOR,
        self::LOGICAL_XOR        => self::LOGICAL_XOR,
        self::BITWISE_OR         => self::BITWISE_OR,
        self::BITWISE_INVERSION  => self::BITWISE_INVERSION,
    ];
}
