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
 */
final class Statement
{
    public const string SELECT    = 'SELECT';
    public const string INSERT    = 'INSERT';
    public const string INTO      = 'INTO';
    public const string UPDATE    = 'UPDATE';
    public const string DELETE    = 'DELETE';
    public const string JOIN      = 'JOIN';
    public const string INNER     = 'INNER';
    public const string OUTER     = 'OUTER';
    public const string LEFT      = 'LEFT';
    public const string RIGHT     = 'RIGHT';
    public const string COUNT     = 'COUNT';
    public const string DISTINCT  = 'DISTINCT';
    public const string ON        = 'ON';
    public const string AS        = 'AS';
    public const string FROM      = 'FROM';
    public const string SET       = 'SET';
    public const string VALUES    = 'VALUES';
    public const string WHERE     = 'WHERE';
    public const string WHERE_AND = 'AND';
    public const string WHERE_OR  = 'OR';
    public const string GROUP_BY  = 'GROUP BY';
    public const string ORDER_BY  = 'ORDER BY';
    public const string LIMIT     = 'LIMIT';
    public const string OFFSET    = 'OFFSET';
    public const string COUNT_ALL = 'COUNT(*)';
}
