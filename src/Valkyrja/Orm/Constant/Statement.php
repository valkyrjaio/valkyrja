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
 * Constant Statement.
 *
 * @author Melech Mizrachi
 */
final class Statement
{
    public const SELECT    = 'SELECT';
    public const INSERT    = 'INSERT';
    public const INTO      = 'INTO';
    public const UPDATE    = 'UPDATE';
    public const DELETE    = 'DELETE';
    public const JOIN      = 'JOIN';
    public const INNER     = 'INNER';
    public const OUTER     = 'OUTER';
    public const LEFT      = 'LEFT';
    public const RIGHT     = 'RIGHT';
    public const COUNT     = 'COUNT';
    public const DISTINCT  = 'DISTINCT';
    public const ON        = 'ON';
    public const AS        = 'AS';
    public const FROM      = 'FROM';
    public const SET       = 'SET';
    public const VALUES    = 'VALUES';
    public const WHERE     = 'WHERE';
    public const WHERE_AND = 'AND';
    public const WHERE_OR  = 'OR';
    public const GROUP_BY  = 'GROUP BY';
    public const ORDER_BY  = 'ORDER BY';
    public const LIMIT     = 'LIMIT';
    public const OFFSET    = 'OFFSET';
    public const COUNT_ALL = 'COUNT(*)';
}
