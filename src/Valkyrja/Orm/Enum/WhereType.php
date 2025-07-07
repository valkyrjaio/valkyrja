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
 *
 * @author Melech Mizrachi
 */
enum WhereType: string
{
    case DEFAULT = '';
    case AND     = 'AND';
    case OR      = 'OR';
    case NOT     = 'NOT';
    case AND_NOT = 'AND NOT';
    case OR_NOT  = 'OR NOT';
}
