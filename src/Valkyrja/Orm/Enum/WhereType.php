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

enum WhereType: string
{
    case DEFAULT = '';
    case AND     = 'AND';
    case OR      = 'OR';
    case NOT     = 'NOT';
    case AND_NOT = 'AND NOT';
    case OR_NOT  = 'OR NOT';
}
