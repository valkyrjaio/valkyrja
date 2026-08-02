<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Orm\Enum;

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
