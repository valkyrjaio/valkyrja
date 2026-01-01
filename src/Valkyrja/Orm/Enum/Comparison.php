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

namespace Valkyrja\Orm\Enum;

/**
 * Enum WhereType.
 */
enum Comparison: string
{
    case EQUALS             = '=';
    case NULL_SAFE_EQUALS   = '<=>';
    case NOT_EQUAL          = '!=';
    case NOT_EQUAL_ALT      = '<>';
    case IN                 = 'IN';
    case NOT_IN             = 'NOT_IN';
    case LIKE               = 'LIKE';
    case NOT_LIKE           = 'NOT LIKE';
    case SOUNDS_LIKE        = 'SOUNDS LIKE';
    case RLIKE              = 'RLIKE';
    case IS                 = 'IS';
    case IS_NOT             = 'IS NOT';
    case MOD                = '%';
    case MOD_ALT            = 'MOD';
    case GREATER_THAN       = '>';
    case GREATER_THAN_EQUAL = '>=';
    case LESS_THAN          = '<';
    case LESS_THAN_EQUAL    = '<=';
    case RIGHT_SHIFT        = '>>';
    case LEFT_SHIFT         = '<<';
    case MEMBER_OF          = 'MEMBER_OF';
    case REGEXP             = 'REGEXP';
    case NOT_REGEXP         = 'NOT REGEXP';
    case BITWISE_XOR        = '^';
    case LOGICAL_XOR        = 'XOR';
    case BITWISE_OR         = '|';
    case BITWISE_INVERSION  = '~';
}
