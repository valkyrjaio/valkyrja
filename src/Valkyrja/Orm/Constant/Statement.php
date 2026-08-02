<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Orm\Constant;

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
