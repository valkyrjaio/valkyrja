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

namespace Valkyrja\Orm\Constants;

/**
 * Constant Operator.
 *
 * @author Melech Mizrachi
 */
final class Operator
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
}
