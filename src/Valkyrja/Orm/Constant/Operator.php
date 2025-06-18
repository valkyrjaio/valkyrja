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

namespace Valkyrja\Orm\Constant;

/**
 * Constant Operator.
 *
 * @author Melech Mizrachi
 */
final class Operator
{
    public const string EQUALS             = '=';
    public const string NULL_SAFE_EQUALS   = '<=>';
    public const string NOT_EQUAL          = '!=';
    public const string NOT_EQUAL_ALT      = '<>';
    public const string IN                 = 'IN';
    public const string NOT_IN             = 'NOT_IN';
    public const string LIKE               = 'LIKE';
    public const string NOT_LIKE           = 'NOT LIKE';
    public const string SOUNDS_LIKE        = 'SOUNDS LIKE';
    public const string RLIKE              = 'RLIKE';
    public const string IS                 = 'IS';
    public const string IS_NOT             = 'IS NOT';
    public const string MOD                = '%';
    public const string MOD_ALT            = 'MOD';
    public const string GREATER_THAN       = '>';
    public const string GREATER_THAN_EQUAL = '>=';
    public const string LESS_THAN          = '<';
    public const string LESS_THAN_EQUAL    = '<=';
    public const string RIGHT_SHIFT        = '>>';
    public const string LEFT_SHIFT         = '<<';
    public const string MEMBER_OF          = 'MEMBER_OF';
    public const string REGEXP             = 'REGEXP';
    public const string NOT_REGEXP         = 'NOT REGEXP';
    public const string BITWISE_XOR        = '^';
    public const string LOGICAL_XOR        = 'XOR';
    public const string BITWISE_OR         = '|';
    public const string BITWISE_INVERSION  = '~';
}
